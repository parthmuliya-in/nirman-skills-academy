<!-- ===== WhatsApp Floating Button START ===== -->

<!-- Font Awesome (include once globally) -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
/* WhatsApp Floating Button */
.whatsapp-float {
    position: fixed;
    width: 60px;
    height: 60px;
    background-color: #25D366;
    color: #fff;
    bottom: 20px;
    right: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    cursor: pointer;
    z-index: 9999;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.whatsapp-float:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 15px rgba(0,0,0,0.4);
}
</style>

<!-- Button -->
<div class="whatsapp-float" data-phone="919099090677">
    <i class="fa-brands fa-whatsapp"></i>
</div>

<script>
/* Safe for author / include files */
document.addEventListener("DOMContentLoaded", function () {

    const btn = document.querySelector(".whatsapp-float");
    if (!btn) return;

    btn.addEventListener("click", function () {

        const phone = btn.getAttribute("data-phone");
        const message = encodeURIComponent("Hello, I want more information.");
        const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

        const url = isMobile
            ? "https://wa.me/" + phone + "?text=" + message
            : "https://web.whatsapp.com/send?phone=" + phone + "&text=" + message;

        window.open(url, "_blank");
    });

});
</script>

<!-- ===== WhatsApp Floating Button END ===== -->
