// course filter******************
// document.addEventListener("DOMContentLoaded", function () {

const btns = document.querySelectorAll(".btn-box");
const sections = document.querySelectorAll(".course-section");

btns.forEach(btn => {
  btn.onclick = () => {
    document.querySelector(".active").classList.remove("active");
    btn.classList.add("active");

    sections.forEach(sec => sec.classList.remove("show"));
    document.getElementById(btn.dataset.target).classList.add("show");
  };
});
document.querySelectorAll('.course-text p').forEach(p => {
    const fullText = p.innerText.trim();
    const words = fullText.split(/\s+/);

    if (words.length >7) {
        p.setAttribute("data-fulltext", fullText); // full text safe
        p.innerText = words.slice(0, 7).join(" ") + "...";
    }
});
// })
// course filter end******************
