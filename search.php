<?php
require_once 'config.php';
requireLogin();

$search_query = '';
$results = [];

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search_query = sanitize($_GET['q']);
    
    $conn = getDBConnection();
    $search_term = "%" . $search_query . "%";
    $stmt = $conn->prepare("SELECT id, name, email, status, avatar, university_domain FROM users WHERE (name LIKE ? OR email LIKE ?) AND id != ? LIMIT 20");
    $stmt->bind_param("ssi", $search_term, $search_term, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - [ thefacebook ]</title>
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
                        <input type="text" name="q" class="form-control form-control-sm" 
                               placeholder="Search..." value="<?php echo htmlspecialchars($search_query); ?>">
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
            
            <div class="col-md-10">
                <div class="profile-box">
                    <div class="profile-box-header">Search Results</div>
                    <div class="profile-box-content p-4">
                        <?php if (empty($search_query)): ?>
                            <p>Enter a name or email to search for people at your school.</p>
                        <?php elseif (empty($results)): ?>
                            <p>No results found for "<?php echo htmlspecialchars($search_query); ?>"</p>
                        <?php else: ?>
                            <p class="mb-3">Found <?php echo count($results); ?> result(s) for "<?php echo htmlspecialchars($search_query); ?>"</p>
                            
                            <div class="search-results">
                                <?php foreach ($results as $person): ?>
                                    <div class="search-result-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-2 text-center">
                                                <img src="uploads/<?php echo htmlspecialchars($person['avatar']); ?>" 
                                                     alt="<?php echo htmlspecialchars($person['name']); ?>"
                                                     class="search-avatar">
                                            </div>
                                            <div class="col-md-10">
                                                <h5 class="mb-1">
                                                    <a href="profile.php?id=<?php echo $person['id']; ?>">
                                                        <?php echo htmlspecialchars($person['name']); ?>
                                                    </a>
                                                </h5>
                                                <p class="mb-0 small">
                                                    <strong>Status:</strong> <?php echo htmlspecialchars($person['status']); ?><br>
                                                    <strong>School:</strong> <?php echo htmlspecialchars($person['university_domain']); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>