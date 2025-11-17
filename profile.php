<?php
require_once 'config.php';
requireLogin();

// Obtener ID del perfil a ver
$profile_id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];

// Obtener información del usuario del perfil
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $profile_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: home.php');
    exit;
}

$profile_user = $result->fetch_assoc();
$is_own_profile = ($profile_id == $_SESSION['user_id']);
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($profile_user['name']); ?>'s Profile - [ thefacebook ]</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid mt-3">
        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-md-2">
                <div class="quick-search-box">
                    <div class="search-header">quick search</div>
                    <form action="search.php" method="GET" class="mt-2">
                        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search...">
                    </form>
                </div>
                
                <div class="sidebar-menu mt-3">
                    <ul class="list-unstyled">
                        <?php include 'includes/left-bar.php'; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-7">
                <div class="profile-header">
                    <h4>
                        <?php echo htmlspecialchars($profile_user['name']); ?>'s Profile
                        <?php if ($is_own_profile): ?>
                            <a href="edit.php" class="small">[ edit ]</a>
                        <?php endif; ?>
                    </h4>
                </div>
                
                <div class="row mt-3">
                    <!-- Picture Box -->
                    <div class="col-md-5">
                        <div class="profile-box">
                            <div class="profile-box-header">Picture</div>
                            <div class="profile-box-content text-center p-3">
                                <img src="uploads/<?php echo htmlspecialchars($profile_user['avatar']); ?>" 
                                     alt="<?php echo htmlspecialchars($profile_user['name']); ?>" 
                                     class="profile-picture">
                            </div>
                        </div>
                        
                        <?php if (!$is_own_profile): ?>
                        <!-- Send Message Box -->
                        <div class="profile-box mt-3">
                            <div class="profile-box-content p-3">
                                <div class="form-group mb-2">
                                    <button class="btn btn-sm btn-block btn-primary">Send <?php echo htmlspecialchars(explode(' ', $profile_user['name'])[0]); ?> a Message</button>
                                </div>
                                <div class="form-group mb-0">
                                    <button class="btn btn-sm btn-block btn-secondary">Poke <?php echo (strpos($profile_user['name'], 'a') !== false) ? 'Her' : 'Him'; ?>!</button>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Connection Box -->
                        <div class="profile-box mt-3">
                            <div class="profile-box-header">Connection</div>
                            <div class="profile-box-content p-3 text-center">
                                <span class="connection-text">
                                    <?php if ($is_own_profile): ?>
                                        This is you
                                    <?php else: ?>
                                        Not in your network
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Mutual Friends Box -->
                        <div class="profile-box mt-3">
                            <div class="profile-box-header">Mutual Friends</div>
                            <div class="profile-box-content p-3">
                                <p class="small mb-0">You have <strong>0 friends</strong> in common with <?php echo htmlspecialchars(explode(' ', $profile_user['name'])[0]); ?>.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Information Box -->
                    <div class="col-md-7">
                        <div class="profile-box">
                            <div class="profile-box-header">Information</div>
                            <div class="profile-box-content p-3">
                                <!-- Account Info -->
                                <div class="info-section">
                                    <div class="info-section-title">Account Info:</div>
                                    <table class="info-table">
                                        <tr>
                                            <td class="info-label">Name:</td>
                                            <td class="info-value"><?php echo htmlspecialchars($profile_user['name']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="info-label">Member Since:</td>
                                            <td class="info-value">
                                                <?php echo date('F j, Y', strtotime($profile_user['created_at'])); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="info-label">Last Update:</td>
                                            <td class="info-value">
                                                <?php echo date('F j, Y', strtotime($profile_user['created_at'])); ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <!-- Basic Info -->
                                <div class="info-section mt-3">
                                    <div class="info-section-title">Basic Info:</div>
                                    <table class="info-table">
                                        <tr>
                                            <td class="info-label">School:</td>
                                            <td class="info-value">
                                                <?php 
                                                $domain_parts = explode('.', $profile_user['university_domain']);
                                                echo ucfirst($domain_parts[0]); 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="info-label">Status:</td>
                                            <td class="info-value"><?php echo htmlspecialchars($profile_user['status']); ?></td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <!-- Contact Info -->
                                <div class="info-section mt-3">
                                    <div class="info-section-title">Contact Info:</div>
                                    <table class="info-table">
                                        <tr>
                                            <td class="info-label">Email:</td>
                                            <td class="info-value">
                                                <a href="mailto:<?php echo htmlspecialchars($profile_user['email']); ?>">
                                                    <?php echo htmlspecialchars($profile_user['email']); ?>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <?php if (!empty($profile_user['bio'])): ?>
                                <!-- Bio / About -->
                                <div class="info-section mt-3">
                                    <div class="info-section-title">About <?php echo htmlspecialchars(explode(' ', $profile_user['name'])[0]); ?>:</div>
                                    <div class="info-bio">
                                        <?php echo nl2br(htmlspecialchars($profile_user['bio'])); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Sidebar -->
            <div class="col-md-3">
                <div class="sidebar-ad">
                    <div class="ad-header">Puget Sound</div>
                    <div class="ad-content">
                        <p class="small text-muted text-center">Advertisement space</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>