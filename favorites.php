<?php
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'classes/RealEstateDatabase.php';

requireRole(['buyer', 'renter']);

$db = new RealEstateDatabase();
$favorites = $db->getFavoritesByUser((int)$_SESSION['user']['userId']);
?>
<?php include 'includes/header.php'; ?>
<h2>Saved Favorites</h2>
<p>These are the properties you have saved for later review.</p>

<?php if (!$favorites): ?>
    <p>You have not saved any properties yet.</p>
<?php else: ?>
    <div class="property-grid">
        <?php foreach ($favorites as $favorite): ?>
            <div class="card property-card">
                <?php if (!empty($favorite['imagePath'])): ?>
                    <img class="property-thumb" src="<?= htmlspecialchars($favorite['imagePath']) ?>" alt="<?= htmlspecialchars($favorite['title']) ?>">
                <?php endif; ?>
                <h3><?= htmlspecialchars($favorite['title']) ?></h3>
                <p><strong>Type:</strong> <?= htmlspecialchars($favorite['propertyType']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($favorite['address']) ?></p>
                <p><strong>City:</strong> <?= htmlspecialchars($favorite['city']) ?></p>
                <p><strong>Price:</strong> $<?= number_format((float)$favorite['price'], 2) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($favorite['status']) ?></p>
                <p><strong>Agent:</strong> <?= htmlspecialchars($favorite['agentName']) ?></p>
                <p><strong>Saved On:</strong> <?= htmlspecialchars($favorite['savedDate']) ?></p>
                <a href="property_details.php?id=<?= (int)$favorite['propertyId'] ?>">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
