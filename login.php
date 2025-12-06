<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';

redirect_if_logged_in();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Invoice System</title>
    <link rel="stylesheet" href="assets/styles.css">
    <!-- Updated auth page styles for modern glassmorphism design -->
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: var(--bg-dark);
            background-image: 
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(13, 148, 136, 0.2), transparent),
                radial-gradient(ellipse 60% 40% at 100% 100%, rgba(13, 148, 136, 0.1), transparent);
        }
        .auth-container {
            background: var(--bg-card);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            border: 1px solid var(--border-color);
            backdrop-filter: blur(20px);
            animation: fadeIn 0.5s ease-out;
        }
        .auth-container h1 {
            text-align: center;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-size: 1.75rem;
        }
        .auth-subtitle {
            text-align: center;
            color: var(--text-secondary);
            margin-bottom: 32px;
            font-size: 0.9375rem;
        }
        .auth-container .form-group {
            margin-bottom: 20px;
        }
        .auth-container label {
            color: var(--text-secondary);
        }
        .auth-container input {
            margin-bottom: 0;
        }
        .auth-container button {
            width: 100%;
            padding: 14px 24px;
            font-size: 1rem;
            margin-top: 8px;
        }
        .auth-link {
            text-align: center;
            margin-top: 24px;
            color: var(--text-secondary);
        }
        .auth-link a {
            color: var(--primary);
            font-weight: 600;
        }
        .auth-link a:hover {
            text-decoration: underline;
        }
        .brand-logo {
            text-align: center;
            margin-bottom: 24px;
        }
        .brand-logo span {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary) 0%, #14b8a6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="brand-logo">
            <span>Invoice System</span>
        </div>
        <h1>Welcome Back</h1>
        <p class="auth-subtitle">Sign in to manage your invoices</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit">Sign In</button>
        </form>

        <div class="auth-link">
            Don't have an account? <a href="register.php">Create one</a>
        </div>
    </div>
</body>
</html>
