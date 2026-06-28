<?php
// session_start();
include "header.php";
include "include/config.php";
include "api/wp_app.php";


// Generate CSRF token once per session
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NirmanSkills Academy</title>
    <link rel="icon" href="assets/images/logo.png" type="image/png">

    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/contact.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <!-- **************************** HEADER ************************************* -->

    <!-- **************************** HEADER ENDS ************************************* -->
    <br><br><br>

    <div class="contact-banner">
        <!-- <h2>Get In Touch</h2>
        <p>The Ultimate Guide To Ace SDE Interviews.</p> -->
    </div> <!-- OVERLAP BOX -->
    <div class="contact-box">
        <div class="top-cols">
            <div class="left-col">
                <h2 style="color: #ff6600;"><b>Get In Touch</b></h2><br>
                <form id="contactForm">
                    <input type="hidden" id="csrf" value="<?= $_SESSION['csrf']; ?>">

                    <div class="row2">
                        <div>
                            <label>Name*</label>
                            <input type="text" id="name" placeholder="Enter your Name">
                        </div>
                        <div>
                            <label>Email*</label>
                            <input type="email" id="email" placeholder="Enter your Email">
                        </div>
                    </div>

                    <div class="row2">
                        <div>
                            <label>Phone Number*</label>
                            <input type="text" id="phone" maxlength="10" placeholder="Enter your Phone Number" required>
                            <div id="phoneErr" class="error-small">Enter valid 10-digit number</div>
                        </div>
                        <div>
                            <label>Subject*</label>
                            <input type="text" id="subject" placeholder="Enter your Subject" required>
                        </div>
                    </div>

                    <label>Message*</label>
                    <textarea id="message" placeholder="Enter your Message" required></textarea>

                    <div class="recaptcha-compact" id="recapBox">
                        <label class="check-container">
                            <input type="checkbox" id="robotCheck"> <span class="checkmark"></span> </label>
                        <span class="robot-text">Apply Term and condition</span>
                    </div>
                    <br>
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>
            <div class="right-col">
                <h2 style="color: #ff6600;"><b>Contact Information</b></h2> <br>
                <p>Nirman Skills Academy is a growing skill-development institute in Ahmedabad, delivering today’s most
                    in-demand skills to students. We offer practical courses in CCC, Graphic Design, Animation, and WEB
                    development(php) — designed to build real industry-ready talent. Our expert mentors focus on
                    hands-on learning, career-oriented projects, and strong technical foundations to help you upgrade
                    your skills and career goals . Join us to start your journey toward a successful tech and creative
                    career.</p> <br>
                <!-- <div class="contact-row"> -->
                <div class="item-form"> <i class="fa-solid fa-location-dot" style="color: #ff6600;"></i> Address</div>
                <br>
                <div class="sub">110,
                    Shyamak complex,opp. NEW income tax building, Panjrapole, Ahmedabad - 380015.
                </div>
                <br>
                <div class="item-form"> <i class="fa-solid fa-phone" style="color: #ff6600;"></i> Phone</div><br>
                <div class="sub">+91 6356837530
                </div>
                <br>
                <div class="item-form"> <i class="fa-solid fa-envelope" style="color: #ff6600;"></i> Email </div><br>
                <div class="sub">info@nirmanskillsacademy.com</div>
                <br>
                <!-- </div> <br> <br> -->
                <h2>
                    <div class="social-icons">
                        <a href="https://www.instagram.com/nirmanskillsacademy/" target="_blank"> <i
                                class="fa-brands fa-instagram"></i> </a> &nbsp; &nbsp; <a
                            href="https://www.facebook.com/profile.php?id=61585162015103" target="_blank"> <i
                                class="fa-brands fa-facebook-f"></i> </a> &nbsp; &nbsp; <a
                            href="mailto:info@nirmanskillsacademy.com" target="_blank"> <i
                                class="fa-solid fa-envelope"></i> </a>
                    </div>
                </h2>
            </div>
        </div>
    </div> <!-- form end --> <!-- map -->
    <div class="form-map"> <!--<iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2902.200730420813!2d72.54236327407483!3d23.024609316254537!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e84e8c555559d%3A0xad89e1a24092ba9e!2siSourcingSolutions!5e1!3m2!1sen!2sin!4v1765458476205!5m2!1sen!2sin"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>-->
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d229.48704741490425!2d72.54382876831308!3d23.031380310418097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMjPCsDAxJzUzLjEiTiA3MsKwMzInMzguMCJF!5e0!3m2!1sen!2sin!4v1766410192073!5m2!1sen!2sin"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>




    <?php
    include "footer.php";
    ?>



    <script>
        document.getElementById("contactForm").addEventListener("submit", function (e) {
            e.preventDefault();

            if (!document.getElementById("robotCheck").checked) {
                alert("Please verify that you are not a robot.");
                return;
            }

            let data = {
                name: document.getElementById("name").value.trim(),
                email: document.getElementById("email").value.trim(),
                phone: document.getElementById("phone").value.trim(),
                subject: document.getElementById("subject").value.trim(),
                message: document.getElementById("message").value.trim(),
                csrf: document.getElementById("csrf").value
            };

            fetch("api/save_contact.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            })
                .then(res => res.json())
                .then(resp => {
                    alert(resp.message);
                    if (resp.status === "success") {
                        document.getElementById("contactForm").reset();
                    }
                })
                .catch(err => console.error("Error:", err));
        });


    </script>



    <Script src="assets/js/script.js"></Script>
    <script src="assets/js/contact.js"></script>



</body>

</html>