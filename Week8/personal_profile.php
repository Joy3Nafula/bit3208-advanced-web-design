<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f0eb;
            min-height: 100vh;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-container {
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(93, 64, 55, 0.15);
            margin-bottom: 20px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #E07A2E;
            margin: 0 auto 15px;
            display: block;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 700;
            color: #5D4037;
            text-align: center;
        }

        .profile-role {
            text-align: center;
            color: #E07A2E;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .profile-about {
            color: #8D6E63;
            line-height: 1.6;
            text-align: center;
            max-width: 500px;
            margin: 0 auto;
        }

        .profile-contact {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 15px;
            align-items: center;
        }

        .profile-contact a {
            color: #E07A2E;
            text-decoration: none;
            font-weight: 500;
        }

        .profile-contact a:hover {
            text-decoration: underline;
        }

        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
        }

        .skill-tag {
            background: #5D4037;
            color: white;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 13px;
        }

        @media (min-width: 768px) {
            .profile-image { width: 180px; height: 180px; }
            .profile-name { font-size: 28px; }
            .profile-contact { flex-direction: row; justify-content: center; gap: 20px; }
            .profile-card { padding: 40px; }
        }

        @media (min-width: 1024px) {
            .profile-image { width: 200px; height: 200px; }
            .profile-name { font-size: 32px; }
            .profile-card { padding: 50px; }
        }
    </style>
</head>
<body>

<div class="profile-container">

    <div class="profile-card">
        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=penny&gender=female" alt="Avatar" class="profile-image">

        <h1 class="profile-name">Joy Karani</h1>
        <p class="profile-role">👨‍💻 Web Developer &amp; Designer</p>

        <div class="profile-about">
            <p>
                I am a passionate web developer with expertise in PHP, MySQL, HTML, CSS, and JavaScript.
                Currently pursuing Computer Science at the Mount Kenya UNI.
                I love building responsive, user-friendly web applications that solve real-world problems.<br>
                Reach out for collaborations and service delivery
            </p>
        </div>

        <div class="profile-contact">
            <a href="mailto:joy@example.com">📧 karanijoy15@gmail.com</a>
            <a href="tel:+254712345678">📞 +254 112 681377</a>
            <a href="https://github.com/Joy3Nafula" target="_blank">🐙 GitHub</a>
            <a href="#" target="_blank">🔗 LinkedIn</a>
        </div>
    </div>

    <div class="profile-card">
        <h3 style="color:#5D4037; margin-bottom:15px; text-align:center;">📊 Skills</h3>
        <div class="skills-container">
            <span class="skill-tag">PHP</span>
            <span class="skill-tag">MySQL</span>
            <span class="skill-tag">HTML5</span>
            <span class="skill-tag">CSS3</span>
            <span class="skill-tag">JavaScript</span>
            <span class="skill-tag">Git</span>
            <span class="skill-tag">GitHub</span>
            <span class="skill-tag">XAMPP</span>
        </div>
    </div>

</div>

</body>
</html>