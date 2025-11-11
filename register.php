<?php
require_once 'config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $agree = isset($_POST['agree']);
    $status = sanitize($_POST['status'] ?? 'Student');
    
    // Validaciones
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } elseif (!isUniversityEmail($email)) {
        $errors[] = "You must use a university email address (e.g., @uvg.edu.gt)";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if (!$agree) {
        $errors[] = "You must agree to the Terms of Use";
    }
    
    // Si no hay errores, registrar usuario
    if (empty($errors)) {
        $conn = getDBConnection();
        
        // Verificar si el email ya existe
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $errors[] = "Email already registered";
        } else {
            // Obtener dominio universitario
            $domain = substr(strrchr($email, "@"), 1);
            
            // Insertar usuario (contraseña en texto plano como se pidió)
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, status, university_domain) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $password, $status, $domain);
            
            if ($stmt->execute()) {
                $success = true;
                $_SESSION['registration_success'] = true;
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
        
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - [ thefacebook ]</title>
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
                <div class="registration-box">
                    <h2>Registration</h2>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            Registration successful! You can now <a href="login.php">log in</a>.
                        </div>
                    <?php else: ?>
                        <p>To register for thefacebook.com, just fill in the four fields below. You will have a chance to enter additional information and submit a picture once you have registered.</p>
                        
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" id="registerForm">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Name:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" value="<?php echo $_POST['name'] ?? ''; ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Status:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="status">
                                        <option>Student</option>
                                        <option>Alumni</option>
                                        <option>Faculty</option>
                                        <option>Staff</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Email: (harvard)</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                                    <small class="form-text text-muted">Must be a university email (e.g., @uvg.edu.gt)</small>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Password:* (not fas)</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name="password" required minlength="6">
                                    <small class="form-text text-muted">* You can choose any password. It does not have to be, and should not be, your FAS password.</small>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="agree" id="agree" required>
                                    <label class="form-check-label" for="agree">
                                        I have read and understood the <a href="terms.php" target="_blank">Terms of Use</a>, and I agree to them.
                                    </label>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Register Now!</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/validation.js"></script>
</body>
</html>