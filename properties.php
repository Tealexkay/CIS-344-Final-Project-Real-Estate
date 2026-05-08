<?php
require_once 'classes/RealEstateDatabase.php';

$db = new RealEstateDatabase();
$city = trim($_GET['city'] ?? '');

if ($city !== '') {
    $properties = $db->getPropertiesByCity($city);
} else {
    $properties = $db->getPropertyListingView();
}
?>
<?php include 'includes/header.php'; ?>
<h2>Property Listings</h2>
<p>Browse the available real estate inventory and open any listing to view details, submit an inquiry, or save it to favorites.</p>

<form method="GET">
    <label for="city">Search by City</label>
    <input type="text" id="city" name="city" value="<?= htmlspecialchars($city) ?>" placeholder="Enter a city name">
    <button type="submit">Search</button>
    <?php if ($city !== ''): ?>
        <a href="properties.php">Clear</a>
    <?php endif; ?>
</form>

<?php if (!$properties): ?>
    <p>No properties found.</p>
<?php endif; ?>

<div class="property-grid">
    <?php foreach ($properties as $property): ?>
        <div class="card property-card">
            <?php if (!empty($property['imagePath'])): ?>
                <img class="property-thumb" src="<?= htmlspecialchars($property['imagePath']) ?>" alt="<?= htmlspecialchars($property['title']) ?>">
            <?php endif; ?>
            <h3><?= htmlspecialchars($property['title']) ?></h3>
            <p><strong>Type:</strong> <?= htmlspecialchars($property['propertyType']) ?></p>
            <?php if (isset($property['address'])): ?>
                <p><strong>Address:</strong> <?= htmlspecialchars($property['address']) ?></p>
            <?php endif; ?>
            <p><strong>City:</strong> <?= htmlspecialchars($property['city']) ?></p>
            <p><strong>Price:</strong> $<?= number_format((float)$property['price'], 2) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($property['status']) ?></p>
            <p><strong>Agent:</strong> <?= htmlspecialchars($property['agentName']) ?></p>
            <a href="property_details.php?id=<?= (int)$property['propertyId'] ?>">View Details</a>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
