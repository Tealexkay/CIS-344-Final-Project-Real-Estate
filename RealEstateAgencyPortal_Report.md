# Real Estate Agency Portal Report

**Course Project:** Real Estate Agency Portal  
**Semester:** Spring 2026  
**Author:** Manus AI

## 1. Project Purpose

The **Real Estate Agency Portal** was developed as a PHP and MySQL web application that simulates a realistic real estate agency environment. The system allows **agents**, **buyers**, and **renters** to interact with property listings through a role-based workflow. Agents can add and manage properties, while buyers and renters can browse listings, save favorites, and submit inquiries.

The final version of the project also includes **property image support**, which improves the visual quality of the portal and makes the listings more realistic for demonstrations and presentations.

| Project Area | Description |
| --- | --- |
| Front end | PHP pages with HTML and CSS |
| Back end | PHP with PDO and MySQL |
| Authentication | Secure login, password hashing, sessions, and role-based access |
| Data management | Users, properties, inquiries, favorites, and transactions |
| Visual enhancements | Property image gallery and image-enabled listings |

## 2. System Objectives

The main objective of the project is to provide a working academic example of a real estate management portal that connects a PHP interface to a MySQL database. The application demonstrates CRUD-style operations, secure authentication, database relationships, and practical role-based behavior.

A second objective is to prepare the project for classroom presentation. For that reason, the system was organized with clear navigation, sample records, seeded property images, and demonstration-friendly features that can be shown during a live walkthrough.

| User Role | Main Capabilities |
| --- | --- |
| Agent | Log in, access dashboard, add property listings, review listing information |
| Buyer | Register, log in, browse properties, submit inquiries, save favorites |
| Renter | Register, log in, browse properties, submit inquiries, save favorites |

## 3. Database Design

The database for this project is named **`real_estate_portal_db`**. It contains the main entities required by the assignment: **Users**, **Properties**, **Inquiries**, **Transactions**, and **Favorites**. These tables are linked through primary keys and foreign keys so that the system can track user roles, listing ownership, user inquiries, completed deals, and saved properties.

The **Properties** table was extended with an `imagePath` field so that each listing can display a corresponding house image inside the portal.

| Table | Purpose | Key Relationships |
| --- | --- | --- |
| `Users` | Stores agents, buyers, and renters | Primary key: `userId` |
| `Properties` | Stores real estate listings | `agentId` references `Users(userId)` |
| `Inquiries` | Stores buyer/renter messages about listings | `userId` and `propertyId` are foreign keys |
| `Transactions` | Stores completed sale or rental records | Linked to both `Users` and `Properties` |
| `Favorites` | Stores properties saved by buyers/renters | Linked to both `Users` and `Properties` |

### Database Features

The SQL implementation includes the required database objects beyond the basic tables. The project contains stored procedures for adding or updating users and for processing transactions, a view for listing-friendly property output, and a trigger that updates property status after a transaction is recorded.

| Database Object | Purpose |
| --- | --- |
| `AddOrUpdateUser` | Inserts a new user or updates an existing user |
| `ProcessTransaction` | Inserts a transaction and updates property status |
| `PropertyListingView` | Combines property and agent information for easy display |
| `AfterTransactionInsert` | Automatically updates property status after insertion |

## 4. PHP Functionality

The PHP implementation was completed so that the portal behaves like a functional web application rather than a partial scaffold. The database connection uses PDO, and the application logic is organized in the `RealEstateDatabase` class. Methods were added or completed for user creation, property creation, inquiry submission, favorite management, user detail retrieval, and property filtering by city.

The portal pages were also revised to support a clearer user experience. The homepage now introduces the platform visually. The listings page shows property cards with images. The property details page shows a larger image for each listing. The dashboard summarizes user-related data such as inquiries, favorites, and transactions.

| PHP File | Implemented Functionality |
| --- | --- |
| `login.php` | Secure password verification and session creation |
| `register.php` | User registration with `password_hash()` |
| `dashboard.php` | User profile, favorites, inquiries, and transactions |
| `add_property.php` | Agent-only listing creation with optional image upload |
| `properties.php` | Searchable property listings with images |
| `property_details.php` | Full property view with image and action links |
| `submit_inquiry.php` | Inquiry workflow for existing or new buyer/renter users |
| `favorites.php` | Saved property view for buyers and renters |

## 5. Login, Sessions, and Role-Based Access Control

A secure login system is an important part of the portal. User passwords are stored as hashes rather than plain text, and logins are verified with `password_verify()`. When a valid login occurs, the session stores the authenticated user information and access is controlled according to the role.

