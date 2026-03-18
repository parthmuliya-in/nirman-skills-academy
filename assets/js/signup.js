document.addEventListener("DOMContentLoaded", () => {
    // ===============================
    // 3D EFFECT + BORDER HIGHLIGHT
    // ===============================
    function apply3DEffect(selector, color = "#ff6600") {
        const container = document.querySelector(selector);
        if (!container) return;

        container.addEventListener("mousemove", (e) => {
            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateY = ((x - centerX) / centerX) * 8;
            const rotateX = ((centerY - y) / centerY) * 8;

            container.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            container.style.border = "3px solid transparent";

            const topDist = y;
            const bottomDist = rect.height - y;
            const leftDist = x;
            const rightDist = rect.width - x;

            const minDist = Math.min(topDist, bottomDist, leftDist, rightDist);

            if (minDist === topDist) container.style.borderTop = `3px solid ${color}`;
            else if (minDist === bottomDist) container.style.borderBottom = `3px solid ${color}`;
            else if (minDist === leftDist) container.style.borderLeft = `3px solid ${color}`;
            else container.style.borderRight = `3px solid ${color}`;
        });

        container.addEventListener("mouseleave", () => {
            container.style.transform = "rotateX(0deg) rotateY(0deg)";
            container.style.border = "3px solid transparent";
        });
    }

    apply3DEffect(".about-section");
    apply3DEffect(".login-container");
    apply3DEffect(".signup-container");

    // ===============================
    // PASSWORD TOGGLE
    // ===============================
    const togglePasswordBtns = document.querySelectorAll("[data-toggle='password']");
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            const input = document.getElementById(btn.dataset.target);
            if (!input) return;

            if (input.type === "password") {
                input.type = "text";
                btn.classList.remove("fa-eye");
                btn.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                btn.classList.remove("fa-eye-slash");
                btn.classList.add("fa-eye");
            }
        });
    });

});