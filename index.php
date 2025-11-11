<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[ thefacebook ]</title>
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
                <div class="welcome-box">
                    <h2>[ Welcome to Thefacebook ]</h2>
                    
                    <p class="lead">Thefacebook is an online directory that connects people through social networks at colleges.</p>
                    
                    <p>We have opened up Thefacebook for popular consumption at <strong>Harvard University</strong>.</p>
                    
                    <p>You can use Thefacebook to:</p>
                    <ul>
                        <li>Search for people at your school</li>
                        <li>Find out who are in your classes</li>
                        <li>Look up your friends' friends</li>
                        <li>See a visualization of your social network</li>
                    </ul>
                    
                    <p>To get started, click below to register. If you have already registered, you can log in.</p>
                    
                    <div class="text-center mt-4">
                        <a href="register.php" class="btn btn-primary">Register</a>
                        <a href="login.php" class="btn btn-secondary">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>