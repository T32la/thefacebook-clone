<?php
require_once 'config.php';
requireLogin();

$errors = [];
$success = false;

// Obtener información actual del usuario
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $status = $_POST['status'] ?? 'Student';
    
    // Validaciones
    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters";
    } elseif (strlen($name) > 100) {
        $errors[] = "Name is too long (max 100 characters)";
    }
    
    if (strlen($bio) > 1000) {
        $errors[] = "Bio is too long (max 1000 characters)";
    }
    
    // Manejar subida de avatar
    $avatar = $user['avatar'];
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['avatar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $filesize = $_FILES['avatar']['size'];
        $tmp_name = $_FILES['avatar']['tmp_name'];
        
        // Validar extensión
        if (!in_array($ext, $allowed)) {
            $errors[] = "Invalid file type. Only JPG, PNG and GIF allowed.";
        } 
        // Validar tamaño
        elseif ($filesize > MAX_FILE_SIZE) {
            $errors[] = "File too large. Maximum 5MB.";
        }
        // Validar que sea una imagen real
        elseif (!getimagesize($tmp_name)) {
            $errors[] = "File is not a valid image.";
        }
        else {
            // Generar nombre único
            $newFilename = 'avatar_' . $_SESSION['user_id'] . '_' . uniqid() . '.' . $ext;
            $uploadPath = UPLOAD_PATH . $newFilename;
            
            // Mover archivo
            if (move_uploaded_file($tmp_name, $uploadPath)) {
                // Eliminar avatar anterior si no es el default
                if ($user['avatar'] !== 'default-avatar.jpg' && 
                    !empty($user['avatar']) && 
                    file_exists(UPLOAD_PATH . $user['avatar'])) {
                    unlink(UPLOAD_PATH . $user['avatar']);
                }
                $avatar = $newFilename;
            } else {
                $errors[] = "Failed to upload avatar. Check folder permissions.";
            }
        }
    } elseif (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Errores de subida
        switch ($_FILES['avatar']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errors[] = "File is too large.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $errors[] = "File was only partially uploaded.";
                break;
            default:
                $errors[] = "An error occurred during upload.";
        }
    }
    
    // Si no hay errores, actualizar
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, bio = ?, status = ?, avatar = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $bio, $status, $avatar, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            // Actualizar sesión
            $_SESSION['user_name'] = $name;
            $_SESSION['user_avatar'] = $avatar;
            
            $stmt->close();
            $conn->close();
            
            // Redirigir para evitar reenvío de formulario
            header('Location: edit.php?success=1');
            exit();
        } else {
            $errors[] = "Update failed: " . $conn->error;
        }
        
        $stmt->close();
    }
}

// Mostrar mensaje de éxito si viene de redirección
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success = true;
    // Recargar datos del usuario
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - [ thefacebook ]</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .preview-avatar {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <div class="text-center mb-3">
                        <img src="uploads/<?php echo htmlspecialchars($user['avatar']); ?>" 
                             alt="Avatar" 
                             class="img-thumbnail preview-avatar"
                             id="avatarPreview">
                    </div>
                    
                    <ul class="list-unstyled">
                        <li><a href="home.php">Profile</a></li>
                        <li><a href="edit.php" style="font-weight: bold;">Edit Profile</a></li>
                        <li><a href="search.php">Search</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="profile-content">
                    <h2>Edit Profile</h2>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <strong>Success!</strong> Profile updated successfully!
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>Error!</strong> Please fix the following issues:
                            <ul class="mb-0 mt-2">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" enctype="multipart/form-data" id="editForm">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Name: <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" 
                                       class="form-control" 
                                       name="name" 
                                       value="<?php echo htmlspecialchars($user['name']); ?>" 
                                       required
                                       maxlength="100">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status:</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status">
                                    <option value="Student" <?php echo $user['status'] === 'Student' ? 'selected' : ''; ?>>Student</option>
                                    <option value="Alumni" <?php echo $user['status'] === 'Alumni' ? 'selected' : ''; ?>>Alumni</option>
                                    <option value="Faculty" <?php echo $user['status'] === 'Faculty' ? 'selected' : ''; ?>>Faculty</option>
                                    <option value="Staff" <?php echo $user['status'] === 'Staff' ? 'selected' : ''; ?>>Staff</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Bio:</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" 
                                          name="bio" 
                                          rows="4" 
                                          maxlength="1000"
                                          placeholder="Tell us about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                                <small class="form-text text-muted">
                                    <span id="bioCount">0</span>/1000 characters
                                </small>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Avatar:</label>
                            <div class="col-sm-9">
                                <input type="file" 
                                       class="form-control-file" 
                                       name="avatar" 
                                       accept="image/jpeg,image/jpg,image/png,image/gif"
                                       id="avatarInput">
                                <small class="form-text text-muted">
                                    JPG, PNG or GIF. Max 5MB. Image will be shown in the preview above.
                                </small>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Email:</label>
                            <div class="col-sm-9">
                                <input type="text" 
                                       class="form-control" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" 
                                       disabled>
                                <small class="form-text text-muted">Email cannot be changed</small>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">School:</label>
                            <div class="col-sm-9">
                                <input type="text" 
                                       class="form-control" 
                                       value="<?php 
                                           $domain_parts = explode('.', $user['university_domain']);
                                           echo htmlspecialchars(ucfirst($domain_parts[0])); 
                                       ?>" 
                                       disabled>
                                <small class="form-text text-muted">School cannot be changed</small>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                Update Profile
                            </button>
                            <a href="home.php" class="btn btn-secondary btn-lg px-5 ml-2">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview de avatar
        document.getElementById('avatarInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validar tamaño
                if (file.size > 5 * 1024 * 1024) {
                    alert('File is too large. Maximum 5MB.');
                    this.value = '';
                    return;
                }
                
                // Validar tipo
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Invalid file type. Only JPG, PNG and GIF allowed.');
                    this.value = '';
                    return;
                }
                
                // Mostrar preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Contador de caracteres del bio
        const bioTextarea = document.querySelector('textarea[name="bio"]');
        const bioCount = document.getElementById('bioCount');
        
        function updateBioCount() {
            bioCount.textContent = bioTextarea.value.length;
        }
        
        bioTextarea.addEventListener('input', updateBioCount);
        updateBioCount();
        
        // Confirmar antes de salir si hay cambios sin guardar
        let formChanged = false;
        const form = document.getElementById('editForm');
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            if (input.type !== 'file') {
                input.addEventListener('change', () => formChanged = true);
            }
        });
        
        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
        
        form.addEventListener('submit', function() {
            formChanged = false;
        });
    </script>
</body>
</html>