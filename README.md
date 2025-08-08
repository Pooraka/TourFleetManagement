# Tour Fleet Management System for Skyline Tours

This repository contains the source code for a comprehensive Tour Fleet Management System, developed as a final year project for the Bachelor of Information Technology degree at the University of Colombo School of Computing. The system is designed to digitize and streamline the core operations of a tour bus rental company.


## 🚩 The Problem
Skyline Tours, a tour bus rental company, was managing its entire operation using a manual, paper-based system. This led to significant business challenges, including:

- **Frequent Booking Overlaps:** Poor planning and lack of a central system caused double bookings and scheduling conflicts.
- **Inaccurate Financial Tracking:** Manual ledgers made it difficult to track payments, manage expenses, and identify potential financial losses.
- **Poor Inventory Management:** Inefficient tracking of spare parts resulted in stock imbalances and unexpected shortages.
- **Operational Disruptions:** Missed vehicle service schedules led to preventable, mid-tour breakdowns, impacting customer satisfaction and company reputation.

This project was motivated by the need to solve these critical issues by developing a centralized, reliable, and efficient software solution.

---

## ✨ Key Features

The system is a full-stack web application built from the ground up to address all aspects of the business. It is organized into several interconnected modules:

####  booking & Customer Management
-   **Quotation Generation:** Create and send professional tour quotations to clients.
-   **Conflict-Free Scheduling:** A visual calendar and robust validation prevent double-bookings.
-   **Customer Database:** Manages all client information and booking history.

#### ⚙️ Fleet & Maintenance
-   **Bus & Vehicle Records:** Central repository for all vehicle details, including registration and insurance.
-   **Automated Service Scheduling:** Schedules routine maintenance and pre-tour inspections to prevent breakdowns.
-   **Spare Parts Inventory:** Tracks stock levels, manages issuances, and sends automated alerts for low-stock items.

#### 💰 Finance & Procurement
-   **Invoice & Payment Tracking:** Manages customer invoices, tracks incoming payments, and handles refunds.
-   **Supplier Tender Process:** Implements a structured tender module for competitive supplier bidding and fair pricing.
-   **Purchase Order Management:** Handles the entire procurement lifecycle from PO generation to supplier payment.

#### 📊 Reporting & Automation
-   **Business Intelligence:** Generates comprehensive reports to support informed management decisions.
-   **Automated Notifications:** Sends email reminders for service dues, low stock levels, and other critical events.
-   **Document Generation:** Automatically creates booking receipts, refund notes, purchase orders, and goods received notices.

---

## 🛠️ Tech Stack & Architecture

-   **Backend:** PHP
-   **Frontend:** HTML, CSS, JavaScript
-   **Database:** MySQL
-   **Web Server:** Apache (via XAMPP)
-   **Architecture:** Model-View-Controller (MVC)
-   **Methodology:** Rational Unified Process (RUP)

---

## 🚀 Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites

-   A local server environment like [XAMPP](https://www.apachefriends.org/index.html) or WAMP.

### Installation

1.  **Clone the repository:**
    ```sh
    git clone https://github.com/Pooraka/TourFleetManagement.git
    ```
2.  **Move to your server directory:**
    -   Place the cloned folder into the `htdocs` directory of your XAMPP installation.

3.  **Set up the Database:**
    -   Open `phpMyAdmin` (usually at `http://localhost/phpmyadmin`).
    -   Create a new database and name it `tour_fleet_management_system_db`.
    -   Select the new database and go to the `Import` tab.
    -   Upload the `tour_fleet_management_system_db.sql` file (this file is inside the 'dbbackup' folder) and click `Go`.

4.  **Configure the Application:**
    -   Navigate to the database connection file in the project (e.g., `/config/database.php`).
    -   Ensure the database name, username (usually `root`), and password (usually empty) match your local setup.

5.  **Run the Application:**
    -   Open your web browser and navigate to `http://localhost/tourfleetmanagement/view/login.php`.

---

## 🔮 Future Enhancements

The system has a solid foundation with several opportunities for future development:

-   **Real-Time GPS Tracking:** Integrate a GPS module to monitor the live location of fleet vehicles.
-   **Advanced Fuel Management:** Track fuel consumption to optimize operational costs and monitor vehicle performance.
-   **Public Transport Route Management:** Extend the system to manage buses assigned to fixed public transport routes.

---

## 👤 Author

**K.D.P. Hasendra**

-   **LinkedIn:** [https://www.linkedin.com/in/kdphasendra]
