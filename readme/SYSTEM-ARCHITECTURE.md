# Iskole School Management System - Architecture Documentation

## Table of Contents

1. [Overview](#overview)
2. [System Architecture](#system-architecture)
3. [Design Patterns](#design-patterns)
4. [Application Layers](#application-layers)
5. [Directory Structure](#directory-structure)
6. [Core Components](#core-components)
7. [Security Architecture](#security-architecture)
8. [Role-Based Access Control](#role-based-access-control)
9. [Module Interactions](#module-interactions)
10. [Data Flow](#data-flow)
11. [Session Management](#session-management)
12. [File Upload System](#file-upload-system)

---

## 1. Overview

**Iskole** is a comprehensive School Management System built using PHP with a custom MVC (Model-View-Controller) framework. The system manages various aspects of school operations including user management, attendance tracking, marks/grades, timetables, announcements, and more.

### Key Features

- Multi-role support (MP, Admin, Teacher, Student, Parent)
- RESTful API for AJAX operations
- Role-based access control
- Session-based authentication
- File upload management (images, documents)
- Real-time attendance and marks tracking
- Announcement system
- Timetable management

### Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 8.0+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla JS)
- **Web Server**: Apache with mod_rewrite
- **Deployment**: Docker, Docker Compose

---

## 2. System Architecture

Iskole follows a **3-Tier Architecture** pattern:

```
┌─────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                        │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │  Web Browser │  │   AJAX/API   │  │  Mobile App  │      │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘      │
└─────────┼──────────────────┼──────────────────┼─────────────┘
          │                  │                  │
          ▼                  ▼                  ▼
┌─────────────────────────────────────────────────────────────┐
│                    APPLICATION LAYER                         │
│  ┌──────────────────────────────────────────────────────┐   │
│  │              Front Controller (App.php)               │   │
│  └────────────┬─────────────────────────────────────────┘   │
│               │                                              │
│  ┌────────────▼──────────────────────────────────────────┐  │
│  │                    CONTROLLERS                         │  │
│  │  ┌─────────┐ ┌────────┐ ┌─────────┐ ┌─────────┐      │  │
│  │  │   MP    │ │ Admin  │ │ Teacher │ │ Student │      │  │
│  │  └─────────┘ └────────┘ └─────────┘ └─────────┘      │  │
│  │  ┌─────────┐ ┌────────┐ ┌─────────┐                  │  │
│  │  │ Parent  │ │  API   │ │  Login  │                  │  │
│  │  └─────────┘ └────────┘ └─────────┘                  │  │
│  └──────────────┬──────────────────────────────────────┘   │
│                 │                                            │
│  ┌──────────────▼──────────────────────────────────────┐   │
│  │                      MODELS                          │   │
│  │  ┌──────┐ ┌─────┐ ┌────────┐ ┌────────────────┐    │   │
│  │  │ User │ │ MP  │ │Teacher │ │ Announcement   │    │   │
│  │  └──────┘ └─────┘ └────────┘ └────────────────┘    │   │
│  │  ┌────────┐ ┌────────┐                             │   │
│  │  │Student │ │ Parent │                             │   │
│  │  └────────┘ └────────┘                             │   │
│  └──────────────┬──────────────────────────────────────┘   │
│                 │                                            │
│  ┌──────────────▼──────────────────────────────────────┐   │
│  │                      VIEWS                           │   │
│  │  ┌──────────────────────────────────────────────┐   │   │
│  │  │  Templates (mp/, admin/, teacher/, etc.)     │   │   │
│  │  └──────────────────────────────────────────────┘   │   │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────┬───────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│                      DATA LAYER                              │
│  ┌──────────────────────────────────────────────────────┐   │
│  │               MySQL Database                          │   │
│  │  ┌─────────┐ ┌──────────┐ ┌────────┐ ┌──────────┐  │   │
│  │  │  users  │ │attendance│ │ marks  │ │ classes  │  │   │
│  │  └─────────┘ └──────────┘ └────────┘ └──────────┘  │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

---

## 3. Design Patterns

### 3.1 Front Controller Pattern

- **Implementation**: `App.php` acts as the single entry point
- **Purpose**: Centralized request handling, routing, and authentication
- **Flow**: All requests → `.htaccess` → `index.php` → `App.php` → Controller

### 3.2 MVC (Model-View-Controller)

- **Model**: Data access and business logic
- **View**: Presentation layer (HTML templates)
- **Controller**: Request handling and coordination

### 3.3 Singleton Pattern

- **Database Connection**: `Database::getInstance()`
- **Session Management**: `Session::getInstance()`
- **Purpose**: Single instance of critical resources

### 3.4 Factory Pattern (Implicit)

- **Controller Loading**: Dynamic controller instantiation based on URL
- **Model Loading**: `$this->model($modelName)` creates model instances

---

## 4. Application Layers

### 4.1 Presentation Layer

**Components**: HTML templates, CSS, JavaScript, AJAX

**Responsibilities**:

- Render user interfaces for different roles
- Handle user input and validation
- Make asynchronous API calls
- Display data and feedback to users

**Location**: `/app/Views/`

### 4.2 Business Logic Layer

**Components**: Controllers, Models

**Responsibilities**:

- Process user requests
- Enforce business rules
- Validate data
- Coordinate between models and views
- Handle authentication and authorization

**Location**: `/app/Controllers/`, `/app/Model/`

### 4.3 Data Access Layer

**Components**: Models, Database class

**Responsibilities**:

- Database queries (CRUD operations)
- Data validation
- Relationship management
- Transaction handling

**Location**: `/app/Model/`, `/app/Core/Database.php`

---

## 5. Directory Structure

```
Iskole/
├── .env                          # Environment configuration
├── .htaccess                     # Root htaccess (optional)
├── docker-compose.yml            # Docker orchestration
├── Dockerfile                    # Docker image definition
├── README.md                     # Project overview
├── ROUTING-GUIDE.md              # Routing documentation
├── DATABASE-SCHEMA.md            # Database documentation
├── API-DOCUMENTATION.md          # API documentation
├── SYSTEM-ARCHITECTURE.md        # This file
│
├── app/                          # Application core
│   ├── init.php                  # Bootstrap file
│   │
│   ├── Core/                     # Core framework files
│   │   ├── App.php               # Front controller (router)
│   │   ├── Controller.php        # Base controller
│   │   ├── Database.php          # Database singleton
│   │   └── Session.php           # Session management
│   │
│   ├── Controllers/              # Application controllers
│   │   ├── LoginController.php  # Authentication
│   │   ├── MpController.php     # Management Portal
│   │   ├── AdminController.php  # Admin operations
│   │   ├── TeacherController.php
│   │   ├── StudentController.php
│   │   ├── ParentController.php
│   │   ├── ApiController.php    # RESTful API
│   │   ├── HeaderController.php # Header generation
│   │   ├── HomeController.php   # Home/Dashboard
│   │   └── userDirectoryController.php
│   │
│   ├── Model/                    # Data models
│   │   ├── UserModel.php        # User operations
│   │   ├── MpModel.php          # MP-specific operations
│   │   ├── TeacherModel.php
│   │   ├── StudentModel.php
│   │   ├── ParentModel.php
│   │   └── AnnouncementModel.php
│   │
│   └── Views/                    # View templates
│       ├── templates/            # Shared templates
│       ├── mp/                   # MP views
│       ├── admin/                # Admin views
│       ├── teacher/              # Teacher views
│       ├── student/              # Student views
│       ├── parent/               # Parent views
│       └── login.php             # Login page
│
├── public/                       # Web root (document root)
│   ├── index.php                 # Entry point
│   ├── .htaccess                 # URL rewriting rules
│   │
│   ├── assets/                   # Uploaded files
│   │   ├── exam_timetable_images/
│   │   └── exam_timetable.json
│   │
│   ├── css/                      # Stylesheets
│   ├── js/                       # JavaScript files
│   └── images/                   # Static images
│
├── docker/                       # Docker configurations
│   └── nginx/                    # Nginx configs (if used)
│
└── scripts/                      # Utility scripts
    ├── backup.sh
    └── deploy.sh
```

---

## 6. Core Components

### 6.1 App.php (Front Controller)

**Purpose**: Central routing and request handling

**Key Methods**:

- `__construct()`: Initializes routing, parses URL, loads controller
- `parseUrl()`: Extracts and sanitizes URL segments

**Workflow**:

```php
1. Parse URL → [controller, method, param1, param2, ...]
2. Validate controller exists
3. Instantiate controller
4. Check method exists
5. Verify authentication (except public routes)
6. Execute: controller->method(params)
```

**Default Behavior**:

- Default controller: `LoginController`
- Default method: `index`
- Redirects to `/login` if not authenticated

### 6.2 Controller.php (Base Controller)

**Purpose**: Provides common functionality for all controllers

**Key Methods**:

- `view($view, $data)`: Load and render view templates
- `model($model)`: Load and instantiate models

**Properties**:

- `$session`: Session manager instance

### 6.3 Database.php (Singleton)

**Purpose**: Centralized database connection management

**Features**:

- PDO connection with error handling
- Singleton pattern (one connection per request)
- Configuration from `.env` file
- UTF-8 charset
- Exception mode for errors

**Configuration**:

```php
MYSQL_HOST      → Database host
MYSQL_PORT      → Database port (default: 3306)
MYSQL_DB        → Database name
MYSQL_USER      → Database username
MYSQL_PASSWORD  → Database password
```

### 6.4 Session.php (Singleton)

**Purpose**: Manage user sessions

**Key Methods**:

- `set($key, $value)`: Store session data
- `get($key)`: Retrieve session data
- `delete($key)`: Remove session data
- `destroy()`: End session

**Features**:

- Prevents duplicate session starts
- Singleton pattern for consistency
- Automatic session initialization

---

## 7. Security Architecture

### 7.1 Authentication System

**Session-Based Authentication**:

```php
// Check in App.php
if (!in_array($this->controller::class, $public) && !isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
```

**Login Flow**:

1. User submits credentials to `/login` (POST)
2. `LoginController` validates credentials
3. On success: Set `$_SESSION['user_id']` and `$_SESSION['role']`
4. Redirect to role-specific dashboard
5. On failure: Display error message

**Session Variables**:

- `user_id`: Unique user identifier
- `role`: User role (mp, admin, teacher, student, parent)
- Additional user data as needed

### 7.2 Authorization (Role-Based)

**Role Hierarchy**:

```
MP (Management Portal) → Highest privileges
  ├── Admin → School administration
  ├── Teacher → Teaching staff
  ├── Student → Enrolled students
  └── Parent → Guardian accounts
```

**Access Control**:

- Each controller checks `$_SESSION['role']`
- Views render based on role permissions
- API endpoints validate role before operations

**Example**:

```php
// In a controller
if ($_SESSION['role'] !== 'admin') {
    header('Location: /notfound');
    exit;
}
```

### 7.3 Input Sanitization

**URL Sanitization**:

```php
filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)
```

**File Upload Validation**:

```php
// MIME type checking
$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);

// Size limits
if ($file['size'] > 5 * 1024 * 1024) { // 5MB
    // Reject
}
```

**SQL Injection Prevention**:

- All models use PDO prepared statements
- Never concatenate user input into SQL

**Example**:

```php
$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
```

### 7.4 CSRF Protection

**TODO**: Implement CSRF tokens for forms

```php
// Generate token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Validate on POST
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token mismatch');
}
```

### 7.5 XSS Prevention

**Output Escaping**:

```php
// In views
echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
```

**Content Security Policy** (Recommended):

```apache
# In .htaccess
Header set Content-Security-Policy "default-src 'self';"
```

---

## 8. Role-Based Access Control

### 8.1 Roles and Permissions

| Role        | Controller        | Capabilities                                                     |
| ----------- | ----------------- | ---------------------------------------------------------------- |
| **MP**      | MpController      | Full system access, user management, global settings             |
| **Admin**   | AdminController   | School operations, timetables, announcements, reports            |
| **Teacher** | TeacherController | Attendance, marks entry, view classes, announcements             |
| **Student** | StudentController | View attendance, marks, timetable, announcements                 |
| **Parent**  | ParentController  | View child's attendance, marks, timetable, teacher communication |

### 8.2 Controller-Role Mapping

```
/mp/*       → Requires role = 'mp'
/admin/*    → Requires role = 'admin'
/teacher/*  → Requires role = 'teacher'
/student/*  → Requires role = 'student'
/parent/*   → Requires role = 'parent'
/login      → Public (no authentication)
```

### 8.3 View Segregation

Views are organized by role:

```
Views/
  ├── mp/index.php          → MP dashboard
  ├── admin/index.php       → Admin dashboard
  ├── teacher/index.php     → Teacher dashboard
  ├── student/index.php     → Student dashboard
  └── parent/index.php      → Parent dashboard
```

Each dashboard displays role-appropriate data and actions.

---

## 9. Module Interactions

### 9.1 User Management Module

**Flow**:

```
MP/Admin → userDirectoryController → UserModel → Database
                                   ↓
                              View: userDirectory.php
```

**Operations**:

- Create user (role selection)
- Edit user details
- Delete user (soft/hard delete)
- View user directory with filters

### 9.2 Attendance Module

**Flow**:

```
Teacher → TeacherController.markAttendance() → TeacherModel.saveAttendance()
                                             ↓
                                      Database (attendance table)
                                             ↓
Student → StudentController.viewAttendance() → StudentModel.getAttendance()
```

**Operations**:

- Mark attendance (Present/Absent/Late)
- View attendance records
- Generate attendance reports
- Export to CSV/PDF

### 9.3 Marks Management Module

**Flow**:

```
Teacher → TeacherController.enterMarks() → TeacherModel.saveMarks()
                                         ↓
                                  Database (marks table)
                                         ↓
Student → StudentController.viewMarks() → StudentModel.getMarks()
```

**Operations**:

- Enter marks by subject, exam type
- Update marks
- Calculate averages, grades
- Generate report cards

### 9.4 Announcement Module

**Flow**:

```
Admin → AdminController.createAnnouncement() → AnnouncementModel.save()
                                             ↓
                                        Database (announcements table)
                                             ↓
All Users → View announcements on dashboard
```

**Operations**:

- Create announcements (text, images)
- Target specific roles/grades
- Edit/delete announcements
- View announcements feed

### 9.5 Timetable Module

**Flow**:

```
Admin → AdminController.uploadExamTimeTable() → File Upload System
                                              ↓
                                        /assets/exam_timetable_images/
                                              ↓
                                        exam_timetable.json (metadata)
                                              ↓
Students → View timetable by grade
```

**Operations**:

- Upload exam timetables (images)
- Toggle visibility by grade
- View timetables
- Download timetables

---

## 10. Data Flow

### 10.1 Typical Request Flow (Page Load)

```
1. User navigates to: http://iskole.com/teacher/attendance

2. Apache .htaccess rewrites to: index.php?url=teacher/attendance

3. index.php:
   - session_start()
   - require init.php
   - new App()

4. App.php:
   - parseUrl() → ['teacher', 'attendance']
   - Load TeacherController.php
   - Instantiate TeacherController
   - Check authentication: $_SESSION['user_id'] exists?
   - Check role: $_SESSION['role'] === 'teacher'?
   - Call: TeacherController->attendance()

5. TeacherController->attendance():
   - Load TeacherModel
   - Fetch data: $model->getClasses()
   - Call: $this->view('teacher/attendance', ['classes' => $classes])

6. View (teacher/attendance.php):
   - Extract $classes
   - Render HTML with data
   - Include JavaScript for interactivity

7. Browser displays attendance page
```

### 10.2 API Request Flow (AJAX)

```
1. User action (e.g., mark attendance) triggers JavaScript:
   fetch('/api/saveAttendance', {
       method: 'POST',
       headers: { 'Content-Type': 'application/json' },
       body: JSON.stringify({ student_id: 123, date: '2024-01-15', status: 'present' })
   })

2. Apache rewrites to: index.php?url=api/saveAttendance

3. App.php:
   - parseUrl() → ['api', 'saveAttendance']
   - Load ApiController.php
   - Instantiate ApiController
   - Check authentication
   - Call: ApiController->saveAttendance()

4. ApiController->saveAttendance():
   - Validate input
   - Load TeacherModel
   - Call: $model->saveAttendance($data)
   - Return JSON: { "success": true, "message": "Attendance saved" }

5. JavaScript receives response:
   response.json().then(data => {
       if (data.success) {
           alert('Attendance saved!');
       }
   })
```

---

## 11. Session Management

### 11.1 Session Lifecycle

```
1. Login:
   - User submits credentials
   - LoginController validates
   - On success: $_SESSION['user_id'] = $userId
                 $_SESSION['role'] = $role
                 $_SESSION['name'] = $name

2. Request:
   - App.php checks $_SESSION['user_id']
   - Controller accesses session via $this->session

3. Logout:
   - User clicks logout
   - LoginController->logout()
   - session_destroy()
   - Redirect to /login
```

### 11.2 Session Security

**Session Fixation Prevention**:

```php
// After successful login
session_regenerate_id(true);
```

**Session Timeout** (Recommended):

```php
// In App.php
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: /login');
    exit;
}
$_SESSION['last_activity'] = time();
```

**Secure Session Configuration** (In php.ini or init.php):

```php
ini_set('session.cookie_httponly', 1); // Prevent JavaScript access
ini_set('session.cookie_secure', 1);   // HTTPS only
ini_set('session.use_strict_mode', 1); // Reject uninitialized session IDs
```

---

## 12. File Upload System

### 12.1 Upload Flow

```
1. User selects file in form
2. Form submits (POST) with enctype="multipart/form-data"
3. Controller receives $_FILES array
4. Validate:
   - File type (MIME check)
   - File size
   - File name sanitization
5. Move file to target directory:
   move_uploaded_file($tmpName, $targetPath)
6. Store metadata (path, name, size) in database or JSON
7. Return success/error response
```

### 12.2 Example: Exam Timetable Upload

**Controller**: `AdminController->uploadExamTimeTable()`

**Validation**:

```php
$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
if (!isset($allowed[$mime])) {
    // Reject
}
```

**Storage**:

```php
$dir = __DIR__ . '/../../public/assets/exam_timetable_images';
$filename = 'exam_tt_' . $grade . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$targetPath = $dir . '/' . $filename;
move_uploaded_file($file['tmp_name'], $targetPath);
```

**Metadata** (JSON):

```json
{
  "grade_10": {
    "file": "/assets/exam_timetable_images/exam_tt_grade_10_20240115_120000_a1b2c3d4.jpg",
    "hidden": false,
    "uploaded_at": "2024-01-15T12:00:00+00:00"
  }
}
```

### 12.3 Security Considerations

**File Type Validation**:

- Always check MIME type, not just extension
- Use `finfo_file()` for accurate detection

**File Name Sanitization**:

```php
$grade = preg_replace('/[^0-9A-Za-z_-]/', '', $_POST['grade']);
$filename = preg_replace('/[^A-Za-z0-9_.-]/', '', $originalName);
```

**Storage Location**:

- Store in `/public/assets/` for web access
- Never store in application directory (`/app/`)
- Use random filenames to prevent overwriting

**Access Control**:

- Implement checks before serving files
- Prevent directory traversal attacks

---

## Summary

The Iskole School Management System is a robust, secure, and scalable application built on a custom MVC framework. Key architectural strengths include:

1. **Front Controller Pattern**: Centralized routing and authentication
2. **Singleton Pattern**: Efficient resource management (Database, Session)
3. **Role-Based Access Control**: Fine-grained permissions
4. **API-Driven**: RESTful endpoints for modern AJAX operations
5. **Secure by Design**: Input sanitization, prepared statements, session management
6. **Modular Structure**: Clear separation of concerns (MVC)

The system is designed for extensibility, allowing new roles, modules, and features to be added with minimal disruption to existing code.

---

**Related Documentation**:

- [Routing Guide](ROUTING-GUIDE.md)
- [Database Schema](DATABASE-SCHEMA.md)
- [API Documentation](API-DOCUMENTATION.md)
- [Development Guide](DEVELOPMENT-GUIDE.md)
- [Deployment Guide](DEPLOYMENT-GUIDE.md)