Agents are allowed to add property listings. Buyers and renters are allowed to browse properties, save favorites, and submit inquiries. Unauthorized access is restricted through shared authentication helper functions.

| Security Feature | Implementation |
| --- | --- |
| Password hashing | `password_hash()` during registration |
| Password verification | `password_verify()` during login |
| Session handling | PHP sessions started in configuration file |
| Role-based control | `requireLogin()` and `requireRole()` helper functions |
| Protected pages | Dashboard, add property, favorites, and inquiry submission pages |

## 6. Property Image Integration

One of the major improvements in the final version is the addition of **house images** throughout the portal. The supplied images were added to the `assets/images/` directory and seeded into the sample property records through the SQL script. This means that once the SQL file is imported, each sample property immediately displays a real image.

The property listing page now presents image cards, the detail page displays a larger property image, the favorites page shows saved property thumbnails, and the homepage includes featured visual content for presentation purposes.

| Image File | Usage in the Project |
| --- | --- |
| `house1.jpg` | Homepage featured image and seeded property listing |
| `house2.jpg` | Sample house listing |
| `house3.jpg` | Sample premium home listing |
| `house4.jpg` | Sample apartment listing |
| `house5.jpg` | Sample villa listing |
| `house6.jpg` | Sample residence listing |

## 7. Sample Visuals for Presentation

The following images are included in the project and can be shown during the live presentation as part of the working listing interface.

![Featured Property](../assets/images/house1.jpg)

![Sample Family Home](../assets/images/house2.jpg)

![Luxury Apartment](../assets/images/house4.jpg)

## 8. Challenges and Solutions

During development, the main challenge was transforming the starter scaffold into a fully connected portal. Several methods in the original code were incomplete, which prevented the forms and role-based workflow from functioning correctly. Another challenge was making the portal visually appealing enough for a classroom demonstration.

These challenges were solved by completing the missing database methods, expanding the SQL script to include all required objects, integrating secure authentication, and adding image support across the project. The result is a more realistic application that is easier to demonstrate and explain.

| Challenge | Solution |
| --- | --- |
| Missing database tables or functionality | Completed SQL schema and seed data |
| Incomplete PHP methods | Implemented CRUD-style methods in `RealEstateDatabase.php` |
| Insecure login approach | Added password hashing and verification |
| Weak presentation visuals | Added image-rich property cards and homepage visuals |
| Limited demonstration flow | Added favorites, dashboard improvements, and clearer navigation |

## 9. Testing and Demonstration Checklist

The system should be tested locally in XAMPP after importing the SQL file into phpMyAdmin. Once the database is imported and the project folder is placed in `htdocs`, the application can be demonstrated from the browser.

A recommended presentation order is to show the homepage first, then log in with different roles, browse listings, open a property detail page, submit an inquiry, and finally show the favorites and dashboard pages. This demonstrates the most important assignment requirements in a clear sequence.

| Demo Step | What to Show |
| --- | --- |
| 1 | Homepage with featured property images |
| 2 | Registration and secure login |
| 3 | Agent dashboard and Add Property page |
| 4 | Property listings with images |
| 5 | Property details and inquiry submission |
| 6 | Favorites page for buyer/renter users |
| 7 | MySQL tables, stored procedures, view, and trigger in phpMyAdmin |

## 10. Setup Instructions

To run the project locally, copy the project folder into the XAMPP `htdocs` directory, start Apache and MySQL, and import `sql/real_estate_portal_db.sql` into phpMyAdmin. The database connection file should point to `localhost`, database name `real_estate_portal_db`, user `root`, and an empty password if the default XAMPP configuration is being used.

After the import, the portal can be opened in the browser. The seeded users allow immediate testing of each role, and the seeded property images make the listings ready for presentation.

| Setting | Value |
| --- | --- |
| Web root | `C:\xampp\htdocs\FinalStarterProjectS26` |
| URL | `http://localhost/FinalStarterProjectS26/` |
| Database name | `real_estate_portal_db` |
| Default user | `root` |
| Default password | empty string |
| Seed login password | `Password123!` |

## 11. Conclusion

The completed **Real Estate Agency Portal** satisfies the major academic requirements of the assignment by connecting PHP pages to a MySQL database, implementing secure authentication, enforcing role-based access, and supporting user interactions such as browsing, favorites, inquiries, and transactions. The addition of house images strengthens the realism of the portal and improves its quality for both submission and presentation.

Overall, the final project demonstrates database integration, object-oriented PHP structure, session-based security, and practical web application functionality in a format suitable for classroom demonstration and GitHub submission.
