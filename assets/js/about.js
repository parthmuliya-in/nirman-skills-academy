// *************************************** ist section ****************************
const aboutSection = document.querySelector(".about-section");

aboutSection.addEventListener("mousemove", (e) => {
    const box = aboutSection.getBoundingClientRect();

    const x = e.clientX - box.left;   // mouse X inside box
    const y = e.clientY - box.top;    // mouse Y inside box

    const centerX = box.width / 2;
    const centerY = box.height / 2;

    let rotateY = ((x - centerX) / centerX) * 8;  // tilt left-right
    let rotateX = ((centerY - y) / centerY) * 8;  // tilt top-bottom

    aboutSection.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;

    // Border detection
    let borderColor = "#ff6600";

    // Left Side
    if (x < box.width * 0.25) {
        aboutSection.style.borderLeft = `3px solid ${borderColor}`;
        aboutSection.style.borderRight = "3px solid transparent";
        aboutSection.style.borderTop = "3px solid transparent";
        aboutSection.style.borderBottom = "3px solid transparent";
    }

    // Right Side
    else if (x > box.width * 0.75) {
        aboutSection.style.borderRight = `3px solid ${borderColor}`;
        aboutSection.style.borderLeft = "3px solid transparent";
        aboutSection.style.borderTop = "3px solid transparent";
        aboutSection.style.borderBottom = "3px solid transparent";
    }

    // Top Side
    else if (y < box.height * 0.25) {
        aboutSection.style.borderTop = `3px solid ${borderColor}`;
        aboutSection.style.borderLeft = "3px solid transparent";
        aboutSection.style.borderRight = "3px solid transparent";
        aboutSection.style.borderBottom = "3px solid transparent";
    }

    // Bottom Side
    else if (y > box.height * 0.75) {
        aboutSection.style.borderBottom = `3px solid ${borderColor}`;
        aboutSection.style.borderTop = "3px solid transparent";
        aboutSection.style.borderLeft = "3px solid transparent";
        aboutSection.style.borderRight = "3px solid transparent";
    }
});

// Reset rotation + border when mouse leaves
aboutSection.addEventListener("mouseleave", () => {
    aboutSection.classList.add("reset-effect");

    setTimeout(() => {
        aboutSection.classList.remove("reset-effect");
    }, 300);
});




// *************************************** 2nd section ****************************
// SCROLL REVEAL ANIMATION
// SCROLL REVEAL ANIMATION (REPEAT EVERY TIME)
// --- STEP 1: Wrap all words in spans ---
document.querySelectorAll(".reveal-para").forEach(para => {
    let words = para.innerText.trim().split(/\s+/);
    para.innerHTML = words.map(w => `<span>${w} </span>`).join(" ");

});

// --- STEP 2: Scroll-based reveal + reset ---
const revealParas = document.querySelectorAll(".reveal-para");
const revealElements = document.querySelectorAll(".reveal-text");

const wordRevealScroll = () => {
    revealParas.forEach(para => {
        const rect = para.getBoundingClientRect();

        if (rect.top < window.innerHeight - 100 && rect.bottom > 100) {

            para.classList.add("active");

            // stagger effect word-by-word
            para.querySelectorAll("span").forEach((span, i) => {
                span.style.transitionDelay = (i * 0.05) + "s";
            });

        } else {
            // RESET when going out of view
            para.classList.remove("active");

            para.querySelectorAll("span").forEach(span => {
                span.style.transitionDelay = "0s";
            });
        }
    });
};
const revealOnScroll = () => {
    revealElements.forEach(el => {
        const rect = el.getBoundingClientRect();

        if (rect.top < window.innerHeight - 100 && rect.bottom > 100) {
            el.classList.add("active");
        } else {
            el.classList.remove("active");
        }
    });
};

window.addEventListener("scroll", wordRevealScroll);
window.addEventListener("load", wordRevealScroll);


window.addEventListener("scroll", revealOnScroll);
window.addEventListener("load", revealOnScroll);






// r4************KHUSHBU
const tiltBox = document.querySelector(".row4-tilt");

tiltBox.addEventListener("mousemove", (e) => {
    const box = tiltBox.getBoundingClientRect();
    const x = e.clientX - box.left;
    const y = e.clientY - box.top;

    const cx = box.width / 2;
    const cy = box.height / 2;

    let rotateY = ((x - cx) / cx) * 10;
    let rotateX = ((cy - y) / cy) * 10;

    tiltBox.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
});

tiltBox.addEventListener("mouseleave", () => {
    tiltBox.style.transform = "rotateX(0deg) rotateY(0deg)";
});

/* BORDER HOVER EFFECT */
const section = document.querySelector(".row4-section");

section.addEventListener("mousemove", (e) => {
    const r = section.getBoundingClientRect();
    const x = e.clientX - r.left;
    const y = e.clientY - r.top;

    const w = r.width;
    const h = r.height;

    const left = x < w * 0.33;
    const right = x > w * 0.66;
    const top = y < h * 0.33;
    const bottom = y > h * 0.66;

    section.style.borderTopColor = "transparent";
    section.style.borderBottomColor = "transparent";
    section.style.borderLeftColor = "transparent";
    section.style.borderRightColor = "transparent";

    if (left) section.style.borderLeftColor = "var(--hover-border)";
    if (right) section.style.borderRightColor = "var(--hover-border)";
    if (top) section.style.borderTopColor = "var(--hover-border)";
    if (bottom) section.style.borderBottomColor = "var(--hover-border)";
});

