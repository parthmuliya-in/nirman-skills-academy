<?php
// session_start();
include "header.php";
include "include/config.php"; // database connection
include("api/wp_app.php");
// Check if user is logged in
$show_subscribe = false;
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    $show_subscribe = true;
}
$courses = [];

$sql = "SELECT id, title, description, thumbnail FROM courses ORDER BY id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
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
    <!-- <link rel="stylesheet" href="assets/css/d.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <!-- **************************** HEADER ************************************* -->

    <!-- **************************** HEADER ENDS ************************************* -->



    <!-- Advertisement Popup -->
    <div id="ad-overlay"></div>
    <div id="ad-popup">
        <span class="ad-close">&times;</span>

        <div class="ad-content">

            <!-- Left Image -->

            <div class="ad-left">
                <img src="assets/images/nirmanskillofahmedabad.jpg" alt="Ad Image">
            </div>

            <!-- Right Content -->
            <div class="ad-right">

                <img src="assets/images/logo.png" class="ad-logo">
                <h2>Get Exiting Update for your Fingertips</h2>
                <p class="ad-desc">
                    stay informed with offers,updates,reminders,and all the latest course and community
                    announcements,right in your inbox.
                </p>

                <div class="ad-buttons">

                    <!-- Button Box 1 -->
                    <div class="ad-box">
                        <div class="ad-box-left"><i class="fa-solid fa-book-open"></i></div>
                        <div>
                            <h3>Free Handbooks & study Materials</h3>
                            <p>comprenhesive guides for every topic</p>
                        </div>
                    </div>

                    <!-- Button Box 2 -->
                    <div class="ad-box">
                        <div class="ad-box-left"><i class="fa-solid fa-tags"></i></div>
                        <div>
                            <h3>Exclusive Discount & Offers</h3>
                            <p>Early access to course deals & promotions</p>
                        </div>
                    </div>

                    <!-- Button Box 3 -->
                    <div class="ad-box">
                        <div class="ad-box-left"><i class="fa-regular fa-newspaper"></i></div>
                        <div>
                            <h3>Latest Updates & News</h3>
                            <p>Stay ahead with new courses and features</p>
                        </div>
                    </div>
                </div>

                <!-- Input Field -->
                <input type="email" id="ad-email" placeholder="Enter your email" class="ad-input">

                <!-- Submit Button -->
                <button class="ad-submit" id="ad-submit">Stay Updated</button>

                <!-- Message -->
                <p id="ad-message" class="ad-message"></p>
            </div>
        </div>
    </div>

    <!-- **************************** HERO SECTION ************************************* -->
    <section class="hero">
        <div class="hero-left">
            <br>
            <p class="tagline" id="tagline-id"><span class="get-touch-btn">
                    Build Skills. Unlock Opportunities
                </span></p>
            <h1>Design. Code. Rise. <br> Become a top
                <span>
                    Professional In Tech And Creativity
                </span>
            </h1>

            <p class="desc">
                Go from beginer to expert, gain practical tech design skills and the confidence to launch your career
            </p>

            <ul class="features">
                <li>✔ Achieve your dream career goals</li>
                <li>✔ Gain hands-on experience with projects</li>
                <li>✔ Achieve your dream career goals</li>
            </ul>

            <a href="courses.php" class="explore-btn">Explore Courses</a>
        </div>

        <div class="hero-right">
            <div class="course-icons">
                <div class="icon-box"><img src="assets/images/ICONS/adobeaftereffectanimation-icon.png"
                        alt="Adobe after-effect icon for motion graphics and animation course "></div>
                <div class="icon-box"><img src="assets/images/ICONS/adobe-illustratorcourseicon.png"
                        alt="Adobe Illustrator software icon for logo and vector design course"></div>
                <div class="icon-box"><img src="assets/images/ICONS/adobephotoshopcourse-icon.png"
                        alt="Adobe photoshop software icon for graphic design course"></div>
                <div class="icon-box"><img src="assets/images/ICONS/adobepremierprovideoeditingicon.png"
                        alt="Adobe premier pro icon for video editing"></div>
                <div class="icon-box"><img src="assets/images/ICONS/phpweb-developmentcourseicon.png"
                        alt="Php programming language icon dor web development course"></div>
                <div class="icon-box"><img src="assets/images/ICONS/pythoprogramming-courseicon.png"
                        alt="python programming language icon for software development course"></div>
                <div class="icon-box"><img src="assets/images/ICONS/web-developmentjavascript-icon.png"
                        alt="Java-script icon for web-development course"></div>
                <div class="icon-box"><img src="assets/images/ICONS/web-developmenthtmlicon.png"
                        alt="HTML5 icon for web-development course"></div>
                <div class="icon-box"><img src="assets/images/ICONS/course-on-computerconcept-icon.png"
                        alt="basic computer course in ahmedabad for beginner "></div>
                <div class="icon-box"><img src="assets/images/ICONS/css3-responsive-websitedesign.png"
                        alt="CSS3 responsive web design and web styling course with practical knowledge "></div>
            </div>

            <div class="slider">
                <div class="slides">
                    <img src="assets/images/nirmanacademy-homepagebanner.jpg" class="slide s1"
                        alt="Professional web-design, graphic design and animation training for student and working professional in ahmedabad">
                    <img src="assets/images/creative-designandcoding-sourses.jpg" class="slide s2"
                        alt="Learn Graphic design, animation and website development with practical training">
                    <img src="assets/images/digital-skillstrainingbanner.jpg" class="slide s3"
                        alt="career focused it course and design courses in ahmedabad at Affortable prices.">
                </div>
            </div>


        </div>
    </section>
    <!-- **************************** HERO SECTION END ************************************* -->



    <!-- ****************EXPLORE OUR BEST COURSES*********************** -->

    <div class="Course Courses ">
        <h5 id="animated-text">Our Courses</h5>

        <br>
        <h1 class="Course-text"> Choose Smart.<span>Learn Fast</span></h1>
        <p> Build in-demand skills across web development, graphic design,
            and animation with guidance from top industry professionals.</p>
        <br>
    </div>
    <div class=" Course carousel-container shadow-section carousel-wrapper">
        <div class="right-solid"></div>
        <div class="right-fade"></div>
        <div class="Courses ">
            <section>
                <div class="courseflex carousel scroll-animation">
                    <div class="c1 course-page">
                        <div class="cm">

                            <img src="assets/images/graphiccourseinahmedabad.jpg"
                                alt="Affortable graphic design course with skills internship and practical training in Ahmedabad at niramn skills academy.">
                        </div>
                        <br>
                        <div class="ct1">
                            <div class="ct1c1">
                                <h5>Beginner's Guide to Vector Graphic Design description</h5>
                            </div>
                        </div>
                        <br>
                        <div class="ct1">
                            <p>This beginner-friendly course introduces you to Adobe...
                                Illustrator,
                                the industry-standard tool for creating scalable vector graphics Link:
                                Allcourses/Graphicdesign.php
                                <applications class=""></applications>
                            </p>
                        </div>
                        <br>

                        <div class="ct1">
                            <div class="ct1c2">
                                <a href="Allcourses/course1.html">View Details</a>
                            </div>
                        </div>

                    </div>
                    <div class="c1">
                        <div class="cm">
                            <img src="assets/images/phpcourseinahmedabad.jpg"
                                alt="Affortable php course with job-oriented skills internship and coding training in Ahmedabad at nirman skills academy.">
                        </div>
                        <br>
                        <div class="ct1">
                            <div class="ct1c1">
                                <h5>Master Motion Graphics & Animation description </h5>
                            </div>
                        </div>
                        <br>
                        <div class="ct1">
                            <p>
                                Adobe After Effects for Beginners: Master Motion
                                Graphics...
                                & Animation link:Allcourses/Animation.php
                            </p>
                        </div>
                        <br>

                        <div class="ct1">
                            <div class="ct1c2"><a href="Allcourses/course2.html">View Details</a></div>
                        </div>
                    </div>
                    <div class="c1">
                        <div class="cm">
                            <img src="assets/images/pythoncourseinahmedabad.jpg"
                                alt="Affortable python programming course with skills internship and real projects training in ahmedabad at nirman skills academy.">
                        </div>
                        <br>
                        <div class="ct1">
                            <div class="ct1c1">
                                <h5>Ultimate Core PHP Full Stack Developer Course description</h5>
                            </div>
                        </div>
                        <br>
                        <div class="ct1">
                            <p>This course helps you learn ess
                                ential skills
                                with...
                                practical real-world training. link:Allcourses/Ultimatephp.php
                            </p>
                        </div>
                        <br>

                        <div class="ct1">
                            <div class="ct1c2"><a href="Allcourses/course3.html">View Details</a></div>
                        </div>

                    </div>
                    <div class="c1">
                        <div class="cm">
                            <img src="assets/images/animationcourseinahmedabad.jpg"
                                alt="Affortable animation course offering skills internship and hands-on animation training in Ahmedabad at nirman skills academy.">
                        </div>
                        <br>
                        <div class="ct1">
                            <div class="ct1c1">
                                <h5>Beginner's Guide to Vector Graphic Design DESCRIPTION </h5>
                            </div>
                        </div>
                        <br>
                        <div class="ct1">
                            <p>This beginner-friendly course introduces you to Adobe....
                                , Illustrator the industry-standard tool for creating scalable vector graphics</p>
                        </div>
                        <br>

                        <div class="ct1">
                            <div class="ct1c2"><a href="Allcourses/course1.html">View Details</a></div>
                        </div>

                    </div>
                    <div class="c1">
                        <div class="cm">
                            <img src="assets/images/ccccourseinahmedabad.jpg"
                                alt="Affortable ccc computer course with basic skills internship training in ahmedabad at nirman skills academy.">
                        </div>
                        <br>
                        <div class="ct1">
                            <div class="ct1c1">
                                <h5>Beginner's Guide to Vector Graphic Design DESCRIPTION</h5>
                            </div>
                        </div>
                        <br>
                        <div class="ct1">
                            <p>This beginner-friendly course introduces you to Adobe ....
                                Illustrator, the industry-standard tool for creating scalable vector graphics
                            </p>
                        </div>
                        <br>

                        <div class="ct1">
                            <div class="ct1c2"><a href="Allcourses/course2.html">View Details</a></div>
                        </div>

                    </div>
                    <div class="c1">
                        <div class="cm">
                            <img src="assets/images/graphiccourseinahmedabad.jpg"
                                alt="Affortable graphic design course with skills internship and practical training in Ahmedabad at niramn skills academy.">
                        </div>
                        <br>
                        <div class="ct1">
                            <div class="ct1c1">
                                <h5>Beginner's Guide to Vector Graphic Design DESCRIPTION</h5>
                            </div>
                        </div>
                        <br>
                        <div class="ct1">
                            <p>This beginner-friendly course introduces you to Adobe ....
                                Illustrator, the industry-standard tool for creating scalable vector graphics
                            </p>
                        </div>
                        <br>

                        <div class="ct1">
                            <div class="ct1c2"><a href="Allcourses/course3.html">View Details</a></div>
                        </div>
            </section>
        </div>

    </div>
    </div>
    <!-- *****************course end********************** -->


    <!-- ************Course-CHOOSE************************** -->
    <div class="Course-CHOOSE ">
        <h6 id="animated-text2">What Makes Us Different</h6>
        <h4>Transforming curiosity into mastery with<span>&nbsp; every lesson</span></h4>
        <p>Gain hands-on experience, guided by industry experts, and
            turn knowledge into real-world skills.</p>
        <br>
        <div class="Course-CHOOSE-flex ">
            <div class="Course-CHOOSE-c1">
                <h2><i class="fa-solid fa-user-check" style="margin-right:8px;color: purple;"></i></h2>
                <br>
                <h3>
                    INTERVIEW PREPERATION
                </h3>
                <br>
                <p>Get ready to crack interviews with guidance, mock rounds, and essential skills.</p>
            </div>
            <div class="Course-CHOOSE-c1">
                <h2>
                    <i class="fa-solid fa-forward-fast" style="color:indianred"></i>

                </h2>
                <br>
                <h3>
                    FAST TRACK BATCH
                </h3>
                <br>
                <p>Learn Graphic Design, PHP, Python in less time with intensive,practical sessions.</p>
            </div>
            <div class="Course-CHOOSE-c1">
                <h2><i class="fa-solid fa-clock" style="color: aqua;"></i></h2>
                <br>
                <h3>
                    Classes on Your Clock
                </h3>
                <br>
                <p>No fixed schedule learning that moves with your lifestyle.</p>
            </div>
            <div class="Course-CHOOSE-c1">
                <h2><i class="fa-solid fa-certificate" style="color: red;"></i></h2>
                <br>
                <h3>
                    Professional Certification
                </h3>
                <br>
                <p>Achieve certifications designed to enhance your professional profile./p>
            </div>
            <div class="Course-CHOOSE-c1">
                <h2><i class="fa-solid fa-file-contract" style="color:deeppink;"></i></h2>
                <br>
                <h3>
                    PROGRESS BASED INTERNSHIP

                </h3>
                <br>
                <p> Unlock hands-on projects step by step and build your professional experience as you grow.</p>
            </div>
            <div class="Course-CHOOSE-c1">
                <h2><i class="fa-solid fa-chalkboard-user" style="color: rgb(179, 121, 130);"></i></h2>
                <br>
                <h3>
                    GET ADVICE FROM MENTOR
                </h3>
                <br>
                <p>Get one-on-one advice and guidance from industry experts.</p>
            </div>
            <div class="Course-CHOOSE-c1">
                <h2><i class="fa-solid fa-diagram-project" style="color: orange;"></i></h2>
                <br>
                <h3>
                    Real Projects, Real Experience
                </h3>
                <br>
                <p>Work on live assignments, build your portfolio, and see your ideas
                    come to life.</p>
            </div>
            <div class="Course-CHOOSE-c1">
                <h2><i class="fa-solid fa-file-lines" style="color: brown;"></i></h2>
                <br>
                <h3>
                    Study smart with organized notes for fast concept revision.
                </h3>
                <br>
                <p>Review all your topics easily with well-organized notes that make learning faster and simpler.</p>
            </div>
        </div>
    </div>
    <!-- ************Course-CHOOSE end************************** -->



    <!-- ****************who are section******************** -->
    <div class="students">
        <h2>Who we&nbsp;<span>are</span></h2>
        <div class="student-flex">
            <div class="students-c1">
                <h5>Your Trusted <span>Partner in Skill </span>Development</h5>
                <br>
                <br>
                <ul>
                    <li>
                        RThe Nirman Skills Academy is a modern, career-focused training institute in Ahmedabad,dedicated
                        to
                        helping students develop real, industry-ready skills.As a new-age skill academy, our mission is
                        simple—to provide practical skills that empower students to build strong and successful careers.
                    </li>
                    <br>
                    <br>
                    <hr>
                    <br>
                    <br>
                    <li>
                        At The Nirman Skills Academy, we focus on hands-on learning, updated tools, and training
                        programs designed for real-world industry needs, making us one of the most promising skill
                        development academies in Ahmedabad. Whether you are a beginner or looking to upskill, we
                        help you learn step by step with complete guidance and personalized support.
                    </li>
                    <br>
                    <br>
                    <hr>
                    <br>
                    <br>
                    <li>
                        We aim to create an environment where students don’t just complete a course—
                        they gain confidence, skills, and a clear direction for their future.

                    </li>
                </ul>
            </div>
            <div class="students-wrapper">
                <div class="side-box left-box">
                    44,200+
                    Trained Students
                </div>
                <div class="img-student">
                    <img src="assets/images/skilldevelopmentcoursesinahmedabad.jpg"
                        alt="career-focused skill development training for Students">
                </div>
                <div class="side-box right-box">
                    1500+
                    Hiring Partners
                </div>
            </div>
        </div>
    </div>
    <!-- ****************who are sectio end******************** -->



    <!-- **************************** SKILL SCROLL SECTION ************************************* -->

    <section class="skill-scroll-section">

        <h2 class="scroll-title">Shape Your Future with <span>In-Demand Skills</span></h2>

        <div class="scroll-progress">
            <div class="tab" id="tab1">Graphic Design</div>
            <div class="tab" id="tab2">Animation</div>
            <div class="tab" id="tab3">Website Development</div>
            <div class="progress-fill"></div>
        </div>

        <div class="image-stack">

            <!-- Slide 1 -->
            <div class="scroll-slide slide1">
                <div class="slide-left">
                    <h3>Graphic Design</h3>
                    <p> Graphic design turns ideas into stunning visuals with creativity and thoughtful design skills.
                        In this course, you will learn how to transform concepts into powerful visuals by creating
                        logos, posters, social media graphics, and digital artwork that truly communicate messages.
                        Through practical learning and creative exercises, you’ll develop designs that not only look
                        good but also speak, connect, and leave a lasting impact.</p>
                </div>

                <div class="slide-right">
                    <img src="assets/images/G1-01.jpg" class="stack-img img-a" alt="graphic image">
                    <img src="assets/images/G2-01.jpg" class="stack-img img-b" alt="graphic image">
                    <img src="assets/images/G3-01.jpg" class="stack-img img-c" alt="graphic image">
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="scroll-slide slide2">
                <div class="slide-left">
                    <h3>Aimation</h3>
                    <p>
                        Bring drawings to life by learning the art of motion, character movement, and visual
                        storytelling. This course helps you understand how static visuals turn into engaging animated
                        scenes with expressions, timing, and flow. Through creative practice and hands-on projects, you
                        will learn to create characters, scenes, and stories that move, connect with audiences, and turn
                        simple ideas into exciting animated adventures.
                    </p>
                </div>

                <div class="slide-right">
                    <video src="assets/videos/robot.mp4" autoplay loop muted playsinline
                        class="stack-img img-a"></video>
                    <video src="assets/videos/Bird.mp4" autoplay loop muted playsinline class="stack-img img-b"></video>
                    <video src="assets/videos/octopus.mp4" autoplay loop muted playsinline
                        class="stack-img img-c"></video>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="scroll-slide slide3">
                <div class="slide-left">
                    <h3>Website Development</h3>
                    <p>
                        From coding basics to full-fledged websites, learn step by step and see your projects come
                        alive. This course helps you understand how websites work from the inside, starting with simple
                        concepts and moving towards complete website creation. You will learn how to build pages,
                        connect different parts, and make websites work smoothly. With practical learning and real
                        projects, you gain confidence to create, manage, and improve websites on your own. </p>
                </div>

                <div class="slide-right">
                    <img src="assets/images/webdevelopmenttraining.jpg" class="stack-img img-a"
                        alt="Web Development training with practical learning">
                    <img src="assets/images/webdevelopmentforbeginner.jpg" class="stack-img img-b"
                        alt="Hands-on web-development training for beginner">
                    <img src="assets/images/courseofwebdevelopment.jpg" class="stack-img img-c"
                        alt="Professional web-development skills learning sessions">
                </div>
            </div>

        </div>
    </section>
    <!-- **************************** SKILL SCROLL SECTION END ************************************* -->




    <section class="vision-mission-section">

        <div class="vision-mision-container">
            <button class="ani-btn">A Vision That Inspires Growth
                <span class="bottom-line"></span>
                <span class="left-line"></span>
            </button>
            <h2 class="vision-main-heading">Where Learning Meets <span class="learn-anim">Innovation</span></h2>

            <p class="vision-main-para">
                Learning here isn’t just watching—it’s doing. Hands-on projects led by top
                professionals prepare you for real success.
            </p>
            <div class="vm-boxes">

                <!-- Vision Box -->
                <div class="vm-box">
                    <div class="vm-icon">
                        <i class="fa-solid fa-lightbulb vm-icon"></i>

                    </div>
                    <h3> Our Future Direction</h3>
                    <p>At Nirman Skills Academy, Ahmedabad, our vision is to help every learner
                        build practical skills, grow confidently, and succeed in their career. We
                        strive to be a place where learning meets real-world opportunities,
                        preparing students to achieve their dreams and make a positive impact in
                        their professional journey.</p>

                </div>

                <!-- Mission Box -->
                <div class="vm-box">
                    <div class="vm-icon">
                        <i class="fa-solid fa-rocket vm-icon"></i>

                    </div>
                    <h3>Our Commitment</h3>
                    <p> At Nirman Skills Academy, Ahmedabad, we help learners gain industryready skills, hands-on
                        training, and career-focused knowledge. Our
                        mission is to bridge the gap between education and real-world
                        opportunities, empowering every student to grow, innovate, and succeed in
                        a bright, rewarding career.</p>
                </div>

            </div>
        </div>

    </section>

    <section class="faq-section">
        <div class="faq-container">

            <h2 class="faq-title">Frequently Asked <span>Questions</span></h2>

            <div class="faq-item">
                <button class="faq-question">
                    What is the FastTrack Batch, and who is it for?
                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>The FastTrack Batch is a specially designed short-term training program that helps students learn
                        key skills in a shorter duration. It’s ideal for those who want to upskill quickly or start
                        their career sooner, without compromising on hands-on learning and guidance.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    What is a Progress-Based Internship?
                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>A Progress-Based Internship is offered to students after completing their course. During this
                        internship, your tasks and responsibilities grow as you demonstrate skills and understanding.
                        The more you learn and perform, the more advanced projects you get to work on, ensuring
                        continuous growth and hands-on, real-world experience.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    What is a Professional Certificate, and why should I pursue it?​
                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>A Professional Certificate is an industry-recognized credential that validates your skills and
                        knowledge in a specific field. It helps you stand out to employers, enhances your career
                        opportunities, and proves that you have the practical expertise needed to succeed in the
                        professional world.​ </p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    What are the Online and Offline Batches, and how do they work?​
                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>We offer both Online and Offline Batches to suit your learning preference. Online Batches let you
                        learn from anywhere with live interactive sessions, while Offline Batches give you hands-on,
                        in-person training at our academy. You can choose the mode that fits your lifestyle best.​ </p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    What are Flexible Timings, and how can they help me?​
                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>Flexible Timings allow you to schedule your classes according to your convenience. Whether you
                        are a student, working professional, or have other commitments, you can pick time slots that fit
                        your routine without compromising your learning.​</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    How does the referral discount work at Nirman skills Academy?
                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>If you enroll in any Nirman Academy course through a reference from a past student, you will
                        receive a 20% discount on your course fee.</p>
                </div>
            </div>

        </div>
    </section>

    <div class="form-container">
        <h2>Book Your Free Consultation</h2>
        <form id="consultForm" method="POST" action="api/consult.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact No</label>
                <input type="tel" id="contact" name="contact" placeholder="Enter your phone number" required>
            </div>
            <div class="form-group">
                <label for="service">Select Service</label>
                <select id="service" name="service" required>
                    <option value="" disabled selected>Select an option</option>
                    <option value="CCC">CCC</option>
                    <option value="Graphic Design">Graphic Design</option>
                    <option value="Animation">Animation</option>
                    <option value="Website Development">Website Development</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>


    <?php
    include "footer.php";
    ?>  
    <style>
        #ad-popup {
            transform: translate(-50%, -50%) scale(0.6);
            transition: 0.4s ease;
        }

        #ad-popup.active {
            display: block;
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }
    </style>


    <script>


        let popupShown = false; const popup = document.getElementById("ad-popup"); const overlay = document.getElementById("ad-overlay"); const closeBtn = document.querySelector(".ad-close"); /* Show popup on scroll (50%) */ window.addEventListener("scroll", () => { if (!popupShown && window.scrollY > window.innerHeight * 0.5) { popup.classList.add("active"); overlay.style.display = "block"; popupShown = true; } }); /* Close popup function */ function closePopup() { popup.classList.remove("active"); overlay.style.display = "none"; } /* Close events */ closeBtn.addEventListener("click", closePopup); overlay.addEventListener("click", closePopup);

        // Email validation function
        function isValidEmail(email) {
            const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[A-Za-z]{2,}$/;
            return regex.test(email);
        }

        document.getElementById("ad-submit").onclick = function () {

            const email = document.getElementById("ad-email").value.trim();
            const msg = document.getElementById("ad-message");

            if (!email) {
                msg.textContent = "Please enter your email.";
                msg.className = "ad-message error";
                return;
            }

            if (!isValidEmail(email)) {
                msg.textContent = "Please enter a valid verified email address.";
                msg.className = "ad-message error";
                return;
            }

            // Send to backend
            fetch("api/subscribe.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email: email })
            })
                .then(response => response.json())
                .then(result => {
                    if (result.status === "success") {
                        msg.textContent = result.message;
                        msg.className = "ad-message success";

                        // Optional: clear input
                        document.getElementById("ad-email").value = "";

                        // Auto close popup after 2 sec
                        setTimeout(() => {
                            document.getElementById("ad-popup").style.display = "none";
                        }, 2000);
                    } else {
                        msg.textContent = result.message;
                        msg.className = "ad-message error";
                    }
                })
                .catch(() => {
                    msg.textContent = "Something went wrong!";
                    msg.className = "ad-message error";
                });
        };
    </script>
    <script>
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

        function convertH2ToButton(h2Id) {
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

        //faq-item
        const faqItems = document.querySelectorAll(".faq-item");

        faqItems.forEach(item => {
            item.querySelector(".faq-question").addEventListener("click", () => {
                item.classList.toggle("active");
            });
        });
    </script>
    <Script src="assets/js/script.js"></Script>

</body>

</html>