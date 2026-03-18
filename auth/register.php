<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../includes/config.php";

// ---------------- CONFIG ----------------
$recaptcha_secret = "YOUR_SECRET_KEY"; // <-- Replace with your reCAPTCHA secret key

// ---------------- FUNCTIONS ----------------
function clean($v){
    return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
}

function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function log_spam($conn, $email, $name, $reason){
    $ip = getUserIP();
    $stmt = $conn->prepare("INSERT INTO spam_protection(ip_address,email,name,reason,created_at) VALUES(?,?,?,?,NOW())");
    $stmt->bind_param("ssss", $ip, $email, $name, $reason);
    $stmt->execute();
    $stmt->close();
}

// ---------------- BLOCK DIRECT GET ----------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(["status"=>"error","message"=>"Invalid request"]);
    exit;
}

// ---------------- HONEYPOT ----------------
if (!empty($_POST['website'])){
    log_spam($conn,"","Honeypot triggered","Honeypot triggered");
    echo json_encode(["status"=>"error","message"=>"Spam detected"]);
    exit;
}

// ---------------- CSRF TOKEN ----------------
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')){
    echo json_encode(["status"=>"error","message"=>"Security token mismatch"]);
    exit;
}

// ---------------- INPUTS ----------------
$name     = clean($_POST['name'] ?? '');
$email    = clean($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

if($name === "" || $email === "" || $password === "" || $recaptcha_response === ""){
    log_spam($conn,$email,$name,"Missing fields");
    echo json_encode(["status"=>"error","message"=>"All fields required"]);
    exit;
}

// ---------------- GOOGLE RECAPTCHA VERIFICATION ----------------
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
$response = json_decode($response,true);

if(!$response['success']){
    log_spam($conn,$email,$name,"reCAPTCHA failed");
    echo json_encode(["status"=>"error","message"=>"reCAPTCHA verification failed"]);
    exit;
}

// ---------------- RATE LIMIT ----------------
$ip = getUserIP();
$ipCheck = $conn->prepare("SELECT attempts,last_attempt FROM spam_attempts WHERE ip_address=? LIMIT 1");
$ipCheck->bind_param("s",$ip);
$ipCheck->execute();
$res = $ipCheck->get_result();
$ipRow = $res->fetch_assoc();
$ipCheck->close();

if($ipRow){
    $timeDiff = time() - strtotime($ipRow['last_attempt']);
    if($ipRow['attempts'] >= 10 && $timeDiff < 600){
        log_spam($conn,$email,$name,"Rate limit triggered");
        echo json_encode(["status"=>"error","message"=>"Too many attempts. Try later"]);
        exit;
    }
    if($timeDiff >= 600){
        $reset = $conn->prepare("UPDATE spam_attempts SET attempts=0 WHERE ip_address=?");
        $reset->bind_param("s",$ip);
        $reset->execute();
        $reset->close();
    }
} else {
    $new = $conn->prepare("INSERT INTO spam_attempts(ip_address,attempts,last_attempt) VALUES(?,1,NOW())");
    $new->bind_param("s",$ip);
    $new->execute();
    $new->close();
}

// Increment attempts
$inc = $conn->prepare("UPDATE spam_attempts SET attempts=attempts+1,last_attempt=NOW() WHERE ip_address=?");
$inc->bind_param("s",$ip);
$inc->execute();
$inc->close();

// ---------------- EMAIL VALIDATION ----------------
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    log_spam($conn,$email,$name,"Invalid email format");
    echo json_encode(["status"=>"error","message"=>"Invalid email"]);
    exit;
}

// ---------------- BLOCK DISPOSABLE EMAILS ----------------
$disposableDomains = ["tempmail.com","10minutemail.com","mailinator.com","guerrillamail.com"];
$domain = explode("@",$email)[1] ?? '';
if(in_array($domain,$disposableDomains)){
    log_spam($conn,$email,$name,"Disposable email blocked");
    echo json_encode(["status"=>"error","message"=>"Email not allowed"]);
    exit;
}

// ---------------- CHECK DUPLICATE ----------------
$check = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$check->bind_param("s",$email);
$check->execute();
$res = $check->get_result();
if($res->num_rows > 0){
    log_spam($conn,$email,$name,"Duplicate email");
    echo json_encode(["status"=>"error","message"=>"Email already exists"]);
    exit;
}
$check->close();

// ---------------- PASSWORD HASH ----------------
$hashed = password_hash($password,PASSWORD_DEFAULT);

// ---------------- INSERT USER ----------------
$stmt = $conn->prepare("INSERT INTO users(name,email,password,created_at) VALUES(?,?,?,NOW())");
$stmt->bind_param("sss",$name,$email,$hashed);

if($stmt->execute()){
    echo json_encode(["status"=>"success","message"=>"Registration successful"]);
} else {
    log_spam($conn,$email,$name,"Database insert failed");
    echo json_encode(["status"=>"error","message"=>"Database error"]);
}

$stmt->close();
$conn->close();
?>
