<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">

</head>

<body>
    <section class="faq-section">
        <div class="faq-container">

            <h2 class="faq-title">Frequently Asked <span>Questions</span>Animation :</h2>

            <div class="faq-item">
                <button class="faq-question">
                    How long does this Animation course take?

                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>This Animation course offers Fast-Track and Regular batches. The Fast-Track batch lasts 2 months,
                        while the Regular
                        batch lasts 3 months. You can choose online or offline classes and learn at a schedule that
                        suits you.

                        .</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Which batch should I choose, Fast-Track or Regular?
                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>
                        It depends on your goal. The Fast-Track batch (2 months) is for those who want to complete the
                        course quickly, while the
                        Regular batch (3 months) is better for detailed learning with more practice time.


                    </p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Can I practice real projects during the course?
                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>
                        Yes! You will work on real animation projects in both batches. This helps you gain practical
                        experience and build a strong
                        portfolio, ready for jobs or freelance opportunities.

                    </p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Can I learn animation online or offline with this course?
                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>
                        Yes! This professional Animation course offers both online and offline classes, making it
                        flexible and convenient. You will
                        learn animation skills, work on real projects, and build a portfolio that helps you start a
                        career or freelance work in
                        animation.
                    </p>
                </div>
            </div>



        </div>
    </section>

    <script>
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

    </script>

</body>

</html>