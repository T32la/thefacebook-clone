<?php
require_once 'config.php';
requireLogin();

// Obtener información del usuario
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - [ thefacebook ]</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <div class="text-center mb-3">
                        <img src="uploads/<?php echo htmlspecialchars($user['avatar']); ?>" 
                             alt="<?php echo htmlspecialchars($user['name']); ?>" 
                             class="img-thumbnail"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    
                    <h5 class="text-center"><?php echo htmlspecialchars($user['name']); ?></h5>
                    <p class="text-center text-muted"><?php echo htmlspecialchars($user['status']); ?></p>
                    
                    <hr>
                    
                    <ul class="list-unstyled">
                        <li><a href="home.php">Profile</a></li>
                        <li><a href="edit.php">Edit Profile</a></li>
                        <li><a href="search.php">Search</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="profile-content">
                    <h2>Welcome to Thefacebook, <?php echo htmlspecialchars($user['name']); ?>!</h2>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <strong>Profile Information</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150"><strong>Name:</strong></td>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td><?php echo htmlspecialchars($user['status']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>University:</strong></td>
                                    <td><?php echo htmlspecialchars($user['university_domain']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Member since:</strong></td>
                                    <td><?php echo date('F j, Y', strtotime($user['created_at'])); ?></td>
                                </tr>
                                <?php if (!empty($user['bio'])): ?>
                                <tr>
                                    <td><strong>Bio:</strong></td>
                                    <td><?php echo nl2br(htmlspecialchars($user['bio'])); ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h4>What you can do:</h4>
                        <ul>
                            <li>Search for people at your school</li>
                            <li>Find out who are in your classes</li>
                            <li>Look up your friends' friends</li>
                            <li>See a visualization of your social network</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>