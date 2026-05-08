
-- Real Estate Agency Portal Database Script
-- Spring 2026


CREATE DATABASE IF NOT EXISTS real_estate_portal_db;
USE real_estate_portal_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP VIEW IF EXISTS PropertyListingView;
DROP TRIGGER IF EXISTS AfterTransactionInsert;
DROP PROCEDURE IF EXISTS AddOrUpdateUser;
DROP PROCEDURE IF EXISTS ProcessTransaction;
DROP TABLE IF EXISTS Favorites;
DROP TABLE IF EXISTS Transactions;
DROP TABLE IF EXISTS Inquiries;
DROP TABLE IF EXISTS Properties;
DROP TABLE IF EXISTS Users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE Users (
    userId INT NOT NULL AUTO_INCREMENT,
    userName VARCHAR(50) NOT NULL UNIQUE,
    contactInfo VARCHAR(200),
    passwordHash VARCHAR(255) NOT NULL,
    userType ENUM('agent', 'buyer', 'renter') NOT NULL,
    PRIMARY KEY (userId)
);

CREATE TABLE Properties (
    propertyId INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    propertyType VARCHAR(50) NOT NULL,
    address VARCHAR(200) NOT NULL,
    city VARCHAR(100) NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    status ENUM('available', 'sold', 'rented') NOT NULL DEFAULT 'available',
    imagePath VARCHAR(255) NULL,
    agentId INT NOT NULL,
    PRIMARY KEY (propertyId),
    CONSTRAINT fk_properties_agent FOREIGN KEY (agentId) REFERENCES Users(userId)
);

CREATE TABLE Inquiries (
    inquiryId INT NOT NULL AUTO_INCREMENT,
    userId INT NOT NULL,
    propertyId INT NOT NULL,
    message VARCHAR(255) NOT NULL,
    inquiryDate DATETIME NOT NULL,
    PRIMARY KEY (inquiryId),
    CONSTRAINT fk_inquiries_user FOREIGN KEY (userId) REFERENCES Users(userId),
    CONSTRAINT fk_inquiries_property FOREIGN KEY (propertyId) REFERENCES Properties(propertyId)
);

CREATE TABLE Transactions (
    transactionId INT NOT NULL AUTO_INCREMENT,
    propertyId INT NOT NULL,
    userId INT NOT NULL,
    transactionType ENUM('sale', 'rental') NOT NULL,
    transactionDate DATETIME NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    PRIMARY KEY (transactionId),
    CONSTRAINT fk_transactions_property FOREIGN KEY (propertyId) REFERENCES Properties(propertyId),
    CONSTRAINT fk_transactions_user FOREIGN KEY (userId) REFERENCES Users(userId)
);

CREATE TABLE Favorites (
    favoriteId INT NOT NULL AUTO_INCREMENT,
    userId INT NOT NULL,
    propertyId INT NOT NULL,
    savedDate DATETIME NOT NULL,
    PRIMARY KEY (favoriteId),
    CONSTRAINT fk_favorites_user FOREIGN KEY (userId) REFERENCES Users(userId),
    CONSTRAINT fk_favorites_property FOREIGN KEY (propertyId) REFERENCES Properties(propertyId),
    CONSTRAINT uq_favorite_user_property UNIQUE (userId, propertyId)
);

DELIMITER $$

CREATE PROCEDURE AddOrUpdateUser(
    IN p_userId INT,
    IN p_userName VARCHAR(50),
    IN p_contactInfo VARCHAR(200),
    IN p_passwordHash VARCHAR(255),
    IN p_userType ENUM('agent', 'buyer', 'renter')
)
BEGIN
    IF p_userId IS NULL OR p_userId = 0 THEN
        INSERT INTO Users (userName, contactInfo, passwordHash, userType)
        VALUES (p_userName, p_contactInfo, p_passwordHash, p_userType);
    ELSE
        UPDATE Users
        SET userName = p_userName,
            contactInfo = p_contactInfo,
            passwordHash = p_passwordHash,
            userType = p_userType
        WHERE userId = p_userId;
    END IF;
END $$

