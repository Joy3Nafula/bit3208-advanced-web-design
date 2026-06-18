# Week 6: Multi-User Role Management System
## Project Overview

This week transforms the Task Manager into a multi-user system with role-based access control (RBAC). The system supports three user roles with different permissions and includes session management with persistent cookies.

---

## Features Implemented

- 3 User Roles: Admin, Manager, Student
- 3 Tables in 3NF: roles, users, tasks
- Role-based access control with different permissions per role
- PHP sessions with 7-day remember me cookies
- Admin can manage all users and tasks
- Manager can assign tasks to students
- Students can only view and manage their own tasks
- Full CRUD operations: Create, Read, Update, Delete



## User Roles and Permissions

Admin: Full system access - manage users, all tasks, assign tasks
Manager: Manage tasks, assign to students, view users (read-only)
Student: View and manage own tasks only



## File Structure

BIT3208_JOY_KARANI_WEEK6/
├── login.php                 Login page with role detection
├── register.html             Registration with role dropdown
├── process_login.php         Login processor with JOIN query
├── process_registration.php  Registration processor
├── dashboard.php             Role-based dashboard
├── header.php                Navigation with role badge
├── check_login.php           Auto-login with cookie
├── profile.php               User profile management
├── logout.php                Logout with cookie clear
├── manage_users.php          User management (Admin/Manager)
├── assign_task.php           Task assignment (Admin/Manager)
├── add_task.php              Create task (CRUD - C)
├── edit_task.php             Update task (CRUD - U)
├── delete_task.php           Delete task (CRUD - D)
├── style.css                 Styling with purple gradient
└── Week6db.sql               Database export


## CRUD Operations

CREATE: add_task.php - Insert new task into database
READ: dashboard.php - Display tasks from database
UPDATE: edit_task.php - Modify existing task
DELETE: delete_task.php - Remove task from database



## Session & Cookie Management

Session started with session_start() on all pages
Login state stored in $_SESSION with user_id and role
Remember Me uses 7-day cookie with remember_token
Auto-login through check_login.php validating cookie
Logout destroys session and clears cookie


## Testing Credentials

Admin: admin@example.com / Test1234
Manager: manager@example.com / Test1234
Student: student@example.com / Test1234

---

## Technologies Used

Frontend: HTML5, CSS3, JavaScript
Backend: PHP 8.x
Database: MySQL
Server: XAMPP (Apache + MySQL + PHP)
Design: Canva, Hand-drawn wireframes
Version Control: GitHub

---

## How to Run

Start XAMPP (Apache and MySQL)
Import Week6db.sql in phpMyAdmin
Access: http://localhost/BIT3208_JOY_KARANI_WEEK6/login.php
Login using credentials above

---

## Week 6 Reflection

Built multi-user role system with 3 tables in 3NF. Admin manages all users/tasks, Manager assigns tasks, Students view own tasks. Sessions with 7-day cookies maintain login. Used JOIN queries to display user roles. Technologies: PHP, MySQL, sessions, cookies, RBAC.

---

## GitHub Repository

https://github.com/Joy3Nafula/bit320-advanced-web-design

---

**Last Updated:** June 2025 | **Status:** Week 6 Complete
