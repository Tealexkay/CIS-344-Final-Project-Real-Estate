<?php
require_once 'classes/RealEstateDatabase.php';
$db = new RealEstateDatabase();

$propertyId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$property = $db->getPropertyById($propertyId);
?>
<?php include 'includes/header.php'; ?>
<h2>Property Details</h2>

<?php if (!$property): ?>
    <p class="error">Property not found.</p>
<?php else: ?>
    <div class="card property-detail-card">
        <?php if (!empty($property['imagePath'])): ?>
            <img class="property-detail-image" src="<?= htmlspecialchars($property['imagePath']) ?>" alt="<?= htmlspecialchars($property['title']) ?>">
        <?php endif; ?>
        <h3><?= htmlspecialchars($property['title']) ?></h3>
        <p><strong>Type:</strong> <?= htmlspecialchars($property['propertyType']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($property['address']) ?></p>
        <p><strong>City:</strong> <?= htmlspecialchars($property['city']) ?></p>
        <p><strong>Price:</strong> $<?= number_format((float)$property['price'], 2) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($property['status']) ?></p>
        <p><strong>Agent:</strong> <?= htmlspecialchars($property['agentName']) ?></p>
    </div>

    <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['userType'], ['buyer', 'renter'], true)): ?>
        <p><a href="submit_inquiry.php?propertyId=<?= (int)$property['propertyId'] ?>">Submit Inquiry</a></p>
        <p><a href="save_favorite.php?propertyId=<?= (int)$property['propertyId'] ?>">Save to Favorites</a></p>
    <?php endif; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
