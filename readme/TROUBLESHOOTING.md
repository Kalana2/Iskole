# Iskole School Management System - Troubleshooting Guide

## Table of Contents

1. [Installation Issues](#installation-issues)
2. [Server and Apache Issues](#server-and-apache-issues)
3. [Database Issues](#database-issues)
4. [Authentication and Session Issues](#authentication-and-session-issues)
5. [Routing and URL Issues](#routing-and-url-issues)
6. [File Upload Issues](#file-upload-issues)
7. [Performance Issues](#performance-issues)
8. [AJAX and API Issues](#ajax-and-api-issues)
9. [Frontend Issues](#frontend-issues)
10. [Docker Issues](#docker-issues)
11. [Security Issues](#security-issues)
12. [Debugging Techniques](#debugging-techniques)

---

## 1. Installation Issues

### Issue: Cannot Clone Repository

**Symptoms**:

```bash
fatal: repository 'https://github.com/...' not found
```

**Solutions**:

1. Verify repository URL is correct
2. Check if repository is private (requires authentication)
3. Use SSH instead of HTTPS:
   ```bash
   git clone git@github.com:yourusername/iskole.git
   ```

---

### Issue: Permission Denied During Installation

**Symptoms**:

```bash
Permission denied (publickey).
```

**Solutions**:

1. Add SSH key to GitHub:

   ```bash
   ssh-keygen -t ed25519 -C "your_email@example.com"
   cat ~/.ssh/id_ed25519.pub
   # Add to GitHub Settings → SSH Keys
   ```

2. Or use HTTPS with personal access token

---

### Issue: Composer Dependencies Fail

**Symptoms**:

```bash
The requested PHP extension ext-mysql is missing
```

**Solutions**:

```bash
# Install missing PHP extensions
sudo apt install php-mysql php-mbstring php-xml php-curl

# Update Composer
composer update

# Clear cache
composer clear-cache
```

---

## 2. Server and Apache Issues

### Issue: Blank White Page (No Errors Displayed)

**Symptoms**:

- Browser shows blank page
- No error messages visible

**Diagnosis**:

```bash
# Check Apache error log
sudo tail -f /var/log/apache2/error.log

# Check PHP error log
sudo tail -f /var/log/php/error.log
```

**Solutions**:

1. **Enable error display** (development only):

   ```php
   // Add to public/index.php
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);
   ```

2. **Check file permissions**:

   ```bash
   ls -la /var/www/iskole/
   # Should show www-data as owner
   sudo chown -R www-data:www-data /var/www/iskole
   ```

3. **Verify PHP syntax**:
   ```bash
   php -l /var/www/iskole/public/index.php
   # Should output: No syntax errors detected
   ```

---

### Issue: 404 Not Found for All Routes

**Symptoms**:

- Home page loads, but any other route returns 404
- URL: `http://iskole.local/admin` → 404 Not Found

**Diagnosis**:

```bash
# Check if mod_rewrite is enabled
apache2ctl -M | grep rewrite
# Should show: rewrite_module (shared)
```

**Solutions**:

1. **Enable mod_rewrite**:

   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

2. **Verify .htaccess exists**:

   ```bash
   ls -la /var/www/iskole/public/.htaccess
   ```

3. **Check AllowOverride in Apache config**:

   ```apache
   # /etc/apache2/sites-available/iskole.conf
   <Directory /var/www/iskole/public>
       AllowOverride All  # Must be "All", not "None"
       Require all granted
   </Directory>
   ```

4. **Restart Apache**:
   ```bash
   sudo systemctl restart apache2
   ```

---

### Issue: 403 Forbidden Error

**Symptoms**:

```
Forbidden
You don't have permission to access this resource.
```

**Solutions**:

1. **Check file permissions**:

   ```bash
   sudo chmod -R 755 /var/www/iskole
   sudo chown -R www-data:www-data /var/www/iskole
   ```

2. **Verify Apache config**:

   ```apache
   <Directory /var/www/iskole/public>
       Require all granted  # Not "Require all denied"
   </Directory>
   ```

3. **Check SELinux** (if on CentOS/RHEL):
   ```bash
   sudo setenforce 0  # Disable temporarily
   # Or configure SELinux contexts properly
   ```

---

### Issue: 500 Internal Server Error

**Symptoms**:

- Server error page displayed
- Error in Apache logs

**Diagnosis**:

```bash
sudo tail -f /var/log/apache2/error.log
```

**Common Causes & Solutions**:

1. **.htaccess syntax error**:

   ```bash
   # Test .htaccess
   apache2ctl configtest
   ```

2. **PHP syntax error**:

   ```bash
   php -l app/Core/App.php
   ```

3. **Missing PHP extensions**:

   ```bash
   php -m  # List installed modules
   sudo apt install php-mysql php-mbstring
   ```

4. **Memory limit exceeded**:
   ```ini
   ; php.ini
   memory_limit = 256M
   ```

---

## 3. Database Issues

### Issue: Database Connection Failed

**Symptoms**:

```
PDOException: SQLSTATE[HY000] [1045] Access denied for user 'iskole_user'@'localhost'
```

**Solutions**:

1. **Verify .env credentials**:

   ```bash
   cat .env
   # Check MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB
   ```

2. **Test connection manually**:

   ```bash
   mysql -h localhost -u iskole_user -p iskole_dev
   # Enter password when prompted
   ```

3. **Reset database user**:

   ```sql
   mysql -u root -p

   DROP USER 'iskole_user'@'localhost';
   CREATE USER 'iskole_user'@'localhost' IDENTIFIED BY 'new_password';
   GRANT ALL PRIVILEGES ON iskole_dev.* TO 'iskole_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

4. **Update .env** with new password

---

### Issue: Database Does Not Exist

**Symptoms**:

```
SQLSTATE[HY000] [1049] Unknown database 'iskole_dev'
```

**Solutions**:

1. **Create database**:

   ```sql
   mysql -u root -p
   CREATE DATABASE iskole_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   EXIT;
   ```

2. **Import schema**:

   ```bash
   mysql -u iskole_user -p iskole_dev < database/schema.sql
   ```

3. **Verify tables created**:
   ```bash
   mysql -u iskole_user -p iskole_dev -e "SHOW TABLES;"
   ```

---

### Issue: Table Does Not Exist

**Symptoms**:

```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'iskole_dev.users' doesn't exist
```

**Solutions**:

1. **Check if database was imported**:

   ```bash
   mysql -u iskole_user -p iskole_dev -e "SHOW TABLES;"
   ```

2. **Import schema**:

   ```bash
   mysql -u iskole_user -p iskole_dev < database/schema.sql
   ```

3. **Manually create table** (if schema missing):
   ```sql
   CREATE TABLE users (
       user_id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       email VARCHAR(255) UNIQUE NOT NULL,
       password VARCHAR(255) NOT NULL,
       role ENUM('mp', 'admin', 'teacher', 'student', 'parent') NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

---

### Issue: MySQL Server Has Gone Away

**Symptoms**:

```
SQLSTATE[HY000]: General error: 2006 MySQL server has gone away
```

**Solutions**:

1. **Increase timeout and packet size** (`/etc/mysql/my.cnf`):

   ```ini
   [mysqld]
   max_allowed_packet = 64M
   wait_timeout = 600
   interactive_timeout = 600
   ```

2. **Restart MySQL**:

   ```bash
   sudo systemctl restart mysql
   ```

3. **Check for long-running queries**:
   ```sql
   SHOW PROCESSLIST;
   ```

---

## 4. Authentication and Session Issues

### Issue: Cannot Login (Credentials Correct)

**Symptoms**:

- Enter correct email/password
- Redirected back to login page
- No error message

**Solutions**:

1. **Check if sessions are working**:

   ```php
   // Add to LoginController after setting session
   var_dump($_SESSION);
   die();
   ```

2. **Verify session save path is writable**:

   ```php
   // Add to index.php
   echo session_save_path();
   // Check if directory is writable
   ```

3. **Check session configuration** (`php.ini`):

   ```ini
   session.save_handler = files
   session.save_path = "/var/lib/php/sessions"
   session.use_cookies = 1
   session.use_only_cookies = 1
   ```

4. **Ensure session_start() is called**:
   ```php
   // public/index.php
   session_start();  // Must be before any output
   ```

---

### Issue: Session Not Persisting

**Symptoms**:

- Login successful, but redirected to login on next request
- Session data disappears

**Solutions**:

1. **Check cookies in browser**:

   - Open DevTools → Application → Cookies
   - Look for PHPSESSID cookie
   - Verify cookie not being blocked

2. **Check session timeout**:

   ```ini
   ; php.ini
   session.gc_maxlifetime = 1440  # 24 minutes
   ```

3. **Regenerate session ID after login**:

   ```php
   // In LoginController
   session_regenerate_id(true);
   $_SESSION['user_id'] = $userId;
   ```

4. **Ensure no output before session_start()**:
   ```php
   // index.php
   <?php  // No whitespace before this
   session_start();
   ```

---

### Issue: Logged Out Unexpectedly

**Symptoms**:

- User is logged in
- After some time, redirected to login

**Solutions**:

1. **Increase session lifetime**:

   ```ini
   ; php.ini
   session.gc_maxlifetime = 3600  # 1 hour
   session.cookie_lifetime = 3600
   ```

2. **Implement "Remember Me" functionality**:

   ```php
   if (isset($_POST['remember'])) {
       setcookie('remember_token', $token, time() + (86400 * 30), '/');
   }
   ```

3. **Check for session_destroy() calls**:
   ```bash
   grep -r "session_destroy()" /var/www/iskole/app/
   ```

---

## 5. Routing and URL Issues

### Issue: Route Not Found

**Symptoms**:

- URL: `http://iskole.local/admin/users`
- Result: NotfoundController or 404

**Diagnosis**:

```php
// Add to App.php parseUrl() method
var_dump($url);
die();
```

**Solutions**:

1. **Check controller exists**:

   ```bash
   ls -la app/Controllers/AdminController.php
   ```

2. **Check method exists**:

   ```php
   // AdminController.php
   public function users()
   {
       // Method implementation
   }
   ```

3. **Check capitalization**:
   - File: `AdminController.php` (PascalCase)
   - URL: `/admin/users` (lowercase)

---

### Issue: Parameters Not Passed to Controller

**Symptoms**:

- URL: `http://iskole.local/student/view/123`
- `$id` is empty in controller

**Solutions**:

1. **Check parameter order in controller**:

   ```php
   // StudentController.php
   public function view($id)  // First param after method name
   {
       var_dump($id);  // Should be 123
   }
   ```

2. **Check URL parsing**:
   ```php
   // App.php
   $this->params = $url ? array_values($url) : [];
   var_dump($this->params);
   ```

---

## 6. File Upload Issues

### Issue: File Upload Not Working

**Symptoms**:

- File selected, form submitted
- No file uploaded, no error message

**Solutions**:

1. **Check form enctype**:

   ```html
   <form method="POST" enctype="multipart/form-data">
     <input type="file" name="document" />
   </form>
   ```

2. **Check PHP upload limits** (`php.ini`):

   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   max_file_uploads = 20
   ```

3. **Check upload errors**:

   ```php
   if ($file['error'] !== UPLOAD_ERR_OK) {
       $errors = [
           UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
           UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
           UPLOAD_ERR_PARTIAL => 'File partially uploaded',
           UPLOAD_ERR_NO_FILE => 'No file uploaded',
           UPLOAD_ERR_NO_TMP_DIR => 'Missing temp directory',
           UPLOAD_ERR_CANT_WRITE => 'Failed to write to disk',
       ];
       die($errors[$file['error']]);
   }
   ```

4. **Check directory permissions**:
   ```bash
   sudo chmod 775 /var/www/iskole/public/assets
   sudo chown www-data:www-data /var/www/iskole/public/assets
   ```

---

### Issue: File Upload Returns "Failed to Move File"

**Symptoms**:

```php
move_uploaded_file() returned false
```

**Solutions**:

1. **Check target directory exists**:

   ```php
   $dir = '/var/www/iskole/public/uploads/';
   if (!is_dir($dir)) {
       mkdir($dir, 0755, true);
   }
   ```

2. **Check permissions**:

   ```bash
   ls -ld /var/www/iskole/public/uploads/
   # Should show: drwxrwxr-x www-data www-data
   ```

3. **Check disk space**:
   ```bash
   df -h
   ```

---

## 7. Performance Issues

### Issue: Slow Page Load Times

**Symptoms**:

- Pages take 5+ seconds to load
- Database queries slow

**Diagnosis**:

```php
// Add timing to index.php
$start = microtime(true);
// ... application code
$end = microtime(true);
echo "Execution time: " . ($end - $start) . " seconds";
```

**Solutions**:

1. **Enable OPcache** (`php.ini`):

   ```ini
   opcache.enable=1
   opcache.memory_consumption=128
   opcache.max_accelerated_files=10000
   ```

2. **Add database indexes**:

   ```sql
   CREATE INDEX idx_email ON users(email);
   CREATE INDEX idx_role ON users(role);
   ```

3. **Implement caching**:

   ```php
   $cache = Cache::get('users_list');
   if ($cache === null) {
       $cache = $userModel->getAllUsers();
       Cache::set('users_list', $cache);
   }
   ```

4. **Optimize queries**:

   ```sql
   -- Bad: N+1 query problem
   SELECT * FROM students;
   -- Then for each student: SELECT * FROM classes WHERE id = ?

   -- Good: Join
   SELECT s.*, c.class_name
   FROM students s
   LEFT JOIN classes c ON s.class_id = c.id;
   ```

---

### Issue: High CPU Usage

**Symptoms**:

- Server CPU at 100%
- Apache processes consuming resources

**Diagnosis**:

```bash
top
htop  # Better alternative
```

**Solutions**:

1. **Limit Apache processes** (`/etc/apache2/mods-available/mpm_prefork.conf`):

   ```apache
   <IfModule mpm_prefork_module>
       StartServers 5
       MinSpareServers 5
       MaxSpareServers 10
       MaxRequestWorkers 150
       MaxConnectionsPerChild 1000
   </IfModule>
   ```

2. **Enable OPcache** (see above)

3. **Identify slow queries**:
   ```bash
   # Enable slow query log
   sudo nano /etc/mysql/my.cnf
   ```
   ```ini
   slow_query_log = 1
   slow_query_log_file = /var/log/mysql/slow.log
   long_query_time = 2
   ```

---

## 8. AJAX and API Issues

### Issue: AJAX Request Returns 404

**Symptoms**:

```javascript
fetch("/api/getUsers").then((response) => {
  // response.status === 404
});
```

**Solutions**:

1. **Verify API endpoint exists**:

   ```php
   // app/Controllers/ApiController.php
   public function getUsers()
   {
       // Method implementation
   }
   ```

2. **Check URL is correct**:

   ```javascript
   // Relative URL (recommended)
   fetch("/api/getUsers");

   // Absolute URL
   fetch("http://iskole.local/api/getUsers");
   ```

3. **Check authentication**:
   ```javascript
   // Include credentials (cookies)
   fetch("/api/getUsers", {
     credentials: "same-origin",
   });
   ```

---

### Issue: AJAX Request Returns Empty Response

**Symptoms**:

```javascript
response.json().then((data) => {
  console.log(data); // Empty object or null
});
```

**Solutions**:

1. **Check API returns JSON**:

   ```php
   // ApiController.php
   header('Content-Type: application/json');
   echo json_encode(['users' => $users]);
   exit;
   ```

2. **Check for PHP errors**:

   ```bash
   sudo tail -f /var/log/apache2/error.log
   ```

3. **Debug API response**:
   ```javascript
   fetch("/api/getUsers")
     .then((response) => response.text())
     .then((text) => console.log("Raw response:", text));
   ```

---

### Issue: CORS Error

**Symptoms**:

```
Access to fetch at 'http://api.iskole.com' from origin 'http://iskole.com' has been blocked by CORS policy
```

**Solutions**:

1. **Add CORS headers** (if API on different domain):

   ```php
   // ApiController.php
   header('Access-Control-Allow-Origin: http://iskole.com');
   header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
   header('Access-Control-Allow-Headers: Content-Type, Authorization');
   header('Access-Control-Allow-Credentials: true');
   ```

2. **For same-origin, ensure using relative URLs**:
   ```javascript
   fetch("/api/getUsers"); // Not 'http://localhost/api/getUsers'
   ```

---

## 9. Frontend Issues

### Issue: CSS Not Loading

**Symptoms**:

- Page loads, but no styling
- Browser shows 404 for CSS file

**Solutions**:

1. **Check CSS file path**:

   ```html
   <!-- Use absolute path from public/ -->
   <link rel="stylesheet" href="/css/style.css" />
   ```

2. **Verify file exists**:

   ```bash
   ls -la /var/www/iskole/public/css/style.css
   ```

3. **Check browser console** (F12):

   - Look for 404 errors
   - Verify correct path

4. **Clear browser cache**: Ctrl+Shift+R

---

### Issue: JavaScript Not Working

**Symptoms**:

- JavaScript functionality broken
- No errors in console

**Solutions**:

1. **Check JavaScript file loaded**:

   ```html
   <script src="/js/main.js"></script>
   ```

2. **Check console for errors** (F12):

   ```
   Uncaught ReferenceError: functionName is not defined
   ```

3. **Check script order**:

   ```html
   <!-- Load dependencies first -->
   <script src="/js/library.js"></script>
   <script src="/js/main.js"></script>
   ```

4. **Check DOM ready**:
   ```javascript
   document.addEventListener("DOMContentLoaded", () => {
     // Your code here
   });
   ```

---

## 10. Docker Issues

### Issue: Docker Containers Not Starting

**Symptoms**:

```bash
docker-compose up -d
# Containers exit immediately
```

**Solutions**:

1. **Check logs**:

   ```bash
   docker-compose logs web
   docker-compose logs db
   ```

2. **Check port conflicts**:

   ```bash
   sudo netstat -tuln | grep 80
   # If port 80 in use, change in docker-compose.yml
   ```

3. **Rebuild containers**:
   ```bash
   docker-compose down
   docker-compose up -d --build
   ```

---

### Issue: Cannot Connect to Database in Docker

**Symptoms**:

```
SQLSTATE[HY000] [2002] Connection refused
```

**Solutions**:

1. **Check database container running**:

   ```bash
   docker-compose ps
   # db container should be "Up"
   ```

2. **Use correct hostname**:

   ```properties
   # .env
   MYSQL_HOST=db  # Not "localhost" in Docker
   ```

3. **Check network**:
   ```bash
   docker network ls
   docker network inspect iskole_network
   ```

---

## 11. Security Issues

### Issue: Warning About Exposed .env File

**Symptoms**:

- Security scanner flags `.env` accessible

**Solutions**:

1. **Move .env outside public directory**:

   ```
   iskole/
     ├── .env  # Not in public/
     ├── public/
     └── app/
   ```

2. **Block access in .htaccess**:
   ```apache
   <FilesMatch "^\.env">
       Order allow,deny
       Deny from all
   </FilesMatch>
   ```

---

### Issue: SQL Injection Vulnerability Detected

**Symptoms**:

- Security scanner flags SQL injection

**Solutions**:

1. **Use prepared statements**:

   ```php
   // Bad
   $query = "SELECT * FROM users WHERE email = '$email'";

   // Good
   $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
   $stmt->execute([$email]);
   ```

2. **Scan codebase**:
   ```bash
   grep -r "\$db->query(" app/
   # Replace with prepared statements
   ```

---

## 12. Debugging Techniques

### Technique 1: var_dump() and die()

```php
var_dump($userData);
die();  // Stop execution
```

### Technique 2: Error Logging

```php
error_log("User ID: " . $userId);
error_log("Query: " . $sql);

// Check log
sudo tail -f /var/log/php/error.log
```

### Technique 3: Browser DevTools

1. **Console**: View JavaScript errors, log output
2. **Network**: Inspect AJAX requests/responses
3. **Application**: Check cookies, session storage
4. **Elements**: Inspect DOM, CSS

### Technique 4: Database Query Logging

```php
// Enable query logging
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Log query
error_log("SQL: " . $sql);
error_log("Params: " . json_encode($params));
```

### Technique 5: Step-by-Step Debugging

```php
echo "Step 1: Start\n";
// Code
echo "Step 2: After database query\n";
// Code
echo "Step 3: After data processing\n";
```

---

## Summary

This troubleshooting guide covers:

✅ **Installation Issues**: Clone, permissions, dependencies  
✅ **Server Issues**: Apache config, mod_rewrite, permissions  
✅ **Database Issues**: Connection, schema, queries  
✅ **Authentication Issues**: Sessions, cookies, login  
✅ **Routing Issues**: URL parsing, controllers, methods  
✅ **File Upload Issues**: Permissions, size limits, validation  
✅ **Performance Issues**: Slow queries, caching, optimization  
✅ **AJAX/API Issues**: Endpoints, CORS, responses  
✅ **Frontend Issues**: CSS, JavaScript, browser console  
✅ **Docker Issues**: Containers, networking, database  
✅ **Security Issues**: SQL injection, exposed files  
✅ **Debugging Techniques**: Logging, DevTools, step-by-step

**General Troubleshooting Steps**:

1. Check error logs (Apache, PHP, MySQL)
2. Enable error display (development only)
3. Use var_dump() to inspect variables
4. Check browser console (F12)
5. Test with minimal code (isolate problem)
6. Search error message online
7. Check file permissions
8. Restart services (Apache, MySQL)

---

**Need More Help?**

- [Development Guide](DEVELOPMENT-GUIDE.md) - Development setup and best practices
- [Deployment Guide](DEPLOYMENT-GUIDE.md) - Production troubleshooting
- [System Architecture](SYSTEM-ARCHITECTURE.md) - Understand system components
- [GitHub Issues](https://github.com/yourusername/iskole/issues) - Report bugs
