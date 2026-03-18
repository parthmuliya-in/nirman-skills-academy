const form = document.getElementById("contactForm");
const phoneInput = document.getElementById("phone");
const phoneErr = document.getElementById("phoneErr");
const robot = document.getElementById("robotCheck");
const errorMsg = document.getElementById("verify-expired");
const recapBox = document.getElementById("recapBox");
phoneInput.addEventListener("input", () => {
  phoneErr.style.display = phoneInput.value.length === 10 ? "none" : "block";
});
form.addEventListener("submit", function (e) {
  if (phoneInput.value.length !== 10) {
    phoneErr.style.display = "block"; e.preventDefault(); return;
  }
  if (!robot.checked) {
    e.preventDefault();
    errorMsg.style.display = "block";
    recapBox.classList.add("shake");
    setTimeout(() => recapBox.classList.remove("shake"), 400);
  }
});