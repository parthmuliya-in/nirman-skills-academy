/*
document.addEventListener("DOMContentLoaded", function () { 
// Mobile Menu Toggle
const menuToggler = document.getElementById("menu-toggler");
const navMenu = document.getElementById("primary-menu");


menuToggler.addEventListener("click", () => {
    menuToggler.classList.toggle("active");
    navMenu.classList.toggle("active");
});


// Theme Toggle + Save State
const toggleBtn = document.getElementById("theme-toggle");
const themeIcon = document.getElementById("theme-icon");

// STEP 1: Apply Saved Theme on Load
if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark-mode");
    // themeIcon.classList.remove("fa-moon");
    // themeIcon.classList.add("fa-sun");
    document.body.style.background = "#111";
    document.body.style.color = "#fff";
}

// STEP 2: Toggle Theme Button
toggleBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {
        // themeIcon.classList.remove("fa-moon");
        // themeIcon.classList.add("fa-sun");
        document.body.style.background = "#111";
        document.body.style.color = "#fff";

        localStorage.setItem("theme", "dark"); // SAVE
    } else {
        // themeIcon.classList.remove("fa-sun");
        // themeIcon.classList.add("fa-moon");
        document.body.style.background = "#fff";
        document.body.style.color = "#000";

        localStorage.setItem("theme", "light"); // SAVE
    }
});



//*********************************** move buttons inside menu **************************************

function moveButtonForMobile() {
    const navMenu = document.getElementById("primary-menu");
    const getBtn = document.querySelector(".get-touch-btn");
    const signBtn = document.querySelector(".sign-up-btn");
    const navbar = document.querySelector(".navbar");

    // MOBILE VIEW — move buttons inside menu
    if (window.innerWidth <= 850) {

        if (!navMenu.contains(signBtn)) {
            navMenu.appendChild(signBtn);
        }
        if (!navMenu.contains(getBtn)) {
            navMenu.appendChild(getBtn);
        }

    } else {
        // DESKTOP VIEW — move buttons back to navbar
        if (!navbar.contains(signBtn)) {
            navbar.insertBefore(signBtn, getBtn);
        }
        if (!navbar.contains(getBtn)) {
            navbar.insertBefore(getBtn, document.getElementById("theme-toggle"));
        }
    }
}

// Run on load + resize
window.addEventListener("load", moveButtonForMobile);
window.addEventListener("resize", moveButtonForMobile);



//*************************************************************************

//Add Search Icon Toggle 

const searchIconMobile = document.querySelector(".search-icon-mobile");
const searchBox = document.querySelector(".search-box");

searchIconMobile.addEventListener("click", () => {
    searchBox.classList.toggle("active");
});*/



// <!-- **************************** HERO SECTION ************************************* -->

// UNIVERSAL FUNCTION
/*function convertH2ToButton(h2Id) {
    const h2 = document.getElementById(h2Id);
    if (!h2) return; // agar id nahi milti, skip

    const btn = document.createElement("a");
    btn.className = "get-touch-btn";
    btn.href = "#";
    btn.style.display = "inline-block";
    btn.style.padding = "10px 10px";
    btn.style.lineHeight = "1.2";

    btn.innerHTML = `
        ${h2.textContent}
        <span class="bottom-line"></span>
        <span class="left-line"></span>
    `;

    h2.textContent = "";
    h2.appendChild(btn);
}

// ---- USE IT FOR ALL H2 IDs ----
convertH2ToButton("tagline-id");
convertH2ToButton("animated-text");
convertH2ToButton("animated-text2");

*/

// **************************** SKILL SCROLL SECTION ************************************* 
const fill = document.querySelector(".progress-fill");
const slide1 = document.querySelector(".slide1");
const slide2 = document.querySelector(".slide2");
const slide3 = document.querySelector(".slide3");

const tab1 = document.getElementById("tab1");
const tab2 = document.getElementById("tab2");
const tab3 = document.getElementById("tab3");

// Show first slide initially
slide1.classList.add("active");

window.addEventListener("scroll", () => {

    let section = document.querySelector(".skill-scroll-section");
    let rect = section.getBoundingClientRect();
    let viewHeight = window.innerHeight;

    let visible = viewHeight - rect.top;
    let startPoint = rect.height * 0.70;


    // let totalScrollable = rect.height + 200;
    let totalScrollable = rect.height + 150;
    let percent = (visible - startPoint) / (totalScrollable - startPoint);
    percent = Math.min(Math.max(percent, 0), 1);

    // Update bar width
    let progressWidth = percent * section.querySelector(".scroll-progress").offsetWidth;
    fill.style.width = progressWidth + "px";

    // Each tab's width
    let w1 = tab1.offsetWidth;
    let w2 = tab2.offsetWidth;
    let w3 = tab3.offsetWidth;

    // SLIDE CHANGE BASED ON PROGRESS COVERAGE
    if (progressWidth < w1) {
        slide1.classList.add("active");
        slide2.classList.remove("active");
        slide3.classList.remove("active");
    }
    else if (progressWidth >= w1 && progressWidth < w1 + w2) {
        slide1.classList.remove("active");
        slide2.classList.add("active");
        slide3.classList.remove("active");
    }
    else {
        slide1.classList.remove("active");
        slide2.classList.remove("active");
        slide3.classList.add("active");
    }

    // Scroll lock until last slide finishes
    if (!(progressWidth >= w1 + w2 + w3 - 10)) {
        document.body.classList.add("scroll-locked");
    } else {
        document.body.classList.remove("scroll-locked");
    }

});


// **************************** SKILL SCROLL SECTION ends ************************************* 







//faq-item
const faqItems = document.querySelectorAll(".faq-item");

faqItems.forEach(item => {
    item.querySelector(".faq-question").addEventListener("click", () => {
        item.classList.toggle("active");
    });
});



//add pop-up
let popupShown = false;

window.addEventListener("scroll", function () {
    if (!popupShown && window.scrollY > window.innerHeight * 0.50) {
        document.getElementById("ad-popup").style.display = "block";
        document.getElementById("ad-overlay").style.display = "block";

        setTimeout(() => {
            document.getElementById("ad-popup").style.transform = "translate(-50%, -50%) scale(1)";
        }, 50);

        popupShown = true;
    }
});

/* Close Button */
document.querySelector(".ad-close").addEventListener("click", function () {
    closePopup();
});

document.getElementById("ad-overlay").addEventListener("click", function () {
    closePopup();
});

function closePopup() {
    let popup = document.getElementById("ad-popup");
    popup.style.transform = "translate(-50%, -50%) scale(0.5)";
    popup.style.opacity = "0";

    setTimeout(() => {
        popup.style.display = "none";
        document.getElementById("ad-overlay").style.display = "none";
    }, 300);
}


//})