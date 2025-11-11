<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - [ thefacebook ]</title>
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
                <div class="about-box">
                    <h2>[ About ]</h2>
                    
                    <div class="section-box">
                        <h4>The Project</h4>
                        <p>Thefacebook is an online directory that connects people through social networks at colleges and universities.</p>
                    </div>
                    
                    <div class="section-box">
                        <h4>The People</h4>
                        
                        <div class="creator-card">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <img src="uploads/creator1.jpg" alt="Creator 1" class="creator-img">
                                </div>
                                <div class="col-md-9">
                                    <h5 class="text-primary">Tu Nombre</h5>
                                    <p><strong>Founder, Master and Commander, Enemy of the State.</strong></p>
                                    <p>Estudiante de Ingeniería en Ciencias de la Computación. Desarrollador full-stack apasionado por crear aplicaciones web innovadoras.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="creator-card">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <img src="uploads/creator2.jpg" alt="Creator 2" class="creator-img">
                                </div>
                                <div class="col-md-9">
                                    <h5 class="text-primary">Compañero 1</h5>
                                    <p><strong>Business Stuff, Corporate Stuff, Brazilian Affairs.</strong></p>
                                    <p>Encargado de la lógica de negocio y diseño de base de datos. Especialista en PHP y MySQL.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="creator-card">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <img src="uploads/creator3.jpg" alt="Creator 3" class="creator-img">
                                </div>
                                <div class="col-md-9">
                                    <h5 class="text-primary">Compañero 2</h5>
                                    <p><strong>Graphic Art, General Rockstar.</strong></p>
                                    <p>Diseñador UI/UX del proyecto. Responsable del diseño visual y la experiencia de usuario.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="contact.php" class="btn btn-primary">Contact us</a>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-secondary">Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>