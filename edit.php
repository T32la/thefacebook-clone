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
    // Recoger todos los campos del formulario
    $name = trim($_POST['name'] ?? '');
    $status = $_POST['status'] ?? 'Student';
    $sex = $_POST['sex'] ?? 'Male';
    $birthday = $_POST['birthday'] ?? null;
    $hometown = trim($_POST['hometown'] ?? '');
    $residence = trim($_POST['residence'] ?? '');
    $high_school = trim($_POST['high_school'] ?? '');
    $screenname = trim($_POST['screenname'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $looking_for = $_POST['looking_for'] ?? '';
    $interested_in = $_POST['interested_in'] ?? '';
    $relationship_status = $_POST['relationship_status'] ?? '';
    $partner_name = trim($_POST['partner_name'] ?? '');
    $political_views = trim($_POST['political_views'] ?? '');
    $interests = trim($_POST['interests'] ?? '');
    $favorite_music = trim($_POST['favorite_music'] ?? '');
    $favorite_books = trim($_POST['favorite_books'] ?? '');
    $favorite_movies = trim($_POST['favorite_movies'] ?? '');
    $favorite_tv = trim($_POST['favorite_tv'] ?? '');
    $favorite_quotes = trim($_POST['favorite_quotes'] ?? '');
    $about_me = trim($_POST['about_me'] ?? '');
    
    // Validaciones
    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters";
    }
    
    // Validar fecha de nacimiento
    if (!empty($birthday)) {
        $date = DateTime::createFromFormat('Y-m-d', $birthday);
        if (!$date || $date->format('Y-m-d') !== $birthday) {
            $errors[] = "Invalid birthday format";
        }
    } else {
        $birthday = null;
    }
    
    // Manejar subida de avatar
    $avatar = $user['avatar'];
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['avatar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $filesize = $_FILES['avatar']['size'];
        $tmp_name = $_FILES['avatar']['tmp_name'];
        
        if (!in_array($ext, $allowed)) {
            $errors[] = "Invalid file type. Only JPG, PNG and GIF allowed.";
        } elseif ($filesize > MAX_FILE_SIZE) {
            $errors[] = "File too large. Maximum 5MB.";
        } elseif (!getimagesize($tmp_name)) {
            $errors[] = "File is not a valid image.";
        } else {
            $newFilename = 'avatar_' . $_SESSION['user_id'] . '_' . uniqid() . '.' . $ext;
            $uploadPath = UPLOAD_PATH . $newFilename;
            
            if (move_uploaded_file($tmp_name, $uploadPath)) {
                if ($user['avatar'] !== 'default-avatar.jpg' && 
                    !empty($user['avatar']) && 
                    file_exists(UPLOAD_PATH . $user['avatar'])) {
                    unlink(UPLOAD_PATH . $user['avatar']);
                }
                $avatar = $newFilename;
            } else {
                $errors[] = "Failed to upload avatar.";
            }
        }
    }
    
    // Si no hay errores, actualizar
    if (empty($errors)) {
        $stmt = $conn->prepare("
            UPDATE users SET 
                name = ?, 
                status = ?, 
                sex = ?, 
                birthday = ?, 
                hometown = ?, 
                residence = ?, 
                high_school = ?, 
                screenname = ?, 
                mobile = ?, 
                bio = ?, 
                avatar = ?, 
                looking_for = ?, 
                interested_in = ?, 
                relationship_status = ?, 
                partner_name = ?, 
                political_views = ?, 
                interests = ?, 
                favorite_music = ?, 
                favorite_books = ?, 
                favorite_movies = ?, 
                favorite_tv = ?, 
                favorite_quotes = ?, 
                about_me = ?
            WHERE id = ?
        ");
        
        $stmt->bind_param(
            "sssssssssssssssssssssssi",
            $name, $status, $sex, $birthday, $hometown, $residence, $high_school,
            $screenname, $mobile, $bio, $avatar, $looking_for, $interested_in,
            $relationship_status, $partner_name, $political_views, $interests,
            $favorite_music, $favorite_books, $favorite_movies, $favorite_tv,
            $favorite_quotes, $about_me, $_SESSION['user_id']
        );
        
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $name;
            $_SESSION['user_avatar'] = $avatar;
            $stmt->close();
            $conn->close();
            header('Location: edit.php?success=1');
            exit();
        } else {
            $errors[] = "Update failed: " . $conn->error;
        }
        $stmt->close();
    }
}

