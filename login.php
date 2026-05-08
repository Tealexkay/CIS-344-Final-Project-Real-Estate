<?php
require_once 'config/config.php';
require_once 'classes/RealEstateDatabase.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new RealEstateDatabase();
    $userName = trim($_POST['userName'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($userName !== '' && $password !== '') {
        $user = $db->getUserByUsername($userName);

        if ($user && password_verify($password, $user['passwordHash'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'userId' => $user['userId'],
                'userName' => $user['userName'],
                'contactInfo' => $user['contactInfo'],
                'userType' => $user['userType']
            ];

            header('Location: dashboard.php');
            exit;
        }
    }

    $message = 'Invalid username or password.';
}
?>
<?php include 'includes/header.php'; ?>
<h2>Login</h2>
<?php if ($message): ?>
    <p class="error"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Username</label>
    <input type="text" name="userName" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>
<?php include 'includes/footer.php'; ?>
