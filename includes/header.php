<header class="facebook-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-2">
                <div class="header-icon">
                    <img src="images/face-icon.png" alt="Face" width="50" height="50">
                </div>
            </div>
            
            <div class="col-md-7">
                <h1 class="header-title">[ thefacebook ]</h1>
            </div>
            
            <div class="col-md-3 text-right">
                <nav class="header-nav">
                    <a href="login.php">login</a>
                    <a href="register.php">register</a>
                    <a href="about.php">about</a>
                </nav>
                <?php if (isLoggedIn()): ?>
                    <div class="user-avatar mt-2">
                        <img src="uploads/<?php echo htmlspecialchars($_SESSION['user_avatar']); ?>" 
                             alt="Avatar" 
                             class="avatar-small">
                        <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>