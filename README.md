# SaigonBus - Bus Network Management System

![SaigonBus Logo](SaiGonBus.png)

## 📌 Project Overview
The **Bus Network Management System (SaigonBus)** is a web-based application built with **PHP, MySQL, HTML, and CSS**. It serves as a centralized platform for public transportation providers in Ho Chi Minh City to manage and retrieve operational data efficiently. 

This system allows managers to perform multiple database functions on drivers, buses, routes, and stations through an intuitive, dynamic user interface.

## 🚀 Features
* **Interactive Dashboard:** A responsive UI with seamless navigation between Home, Database, Search, and Update sections without needing multiple static pages.
* **Database Viewer (`show.php` & `web1.php`):** Dynamically fetches and displays full records of Drivers, Buses, Routes, and Stations. Column headers are automatically formatted for readability.
* **Smart Search Engine (`result.php`):** An advanced, order-independent search algorithm. It tokenizes user input and searches across multiple table columns simultaneously, ensuring highly accurate results regardless of the keyword order.
* **Real-time Status Updates (`update.php`):** Allows administrators to quickly update a bus's operational status (Active, Inactive, Maintenance) and log its latest maintenance date.

## 🛠️ Technologies Used
* **Frontend:** HTML5, CSS3 (Custom Variables, CSS Grid/Flexbox), JavaScript (DOM manipulation for view toggling)
* **Backend:** PHP 8+
* **Database:** MySQL (Relational Database Management System)

## 🗄️ Database Structure
The application connects to a MySQL database named `bus_system`. The core entities include:
1. **`driver`**: Stores driver personal info, license class, and contact details.
2. **`bus`**: Stores bus numbers, capacity, fuel type, and current operational status.
3. **`route`**: Details departures, destinations, distance, and frequencies.
4. **`station`**: Information on station capacity, location, and type.
5. **`contain`**: A relationship table linking routes and stations.

## ⚙️ Installation & Setup Instructions

To run this project locally on your machine, follow these steps:

### 1. Prerequisites
Install a local server environment such as **XAMPP**, **MAMP**, or **WAMP**.

### 2. Database Configuration
1. Open phpMyAdmin (`http://localhost/phpmyadmin`).
2. Create a new database named `bus_system`.
3. Import your SQL schema/dump file to create the necessary tables and populate them with dummy data.
4. **Important**: Verify the database credentials. Open all PHP files (`web1.php`, `index.php`, `result.php`, `show.php`, `update.php`) and update the connection variables if your local setup differs:
```php
   $servername = "localhost";
   $username = "tritin2805"; // Default XAMPP is usually "root"
   $password = "tin280506";  // Default XAMPP is usually "" (empty)
   $dbname = "bus_system";