section.addEventListener("mouseleave", () => {
    section.style.borderTopColor = "transparent";
    section.style.borderBottomColor = "transparent";
    section.style.borderLeftColor = "transparent";
    section.style.borderRightColor = "transparent";
});
// r4 end******************


// r5*******************
const r5Container = document.querySelector(".r5flex");

// Tilt effect
r5Container.addEventListener("mousemove", (e) => {
    const box = r5Container.getBoundingClientRect();
    const x = e.clientX - box.left;
    const y = e.clientY - box.top;

    const cx = box.width / 2;
    const cy = box.height / 2;

    const rotateY = ((x - cx) / cx) * 10;
    const rotateX = ((cy - y) / cy) * 10;

    r5Container.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
});

// Reset tilt
r5Container.addEventListener("mouseleave", () => {
    r5Container.style.transform = "rotateX(0deg) rotateY(0deg)";
});

// Border hover effect
r5Container.addEventListener("mousemove", (e) => {
    const r = r5Container.getBoundingClientRect();
    const x = e.clientX - r.left;
    const y = e.clientY - r.top;

    const w = r.width;
    const h = r.height;

    const left = x < w * 0.33;
    const right = x > w * 0.66;
    const top = y < h * 0.33;
    const bottom = y > h * 0.66;

    r5Container.style.borderTopColor = "transparent";
    r5Container.style.borderBottomColor = "transparent";
    r5Container.style.borderLeftColor = "transparent";
    r5Container.style.borderRightColor = "transparent";

    if (left) r5Container.style.borderLeftColor = "#ff6600";
    if (right) r5Container.style.borderRightColor = "#ff6600";
    if (top) r5Container.style.borderTopColor = "#ff6600";
    if (bottom) r5Container.style.borderBottomColor = "#ff6600";
});

// Reset borders
r5Container.addEventListener("mouseleave", () => {
    r5Container.style.borderTopColor = "transparent";
    r5Container.style.borderBottomColor = "transparent";
    r5Container.style.borderLeftColor = "transparent";
    r5Container.style.borderRightColor = "transparent";
});

const container = document.querySelector(".scroll-content");
let delay = 3000;
let slideDuration = 700;

function slideNext() {

    const first = container.children[0];

    // Slide first box UP
    first.style.transition = `transform ${slideDuration}ms ease, opacity ${slideDuration}ms ease`;
    first.style.transform = "translateY(-100%)";
    first.style.opacity = "0";

    setTimeout(() => {

        // Move first box to bottom
        container.appendChild(first);

        // Reset style so it appears normal again
        first.style.transition = "none";
        first.style.transform = "translateY(0)";
        first.style.opacity = "1";

        // Continue loop
        setTimeout(slideNext, delay);

    }, slideDuration);
}

slideNext();
// r5end







// ************DRISHTI
document.addEventListener("DOMContentLoaded", () => {
  const aboutSection = document.querySelector(".about-section-banner");

  aboutSection.addEventListener("mousemove", e => {
      const rect = aboutSection.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      const centerX = rect.width / 2;
      const centerY = rect.height / 2;

      // ROTATE
      const rotateY = ((x - centerX) / centerX) * 8;
      const rotateX = ((centerY - y) / centerY) * 8;
      aboutSection.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;

      // BORDER LOGIC
      const borderColor = "#ff6600";
      aboutSection.style.borderTop = "3px solid transparent";
      aboutSection.style.borderBottom = "3px solid transparent";
      aboutSection.style.borderLeft = "3px solid transparent";
      aboutSection.style.borderRight = "3px solid transparent";

      const topDist = y;
      const bottomDist = rect.height - y;
      const leftDist = x;
      const rightDist = rect.width - x;
      const minDist = Math.min(topDist, bottomDist, leftDist, rightDist);

      if(minDist === topDist) aboutSection.style.borderTop = `3px solid ${borderColor}`;
      else if(minDist === bottomDist) aboutSection.style.borderBottom = `3px solid ${borderColor}`;
      else if(minDist === leftDist) aboutSection.style.borderLeft = `3px solid ${borderColor}`;
      else if(minDist === rightDist) aboutSection.style.borderRight = `3px solid ${borderColor}`;
  });

  // RESET
  aboutSection.addEventListener("mouseleave", () => {
      aboutSection.classList.add("reset-effect");
      setTimeout(() => {
          aboutSection.classList.remove("reset-effect");
      }, 300);
  });
});






const track = document.querySelector('.image-grid-track');

let pos = 0;
let speed = 1;   // editable

function sliderMove() {
    const gridWidth = document.querySelector('.image-grid').offsetWidth + 20;

    pos -= speed;

    if (pos <= -gridWidth) {
        pos = 0;
    }

    track.style.transform = `translateX(${pos}px)`;
    requestAnimationFrame(sliderMove);
}

sliderMove();
