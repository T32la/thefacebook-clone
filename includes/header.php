<header class="thefacebook-header">
    <div class="header-container">
        <!-- Columna Izquierda: Foto del creador -->
        <div class="header-left-column">
            <img src="src/img/fb.jpg" alt="thefacebook" class="header-face-photo">
        </div>
        
        <!-- Columna Derecha: Logo + Navbar -->
        <div class="header-right-column">
            <!-- Logo en la parte superior -->
            <div class="header-logo-wrapper">
                <h1 class="header-logo">[ thefacebook ]</h1>
            </div>
            
            <!-- Navbar en la parte inferior -->
            <div class="header-navbar-wrapper">
                <nav class="header-navbar">
                    <a href="index.php">home</a>
                    <a href="search.php">search</a>
                    <a href="global.php">global</a>
                    <a href="network.php">social net</a>
                    <a href="invite.php">invite</a>
                    <a href="faq.php">faq</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="logout.php">logout</a>
                    <?php else: ?>
                        <a href="login.php">login</a>
                        <a href="register.php">register</a>
                        <a href="about.php">about</a>
                    <?php endif; ?>
                </nav>
                
 
            </div>
        </div>
    </div>
</header>