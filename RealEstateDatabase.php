<?php
require_once __DIR__ . '/Database.php';

class RealEstateDatabase {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function addUser(string $userName, string $contactInfo, string $passwordHash, string $userType): bool {
        $sql = "INSERT INTO Users (userName, contactInfo, passwordHash, userType)
                VALUES (:userName, :contactInfo, :passwordHash, :userType)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':userName' => $userName,
            ':contactInfo' => $contactInfo,
            ':passwordHash' => $passwordHash,
            ':userType' => $userType
        ]);
    }

    public function getUserByUsername(string $userName) {
        $sql = "SELECT * FROM Users WHERE userName = :userName LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userName' => $userName]);
        return $stmt->fetch();
    }

    public function getUserById(int $userId) {
        $sql = "SELECT * FROM Users WHERE userId = :userId LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetch();
    }

    public function addProperty(
        string $title,
        string $propertyType,
        string $address,
        string $city,
        float $price,
        string $status,
        int $agentId,
        ?string $imagePath = null
    ): bool {
        $sql = "INSERT INTO Properties (title, propertyType, address, city, price, status, imagePath, agentId)
                VALUES (:title, :propertyType, :address, :city, :price, :status, :imagePath, :agentId)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':title' => $title,
            ':propertyType' => $propertyType,
            ':address' => $address,
            ':city' => $city,
            ':price' => $price,
            ':status' => $status,
            ':imagePath' => $imagePath,
            ':agentId' => $agentId
        ]);
    }

    public function getAllProperties(): array {
        $sql = "SELECT p.propertyId, p.title, p.propertyType, p.address, p.city, p.price, p.status, p.imagePath, p.agentId,
                       u.userName AS agentName
                FROM Properties p
                JOIN Users u ON p.agentId = u.userId
                ORDER BY p.propertyId DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function getPropertyListingView(): array {
        $sql = "SELECT * FROM PropertyListingView ORDER BY propertyId DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function getPropertyById(int $propertyId) {
        $sql = "SELECT p.*, u.userName AS agentName
                FROM Properties p
                JOIN Users u ON p.agentId = u.userId
                WHERE p.propertyId = :propertyId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':propertyId' => $propertyId]);
        return $stmt->fetch();
    }

    public function addInquiry(int $userId, int $propertyId, string $message): bool {
        $sql = "INSERT INTO Inquiries (userId, propertyId, message, inquiryDate)
                VALUES (:userId, :propertyId, :message, NOW())";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':userId' => $userId,
            ':propertyId' => $propertyId,
            ':message' => $message
        ]);
    }

    public function addFavorite(int $userId, int $propertyId): bool {
        $checkSql = "SELECT favoriteId FROM Favorites WHERE userId = :userId AND propertyId = :propertyId LIMIT 1";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->execute([
            ':userId' => $userId,
            ':propertyId' => $propertyId
        ]);

        if ($checkStmt->fetch()) {
            return true;
        }

        $sql = "INSERT INTO Favorites (userId, propertyId, savedDate)
                VALUES (:userId, :propertyId, NOW())";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':userId' => $userId,
            ':propertyId' => $propertyId
        ]);
    }

    public function getFavoritesByUser(int $userId): array {
        $sql = "SELECT f.favoriteId, f.savedDate, p.propertyId, p.title, p.propertyType, p.address, p.city, p.price, p.status,
                       p.imagePath, u.userName AS agentName
                FROM Favorites f
                JOIN Properties p ON f.propertyId = p.propertyId
                JOIN Users u ON p.agentId = u.userId
                WHERE f.userId = :userId
                ORDER BY f.savedDate DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll();
    }

    public function getUserDetails(int $userId): array {
        $userSql = "SELECT * FROM Users WHERE userId = :userId";
        $userStmt = $this->conn->prepare($userSql);
        $userStmt->execute([':userId' => $userId]);
        $user = $userStmt->fetch();

        if (!$user) {
            return [];
        }

        $inquiriesSql = "SELECT i.inquiryId, i.message, i.inquiryDate, p.propertyId, p.title, p.city, p.status, p.imagePath
                         FROM Inquiries i
                         JOIN Properties p ON i.propertyId = p.propertyId
                         WHERE i.userId = :userId
                         ORDER BY i.inquiryDate DESC";
        $inquiriesStmt = $this->conn->prepare($inquiriesSql);
        $inquiriesStmt->execute([':userId' => $userId]);

        $favoritesSql = "SELECT f.favoriteId, f.savedDate, p.propertyId, p.title, p.city, p.price, p.status, p.imagePath
                         FROM Favorites f
                         JOIN Properties p ON f.propertyId = p.propertyId
                         WHERE f.userId = :userId
                         ORDER BY f.savedDate DESC";
        $favoritesStmt = $this->conn->prepare($favoritesSql);
        $favoritesStmt->execute([':userId' => $userId]);

        $transactionsSql = "SELECT t.transactionId, t.transactionType, t.transactionDate, t.amount,
                                   p.propertyId, p.title, p.city, p.imagePath
                            FROM Transactions t
                            JOIN Properties p ON t.propertyId = p.propertyId
                            WHERE t.userId = :userId
                            ORDER BY t.transactionDate DESC";
        $transactionsStmt = $this->conn->prepare($transactionsSql);
        $transactionsStmt->execute([':userId' => $userId]);

        return [
            'user' => $user,
            'inquiries' => $inquiriesStmt->fetchAll(),
            'favorites' => $favoritesStmt->fetchAll(),
            'transactions' => $transactionsStmt->fetchAll()
        ];
    }

    public function getPropertiesByCity(string $city): array {
        $sql = "SELECT p.propertyId, p.title, p.propertyType, p.address, p.city, p.price, p.status, p.imagePath, p.agentId,
                       u.userName AS agentName
                FROM Properties p
                JOIN Users u ON p.agentId = u.userId
                WHERE p.city LIKE :city
                ORDER BY p.propertyId DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':city' => '%' . $city . '%']);
        return $stmt->fetchAll();
    }
}
?>