if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success = true;
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
        .section-header {
            background-color: #3b5998;
            color: white;
            padding: 8px 12px;
            margin-top: 20px;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 12px;
        }
        .form-section {
            margin-bottom: 30px;
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
                             class="img-thumbnail"
                             id="avatarPreview"
                             style="width: 150px; height: 150px; object-fit: cover;">
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
                        
                        <!-- ACCOUNT INFO -->
                        <div class="section-header">Account Info</div>
                        <div class="form-section">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Name: <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" 
                                           value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Email:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" 
                                           value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                    <small class="form-text text-muted">Email cannot be changed</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- BASIC INFO -->
                        <div class="section-header">Basic Info</div>
                        <div class="form-section">
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
                                <label class="col-sm-3 col-form-label">Sex:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="sex">
                                        <option value="Male" <?php echo $user['sex'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo $user['sex'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Birthday:</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" name="birthday" 
                                           value="<?php echo htmlspecialchars($user['birthday'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Home Town:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="hometown" 
                                           value="<?php echo htmlspecialchars($user['hometown'] ?? ''); ?>"
                                           placeholder="e.g. New York, NY">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Residence:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="residence" 
                                           value="<?php echo htmlspecialchars($user['residence'] ?? ''); ?>"
                                           placeholder="e.g. Kirkland House 111">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">High School:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="high_school" 
                                           value="<?php echo htmlspecialchars($user['high_school'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- CONTACT INFO -->
                        <div class="section-header">Contact Info</div>
                        <div class="form-section">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Screenname:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="screenname" 
                                           value="<?php echo htmlspecialchars($user['screenname'] ?? ''); ?>"
                                           placeholder="AIM, Yahoo, etc.">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Mobile:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="mobile" 
                                           value="<?php echo htmlspecialchars($user['mobile'] ?? ''); ?>"
                                           placeholder="555-1234">
                                </div>
                            </div>
                        </div>
                        
                        <!-- PERSONAL INFO -->
                        <div class="section-header">Personal Info</div>
                        <div class="form-section">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Looking For:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="looking_for">
                                        <option value="">-- Select --</option>
                                        <option value="Friendship" <?php echo ($user['looking_for'] ?? '') === 'Friendship' ? 'selected' : ''; ?>>Friendship</option>
                                        <option value="Dating" <?php echo ($user['looking_for'] ?? '') === 'Dating' ? 'selected' : ''; ?>>Dating</option>
                                        <option value="A Relationship" <?php echo ($user['looking_for'] ?? '') === 'A Relationship' ? 'selected' : ''; ?>>A Relationship</option>
                                        <option value="Networking" <?php echo ($user['looking_for'] ?? '') === 'Networking' ? 'selected' : ''; ?>>Networking</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Interested In:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="interested_in">
                                        <option value="">-- Select --</option>
                                        <option value="Women" <?php echo ($user['interested_in'] ?? '') === 'Women' ? 'selected' : ''; ?>>Women</option>
                                        <option value="Men" <?php echo ($user['interested_in'] ?? '') === 'Men' ? 'selected' : ''; ?>>Men</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Relationship Status:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="relationship_status" id="relationshipStatus">
                                        <option value="">-- Select --</option>
                                        <option value="Single" <?php echo ($user['relationship_status'] ?? '') === 'Single' ? 'selected' : ''; ?>>Single</option>
                                        <option value="In a Relationship" <?php echo ($user['relationship_status'] ?? '') === 'In a Relationship' ? 'selected' : ''; ?>>In a Relationship</option>
                                        <option value="Married" <?php echo ($user['relationship_status'] ?? '') === 'Married' ? 'selected' : ''; ?>>Married</option>
                                        <option value="It's Complicated" <?php echo ($user['relationship_status'] ?? '') === "It's Complicated" ? 'selected' : ''; ?>>It's Complicated</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row" id="partnerNameRow" style="display: none;">
                                <label class="col-sm-3 col-form-label">Partner Name:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="partner_name" 
                                           value="<?php echo htmlspecialchars($user['partner_name'] ?? ''); ?>"
                                           placeholder="Name of your partner">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Political Views:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="political_views" 
                                           value="<?php echo htmlspecialchars($user['political_views'] ?? ''); ?>"
                                           placeholder="e.g. Very Liberal, Moderate, Conservative">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Interests:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="interests" rows="3"
                                              placeholder="Separate with commas"><?php echo htmlspecialchars($user['interests'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- FAVORITES -->
                        <div class="section-header">Favorites</div>
                        <div class="form-section">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Favorite Music:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="favorite_music" rows="3"><?php echo htmlspecialchars($user['favorite_music'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Favorite Movies:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="favorite_movies" rows="3"><?php echo htmlspecialchars($user['favorite_movies'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Favorite TV Shows:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="favorite_tv" rows="3"><?php echo htmlspecialchars($user['favorite_tv'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Favorite Books:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="favorite_books" rows="3"><?php echo htmlspecialchars($user['favorite_books'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Favorite Quotes:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="favorite_quotes" rows="3"><?php echo htmlspecialchars($user['favorite_quotes'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ABOUT ME -->
                        <div class="section-header">About Me</div>
                        <div class="form-section">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Bio:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="bio" rows="3"
                                              placeholder="Short bio..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">About Me:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="about_me" rows="5"
                                              placeholder="Tell us more about yourself..."><?php echo htmlspecialchars($user['about_me'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- PICTURE -->
                        <div class="section-header">Picture</div>
                        <div class="form-section">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Avatar:</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control-file" name="avatar" 
                                           accept="image/*" id="avatarInput">
                                    <small class="form-text text-muted">JPG, PNG or GIF. Max 5MB.</small>
                                </div>
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
                if (file.size > 5 * 1024 * 1024) {
                    alert('File is too large. Maximum 5MB.');
                    this.value = '';
                    return;
                }
                
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Invalid file type. Only JPG, PNG and GIF allowed.');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Mostrar/ocultar campo de nombre de pareja
        function togglePartnerName() {
            const relationshipStatus = document.getElementById('relationshipStatus').value;
            const partnerNameRow = document.getElementById('partnerNameRow');
            
            if (relationshipStatus === 'In a Relationship' || relationshipStatus === 'Married') {
                partnerNameRow.style.display = 'flex';
            } else {
                partnerNameRow.style.display = 'none';
            }
        }
        
        document.getElementById('relationshipStatus').addEventListener('change', togglePartnerName);
        togglePartnerName(); // Ejecutar al cargar
        
        // Confirmar antes de salir si hay cambios
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
