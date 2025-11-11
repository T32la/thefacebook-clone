<div class="sidebar-box">
    <?php if (!isLoggedIn()): ?>
        <form method="POST" action="login.php">
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
    <?php else: ?>
        <div class="text-center mb-3">
            <img src="uploads/<?php echo htmlspecialchars($_SESSION['user_avatar']); ?>" 
                 alt="Avatar" 
                 class="img-thumbnail"
                 style="width: 100px; height: 100px; object-fit: cover;">
        </div>
        <ul class="list-unstyled">
            <li><a href="home.php">Profile</a></li>
            <li><a href="edit.php">Edit Profile</a></li>
            <li><a href="search.php">Search</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    <?php endif; ?>
</div>