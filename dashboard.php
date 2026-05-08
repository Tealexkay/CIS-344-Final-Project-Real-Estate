<?php
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'classes/RealEstateDatabase.php';

requireLogin();

$db = new RealEstateDatabase();
$user = $_SESSION['user'];
$userDetails = $db->getUserDetails((int)$user['userId']);
$profile = $userDetails['user'] ?? $user;
$inquiries = $userDetails['inquiries'] ?? [];
$favorites = $userDetails['favorites'] ?? [];
$transactions = $userDetails['transactions'] ?? [];
?>
<?php include 'includes/header.php'; ?>
<h2>Dashboard</h2>

<div class="card">
    <p><strong>Welcome:</strong> <?= htmlspecialchars($profile['userName']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($profile['userType']) ?></p>
    <p><strong>Contact Info:</strong> <?= htmlspecialchars($profile['contactInfo'] ?? 'Not provided') ?></p>
</div>

<?php if ($profile['userType'] === 'agent'): ?>
    <div class="card">
        <h3>Agent Actions</h3>
        <p><a href="add_property.php">Add Property</a></p>
        <p><a href="properties.php">Manage and review listings</a></p>
    </div>
<?php endif; ?>

<?php if (in_array($profile['userType'], ['buyer', 'renter'], true)): ?>
    <div class="card">
        <h3>Buyer and Renter Actions</h3>
        <p><a href="properties.php">Browse available properties</a></p>
        <p><a href="favorites.php">View saved favorites</a></p>
    </div>
<?php endif; ?>

<div class="card">
    <h3>Recent Inquiries</h3>
    <?php if (!$inquiries): ?>
        <p>No inquiries found for this account.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($inquiries as $inquiry): ?>
                <li>
                    <strong><?= htmlspecialchars($inquiry['title']) ?></strong>
                    in <?= htmlspecialchars($inquiry['city']) ?>
                    (<?= htmlspecialchars($inquiry['status']) ?>)<br>
                    <?= htmlspecialchars($inquiry['message']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Saved Favorites</h3>
    <?php if (!$favorites): ?>
        <p>No favorite properties saved yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($favorites as $favorite): ?>
                <li>
                    <a href="property_details.php?id=<?= (int)$favorite['propertyId'] ?>">
                        <?= htmlspecialchars($favorite['title']) ?>
                    </a>
                    in <?= htmlspecialchars($favorite['city']) ?>
                    for $<?= number_format((float)$favorite['price'], 2) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Transactions</h3>
    <?php if (!$transactions): ?>
        <p>No transactions recorded for this account.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($transactions as $transaction): ?>
                <li>
                    <strong><?= htmlspecialchars($transaction['transactionType']) ?></strong>
                    for <?= htmlspecialchars($transaction['title']) ?> in <?= htmlspecialchars($transaction['city']) ?>
                    on <?= htmlspecialchars($transaction['transactionDate']) ?>
                    for $<?= number_format((float)$transaction['amount'], 2) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
