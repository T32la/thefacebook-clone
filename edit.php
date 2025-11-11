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
    $name = sanitize($_POST['name'] ?? '');
    $bio = sanitize($_POST['bio'] ?? '');
    $status = sanitize($_POST['status'] ?? 'Student');

    // Validaciones
    if (empty($name)) {
        $errors[] = "Name is required";
    }

    // Manejar subida de avatar
    // $avatar = $user['avatar'];
    // if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    //     $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    //     $filename = $_FILES['avatar']['name'];
    //     $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    //     if (!in_array($ext, $allowed)) {
    //         $errors[] = "Invalid file type. Only JPG, PNG and GIF allowed.";
    //     } elseif ($_FILES['avatar']['size'] > MAX_FILE_SIZE) {
    //         $errors[] = "File too large. Maximum 5MB.";
    //     } else {
    //         $newFilename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
    //         $uploadPath = UPLOAD_PATH . $newFilename;

    //         if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
    //             // Eliminar avatar anterior si no es el default
    //             if ($user['avatar'] !== 'default-avatar.jpg' && file_exists(UPLOAD_PATH . $user['avatar'])) {
    //                 unlink(UPLOAD_PATH . $user['avatar']);
    //             }
    //             $avatar = $newFilename;
    //         } else {
    //             $errors[] = "Failed to upload avatar.";
    //         }
    //     }
    // }

    // Manejar subida de avatar
    $avatar = $user['avatar'];
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['avatar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $errors[] = "Invalid file type. Only JPG, PNG and GIF allowed.";
        } elseif ($_FILES['avatar']['size'] > MAX_FILE_SIZE) {
            $errors[] = "File too large. Maximum 5MB.";
        } else {
            // Verificar que la carpeta uploads existe
            if (!file_exists(UPLOAD_PATH)) {
                mkdir(UPLOAD_PATH, 0777, true);
            }

            $newFilename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
            $uploadPath = UPLOAD_PATH . $newFilename;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                // Eliminar avatar anterior si no es el default
                if ($user['avatar'] !== 'default-avatar.jpg' && file_exists(UPLOAD_PATH . $user['avatar'])) {
                    unlink(UPLOAD_PATH . $user['avatar']);
                }
                $avatar = $newFilename;
            } else {
                $errors[] = "Failed to upload avatar. Check folder permissions.";
            }
        }
    }

    // Si no hay errores, actualizar
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, bio = ?, status = ?, avatar = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $bio, $status, $avatar, $_SESSION['user_id']);

        if ($stmt->execute()) {
            $success = true;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_avatar'] = $avatar;

            // Recargar datos del usuario
            $stmt2 = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt2->bind_param("i", $_SESSION['user_id']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $user = $result2->fetch_assoc();
            $stmt2->close();
        } else {
            $errors[] = "Update failed. Please try again.";
        }

        $stmt->close();
    }
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
                        <div class="alert alert-success">
                            Profile updated successfully!
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status:</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status">
                                    <option <?php echo $user['status'] === 'Student' ? 'selected' : ''; ?>>Student</option>
                                    <option <?php echo $user['status'] === 'Alumni' ? 'selected' : ''; ?>>Alumni</option>
                                    <option <?php echo $user['status'] === 'Faculty' ? 'selected' : ''; ?>>Faculty</option>
                                    <option <?php echo $user['status'] === 'Staff' ? 'selected' : ''; ?>>Staff</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Bio:</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="bio" rows="4"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                                <small class="form-text text-muted">Tell us about yourself</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Avatar:</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control-file" name="avatar" accept="image/*">
                                <small class="form-text text-muted">JPG, PNG or GIF. Max 5MB.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Email:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                <small class="form-text text-muted">Email cannot be changed</small>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                            <a href="home.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
