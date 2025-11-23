# Iskole School Management System - Development Guide

## Table of Contents

1. [Development Environment Setup](#development-environment-setup)
2. [Project Setup](#project-setup)
3. [Coding Standards](#coding-standards)
4. [Development Workflow](#development-workflow)
5. [Creating New Features](#creating-new-features)
6. [Database Migrations](#database-migrations)
7. [Testing](#testing)
8. [Debugging](#debugging)
9. [Common Development Tasks](#common-development-tasks)
10. [Best Practices](#best-practices)
11. [Troubleshooting Development Issues](#troubleshooting-development-issues)

---

## 1. Development Environment Setup

### 1.1 Prerequisites

**Required Software**:

- PHP 7.4 or higher
- MySQL 8.0 or higher
- Apache 2.4 with mod_rewrite enabled
- Git
- Composer (optional, for future dependencies)
- Docker & Docker Compose (for containerized development)

**Recommended Tools**:

- IDE: PHPStorm, VS Code with PHP extensions
- Database Client: phpMyAdmin, MySQL Workbench, DBeaver
- API Testing: Postman, Insomnia, cURL
- Browser DevTools: Chrome DevTools, Firefox Developer Tools

### 1.2 System Requirements

**Minimum**:

- 2 GB RAM
- 2 CPU cores
- 10 GB disk space

**Recommended**:

- 4 GB RAM
- 4 CPU cores
- 20 GB disk space
- SSD for better performance

### 1.3 PHP Configuration

Edit `php.ini` for development:

```ini
; Development settings
display_errors = On
display_startup_errors = On
error_reporting = E_ALL
log_errors = On
error_log = /path/to/php-error.log

; File uploads
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20

; Session
session.cookie_httponly = 1
session.use_strict_mode = 1

; Memory
memory_limit = 256M

; Timezone
date.timezone = Africa/Nairobi  ; Or your timezone
```

### 1.4 Apache Configuration

Enable required modules:

```bash
sudo a2enmod rewrite
sudo a2enmod headers
sudo systemctl restart apache2
```

Virtual host configuration (`/etc/apache2/sites-available/iskole.conf`):

```apache
<VirtualHost *:80>
    ServerName iskole.local
    DocumentRoot /var/www/iskole/public

    <Directory /var/www/iskole/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/iskole_error.log
    CustomLog ${APACHE_LOG_DIR}/iskole_access.log combined
</VirtualHost>
```

Enable the site:

```bash
sudo a2ensite iskole.conf
sudo systemctl reload apache2
```

Add to `/etc/hosts`:

```
127.0.0.1   iskole.local
```

---

## 2. Project Setup

### 2.1 Clone Repository

```bash
git clone https://github.com/yourusername/iskole.git
cd iskole
```

### 2.2 Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env  # If exists, or create new
```

Edit `.env`:

```properties
# Database Configuration
MYSQL_HOST=localhost
MYSQL_PORT=3306
MYSQL_DB=iskole_dev
MYSQL_USER=iskole_user
MYSQL_PASSWORD=your_secure_password

# Application Settings
APP_ENV=development
APP_DEBUG=true
APP_URL=http://iskole.local
```

### 2.3 Database Setup

Create database:

```bash
mysql -u root -p
```

```sql
CREATE DATABASE iskole_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'iskole_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON iskole_dev.* TO 'iskole_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Import schema:

```bash
mysql -u iskole_user -p iskole_dev < database/schema.sql
```

Import sample data (optional):

```bash
mysql -u iskole_user -p iskole_dev < database/seed_data.sql
```

### 2.4 Docker Setup (Alternative)

Using Docker Compose:

```bash
docker-compose up -d
```

This will start:

- PHP-FPM container
- MySQL container
- Apache/Nginx container

Access application at `http://localhost:8080`

---

## 3. Coding Standards

### 3.1 PHP Coding Standards

Follow **PSR-12** Extended Coding Style Guide:

**File Structure**:

```php
<?php
// File-level docblock
/**
 * Short description of file purpose
 *
 * @author Your Name <your.email@example.com>
 * @version 1.0.0
 */

// Namespace (if used)
namespace App\Controllers;

// Use statements
use App\Core\Controller;
use App\Model\UserModel;

// Class definition
class UserController extends Controller
{
    // Class constants
    const MAX_USERS = 100;

    // Properties
    private $userModel;

    // Constructor
    public function __construct()
    {
        parent::__construct();
        $this->userModel = $this->model('UserModel');
    }

    // Methods
    public function index()
    {
        // Method body
    }
}
```

**Naming Conventions**:

- **Classes**: `PascalCase` (e.g., `UserController`, `TeacherModel`)
- **Methods**: `camelCase` (e.g., `getUserById`, `saveAttendance`)
- **Variables**: `camelCase` (e.g., `$userId`, `$attendanceData`)
- **Constants**: `UPPER_SNAKE_CASE` (e.g., `MAX_UPLOAD_SIZE`)
- **Files**: Match class name (e.g., `UserController.php`)

**Indentation**:

- Use 4 spaces (no tabs)
- Opening braces on same line for methods/functions
- Closing braces on new line

**Comments**:

```php
/**
 * Get user by ID
 *
 * @param int $userId The user's ID
 * @return array|null User data or null if not found
 */
public function getUserById($userId)
{
    // Validate user ID
    if (!is_numeric($userId)) {
        return null;
    }

    // Fetch from database
    return $this->userModel->findById($userId);
}
```

### 3.2 SQL Standards

**Use Prepared Statements**:

```php
// GOOD
$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

// BAD - SQL Injection vulnerability
$query = "SELECT * FROM users WHERE email = '$email'";
```

**Named Parameters** (when multiple params):

```php
$stmt = $db->prepare("INSERT INTO users (name, email, role) VALUES (:name, :email, :role)");
$stmt->execute([
    ':name' => $name,
    ':email' => $email,
    ':role' => $role
]);
```

**Table and Column Names**:

- Use lowercase with underscores (e.g., `attendance_records`, `user_id`)
- Pluralize table names (e.g., `users`, `classes`, `announcements`)

### 3.3 JavaScript/Frontend Standards

**Naming**:

- Variables: `camelCase` (e.g., `userName`, `attendanceData`)
- Constants: `UPPER_SNAKE_CASE` (e.g., `API_BASE_URL`)
- Functions: `camelCase` (e.g., `saveAttendance`, `fetchUserData`)

**Modern JavaScript**:

```javascript
// Use const/let, not var
const apiUrl = "/api/users";
let userData = null;

// Use arrow functions
const fetchUsers = () => {
  return fetch(apiUrl).then((res) => res.json());
};

// Use template literals
const greeting = `Hello, ${userName}!`;

// Use async/await
async function loadUserData(userId) {
  try {
    const response = await fetch(`/api/users/${userId}`);
    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error:", error);
  }
}
```

**Comments**:

```javascript
/**
 * Save attendance record via API
 * @param {number} studentId - The student's ID
 * @param {string} date - Attendance date (YYYY-MM-DD)
 * @param {string} status - Attendance status (present/absent/late)
 * @returns {Promise<Object>} API response
 */
async function saveAttendance(studentId, date, status) {
  // Implementation
}
```

### 3.4 HTML/CSS Standards

**HTML**:

- Use semantic tags (`<header>`, `<nav>`, `<main>`, `<section>`, `<article>`)
- Proper indentation (2 or 4 spaces)
- Close all tags
- Use lowercase for tags and attributes

**CSS**:

- Use BEM naming convention: `.block__element--modifier`
- Organize by component
- Use CSS variables for colors and spacing

```css
:root {
  --primary-color: #3498db;
  --secondary-color: #2ecc71;
  --spacing-unit: 8px;
}

.card {
  padding: calc(var(--spacing-unit) * 2);
  background-color: var(--primary-color);
}

.card__header {
  font-size: 1.5rem;
  font-weight: bold;
}

.card__header--large {
  font-size: 2rem;
}
```

---

## 4. Development Workflow

### 4.1 Git Workflow

**Branch Strategy**:

```
main (production)
  ├── develop (integration)
  │   ├── feature/user-management
  │   ├── feature/attendance-module
  │   ├── bugfix/login-redirect
  │   └── hotfix/security-patch
```

**Creating a Feature**:

```bash
# Create feature branch from develop
git checkout develop
git pull origin develop
git checkout -b feature/new-feature-name

# Make changes
git add .
git commit -m "feat: Add new feature description"

# Push to remote
git push origin feature/new-feature-name

# Create Pull Request to develop
```

**Commit Message Convention**:

```
feat: Add new feature
fix: Fix bug description
docs: Update documentation
style: Format code (no functional changes)
refactor: Refactor code structure
test: Add tests
chore: Update dependencies, config
```

### 4.2 Code Review Process

**Before Submitting PR**:

1. Test locally
2. Run linters/formatters
3. Check for console errors
4. Verify database changes
5. Update documentation

**PR Template**:

```markdown
## Description

Brief description of changes

## Type of Change

- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing

- How to test these changes
- Test cases covered

## Screenshots (if applicable)

## Checklist

- [ ] Code follows project style guidelines
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] No new warnings
```

---

## 5. Creating New Features

### 5.1 Adding a New Controller

**Step 1: Create Controller File**

`app/Controllers/ReportController.php`:

```php
<?php
class ReportController extends Controller
{
    private $reportModel;

    public function __construct()
    {
        parent::__construct();
        $this->reportModel = $this->model('ReportModel');
    }

    public function index()
    {
        // Check authentication
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Check authorization
        $allowedRoles = ['admin', 'teacher'];
        if (!in_array($_SESSION['role'], $allowedRoles)) {
            header('Location: /notfound');
            exit;
        }

        // Fetch data
        $reports = $this->reportModel->getAllReports();

        // Render view
        $this->view('report/index', ['reports' => $reports]);
    }

    public function generate($type = 'attendance')
    {
        // Generate report logic
        $data = $this->reportModel->generateReport($type);

        // Return JSON for AJAX
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $data]);
    }
}
```

**Step 2: Create Model**

`app/Model/ReportModel.php`:

```php
<?php
class ReportModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAllReports()
    {
        $stmt = $this->db->query("SELECT * FROM reports ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function generateReport($type)
    {
        // Report generation logic
        $stmt = $this->db->prepare("SELECT * FROM {$type}_data WHERE date >= ?");
        $stmt->execute([date('Y-m-01')]); // Current month
        return $stmt->fetchAll();
    }
}
```

**Step 3: Create View**

`app/Views/report/index.php`:

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports - Iskole</title>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <?php include __DIR__ . '/../templates/header.php'; ?>

    <main class="container">
        <h1>Reports</h1>

        <div class="reports-list">
            <?php foreach ($reports as $report): ?>
                <div class="report-card">
                    <h3><?= htmlspecialchars($report['title']) ?></h3>
                    <p><?= htmlspecialchars($report['description']) ?></p>
                    <a href="/report/view/<?= $report['id'] ?>" class="btn">View</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script src="/js/reports.js"></script>
</body>
</html>
```

**Step 4: Test**

Navigate to: `http://iskole.local/report`

### 5.2 Adding a New API Endpoint

**In ApiController.php**:

```php
public function getStudentReport()
{
    header('Content-Type: application/json');

    // Validate request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        return;
    }

    // Get input
    $input = json_decode(file_get_contents('php://input'), true);
    $studentId = $input['student_id'] ?? null;

    if (!$studentId) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing student_id']);
        return;
    }

    // Fetch data
    $model = $this->model('StudentModel');
    $report = $model->getFullReport($studentId);

    // Return response
    echo json_encode([
        'success' => true,
        'data' => $report
    ]);
}
```

**JavaScript to call API**:

```javascript
async function fetchStudentReport(studentId) {
  try {
    const response = await fetch("/api/getStudentReport", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ student_id: studentId }),
    });

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }

    const data = await response.json();
    console.log(data);
    return data;
  } catch (error) {
    console.error("Error fetching report:", error);
  }
}
```

### 5.3 Adding a Database Table

**Create Migration File**

`database/migrations/2024_01_15_create_reports_table.sql`:

```sql
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('attendance', 'marks', 'financial') NOT NULL,
    generated_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (generated_by) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_type (type),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Run Migration**:

```bash
mysql -u iskole_user -p iskole_dev < database/migrations/2024_01_15_create_reports_table.sql
```

---

## 6. Database Migrations

### 6.1 Migration Structure

```
database/
  ├── migrations/
  │   ├── 2024_01_01_create_users_table.sql
  │   ├── 2024_01_02_create_classes_table.sql
  │   └── 2024_01_15_add_phone_to_users.sql
  └── seeds/
      ├── users_seed.sql
      └── classes_seed.sql
```

### 6.2 Writing Migrations

**Creating a Table**:

```sql
-- Migration: 2024_01_20_create_notifications_table.sql

CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_read (user_id, is_read),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Altering a Table**:

```sql
-- Migration: 2024_01_21_add_phone_to_users.sql

ALTER TABLE users
ADD COLUMN phone VARCHAR(20) AFTER email,
ADD COLUMN address TEXT AFTER phone;

-- Add index
CREATE INDEX idx_phone ON users(phone);
```

**Rollback (Manual)**:

```sql
-- Rollback: Drop columns added in 2024_01_21_add_phone_to_users.sql

ALTER TABLE users
DROP COLUMN phone,
DROP COLUMN address;

DROP INDEX idx_phone ON users;
```

### 6.3 Running Migrations

**Single Migration**:

```bash
mysql -u iskole_user -p iskole_dev < database/migrations/2024_01_20_create_notifications_table.sql
```

**All Migrations** (Bash Script):

`scripts/migrate.sh`:

```bash
#!/bin/bash

DB_USER="iskole_user"
DB_PASS="your_password"
DB_NAME="iskole_dev"
MIGRATIONS_DIR="database/migrations"

echo "Running migrations..."

for migration in $MIGRATIONS_DIR/*.sql; do
    echo "Applying: $(basename $migration)"
    mysql -u $DB_USER -p$DB_PASS $DB_NAME < $migration
    if [ $? -eq 0 ]; then
        echo "✓ Success"
    else
        echo "✗ Failed"
        exit 1
    fi
done

echo "All migrations completed!"
```

Make executable and run:

```bash
chmod +x scripts/migrate.sh
./scripts/migrate.sh
```

---

## 7. Testing

### 7.1 Manual Testing Checklist

**Feature Testing**:

- [ ] Feature works as expected
- [ ] All edge cases handled
- [ ] Error messages displayed correctly
- [ ] Data persists correctly
- [ ] UI is responsive

**Security Testing**:

- [ ] Authentication required
- [ ] Authorization enforced
- [ ] Input sanitized
- [ ] SQL injection prevented
- [ ] XSS prevented
- [ ] CSRF token validated (if applicable)

**Browser Testing**:

- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge

**Device Testing**:

- [ ] Desktop (1920x1080)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

### 7.2 API Testing with cURL

**GET Request**:

```bash
curl -X GET "http://iskole.local/api/getUsers" \
  -H "Content-Type: application/json" \
  -b "PHPSESSID=your_session_id"
```

**POST Request**:

```bash
curl -X POST "http://iskole.local/api/saveAttendance" \
  -H "Content-Type: application/json" \
  -d '{"student_id": 123, "date": "2024-01-15", "status": "present"}' \
  -b "PHPSESSID=your_session_id"
```

**With Authentication**:

```bash
# First login to get session
curl -X POST "http://iskole.local/login" \
  -d "email=admin@iskole.com&password=password" \
  -c cookies.txt

# Use session for API request
curl -X GET "http://iskole.local/api/getUsers" \
  -b cookies.txt
```

### 7.3 Database Testing

**Test Data Setup**:

```sql
-- Create test user
INSERT INTO users (name, email, password, role) VALUES
('Test Admin', 'test.admin@iskole.com', '$2y$10$hashedpassword', 'admin');

-- Create test class
INSERT INTO classes (class_name, grade, capacity) VALUES
('Test Class A', '10', 30);

-- Create test students
INSERT INTO users (name, email, password, role, class_id) VALUES
('Test Student 1', 'student1@test.com', '$2y$10$hashedpassword', 'student', 1),
('Test Student 2', 'student2@test.com', '$2y$10$hashedpassword', 'student', 1);
```

**Test Queries**:

```sql
-- Verify user creation
SELECT * FROM users WHERE email = 'test.admin@iskole.com';

-- Verify relationships
SELECT u.name, c.class_name
FROM users u
LEFT JOIN classes c ON u.class_id = c.id
WHERE u.role = 'student';

-- Test constraints
-- This should fail (duplicate email)
INSERT INTO users (name, email, password, role) VALUES
('Duplicate', 'test.admin@iskole.com', 'pass', 'admin');
```

---

## 8. Debugging

### 8.1 PHP Debugging

**Error Logging**:

```php
// In development, enable display_errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log to file
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/php-error.log');
```

**var_dump() and die()**:

```php
// Quick debugging
var_dump($userData);
die(); // Stop execution

// Better formatting
echo '<pre>';
print_r($userData);
echo '</pre>';
die();
```

**Debug Functions**:

```php
/**
 * Debug helper function
 */
function dd($var, $label = 'DEBUG') {
    echo '<pre style="background: #f4f4f4; padding: 10px; border: 1px solid #ccc;">';
    echo '<strong>' . htmlspecialchars($label) . ':</strong>' . PHP_EOL;
    print_r($var);
    echo '</pre>';
    die();
}

// Usage
dd($userData, 'User Data');
```

**SQL Query Debugging**:

```php
// Log query
error_log("SQL: " . $sql);
error_log("Params: " . json_encode($params));

// Check PDO errors
try {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    error_log("SQL: " . $sql);
    error_log("Params: " . json_encode($params));
}
```

### 8.2 JavaScript Debugging

**Console Methods**:

```javascript
// Basic logging
console.log("User data:", userData);

// Grouped logging
console.group("User Details");
console.log("ID:", userId);
console.log("Name:", userName);
console.groupEnd();

// Table view (for arrays of objects)
console.table(users);

// Performance timing
console.time("Data fetch");
await fetchData();
console.timeEnd("Data fetch");

// Stack trace
console.trace("Function called from");
```

**Breakpoints**:

```javascript
// In code
debugger; // Pauses execution in DevTools

// Conditional breakpoint
if (userId === 123) {
  debugger;
}
```

**Network Debugging**:

- Open Chrome DevTools (F12)
- Go to Network tab
- Filter by XHR/Fetch
- Click request to see headers, payload, response

### 8.3 Database Debugging

**Query Logging**:

Enable in MySQL config (`my.cnf`):

```ini
[mysqld]
general_log = 1
general_log_file = /var/log/mysql/query.log
```

**Slow Query Log**:

```ini
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2  # Queries taking > 2 seconds
```

**EXPLAIN Queries**:

```sql
EXPLAIN SELECT u.*, c.class_name
FROM users u
LEFT JOIN classes c ON u.class_id = c.id
WHERE u.role = 'student';
```

---

## 9. Common Development Tasks

### 9.1 Adding a New User Role

**Step 1: Update Database**

```sql
-- Add new role to users table enum (if using ENUM)
ALTER TABLE users MODIFY COLUMN role ENUM('mp', 'admin', 'teacher', 'student', 'parent', 'librarian');
```

**Step 2: Create Controller**

```php
// app/Controllers/LibrarianController.php
<?php
class LibrarianController extends Controller
{
    public function index()
    {
        if ($_SESSION['role'] !== 'librarian') {
            header('Location: /notfound');
            exit;
        }
        $this->view('librarian/index');
    }
}
```

**Step 3: Create Model**

```php
// app/Model/LibrarianModel.php
<?php
class LibrarianModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getBooks()
    {
        $stmt = $this->db->query("SELECT * FROM books ORDER BY title");
        return $stmt->fetchAll();
    }
}
```

**Step 4: Create Views**

```php
// app/Views/librarian/index.php
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Librarian Dashboard</title>
</head>
<body>
    <h1>Library Management</h1>
    <!-- Librarian-specific content -->
</body>
</html>
```

**Step 5: Update HeaderController** (if exists)

```php
// app/Controllers/HeaderController.php
public function getHeaderForRole($role)
{
    $headers = [
        'mp' => 'mp/header.php',
        'admin' => 'admin/header.php',
        'teacher' => 'teacher/header.php',
        'student' => 'student/header.php',
        'parent' => 'parent/header.php',
        'librarian' => 'librarian/header.php', // New role
    ];

    return $headers[$role] ?? 'default/header.php';
}
```

### 9.2 Implementing File Upload

**Controller Method**:

```php
public function uploadDocument()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /notfound');
        exit;
    }

    if (!isset($_FILES['document'])) {
        $_SESSION['error'] = 'No file uploaded';
        header('Location: /admin/documents');
        exit;
    }

    $file = $_FILES['document'];

    // Validate file
    $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowedTypes)) {
        $_SESSION['error'] = 'Invalid file type';
        header('Location: /admin/documents');
        exit;
    }

    if ($file['size'] > $maxSize) {
        $_SESSION['error'] = 'File too large (max 5MB)';
        header('Location: /admin/documents');
        exit;
    }

    // Generate unique filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'doc_' . date('Ymd_His') . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $targetDir = __DIR__ . '/../../public/uploads/documents/';

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $targetPath = $targetDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Save to database
        $model = $this->model('DocumentModel');
        $model->save([
            'filename' => $filename,
            'original_name' => $file['name'],
            'file_path' => '/uploads/documents/' . $filename,
            'uploaded_by' => $_SESSION['user_id']
        ]);

        $_SESSION['success'] = 'Document uploaded successfully';
    } else {
        $_SESSION['error'] = 'Upload failed';
    }

    header('Location: /admin/documents');
    exit;
}
```

### 9.3 Implementing Search Functionality

**Controller**:

```php
public function search()
{
    $query = $_GET['q'] ?? '';
    $role = $_GET['role'] ?? '';

    if (strlen($query) < 2) {
        $results = [];
    } else {
        $model = $this->model('UserModel');
        $results = $model->search($query, $role);
    }

    $this->view('userDirectory', [
        'users' => $results,
        'search_query' => $query
    ]);
}
```

**Model**:

```php
public function search($query, $role = '')
{
    $sql = "SELECT * FROM users WHERE (name LIKE ? OR email LIKE ?)";
    $params = ["%$query%", "%$query%"];

    if ($role) {
        $sql .= " AND role = ?";
        $params[] = $role;
    }

    $sql .= " ORDER BY name LIMIT 50";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
```

**View** (with AJAX):

```javascript
const searchInput = document.getElementById("search");
const resultsContainer = document.getElementById("results");

let debounceTimer;
searchInput.addEventListener("input", (e) => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    const query = e.target.value;
    if (query.length >= 2) {
      searchUsers(query);
    } else {
      resultsContainer.innerHTML = "";
    }
  }, 300); // 300ms debounce
});

async function searchUsers(query) {
  const response = await fetch(
    `/api/searchUsers?q=${encodeURIComponent(query)}`
  );
  const data = await response.json();

  resultsContainer.innerHTML = data.users
    .map(
      (user) => `
        <div class="user-result">
            <span>${user.name}</span>
            <span>${user.email}</span>
        </div>
    `
    )
    .join("");
}
```

---

## 10. Best Practices

### 10.1 Security Best Practices

1. **Never Trust User Input**

   ```php
   // Always validate and sanitize
   $userId = filter_var($_POST['user_id'], FILTER_VALIDATE_INT);
   $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
   $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
   ```

2. **Use Prepared Statements**

   ```php
   // Good
   $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
   $stmt->execute([$userId]);

   // Bad
   $query = "SELECT * FROM users WHERE id = $userId";
   ```

3. **Hash Passwords**

   ```php
   // Register
   $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

   // Login
   if (password_verify($password, $hashedPassword)) {
       // Correct password
   }
   ```

4. **Implement CSRF Protection**

   ```php
   // Generate token
   $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

   // In form
   echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';

   // Validate
   if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
       die('CSRF token mismatch');
   }
   ```

5. **Escape Output**
   ```php
   // In views
   echo htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');
   ```

### 10.2 Performance Best Practices

1. **Database Indexing**

   ```sql
   CREATE INDEX idx_email ON users(email);
   CREATE INDEX idx_role ON users(role);
   CREATE INDEX idx_date ON attendance(date);
   ```

2. **Limit Query Results**

   ```php
   $stmt = $db->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT 50");
   ```

3. **Use Pagination**

   ```php
   $page = $_GET['page'] ?? 1;
   $perPage = 20;
   $offset = ($page - 1) * $perPage;

   $stmt = $db->prepare("SELECT * FROM users LIMIT ? OFFSET ?");
   $stmt->execute([$perPage, $offset]);
   ```

4. **Cache Frequently Used Data**

   ```php
   // Simple file-based cache
   $cacheFile = '/tmp/cache_users.json';
   $cacheTime = 3600; // 1 hour

   if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
       $users = json_decode(file_get_contents($cacheFile), true);
   } else {
       $users = $model->getAllUsers();
       file_put_contents($cacheFile, json_encode($users));
   }
   ```

5. **Optimize Images**
   - Compress images before upload
   - Use appropriate formats (JPEG for photos, PNG for graphics)
   - Implement lazy loading

### 10.3 Code Organization Best Practices

1. **Single Responsibility Principle**

   - Each class should have one purpose
   - Each method should do one thing

2. **DRY (Don't Repeat Yourself)**

   ```php
   // Bad
   $stmt1 = $db->prepare("SELECT * FROM users WHERE id = ?");
   $stmt1->execute([$id1]);
   $user1 = $stmt1->fetch();

   $stmt2 = $db->prepare("SELECT * FROM users WHERE id = ?");
   $stmt2->execute([$id2]);
   $user2 = $stmt2->fetch();

   // Good
   function getUserById($id) {
       $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
       $stmt->execute([$id]);
       return $stmt->fetch();
   }

   $user1 = getUserById($id1);
   $user2 = getUserById($id2);
   ```

3. **Meaningful Names**

   ```php
   // Bad
   function process($d) { }
   $x = getStuff();

   // Good
   function processAttendanceData($attendanceData) { }
   $studentRecords = getStudentRecords();
   ```

4. **Keep Functions Small**

   - Aim for 10-20 lines per function
   - Break large functions into smaller ones

5. **Comment Complex Logic**
   ```php
   // Calculate average with weighted grades
   // Exams: 60%, Assignments: 30%, Participation: 10%
   $average = ($examScore * 0.6) + ($assignmentScore * 0.3) + ($participationScore * 0.1);
   ```

---

## 11. Troubleshooting Development Issues

### 11.1 Common Issues and Solutions

**Issue: Blank White Page**

**Cause**: PHP error with display_errors off

**Solution**:

```php
// Add to top of index.php (development only)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Or check error log
tail -f /var/log/apache2/error.log
```

---

**Issue: 404 Not Found on Routes**

**Cause**: mod_rewrite not enabled or .htaccess not working

**Solution**:

```bash
# Enable mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# Check .htaccess exists in public/
# Verify AllowOverride All in Apache config
```

---

**Issue: Database Connection Failed**

**Cause**: Wrong credentials or database not created

**Solution**:

1. Check `.env` file
2. Verify database exists: `mysql -u root -p -e "SHOW DATABASES;"`
3. Test connection manually:
   ```php
   try {
       $pdo = new PDO('mysql:host=localhost;dbname=iskole_dev', 'iskole_user', 'password');
       echo "Connected!";
   } catch (PDOException $e) {
       echo "Error: " . $e->getMessage();
   }
   ```

---

**Issue: Session Not Persisting**

**Cause**: Session not started or cookies blocked

**Solution**:

1. Ensure `session_start()` in `index.php`
2. Check browser cookies enabled
3. Verify session save path writable:
   ```php
   echo session_save_path();
   // Ensure directory is writable
   ```

---

**Issue: File Upload Not Working**

**Cause**: Permissions, size limits, or wrong temp directory

**Solution**:

```php
// Check upload errors
if ($file['error'] !== UPLOAD_ERR_OK) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
        UPLOAD_ERR_PARTIAL => 'File partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temp directory',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write to disk',
    ];
    echo $errors[$file['error']];
}

// Check php.ini
upload_max_filesize = 10M
post_max_size = 10M
upload_tmp_dir = /tmp
```

---

**Issue: AJAX Requests Failing**

**Cause**: CORS, authentication, or incorrect URL

**Solution**:

```javascript
// Check Network tab in DevTools
// Verify endpoint URL
// Check response status and body

// Add error handling
fetch("/api/endpoint")
  .then((response) => {
    if (!response.ok) {
      throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }
    return response.json();
  })
  .catch((error) => {
    console.error("Fetch error:", error);
  });
```

---

## Summary

This development guide provides a comprehensive foundation for working on the Iskole School Management System. Key takeaways:

1. **Setup**: Proper environment configuration is crucial
2. **Standards**: Consistent coding style improves maintainability
3. **Workflow**: Use Git branches and code reviews
4. **Testing**: Test manually and with automated tools
5. **Debugging**: Use logging and browser DevTools effectively
6. **Best Practices**: Security, performance, and clean code

For production deployment, refer to [DEPLOYMENT-GUIDE.md](DEPLOYMENT-GUIDE.md).

---

**Related Documentation**:

- [System Architecture](SYSTEM-ARCHITECTURE.md)
- [Routing Guide](ROUTING-GUIDE.md)
- [Database Schema](DATABASE-SCHEMA.md)
- [API Documentation](API-DOCUMENTATION.md)
- [Deployment Guide](DEPLOYMENT-GUIDE.md)
