<?php
include "config.php";

$message = "";
if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $message = "Email already registered!";
    } else {
        $sql = "INSERT INTO users(name, email, password) VALUES('$name', '$email', '$hashed_password')";
        if(mysqli_query($conn, $sql)){
            header("Location: login.php?msg=Account created successfully!");
            exit();
        } else {
            $message = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Smart Crop Platform</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-wrapper">

<div class="auth-card">
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 class="gradient-text" style="font-size: 32px; margin-bottom: 8px;">Smart Crop</h1>
        <p style="color: var(--text-muted);">Join the future of agricultural intelligence</p>
    </div>

    <?php if($message): ?>
        <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 14px; border-radius: 12px; margin-bottom: 24px; text-align: center; border: 1px solid rgba(239, 68, 68, 0.2);">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" placeholder="Enter your full name" required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="example@mail.com" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Create a strong password" required>
        </div>

        <button type="submit" name="register" class="btn btn-primary" style="width: 100%;">Create Account</button>
    </form>

    <p style="text-align: center; margin-top: 32px; font-size: 14px; color: var(--text-muted);">
        Already have an account? <a href="login.php" style="color: var(--primary); font-weight: 600; text-decoration: none;">Log In</a>
    </p>
</div>

</body>
</html>