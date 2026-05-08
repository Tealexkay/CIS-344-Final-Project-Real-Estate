# Real Estate Agency Portal

This project is a PHP and MySQL web application for a Real Estate Agency Portal. The portal supports agents, buyers, and renters through secure authentication, role-based access control, property browsing, inquiry submission, favorites management, and image-rich listing presentation.

## Final Project Features

The application now includes the required database and PHP functionality together with property image support. Agents can add property listings and optionally upload new images. Buyers and renters can register, log in, browse property listings, save favorites, and submit inquiries. The dashboard displays profile information and related user activity such as inquiries, favorites, and transactions.

The SQL script creates the required database objects, including the Users, Properties, Inquiries, Transactions, and Favorites tables. It also includes the required stored procedures, the property listing view, the transaction trigger, and seeded property images for the sample listings.

## Project Structure

| Path | Purpose 
| `config/`  Application configuration and database credentials 
| `classes/`  Database connection class and main application methods 
| `includes/` Shared layout and authorization helpers 
| `assets/`  Stylesheet and property image assets 
| `sql/`  MySQL database creation and sample data script 
| `reports/` Presentation-ready project report 
| Root PHP pages  Authentication, dashboard, property, inquiry, and favorites pages 

## Main Pages

 File | Purpose 
 `index.php`  Home page with featured property images 
 `login.php`  Secure login page 
 `register.php`  User registration page 
 `dashboard.php`  User dashboard with profile and activity data 
 `properties.php`  Browse and search property listings with images 
| `property_details.php`  View a single property and actions 
| `add_property.php`  Agent-only property creation page with image upload 
| `submit_inquiry.php`  Inquiry submission for buyers and renters 
| `favorites.php`  View saved properties 
| `save_favorite.php`  Save a property to favorites 
| `logout.php`  End the user session 

## Included Property Images

The supplied house images have been included inside `assets/images/` and are used by the seeded property records in the SQL script.

| Image File | Purpose 
| `assets/images/house1.jpg`  Featured property and sample listing 
| `assets/images/house2.jpg`  Sample family house listing 
| `assets/images/house3.jpg`  Sample executive home listing 
| `assets/images/house4.jpg`  Sample apartment listing 
| `assets/images/house5.jpg`  Sample villa listing 
| `assets/images/house6.jpg`  Sample residential listing 

## Included Report

The project now includes a presentation report:

`reports/RealEstateAgencyPortal_Report.md`

The report explains the project purpose, database design, PHP functionality, login and session handling, role-based access control, image integration.

## Local Setup

This project is designed for a local PHP and MySQL environment such as XAMPP with phpMyAdmin.

1. Copy the `FinalStarterProjectS26` folder into your XAMPP `htdocs` directory.
2. Start Apache and MySQL from XAMPP.
3. Open phpMyAdmin and import the SQL file located at `sql/real_estate_portal_db.sql`.
4. Confirm that the database credentials in `config/config.php` match your local environment.
5. Open the project in the browser at `http://localhost/FinalStarterProjectS26/`.

## Default Login Information

The sample database includes three starter accounts. The plain text password for each seeded account is:

`Password123!`

| Username | Role 
| `agent_maria` | agent |
| `buyer_james` | buyer |
| `renter_lisa` | renter |

## Notes

The project source code, SQL files, house images, and report have been prepared so the portal is ready for local testing, GitHub upload, and classroom presentation. The next recommended step is to import the SQL file into phpMyAdmin, run the project in XAMPP, capture screenshots from your local system.
