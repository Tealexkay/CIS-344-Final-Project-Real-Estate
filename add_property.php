<?php
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'classes/RealEstateDatabase.php';

requireRole(['agent']);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new RealEstateDatabase();

    $title = trim($_POST['title'] ?? '');
    $propertyType = trim($_POST['propertyType'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $status = $_POST['status'] ?? 'available';
    $agentId = (int)$_SESSION['user']['userId'];
    $imagePath = null;

    if ($title && $propertyType && $address && $city && $price > 0) {
        try {
            if (isset($_FILES['propertyImage']) && $_FILES['propertyImage']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/assets/images/uploads/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $originalName = basename($_FILES['propertyImage']['name']);
                $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($extension, $allowedExtensions, true)) {
                    throw new RuntimeException('Only JPG, JPEG, PNG, GIF, and WEBP images are allowed.');
                }

                $newFileName = uniqid('property_', true) . '.' . $extension;
                $targetFile = $uploadDir . $newFileName;

                if (!move_uploaded_file($_FILES['propertyImage']['tmp_name'], $targetFile)) {
                    throw new RuntimeException('The property image could not be uploaded.');
                }

                $imagePath = 'assets/images/uploads/' . $newFileName;
            }

            $db->addProperty($title, $propertyType, $address, $city, $price, $status, $agentId, $imagePath);
            $message = 'Property added successfully.';
        } catch (Throwable $e) {
            $message = 'Error: ' . $e->getMessage();
        }
    } else {
        $message = 'Please complete all required fields.';
    }
}
?>
<?php include 'includes/header.php'; ?>
<h2>Add Property</h2>
<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Title</label>
    <input type="text" name="title" required>

    <label>Property Type</label>
    <input type="text" name="propertyType" placeholder="Apartment, House, Condo..." required>

    <label>Address</label>
    <input type="text" name="address" required>

    <label>City</label>
    <input type="text" name="city" required>

    <label>Price</label>
    <input type="number" step="0.01" name="price" required>

    <label>Status</label>
    <select name="status">
        <option value="available">available</option>
        <option value="sold">sold</option>
        <option value="rented">rented</option>
    </select>

    <label>Property Image</label>
    <input type="file" name="propertyImage" accept="image/*">

    <button type="submit">Add Property</button>
</form>
<?php include 'includes/footer.php'; ?>
