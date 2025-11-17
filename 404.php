<?php
require_once 'config.php';
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - [ thefacebook ]</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .error-404-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        
        .error-404-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 60px 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .error-404-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #3B5998, #667eea, #3B5998);
            background-size: 200% 100%;
            animation: gradientMove 3s ease infinite;
        }
        
        @keyframes gradientMove {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .error-404-icon {
            font-size: 120px;
            margin-bottom: 30px;
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .error-404-number {
            font-size: 140px;
            font-weight: 900;
            color: #3B5998;
            line-height: 1;
            margin-bottom: 20px;
            text-shadow: 
                3px 3px 0px #667eea,
                6px 6px 0px rgba(59, 89, 152, 0.2);
            letter-spacing: -5px;
        }
        
        .error-404-title {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            font-family: Tahoma, Verdana, Arial, sans-serif;
        }
        
        .error-404-subtitle {
            font-size: 18px;
            color: #3B5998;
            margin-bottom: 25px;
            font-weight: 600;
        }
        
        .error-404-message {
            font-size: 15px;
            color: #666;
            margin-bottom: 40px;
            line-height: 1.6;
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .error-404-divider {
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #3B5998, #667eea);
            margin: 30px auto;
            border-radius: 2px;
        }
        
        .btn-home {
            background: linear-gradient(135deg, #3B5998 0%, #667eea 100%);
            color: white;
            border: none;
            padding: 15px 45px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(59, 89, 152, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(59, 89, 152, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .btn-home:active {
            transform: translateY(-1px);
        }
        
        .btn-home i {
            margin-right: 8px;
        }
        
        .error-404-links {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }
        
        .error-404-links a {
            color: #3B5998;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin: 0 12px;
            transition: all 0.2s;
        }
        
        .error-404-links a:hover {
            color: #667eea;
            text-decoration: underline;
        }
        
        .error-404-animation {
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(59, 89, 152, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 4s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.8; }
        }
        
        @media (max-width: 768px) {
            .error-404-card {
                padding: 40px 30px;
            }
            
            .error-404-number {
                font-size: 100px;
            }
            
            .error-404-title {
                font-size: 24px;
            }
            
            .error-404-subtitle {
                font-size: 16px;
            }
            
            .error-404-message {
                font-size: 14px;
            }
            
            .btn-home {
                padding: 12px 35px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="error-404-wrapper">
        <div class="error-404-card">
            <div class="error-404-animation"></div>
            
            <div class="error-404-icon">🔍</div>
            
            <div class="error-404-number">404</div>
            
            <h1 class="error-404-title">Page Not Found</h1>
            
            <p class="error-404-subtitle">Oops! We couldn't find that page</p>
            
            <div class="error-404-divider"></div>
            
            <p class="error-404-message">
                The page you are looking for might have been removed, had its name changed, 
                or is temporarily unavailable. Don't worry, you can find your way back!
            </p>
            
            <a href="<?php echo isLoggedIn() ? 'home.php' : 'index.php'; ?>" class="btn-home">
                <span>🏠</span> Go to Home
            </a>
            
            <div class="error-404-links">
                <?php if (isLoggedIn()): ?>
                    <a href="home.php">My Profile</a>
                    <a href="friends.php">My Friends</a>
                    <a href="search.php">Search</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
                <a href="about.php">About</a>
                <a href="faq.php">FAQ</a>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>