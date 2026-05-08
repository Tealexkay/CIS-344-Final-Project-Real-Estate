<?php
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'classes/RealEstateDatabase.php';

requireRole(['buyer', 'renter']);

$propertyId = isset($_GET['propertyId']) ? (int)$_GET['propertyId'] : 0;

if ($propertyId <= 0) {
    header('Location: properties.php');
    exit;
}

$db = new RealEstateDatabase();

try {
    $db->addFavorite((int)$_SESSION['user']['userId'], $propertyId);
    header('Location: favorites.php');
    exit;
} catch (Throwable $e) {
    die('Unable to save favorite: ' . htmlspecialchars($e->getMessage()));
}
?>
