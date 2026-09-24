# StudentLeaveSystem
# Student Leave Management System

A web-based **Student Leave Management System** developed using **PHP** to simplify and digitize the process of submitting, reviewing, and approving student leave requests.

## 📌 Project Overview

The Student Leave Management System provides a centralized platform where students can submit leave requests online, while the **Head of the Department (HOD)** can review and approve or reject those requests.

The system reduces manual paperwork and provides an organized way to manage student leave applications and their approval status.

## 🚀 Features

### 👨‍🎓 Student

* Student login and authentication
* Submit leave requests online
* Enter leave details and reason
* View submitted leave requests
* Track leave approval status
* View leave history

### 👨‍💼 HOD / Head of Department

* HOD login
* View student leave requests
* Review leave details
* Approve or reject leave applications
* Track approved and rejected requests
* Manage student leave records

## 🛠️ Technologies Used

* **PHP** – Server-side scripting and application logic
* **MySQL** – Database management
* **HTML5** – Web page structure
* **CSS3** – Styling and interface design
* **JavaScript** – Client-side functionality

## 🔄 System Workflow

```text
Student
   ↓
Login
   ↓
Submit Leave Request
   ↓
Leave Stored in Database
   ↓
HOD Reviews Request
   ↓
Approve / Reject
   ↓
Student Views Status
```

## 📂 Main Modules

* Student Authentication
* HOD Authentication
* Leave Request Management
* Leave Approval Management
* Leave Status Tracking
* Leave History
* Database Management

## ⚙️ Installation and Setup

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/student-leave-management-system.git
```

### 2. Move the Project

Place the project folder inside your XAMPP `htdocs` directory:

```text
C:\xampp\htdocs\
```

### 3. Start XAMPP

Start the following services:

* Apache
* MySQL

### 4. Create the Database

Open **phpMyAdmin**:

```text
http://localhost/phpmyadmin
```

Create a new database and import the SQL file provided in the project.

### 5. Configure Database Connection

Update the database configuration in the PHP connection file with your MySQL credentials.

Example:

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "student_leave";
```

### 6. Run the Project

Open your browser and navigate to:

```text
http://localhost/student-leave-management-system/
```

## 🎯 Objective

The main objective of this project is to provide a simple and efficient digital platform for managing student leave applications and HOD approvals while reducing manual paperwork and improving transparency in the leave management process.

## 🔮 Future Enhancements

* Email notifications for leave status updates
* Mobile-responsive interface
* Leave reports and analytics
* Multiple department support
* Admin dashboard
* Automated attendance integration

## 👨‍💻 Developer

Developed as an academic web application using **PHP, MySQL, HTML, CSS, and JavaScript**.

## 📄 License

This project is developed for educational and academic purposes.
