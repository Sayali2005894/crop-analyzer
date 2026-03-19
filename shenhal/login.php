<?php
include "config.php";

$message = "";
if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Smart Crop Platform</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-wrapper">

<div class="auth-card">
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 class="gradient-text" style="font-size: 32px; margin-bottom: 8px;">Welcome Back</h1>
        <p style="color: var(--text-muted);">Sign in to manage your fields</p>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div style="background: rgba(16, 185, 129, 0.1); color: var(--primary); padding: 14px; border-radius: 12px; margin-bottom: 24px; text-align: center; border: 1px solid rgba(16, 185, 129, 0.2);">
            <?php echo $_GET['msg']; ?>
        </div>
    <?php endif; ?>

    <?php if($message): ?>
        <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 14px; border-radius: 12px; margin-bottom: 24px; text-align: center; border: 1px solid rgba(239, 68, 68, 0.2);">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="example@mail.com" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary" style="width: 100%;">Sign In</button>
    </form>

    <p style="text-align: center; margin-top: 32px; font-size: 14px; color: var(--text-muted);">
        Don't have an account? <a href="register.php" style="color: var(--primary); font-weight: 600; text-decoration: none;">Register Now</a>
    </p>
</div>

</body>
</html>