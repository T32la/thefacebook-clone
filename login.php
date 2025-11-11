<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        $conn = getDBConnection();
        
        $stmt = $conn->prepare("SELECT id, name, email, password, avatar FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Comparar contraseña en texto plano
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_avatar'] = $user['avatar'];
                
                header('Location: home.php');
                exit;
            } else {
                $error = "Invalid email or password";
            }
        } else {
            $error = "Invalid email or password";
        }
        
        $stmt->close();
        $conn->close();
    } else {
        $error = "Please enter email and password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - [ thefacebook ]</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="login-sidebar">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" class="form-control form-control-sm" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" class="form-control form-control-sm" name="password" required>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-sm">login</button>
                            <a href="register.php" class="btn btn-secondary btn-sm">register</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="welcome-box">
                    <h2>[ Welcome to Thefacebook ]</h2>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['registration_success'])): ?>
                        <div class="alert alert-success">
                            Registration successful! Please log in with your credentials.
                        </div>
                        <?php unset($_SESSION['registration_success']); ?>
                    <?php endif; ?>
                    
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