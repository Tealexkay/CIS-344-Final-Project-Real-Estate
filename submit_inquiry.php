<?php
require_once 'config/config.php';
require_once 'classes/RealEstateDatabase.php';

$db = new RealEstateDatabase();
$message = '';

$propertyId = isset($_GET['propertyId']) ? (int)$_GET['propertyId'] : (int)($_POST['propertyId'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $messageText = trim($_POST['message'] ?? '');

    if ($propertyId > 0 && $messageText !== '') {
        try {
            if (isset($_SESSION['user'])) {
                if (!in_array($_SESSION['user']['userType'], ['buyer', 'renter'], true)) {
                    throw new RuntimeException('Only buyers and renters can submit inquiries.');
                }

                $userId = (int)$_SESSION['user']['userId'];
            } else {
                $userName = trim($_POST['userName'] ?? '');
                $contactInfo = trim($_POST['contactInfo'] ?? '');
                $password = $_POST['password'] ?? '';
                $userType = $_POST['userType'] ?? '';

                if ($userName === '' || $contactInfo === '' || $password === '' || !in_array($userType, ['buyer', 'renter'], true)) {
                    throw new RuntimeException('Please complete all user fields to submit your inquiry.');
                }

                $existingUser = $db->getUserByUsername($userName);

                if ($existingUser) {
                    if (!password_verify($password, $existingUser['passwordHash'])) {
                        throw new RuntimeException('This username already exists. Please enter the correct password or choose a different username.');
                    }

                    if (!in_array($existingUser['userType'], ['buyer', 'renter'], true)) {
                        throw new RuntimeException('Only buyer or renter accounts can submit property inquiries.');
                    }

                    $userId = (int)$existingUser['userId'];
                } else {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $db->addUser($userName, $contactInfo, $passwordHash, $userType);
                    $newUser = $db->getUserByUsername($userName);

                    if (!$newUser) {
                        throw new RuntimeException('Unable to create the new user account.');
                    }

                    $userId = (int)$newUser['userId'];
                }
            }

            $db->addInquiry($userId, $propertyId, $messageText);
            $message = 'Inquiry submitted successfully.';
        } catch (Throwable $e) {
            $message = 'Error: ' . $e->getMessage();
        }
    } else {
        $message = 'Please enter a message.';
    }
}
?>
<?php include 'includes/header.php'; ?>
<h2>Submit Inquiry</h2>
<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="propertyId" value="<?= (int)$propertyId ?>">

    <?php if (!isset($_SESSION['user'])): ?>
        <h3>Buyer or Renter Information</h3>
        <label>Username</label>
        <input type="text" name="userName" required>

        <label>Contact Info</label>
        <input type="text" name="contactInfo" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>User Type</label>
        <select name="userType" required>
            <option value="">Select role</option>
            <option value="buyer">Buyer</option>
            <option value="renter">Renter</option>
        </select>
        <p>If your username already exists, enter the same username and correct password to continue.</p>
    <?php endif; ?>

    <label>Message</label>
    <textarea name="message" rows="6" required></textarea>

    <button type="submit">Send Inquiry</button>
</form>
<?php include 'includes/footer.php'; ?>
