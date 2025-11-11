<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Use - [ thefacebook ]</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <?php include 'includes/sidebar.php'; ?>
            </div>

            <div class="col-md-9">
                <div class="about-box">
                    <h2>Terms of Use</h2>

                    <div class="section-box">
                        <h4>Introduction</h4>
                        <p>Welcome to thefacebook. By using our services, you agree to these terms. Please read them carefully.</p>
                    </div>

                    <div class="section-box">
                        <h4>Eligibility</h4>
                        <p>You must be a current student, faculty, or staff member of a participating university to use thefacebook. You must use your university email address to register.</p>
                    </div>

                    <div class="section-box">
                        <h4>User Conduct</h4>
                        <p>You agree to:</p>
                        <ul>
                            <li>Provide accurate information about yourself</li>
                            <li>Keep your password secure and confidential</li>
                            <li>Not impersonate others</li>
                            <li>Not post harmful, offensive, or illegal content</li>
                            <li>Respect other users' privacy</li>
                        </ul>
                    </div>

                    <div class="section-box">
                        <h4>Privacy</h4>
                        <p>Your information is visible only to other users at your school who are logged into thefacebook. We do not share your information with third parties.</p>
                    </div>

                    <div class="section-box">
                        <h4>Content</h4>
                        <p>You retain all rights to the content you post. By posting content, you grant thefacebook a license to display it to other users.</p>
                    </div>

                    <div class="section-box">
                        <h4>Termination</h4>
                        <p>We reserve the right to terminate accounts that violate these terms without prior notice.</p>
                    </div>

                    <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-secondary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
