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
$friendCount = 0;

// Obtener universidad del dominio
$domain_parts = explode('.', $user['university_domain']);
$university_name = ucfirst($domain_parts[0]);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['name']); ?>'s Profile - [ thefacebook ]</title>
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
                        <li><a href="home.php">My Profile [ edit ]</a></li>
                        <li><a href="friends.php">My Friends</a></li>
                        <li><a href="parties.php">My Parties</a></li>
                        <li><a href="messages.php">My Messages</a></li>
                        <li><a href="account.php">My Account</a></li>
                        <li><a href="privacy.php">My Privacy</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-7">
                <div class="profile-header">
                    <h4><?php echo htmlspecialchars($user['name']); ?>'s Profile</h4>
                </div>
                
                <div class="row mt-3">
                    <!-- Picture Box -->
                    <div class="col-md-5">
                        <div class="profile-box">
                            <div class="profile-box-header">Picture</div>
                            <div class="profile-box-content text-center p-3">
                                <img src="uploads/<?php echo htmlspecialchars($user['avatar']); ?>" 
                                     alt="<?php echo htmlspecialchars($user['name']); ?>" 
                                     class="profile-picture">
                            </div>
                        </div>
                        
                        <!-- Send Message Box -->
                        <div class="profile-box mt-3">
                            <div class="profile-box-content p-3">
                                <div class="form-group mb-2">
                                    <a href="messages.php?compose=<?php echo $user['id']; ?>" class="btn btn-sm btn-block btn-primary">
                                        Send <?php echo htmlspecialchars(explode(' ', $user['name'])[0]); ?> a Message
                                    </a>
                                </div>
                                <div class="form-group mb-0">
                                    <button class="btn btn-sm btn-block btn-secondary" onclick="pokeUser(<?php echo $user['id']; ?>)">
                                        Poke Him!
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Connection Box -->
                        <div class="profile-box mt-3">
                            <div class="profile-box-header">Connection</div>
                            <div class="profile-box-content p-3 text-center">
                                <span class="connection-text">This is you</span>
                            </div>
                        </div>
                        
                        <!-- Mutual Friends Box (cuando vean otro perfil) -->
                        <div class="profile-box mt-3">
                            <div class="profile-box-header">Friends at <?php echo htmlspecialchars($university_name); ?></div>
                            <div class="profile-box-content p-3">
                                <?php if ($friendCount > 0): ?>
                                    <p class="small mb-2">
                                        <strong><?php echo htmlspecialchars($user['name']); ?></strong> has 
                                        <a href="friends.php"><strong><?php echo $friendCount; ?> friend<?php echo $friendCount != 1 ? 's' : ''; ?></strong></a> 
                                        at <?php echo htmlspecialchars($university_name); ?>.
                                    </p>
                                    
                                    <?php if (!empty($friends)): ?>
                                        <div class="friends-grid">
                                            <?php foreach ($friends as $friend): ?>
                                                <div class="friend-thumb">
                                                    <a href="profile.php?id=<?php echo $friend['id']; ?>">
                                                        <img src="uploads/<?php echo htmlspecialchars($friend['avatar']); ?>" 
                                                             alt="<?php echo htmlspecialchars($friend['name']); ?>"
                                                             title="<?php echo htmlspecialchars($friend['name']); ?>">
                                                        <div class="friend-name"><?php echo htmlspecialchars(explode(' ', $friend['name'])[0]); ?></div>
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p class="small mb-0">You don't have any friends yet. <a href="search.php">Find friends</a></p>
                                <?php endif; ?>
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
                                            <td class="info-value"><?php echo htmlspecialchars($user['name']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="info-label">Member Since:</td>
                                            <td class="info-value">
                                                <?php echo date('F j, Y', strtotime($user['created_at'])); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="info-label">Last Update:</td>
                                            <td class="info-value">
                                                <?php echo date('F j, Y', strtotime($user['updated_at'])); ?>
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
                                                <a href="search.php?school=<?php echo urlencode($university_name); ?>">
                                                    <?php echo htmlspecialchars($university_name); ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="info-label">Status:</td>
                                            <td class="info-value"><?php echo htmlspecialchars($user['status']); ?></td>
                                        </tr>
                                        <?php if (!empty($user['sex'])): ?>
                                        <tr>
                                            <td class="info-label">Sex:</td>
                                            <td class="info-value"><?php echo htmlspecialchars($user['sex']); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($user['residence'])): ?>
                                        <tr>
                                            <td class="info-label">Residence:</td>
                                            <td class="info-value"><?php echo htmlspecialchars($user['residence']); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($user['birthday'])): ?>
                                        <tr>
                                            <td class="info-label">Birthday:</td>
                                            <td class="info-value">
                                                <?php echo date('F j, Y', strtotime($user['birthday'])); ?>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($user['hometown'])): ?>
                                        <tr>
                                            <td class="info-label">Home Town:</td>
                                            <td class="info-value"><?php echo htmlspecialchars($user['hometown']); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($user['high_school'])): ?>
                                        <tr>
                                            <td class="info-label">High School:</td>
                                            <td class="info-value"><?php echo htmlspecialchars($user['high_school']); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                                
                                <!-- Contact Info -->
                                <div class="info-section mt-3">
                                    <div class="info-section-title">Contact Info:</div>
                                    <table class="info-table">
                                        <tr>
                                            <td class="info-label">Email:</td>
                                            <td class="info-value">
                                                <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>">
                                                    <?php echo htmlspecialchars($user['email']); ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php if (!empty($user['screenname'])): ?>
                                        <tr>
                                            <td class="info-label">Screenname:</td>
                                            <td class="info-value"><?php echo htmlspecialchars($user['screenname']); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($user['mobile'])): ?>
                                        <tr>
                                            <td class="info-label">Mobile:</td>
                                            <td class="info-value"><?php echo htmlspecialchars($user['mobile']); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                                
                                <!-- Personal Info -->
                                <?php if (!empty($user['looking_for']) || !empty($user['interested_in']) || !empty($user['relationship_status'])): ?>
                                <div class="info-section mt-3">
                                    <div class="info-section-title">Personal Info:</div>
                                    <table class="info-table">
                                        <?php if (!empty($user['looking_for'])): ?>
                                        <tr>
                                            <td class="info-label">Looking For:</td>
                                            <td class="info-value"><?php echo htmlspecialchars($user['looking_for']); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($user['interested_in'])): ?>
                                        <tr>
                                            <td class="info-label">Interested In:</td>
                                            <td class="info-value"><?php echo htmlspecialchars($user['interested_in']); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($user['relationship_status'])): ?>
                                        <tr>
                                            <td class="info-label">Relationship Status:</td>
                                            <td class="info-value">
                                                <?php 
                                                echo htmlspecialchars($user['relationship_status']);
                                                if (!empty($user['partner_name'])) {
                                                    echo ' with ' . htmlspecialchars($user['partner_name']);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($user['political_views'])): ?>
                                        <tr>
                                            <td class="info-label">Political Views:</td>
                                            <td class="info-value"><?php echo htmlspecialchars($user['political_views']); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($user['interests'])): ?>
                                        <tr>
                                            <td class="info-label">Interests:</td>
                                            <td class="info-value"><?php echo nl2br(htmlspecialchars($user['interests'])); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Favorites -->
                                <?php if (!empty($user['favorite_music']) || !empty($user['favorite_movies']) || !empty($user['favorite_books']) || !empty($user['favorite_tv'])): ?>
                                <div class="info-section mt-3">
                                    <div class="info-section-title">Favorites:</div>
                                    <table class="info-table">
                                        <?php if (!empty($user['favorite_music'])): ?>
                                        <tr>
                                            <td class="info-label">Favorite Music:</td>
                                            <td class="info-value"><?php echo nl2br(htmlspecialchars($user['favorite_music'])); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($user['favorite_movies'])): ?>
                                        <tr>
                                            <td class="info-label">Favorite Movies:</td>
                                            <td class="info-value"><?php echo nl2br(htmlspecialchars($user['favorite_movies'])); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($user['favorite_tv'])): ?>
                                        <tr>
                                            <td class="info-label">Favorite TV:</td>
                                            <td class="info-value"><?php echo nl2br(htmlspecialchars($user['favorite_tv'])); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($user['favorite_books'])): ?>
                                        <tr>
                                            <td class="info-label">Favorite Books:</td>
                                            <td class="info-value"><?php echo nl2br(htmlspecialchars($user['favorite_books'])); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($user['favorite_quotes'])): ?>
                                        <tr>
                                            <td class="info-label">Favorite Quotes:</td>
                                            <td class="info-value"><?php echo nl2br(htmlspecialchars($user['favorite_quotes'])); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                                <?php endif; ?>
                                
                                <!-- About Me -->
                                <?php if (!empty($user['about_me']) || !empty($user['bio'])): ?>
                                <div class="info-section mt-3">
                                    <div class="info-section-title">About Me:</div>
                                    <div class="info-bio">
                                        <?php 
                                        $about = !empty($user['about_me']) ? $user['about_me'] : $user['bio'];
                                        echo nl2br(htmlspecialchars($about)); 
                                        ?>
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
                    <div class="ad-header"><?php echo htmlspecialchars($university_name); ?></div>
                    <div class="ad-content text-center p-3">
                        <p class="small text-muted">Advertisement space</p>
                        <p class="small">Connect with students from <?php echo htmlspecialchars($university_name); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        function pokeUser(userId) {
            if (confirm('Are you sure you want to poke this user?')) {
                fetch('ajax/poke.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'user_id=' + userId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Poke sent!');
                    } else {
                        alert(data.message || 'Failed to send poke');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
            }
        }
    </script>
</body>
</html>