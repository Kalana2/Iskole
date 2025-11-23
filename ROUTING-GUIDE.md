# 🗺️ Iskole Routing System - Complete Guide

## Table of Contents

1. [Introduction](#introduction)
2. [Routing Architecture](#routing-architecture)
3. [URL Structure](#url-structure)
4. [Entry Point Flow](#entry-point-flow)
5. [Controller Resolution](#controller-resolution)
6. [Method & Parameters](#method--parameters)
7. [Authentication System](#authentication-system)
8. [API Routing](#api-routing)
9. [Complete Examples](#complete-examples)
10. [Adding New Routes](#adding-new-routes)
11. [Troubleshooting](#troubleshooting)

---

## 1. Introduction {#introduction}

The Iskole system uses a **Front Controller Pattern** for routing. All HTTP requests are processed through a single entry point (`/public/index.php`) and then delegated to appropriate controllers based on the URL structure.

### Key Features

- ✅ Clean, SEO-friendly URLs
- ✅ MVC Architecture
- ✅ RESTful API support
- ✅ Role-based authentication
- ✅ Dynamic routing
- ✅ Easy to extend

### URL Examples

```
http://localhost:8083/mp/management
http://localhost:8083/admin/examMarks
http://localhost:8083/teacher/classes/view/5
http://localhost:8083/api/users?action=get&id=123
```

---

## 2. Routing Architecture {#routing-architecture}

### Request Flow Diagram

```
┌──────────────────────────────────────────────────────┐
│  Browser Request                                      │
│  http://localhost:8083/mp/management                 │
└─────────────────┬────────────────────────────────────┘
                  │
                  ▼
┌──────────────────────────────────────────────────────┐
│  Apache .htaccess (URL Rewriting)                    │
│  → /public/index.php?url=mp/management               │
└─────────────────┬────────────────────────────────────┘
                  │
                  ▼
┌──────────────────────────────────────────────────────┐
│  /public/index.php (Entry Point)                     │
│  - Start session                                      │
│  - Include core files                                 │
│  - new App()                                          │
└─────────────────┬────────────────────────────────────┘
                  │
                  ▼
┌──────────────────────────────────────────────────────┐
│  app/Core/App.php (Router)                           │
│  - Parse URL                                          │
│  - Resolve controller                                 │
│  - Resolve method                                     │
│  - Check authentication                               │
│  - Execute                                            │
└─────────────────┬────────────────────────────────────┘
                  │
                  ▼
┌──────────────────────────────────────────────────────┐
│  Controller Executes                                  │
│  - Load models                                        │
│  - Process business logic                             │
│  - Render view                                        │
└─────────────────┬────────────────────────────────────┘
                  │
                  ▼
┌──────────────────────────────────────────────────────┐
│  Response Sent to Browser                             │
└──────────────────────────────────────────────────────┘
```

### Project Structure

```
/home/snake/Projects/Iskole/
├── public/
│   ├── index.php          ← Entry point
│   ├── .htaccess          ← URL rewriting
│   ├── css/
│   ├── js/
│   └── assets/
├── app/
│   ├── Core/
│   │   ├── App.php        ← Router
│   │   ├── Controller.php ← Base controller
│   │   └── Database.php   ← DB connection
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── LoginController.php
│   │   ├── MpController.php
│   │   ├── AdminController.php
│   │   ├── TeacherController.php
│   │   ├── StudentController.php
│   │   ├── ParentController.php
│   │   ├── ApiController.php
│   │   └── userDirectoryController.php
│   ├── Model/
│   │   ├── UserModel.php
│   │   ├── MpModel.php
│   │   ├── TeacherModel.php
│   │   ├── StudentModel.php
│   │   └── ParentModel.php
│   └── Views/
│       ├── login/
│       ├── mp/
│       ├── admin/
│       ├── teacher/
│       ├── student/
│       └── parent/
```

---

## 3. URL Structure {#url-structure}

### URL Pattern

```
http://localhost:8083/{controller}/{method}/{param1}/{param2}?query=value
│                     │            │        │       │        │
│                     │            │        │       │        └─ Query parameters
│                     │            │        │       └────────── Parameter 2
│                     │            │        └────────────────── Parameter 1
│                     │            └─────────────────────────── Method name
│                     └──────────────────────────────────────── Controller name
└────────────────────────────────────────────────────────────── Base URL
```

### URL Examples

| URL                            | Controller        | Method       | Parameters    |
| ------------------------------ | ----------------- | ------------ | ------------- |
| `/login`                       | LoginController   | index()      | -             |
| `/mp`                          | MpController      | index()      | -             |
| `/mp/management`               | MpController      | management() | -             |
| `/admin/examMarks`             | AdminController   | examMarks()  | -             |
| `/teacher/classes/view/5`      | TeacherController | classes()    | ["view", "5"] |
| `/api/users?action=get&id=123` | ApiController     | get()        | -             |

---

## 4. Entry Point Flow {#entry-point-flow}

### Step 1: Apache .htaccess

**File:** `/public/.htaccess`

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # If file or directory exists, serve it directly
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d

    # Otherwise route through index.php
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>
```

**Transformations:**

| Browser URL        | Rewritten To                     |
| ------------------ | -------------------------------- |
| `/mp`              | `/index.php?url=mp`              |
| `/mp/management`   | `/index.php?url=mp/management`   |
| `/admin/examMarks` | `/index.php?url=admin/examMarks` |

### Step 2: Entry Point (index.php)

**File:** `/public/index.php`

```php
<?php
// Start session for authentication
session_start();

// Include core application files
require_once '../app/Core/App.php';
require_once '../app/Core/Controller.php';
require_once '../app/Core/Database.php';

// Initialize the routing system
$app = new App();
```

**Execution:**

1. Session started
2. Core classes loaded
3. App instantiated → routing begins

---

## 5. Controller Resolution {#controller-resolution}

### App.php Structure

**File:** `/app/Core/App.php`

```php
<?php
class App
{
    protected $controller = 'LoginController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Controller Resolution
        if ($url && isset($url[0]) && $url[0] !== '' &&
            file_exists(__DIR__ . '/../Controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        } else {
            header('Location: /login');
            exit;
        }

        // Load controller
        require_once __DIR__ . '/../Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Method Resolution
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // Parameters
        $this->params = $url ? array_values($url) : [];

        // Authentication
        $public = ['LoginController'];
        if (!in_array($this->controller::class, $public) && !isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Execute
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl()
    {
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
```

### Controller Naming Convention

| URL Segment | Controller Class    | File Name               |
| ----------- | ------------------- | ----------------------- |
| `mp`        | `MpController`      | `MpController.php`      |
| `admin`     | `AdminController`   | `AdminController.php`   |
| `teacher`   | `TeacherController` | `TeacherController.php` |
| `student`   | `StudentController` | `StudentController.php` |
| `parent`    | `ParentController`  | `ParentController.php`  |
| `api`       | `ApiController`     | `ApiController.php`     |

---

## 6. Method & Parameters {#method--parameters}

### URL Breakdown Example

**URL:** `/admin/examMarks/view/5/mathematics`

```php
// Initial parsing
$url = ["admin", "examMarks", "view", "5", "mathematics"]

// After controller resolution
$this->controller = AdminController
$url = ["examMarks", "view", "5", "mathematics"]

// After method resolution
$this->method = "examMarks"
$url = ["view", "5", "mathematics"]

// After parameter collection
$this->params = ["view", "5", "mathematics"]

// Final execution
AdminController->examMarks("view", "5", "mathematics")
```

### Controller Method Example

```php
class AdminController extends Controller
{
    public function examMarks($action = 'list', $examId = null, $subject = null)
    {
        // $action = "view"
        // $examId = "5"
        // $subject = "mathematics"

        if ($action === 'view' && $examId) {
            $examModel = $this->model('ExamModel');
            $marks = $examModel->getMarksByExam($examId, $subject);
            $this->view('admin/examMarks', ['marks' => $marks]);
        }
    }
}
```

---

## 7. Authentication System {#authentication-system}

### Role-Based Access Control

```php
// In App.php
$public = ['LoginController'];

// Check if controller requires authentication
if (!in_array($this->controller::class, $public) && !isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
```

### Session Structure

```php
$_SESSION = [
    'user_id' => 123,
    'username' => 'john.doe@example.com',
    'role' => 3,  // 1=MP, 2=Admin, 3=Teacher, 4=Student, 5=Parent
    'firstName' => 'John',
    'lastName' => 'Doe'
];
```

### Role Permissions

| Role ID | Role Name         | Access              |
| ------- | ----------------- | ------------------- |
| 1       | Management Portal | Full system access  |
| 2       | Administrator     | Academic management |
| 3       | Teacher           | Class management    |
| 4       | Student           | View own data       |
| 5       | Parent            | View child's data   |

### Adding Public Controllers

```php
$public = [
    'LoginController',
    'RegisterController',  // Add new public controller
];
```

---

## 8. API Routing {#api-routing}

### API URL Structure

```
/api/{resource}?action={action}&param=value
```

### Enhanced App.php with API Support

```php
public function __construct()
{
    $url = $this->parseUrl();

    // Check for API routes
    if ($url && isset($url[0]) && $url[0] === 'api') {
        $this->handleApiRequest($url);
        return;
    }

    // Regular routing...
}

protected function handleApiRequest($url)
{
    header('Content-Type: application/json');

    // Check authentication
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        return;
    }

    // Get resource
    $resource = $url[1] ?? null;

    // Map resources to controllers
    $apiControllers = [
        'users' => 'ApiController'
    ];

    if (!isset($apiControllers[$resource])) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Resource not found']);
        return;
    }

    // Load and execute
    $controllerName = $apiControllers[$resource];
    require_once __DIR__ . '/../Controllers/' . $controllerName . '.php';
    $controller = new $controllerName();

    $action = $_GET['action'] ?? 'index';

    if (method_exists($controller, $action)) {
        call_user_func([$controller, $action]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Action not found']);
    }
}
```

### API Controller Example

```php
class ApiController
{
    public function get()
    {
        $userId = $_GET['id'] ?? null;

        if (!$userId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'User ID required']);
            return;
        }

        $userDirectory = new UserDirectoryController();
        $user = $userDirectory->getUserById($userId);

        if ($user) {
            echo json_encode(['success' => true, 'data' => $user]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    }

    public function search()
    {
        $query = $_GET['q'] ?? '';
        $userDirectory = new UserDirectoryController();
        $users = $userDirectory->searchUsers($query);
        echo json_encode(['success' => true, 'users' => $users]);
    }

    public function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['userID'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'User ID required']);
            return;
        }

        $userDirectory = new UserDirectoryController();
        $result = $userDirectory->updateUser($data['userID'], $data);

        echo json_encode([
            'success' => $result,
            'message' => $result ? 'User updated' : 'Update failed'
        ]);
    }

    public function delete()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['userID'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'User ID required']);
            return;
        }

        $userDirectory = new UserDirectoryController();
        $result = $userDirectory->deleteUser($data['userID']);

        echo json_encode([
            'success' => $result,
            'message' => $result ? 'User deleted' : 'Delete failed'
        ]);
    }
}
```

---

## 9. Complete Examples {#complete-examples}

### Example 1: MP Management Page

**URL:** `http://localhost:8083/mp/management`

**Flow:**

```
1. Parse URL → ["mp", "management"]
2. Controller → MpController
3. Method → management()
4. Parameters → []
5. Authentication → $_SESSION['user_id'] exists → Allowed
6. Execute → MpController->management()
```

**Controller:**

```php
class MpController extends Controller
{
    public function management()
    {
        // Load user directory
        $userDirectory = new UserDirectoryController();
        $users = $userDirectory->getRecentUsers(5);

        // Render view
        $this->view('mp/management', ['users' => $users]);
    }
}
```

---

### Example 2: Teacher Class View

**URL:** `http://localhost:8083/teacher/classes/view/5`

**Flow:**

```
1. Parse URL → ["teacher", "classes", "view", "5"]
2. Controller → TeacherController
3. Method → classes()
4. Parameters → ["view", "5"]
5. Execute → TeacherController->classes("view", "5")
```

**Controller:**

```php
class TeacherController extends Controller
{
    public function classes($action = 'list', $classId = null)
    {
        if ($action === 'view' && $classId) {
            $classModel = $this->model('ClassModel');
            $class = $classModel->getClassById($classId);
            $students = $classModel->getStudentsByClass($classId);

            $this->view('teacher/viewClass', [
                'class' => $class,
                'students' => $students
            ]);
        } else {
            $classModel = $this->model('ClassModel');
            $classes = $classModel->getTeacherClasses($_SESSION['user_id']);
            $this->view('teacher/classList', ['classes' => $classes]);
        }
    }
}
```

---

### Example 3: API GET Request

**URL:** `http://localhost:8083/api/users?action=get&id=123`

**JavaScript:**

```javascript
fetch("/api/users?action=get&id=123")
  .then((response) => response.json())
  .then((data) => {
    if (data.success) {
      console.log("User:", data.data);
      populateForm(data.data);
    }
  });
```

**Response:**

```json
{
  "success": true,
  "data": {
    "userID": 123,
    "firstName": "John",
    "lastName": "Doe",
    "email": "john@example.com",
    "role": "Teacher"
  }
}
```

---

### Example 4: API POST Request

**URL:** `http://localhost:8083/api/users?action=update`

**JavaScript:**

```javascript
fetch("/api/users?action=update", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({
    userID: 123,
    firstName: "John",
    lastName: "Doe",
    email: "john@example.com",
  }),
})
  .then((response) => response.json())
  .then((data) => {
    if (data.success) {
      alert("User updated successfully!");
      location.reload();
    }
  });
```

---

## 10. Adding New Routes {#adding-new-routes}

### Step 1: Create Controller

```php
<?php
// File: app/Controllers/LibraryController.php

class LibraryController extends Controller
{
    public function index()
    {
        // List all books
        $libraryModel = $this->model('LibraryModel');
        $books = $libraryModel->getAllBooks();
        $this->view('library/index', ['books' => $books]);
    }

    public function book($action = 'view', $bookId = null)
    {
        if ($action === 'view' && $bookId) {
            $libraryModel = $this->model('LibraryModel');
            $book = $libraryModel->getBookById($bookId);
            $this->view('library/bookDetail', ['book' => $book]);
        }
    }

    public function search()
    {
        $query = $_GET['q'] ?? '';
        $libraryModel = $this->model('LibraryModel');
        $books = $libraryModel->searchBooks($query);
        $this->view('library/searchResults', ['books' => $books]);
    }
}
```

### Step 2: Create Model

```php
<?php
// File: app/Model/LibraryModel.php

class LibraryModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllBooks()
    {
        $this->db->query("SELECT * FROM books WHERE available = 1");
        return $this->db->resultSet();
    }

    public function getBookById($bookId)
    {
        $this->db->query("SELECT * FROM books WHERE bookID = :id");
        $this->db->bind(':id', $bookId);
        return $this->db->single();
    }

    public function searchBooks($query)
    {
        $this->db->query("
            SELECT * FROM books
            WHERE title LIKE :query
            OR author LIKE :query
        ");
        $this->db->bind(':query', "%{$query}%");
        return $this->db->resultSet();
    }
}
```

### Step 3: Create Views

```php
<!-- File: app/Views/library/index.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Library - Iskole</title>
    <link rel="stylesheet" href="/css/library/library.css">
</head>
<body>
    <h1>Library</h1>

    <form action="/library/search" method="GET">
        <input type="text" name="q" placeholder="Search books...">
        <button type="submit">Search</button>
    </form>

    <div class="books-grid">
        <?php foreach ($data['books'] as $book): ?>
            <div class="book-card">
                <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                <p>by <?php echo htmlspecialchars($book['author']); ?></p>
                <a href="/library/book/view/<?php echo $book['bookID']; ?>">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
```

### Step 4: Add CSS

```css
/* File: public/css/library/library.css */
.books-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  padding: 20px;
}

.book-card {
  border: 1px solid #ddd;
  padding: 20px;
  border-radius: 8px;
  transition: transform 0.2s;
}

.book-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
```

### Step 5: Access Routes

```
http://localhost:8083/library
http://localhost:8083/library/book/view/5
http://localhost:8083/library/search?q=physics
```

---

## 11. Troubleshooting {#troubleshooting}

### Issue 1: 404 Not Found

**Symptoms:** All routes return 404

**Solution:**

```bash
# Enable mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# Check .htaccess exists
ls -la /home/snake/Projects/Iskole/public/.htaccess

# Verify Apache config allows .htaccess
sudo nano /etc/apache2/sites-available/000-default.conf
# Add: AllowOverride All
```

---

### Issue 2: Redirect Loop

**Symptoms:** Browser shows "Too many redirects"

**Solution:**

```php
// Ensure LoginController is in $public array
$public = ['LoginController'];

// Check session is starting
session_start();  // Must be at top of index.php

// Verify session persists
if (isset($_SESSION['user_id'])) {
    error_log('Session active: ' . $_SESSION['user_id']);
}
```

---

### Issue 3: Controller Not Found

**Symptoms:** "Controller file not found" error

**Solution:**

```bash
# Check file exists and has correct name
ls -la app/Controllers/MpController.php

# Verify file permissions
chmod 644 app/Controllers/*.php

# Check class name matches filename
# MpController.php must contain: class MpController
```

---

### Issue 4: API Returns Empty

**Symptoms:** API requests return empty response

**Solution:**

```php
// Add echo to output JSON
public function get() {
    $data = ['success' => true];
    echo json_encode($data);  // ← Must echo!
}

// Check Content-Type header
header('Content-Type: application/json');

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---

## Route Reference Table

| URL Pattern                       | Controller        | Method             | Parameters | Description           |
| --------------------------------- | ----------------- | ------------------ | ---------- | --------------------- |
| `/`                               | LoginController   | index()            | -          | Redirect to login     |
| `/login`                          | LoginController   | index()            | -          | Login page            |
| `/mp`                             | MpController      | index()            | -          | MP dashboard          |
| `/mp/management`                  | MpController      | management()       | -          | User management       |
| `/mp/academic`                    | MpController      | academic()         | -          | Academic reports      |
| `/mp/announcement`                | MpController      | announcement()     | -          | Announcements         |
| `/admin`                          | AdminController   | index()            | -          | Admin dashboard       |
| `/admin/examMarks`                | AdminController   | examMarks()        | -          | Exam marks management |
| `/admin/examMarks/view/5`         | AdminController   | examMarks()        | view, 5    | View specific exam    |
| `/admin/classAndSubjects`         | AdminController   | classAndSubjects() | -          | Class management      |
| `/admin/timeTable`                | AdminController   | timeTable()        | -          | Timetable management  |
| `/teacher`                        | TeacherController | index()            | -          | Teacher dashboard     |
| `/teacher/classes`                | TeacherController | classes()          | -          | Teacher's classes     |
| `/teacher/classes/view/5`         | TeacherController | classes()          | view, 5    | View class details    |
| `/student`                        | StudentController | index()            | -          | Student dashboard     |
| `/student/marks`                  | StudentController | marks()            | -          | Student marks         |
| `/parent`                         | ParentController  | index()            | -          | Parent dashboard      |
| `/parent/child/123`               | ParentController  | child()            | 123        | View child info       |
| `/api/users?action=get&id=1`      | ApiController     | get()              | -          | Get user data         |
| `/api/users?action=search&q=john` | ApiController     | search()           | -          | Search users          |
| `/api/users?action=update`        | ApiController     | update()           | -          | Update user (POST)    |
| `/api/users?action=delete`        | ApiController     | delete()           | -          | Delete user (POST)    |

---

## Summary

### Key Takeaways

1. **All requests** go through `/public/index.php`
2. **URL segments** map to Controller/Method/Parameters
3. **API routes** use `/api/` prefix with action parameter
4. **Authentication** checks `$_SESSION['user_id']` before execution
5. **Controllers** extend base Controller class
6. **Views** are rendered using `$this->view()`
7. **Models** handle database operations

### Best Practices

- ✅ Use prepared statements for SQL queries
- ✅ Validate and sanitize all user input
- ✅ Check authentication before sensitive operations
- ✅ Return proper HTTP status codes for APIs
- ✅ Use meaningful controller and method names
- ✅ Keep controllers thin, models fat
- ✅ Log errors for debugging

---

**Last Updated:** November 21, 2025
**Version:** 1.0.0