CREATE PROCEDURE ProcessTransaction(
    IN p_propertyId INT,
    IN p_userId INT,
    IN p_transactionType ENUM('sale', 'rental'),
    IN p_amount DECIMAL(12,2)
)
BEGIN
    INSERT INTO Transactions (propertyId, userId, transactionType, transactionDate, amount)
    VALUES (p_propertyId, p_userId, p_transactionType, NOW(), p_amount);

    UPDATE Properties
    SET status = CASE
        WHEN p_transactionType = 'sale' THEN 'sold'
        WHEN p_transactionType = 'rental' THEN 'rented'
        ELSE status
    END
    WHERE propertyId = p_propertyId;
END $$

CREATE TRIGGER AfterTransactionInsert
AFTER INSERT ON Transactions
FOR EACH ROW
BEGIN
    UPDATE Properties
    SET status = CASE
        WHEN NEW.transactionType = 'sale' THEN 'sold'
        WHEN NEW.transactionType = 'rental' THEN 'rented'
        ELSE status
    END
    WHERE propertyId = NEW.propertyId;
END $$

DELIMITER ;

CREATE VIEW PropertyListingView AS
SELECT
    p.propertyId,
    p.title,
    p.propertyType,
    p.address,
    p.city,
    p.price,
    p.status,
    p.imagePath,
    u.userName AS agentName
FROM Properties p
JOIN Users u ON p.agentId = u.userId;

-- ---------------------------------------------------------
-- Sample Data
-- Password values correspond to the plain text password: Password123!
-- ---------------------------------------------------------
INSERT INTO Users (userName, contactInfo, passwordHash, userType) VALUES
('agent_maria', 'maria@agency.com | 555-0101', '$2y$10$wH4svM8V8XlKx2UY8ailquYp9jwELyhl725LLJoPLD114F8CbnMDa', 'agent'),
('buyer_james', 'james@email.com | 555-0102', '$2y$10$wH4svM8V8XlKx2UY8ailquYp9jwELyhl725LLJoPLD114F8CbnMDa', 'buyer'),
('renter_lisa', 'lisa@email.com | 555-0103', '$2y$10$wH4svM8V8XlKx2UY8ailquYp9jwELyhl725LLJoPLD114F8CbnMDa', 'renter');

INSERT INTO Properties (title, propertyType, address, city, price, status, imagePath, agentId) VALUES
('Modern Townhouse Residence', 'Townhouse', '741 Maple Heights', 'Santo Domingo', 315000.00, 'available', 'assets/images/house1.jpg', 1),
('Luxury Urban Apartment', 'Apartment', '88 Skyline Avenue', 'Santiago', 210000.00, 'available', 'assets/images/house4.jpg', 1),
('Family Garden House', 'House', '154 Green Valley Drive', 'La Vega', 289500.00, 'available', 'assets/images/house2.jpg', 1),
('Executive Contemporary Home', 'House', '92 Cedar Ridge', 'Punta Cana', 425000.00, 'sold', 'assets/images/house3.jpg', 1),
('Elegant Forest Villa', 'House', '17 Pine Crest Lane', 'San Francisco de Macoris', 398000.00, 'available', 'assets/images/house5.jpg', 1),
('Sunset Family Residence', 'House', '255 Sunset Boulevard', 'Puerto Plata', 355000.00, 'rented', 'assets/images/house6.jpg', 1);

INSERT INTO Inquiries (userId, propertyId, message, inquiryDate) VALUES
(2, 1, 'I would like to schedule a visit this weekend.', '2026-03-10 10:00:00'),
(3, 2, 'Is this apartment still available for immediate occupancy?', '2026-03-11 14:30:00'),
(2, 3, 'Can you share more details about the neighborhood and schools?', '2026-03-12 09:15:00');

INSERT INTO Transactions (propertyId, userId, transactionType, transactionDate, amount) VALUES
(4, 2, 'sale', '2026-03-15 11:00:00', 425000.00),
(6, 3, 'rental', '2026-03-18 16:00:00', 355000.00),
(3, 2, 'sale', '2026-03-20 13:30:00', 289500.00);

INSERT INTO Favorites (userId, propertyId, savedDate) VALUES
(2, 1, '2026-03-09 08:00:00'),
(2, 3, '2026-03-10 08:30:00'),
(3, 5, '2026-03-11 09:00:00');

