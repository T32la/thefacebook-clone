<?php
require_once 'config.php';
requireLogin();

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
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
    <title>My Friends - [ thefacebook ]</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid mt-3">
        <div class="row">
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
            
            <div class="col-md-10">
                <div class="profile-box">
                    <div class="profile-box-header">My Friends</div>
                    <div class="profile-box-content p-4">
                        <p>You don't have any friends yet. Start by searching for people at your school!</p>
                        <a href="search.php" class="btn btn-primary btn-sm">Search for Friends</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>