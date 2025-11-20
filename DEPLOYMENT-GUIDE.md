# Iskole School Management System - Deployment Guide

## Table of Contents

1. [Pre-Deployment Checklist](#pre-deployment-checklist)
2. [Server Requirements](#server-requirements)
3. [Deployment Methods](#deployment-methods)
4. [Manual Deployment](#manual-deployment)
5. [Docker Deployment](#docker-deployment)
6. [Production Configuration](#production-configuration)
7. [Database Setup](#database-setup)
8. [Security Hardening](#security-hardening)
9. [Performance Optimization](#performance-optimization)
10. [Backup Strategy](#backup-strategy)
11. [Monitoring and Maintenance](#monitoring-and-maintenance)
12. [Troubleshooting Production Issues](#troubleshooting-production-issues)
13. [Rollback Procedures](#rollback-procedures)
14. [CI/CD Pipeline](#cicd-pipeline)

---

## 1. Pre-Deployment Checklist

### Code Review

- [ ] All features tested in staging environment
- [ ] Code reviewed and approved
- [ ] No hardcoded credentials or API keys
- [ ] Error handling implemented
- [ ] Logging configured
- [ ] Database migrations tested

### Security

- [ ] All dependencies updated to latest secure versions
- [ ] SQL injection vulnerabilities checked
- [ ] XSS vulnerabilities checked
- [ ] CSRF protection implemented
- [ ] Authentication and authorization working
- [ ] File upload validation in place
- [ ] HTTPS/SSL certificate configured

### Configuration

- [ ] `.env` file configured for production
- [ ] Database credentials secured
- [ ] Error display turned off (`display_errors = Off`)
- [ ] Debug mode disabled
- [ ] Session configuration secured
- [ ] File permissions set correctly

### Performance

- [ ] Database indexes created
- [ ] Static assets minified
- [ ] Images optimized
- [ ] Caching configured
- [ ] CDN configured (if applicable)

### Backup

- [ ] Backup system configured
- [ ] Backup tested and verified
- [ ] Recovery procedure documented
- [ ] Offsite backup configured

---

## 2. Server Requirements

### Minimum Requirements

- **CPU**: 2 cores
- **RAM**: 4 GB
- **Disk**: 20 GB SSD
- **Bandwidth**: 100 GB/month

### Recommended Requirements

- **CPU**: 4 cores (2.5 GHz+)
- **RAM**: 8 GB
- **Disk**: 50 GB SSD
- **Bandwidth**: 500 GB/month

### Software Requirements

- **OS**: Ubuntu 20.04/22.04 LTS, CentOS 8+, or Debian 11+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: 7.4+ (8.0+ recommended)
- **Database**: MySQL 8.0+ or MariaDB 10.5+
- **SSL/TLS**: Let's Encrypt or commercial certificate
- **Firewall**: UFW or iptables
- **Monitoring**: Optional (New Relic, DataDog, or custom)

### PHP Extensions Required

```bash
php-mysql
php-pdo
php-mbstring
php-json
php-curl
php-xml
php-gd        # For image manipulation
php-zip       # For exports
php-intl      # For internationalization
```

---

## 3. Deployment Methods

### Option 1: Manual Deployment

- Direct SSH access
- Manual file transfer (FTP/SFTP)
- Manual configuration
- Best for: Small deployments, simple setups

### Option 2: Docker Deployment

- Containerized application
- Easy scaling
- Consistent environments
- Best for: Modern infrastructure, microservices

### Option 3: CI/CD Pipeline

- Automated testing and deployment
- GitHub Actions, GitLab CI, Jenkins
- Zero-downtime deployments
- Best for: Large teams, frequent updates

---

## 4. Manual Deployment

### 4.1 Server Setup (Ubuntu 22.04)

**Update System**:

```bash
sudo apt update && sudo apt upgrade -y
```

**Install Apache**:

```bash
sudo apt install apache2 -y
sudo systemctl enable apache2
sudo systemctl start apache2
```

**Install PHP**:

```bash
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl php8.1-gd php8.1-zip -y
```

**Install MySQL**:

```bash
sudo apt install mysql-server -y
sudo systemctl enable mysql
sudo systemctl start mysql
sudo mysql_secure_installation
```

**Enable Apache Modules**:

```bash
sudo a2enmod rewrite
sudo a2enmod ssl
sudo a2enmod headers
sudo systemctl restart apache2
```

### 4.2 Create Virtual Host

**Create Directory**:

```bash
sudo mkdir -p /var/www/iskole
sudo chown -R $USER:www-data /var/www/iskole
sudo chmod -R 755 /var/www/iskole
```

**Virtual Host Configuration**:

Create `/etc/apache2/sites-available/iskole.conf`:

```apache
<VirtualHost *:80>
    ServerName iskole.yourdomain.com
    ServerAlias www.iskole.yourdomain.com
    DocumentRoot /var/www/iskole/public

    <Directory /var/www/iskole/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Logging
    ErrorLog ${APACHE_LOG_DIR}/iskole_error.log
    CustomLog ${APACHE_LOG_DIR}/iskole_access.log combined

    # Security Headers
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</VirtualHost>

# Redirect HTTP to HTTPS
<VirtualHost *:443>
    ServerName iskole.yourdomain.com
    ServerAlias www.iskole.yourdomain.com
    DocumentRoot /var/www/iskole/public

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/iskole.yourdomain.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/iskole.yourdomain.com/privkey.pem

    <Directory /var/www/iskole/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Security Headers
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"

    ErrorLog ${APACHE_LOG_DIR}/iskole_ssl_error.log
    CustomLog ${APACHE_LOG_DIR}/iskole_ssl_access.log combined
</VirtualHost>
```

**Enable Site**:

```bash
sudo a2ensite iskole.conf
sudo a2dissite 000-default.conf  # Disable default site
sudo systemctl reload apache2
```

### 4.3 Deploy Application

**Clone Repository**:

```bash
cd /var/www/iskole
git clone https://github.com/yourusername/iskole.git .
# Or upload via SFTP
```

**Set Permissions**:

```bash
sudo chown -R www-data:www-data /var/www/iskole
sudo find /var/www/iskole -type d -exec chmod 755 {} \;
sudo find /var/www/iskole -type f -exec chmod 644 {} \;

# Writable directories
sudo chmod -R 775 /var/www/iskole/public/assets
sudo chmod -R 775 /var/www/iskole/storage  # If exists
```

**Configure Environment**:

```bash
cd /var/www/iskole
cp .env.example .env
nano .env
```

Edit `.env`:

```properties
# Production Settings
MYSQL_HOST=localhost
MYSQL_PORT=3306
MYSQL_DB=iskole_production
MYSQL_USER=iskole_prod_user
MYSQL_PASSWORD=your_strong_password_here

# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://iskole.yourdomain.com
```

**Secure .env**:

```bash
sudo chmod 600 .env
sudo chown www-data:www-data .env
```

### 4.4 SSL Certificate (Let's Encrypt)

**Install Certbot**:

```bash
sudo apt install certbot python3-certbot-apache -y
```

**Obtain Certificate**:

```bash
sudo certbot --apache -d iskole.yourdomain.com -d www.iskole.yourdomain.com
```

**Auto-Renewal**:

```bash
# Test renewal
sudo certbot renew --dry-run

# Cron job (already configured by certbot)
sudo systemctl status certbot.timer
```

---

## 5. Docker Deployment

### 5.1 Docker Setup

**Dockerfile**:

```dockerfile
FROM php:8.1-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd mysqli

# Enable Apache modules
RUN a2enmod rewrite headers ssl

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

CMD ["apache2-foreground"]
```

**docker-compose.yml**:

```yaml
version: "3.8"

services:
  web:
    build: .
    container_name: iskole_web
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www/html
      - ./docker/apache/apache.conf:/etc/apache2/sites-available/000-default.conf
      - ./docker/ssl:/etc/ssl/certs
    environment:
      - MYSQL_HOST=db
      - MYSQL_PORT=3306
      - MYSQL_DB=iskole_production
      - MYSQL_USER=iskole_user
      - MYSQL_PASSWORD=${DB_PASSWORD}
    depends_on:
      - db
    networks:
      - iskole_network
    restart: unless-stopped

  db:
    image: mysql:8.0
    container_name: iskole_db
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
      MYSQL_DATABASE: iskole_production
      MYSQL_USER: iskole_user
      MYSQL_PASSWORD: ${DB_PASSWORD}
    volumes:
      - db_data:/var/lib/mysql
      - ./database/schema.sql:/docker-entrypoint-initdb.d/schema.sql
    ports:
      - "3306:3306"
    networks:
      - iskole_network
    restart: unless-stopped

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: iskole_phpmyadmin
    environment:
      PMA_HOST: db
      PMA_PORT: 3306
      PMA_USER: iskole_user
      PMA_PASSWORD: ${DB_PASSWORD}
    ports:
      - "8080:80"
    depends_on:
      - db
    networks:
      - iskole_network
    restart: unless-stopped

volumes:
  db_data:

networks:
  iskole_network:
    driver: bridge
```

**Environment File** (`.env.docker`):

```properties
DB_ROOT_PASSWORD=your_root_password
DB_PASSWORD=your_db_password
```

### 5.2 Deploy with Docker

**Build and Start**:

```bash
# Load environment variables
export $(cat .env.docker | xargs)

# Build and start containers
docker-compose up -d --build

# Check status
docker-compose ps

# View logs
docker-compose logs -f web
```

**Database Initialization**:

```bash
# If schema not auto-imported
docker exec -i iskole_db mysql -uiskole_user -p${DB_PASSWORD} iskole_production < database/schema.sql
```

**Stop and Remove**:

```bash
docker-compose down
# To remove volumes (CAUTION: deletes data)
docker-compose down -v
```

---

## 6. Production Configuration

### 6.1 PHP Configuration (php.ini)

**Security Settings**:

```ini
; Disable dangerous functions
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source

; Error handling (production)
display_errors = Off
display_startup_errors = Off
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
log_errors = On
error_log = /var/log/php/error.log

; Resource limits
max_execution_time = 30
max_input_time = 60
memory_limit = 256M

; File uploads
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20

; Session security
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1
session.use_only_cookies = 1
session.cookie_samesite = Strict

; Other
expose_php = Off
allow_url_fopen = Off
allow_url_include = Off
```

### 6.2 Apache Configuration

**.htaccess** (in `/public/`):

```apache
# Rewrite Rules
RewriteEngine On
DirectoryIndex index.php

# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Remove www
RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
RewriteRule ^(.*)$ https://%1/$1 [R=301,L]

# Front Controller
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]

# Security Headers
<IfModule mod_headers.c>
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-Content-Type-Options "nosniff"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    Header set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>

# Disable directory browsing
Options -Indexes

# Prevent access to sensitive files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "(\.env|composer\.json|composer\.lock|package\.json)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Cache static assets
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css application/javascript
</IfModule>
```

### 6.3 MySQL Configuration

**Optimize for Production** (`/etc/mysql/my.cnf`):

```ini
[mysqld]
# General
max_connections = 200
max_allowed_packet = 64M

# InnoDB
innodb_buffer_pool_size = 2G  # 70-80% of available RAM
innodb_log_file_size = 512M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT

# Query Cache (deprecated in MySQL 8.0, use application-level caching)
# query_cache_type = 1
# query_cache_size = 64M

# Logging (disable in production for performance)
slow_query_log = 0
# slow_query_log_file = /var/log/mysql/slow.log
# long_query_time = 2

# Character set
character_set_server = utf8mb4
collation_server = utf8mb4_unicode_ci

# Security
local_infile = 0
```

---

## 7. Database Setup

### 7.1 Create Production Database

**Connect to MySQL**:

```bash
mysql -u root -p
```

**Create Database and User**:

```sql
-- Create database
CREATE DATABASE iskole_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER 'iskole_prod_user'@'localhost' IDENTIFIED BY 'your_strong_password';

-- Grant privileges
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, ALTER ON iskole_production.* TO 'iskole_prod_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

-- Verify
SHOW GRANTS FOR 'iskole_prod_user'@'localhost';

EXIT;
```

### 7.2 Import Schema

**Import SQL File**:

```bash
mysql -u iskole_prod_user -p iskole_production < /var/www/iskole/database/schema.sql
```

**Verify Import**:

```bash
mysql -u iskole_prod_user -p iskole_production -e "SHOW TABLES;"
```

### 7.3 Create Admin User

**Insert Admin User**:

```sql
USE iskole_production;

-- Hash password: password_hash('your_password', PASSWORD_DEFAULT)
INSERT INTO users (name, email, password, role, created_at) VALUES
('System Admin', 'admin@iskole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW());

-- Verify
SELECT user_id, name, email, role FROM users WHERE role = 'admin';
```

**Password Hash Generator** (PHP):

```php
<?php
echo password_hash('your_password', PASSWORD_DEFAULT);
```

---

## 8. Security Hardening

### 8.1 Firewall Configuration (UFW)

**Enable UFW**:

```bash
sudo ufw enable
```

**Allow Necessary Ports**:

```bash
sudo ufw allow 22/tcp      # SSH
sudo ufw allow 80/tcp      # HTTP
sudo ufw allow 443/tcp     # HTTPS
```

**Deny All Other Traffic**:

```bash
sudo ufw default deny incoming
sudo ufw default allow outgoing
```

**Check Status**:

```bash
sudo ufw status verbose
```

### 8.2 SSH Hardening

**Edit SSH Config** (`/etc/ssh/sshd_config`):

```
# Disable root login
PermitRootLogin no

# Use key-based authentication
PasswordAuthentication no
PubkeyAuthentication yes

# Change default port (optional)
Port 2222

# Limit users
AllowUsers your_username

# Disable empty passwords
PermitEmptyPasswords no
```

**Restart SSH**:

```bash
sudo systemctl restart sshd
```

### 8.3 Fail2Ban (Brute Force Protection)

**Install Fail2Ban**:

```bash
sudo apt install fail2ban -y
```

**Configure** (`/etc/fail2ban/jail.local`):

```ini
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 5

[sshd]
enabled = true
port = ssh
logpath = /var/log/auth.log

[apache-auth]
enabled = true
port = http,https
logpath = /var/log/apache2/*error.log
```

**Start Fail2Ban**:

```bash
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
sudo fail2ban-client status
```

### 8.4 Application-Level Security

**CSRF Protection** (Add to forms):

```php
// app/Core/Controller.php
protected function generateCsrfToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

protected function validateCsrfToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
```

**Rate Limiting** (Basic):

```php
// app/Core/RateLimiter.php
class RateLimiter
{
    private static $limits = [];

    public static function check($key, $maxAttempts = 5, $decayMinutes = 1)
    {
        $now = time();
        $key = md5($key);

        if (!isset(self::$limits[$key])) {
            self::$limits[$key] = ['attempts' => 0, 'reset_at' => $now + ($decayMinutes * 60)];
        }

        if ($now >= self::$limits[$key]['reset_at']) {
            self::$limits[$key] = ['attempts' => 0, 'reset_at' => $now + ($decayMinutes * 60)];
        }

        self::$limits[$key]['attempts']++;

        if (self::$limits[$key]['attempts'] > $maxAttempts) {
            return false;
        }

        return true;
    }
}
```

---

## 9. Performance Optimization

### 9.1 Opcode Caching (OPcache)

**Enable OPcache** (`php.ini`):

```ini
[opcache]
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
opcache.enable_cli=0
```

**Restart PHP-FPM**:

```bash
sudo systemctl restart php8.1-fpm
```

### 9.2 Database Query Optimization

**Add Indexes**:

```sql
-- Users table
CREATE INDEX idx_email ON users(email);
CREATE INDEX idx_role ON users(role);
CREATE INDEX idx_class_id ON users(class_id);

-- Attendance table
CREATE INDEX idx_student_date ON attendance(student_id, date);
CREATE INDEX idx_date ON attendance(date);

-- Marks table
CREATE INDEX idx_student_subject ON marks(student_id, subject_id);
CREATE INDEX idx_exam_type ON marks(exam_type);

-- Announcements table
CREATE INDEX idx_created_at ON announcements(created_at);
CREATE INDEX idx_target_role ON announcements(target_role);
```

**Optimize Tables**:

```sql
OPTIMIZE TABLE users;
OPTIMIZE TABLE attendance;
OPTIMIZE TABLE marks;
OPTIMIZE TABLE announcements;
```

### 9.3 Static Asset Optimization

**Minify CSS/JS**:

```bash
# Install minifier
npm install -g csso-cli uglify-js

# Minify CSS
csso css/style.css -o css/style.min.css

# Minify JavaScript
uglifyjs js/main.js -o js/main.min.js -c -m
```

**Image Optimization**:

```bash
# Install tools
sudo apt install optipng jpegoptim -y

# Optimize PNG
optipng -o7 images/*.png

# Optimize JPEG
jpegoptim --strip-all --max=85 images/*.jpg
```

### 9.4 Caching Strategy

**Application-Level Caching** (Simple File Cache):

```php
// app/Core/Cache.php
class Cache
{
    private static $cacheDir = '/tmp/iskole_cache/';

    public static function get($key)
    {
        $file = self::$cacheDir . md5($key) . '.cache';
        if (file_exists($file) && (time() - filemtime($file) < 3600)) {
            return unserialize(file_get_contents($file));
        }
        return null;
    }

    public static function set($key, $value)
    {
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0755, true);
        }
        $file = self::$cacheDir . md5($key) . '.cache';
        file_put_contents($file, serialize($value));
    }

    public static function delete($key)
    {
        $file = self::$cacheDir . md5($key) . '.cache';
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
```

**Usage**:

```php
// Check cache first
$users = Cache::get('all_users');
if ($users === null) {
    $users = $userModel->getAllUsers();
    Cache::set('all_users', $users);
}
```

---

## 10. Backup Strategy

### 10.1 Database Backup

**Automated Backup Script** (`scripts/backup_db.sh`):

```bash
#!/bin/bash

# Configuration
DB_USER="iskole_prod_user"
DB_PASS="your_password"
DB_NAME="iskole_production"
BACKUP_DIR="/var/backups/iskole/database"
DATE=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="$BACKUP_DIR/iskole_db_$DATE.sql.gz"

# Create backup directory
mkdir -p $BACKUP_DIR

# Dump database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_FILE

# Check if backup was successful
if [ $? -eq 0 ]; then
    echo "Database backup created: $BACKUP_FILE"

    # Delete backups older than 30 days
    find $BACKUP_DIR -name "*.sql.gz" -mtime +30 -delete

    # Upload to remote storage (optional)
    # scp $BACKUP_FILE user@remote:/backups/
    # or use rclone for cloud storage
else
    echo "Database backup failed!"
    exit 1
fi
```

**Make Executable**:

```bash
chmod +x scripts/backup_db.sh
```

**Schedule with Cron** (daily at 2 AM):

```bash
crontab -e
```

Add:

```
0 2 * * * /var/www/iskole/scripts/backup_db.sh >> /var/log/iskole_backup.log 2>&1
```

### 10.2 File Backup

**Backup Files Script** (`scripts/backup_files.sh`):

```bash
#!/bin/bash

# Configuration
SOURCE_DIR="/var/www/iskole"
BACKUP_DIR="/var/backups/iskole/files"
DATE=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="$BACKUP_DIR/iskole_files_$DATE.tar.gz"

# Create backup directory
mkdir -p $BACKUP_DIR

# Create compressed archive (exclude certain directories)
tar -czf $BACKUP_FILE \
    --exclude='node_modules' \
    --exclude='.git' \
    --exclude='vendor' \
    --exclude='storage/cache' \
    -C $(dirname $SOURCE_DIR) $(basename $SOURCE_DIR)

# Check if backup was successful
if [ $? -eq 0 ]; then
    echo "File backup created: $BACKUP_FILE"

    # Delete backups older than 30 days
    find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
else
    echo "File backup failed!"
    exit 1
fi
```

### 10.3 Restore from Backup

**Restore Database**:

```bash
# Decompress and restore
gunzip < /var/backups/iskole/database/iskole_db_20240115_020000.sql.gz | mysql -u iskole_prod_user -p iskole_production
```

**Restore Files**:

```bash
# Extract archive
tar -xzf /var/backups/iskole/files/iskole_files_20240115_020000.tar.gz -C /var/www/

# Set permissions
sudo chown -R www-data:www-data /var/www/iskole
sudo chmod -R 755 /var/www/iskole
```

---

## 11. Monitoring and Maintenance

### 11.1 Server Monitoring

**Install Monitoring Tools**:

```bash
# htop for process monitoring
sudo apt install htop -y

# iotop for disk I/O monitoring
sudo apt install iotop -y

# netstat for network monitoring
sudo apt install net-tools -y
```

**Basic Health Checks**:

```bash
# CPU and memory usage
htop

# Disk usage
df -h

# Disk I/O
sudo iotop

# Network connections
netstat -tuln

# Apache status
sudo systemctl status apache2

# MySQL status
sudo systemctl status mysql

# PHP-FPM status
sudo systemctl status php8.1-fpm
```

### 11.2 Application Monitoring

**Error Log Monitoring**:

```bash
# Apache errors
sudo tail -f /var/log/apache2/iskole_error.log

# PHP errors
sudo tail -f /var/log/php/error.log

# MySQL errors
sudo tail -f /var/log/mysql/error.log
```

**Custom Health Check Endpoint** (`app/Controllers/HealthController.php`):

```php
<?php
class HealthController extends Controller
{
    public function index()
    {
        header('Content-Type: application/json');

        $health = [
            'status' => 'ok',
            'timestamp' => date('c'),
            'checks' => []
        ];

        // Database check
        try {
            $db = Database::getInstance();
            $db->query('SELECT 1');
            $health['checks']['database'] = 'ok';
        } catch (Exception $e) {
            $health['checks']['database'] = 'error';
            $health['status'] = 'error';
        }

        // Disk space check
        $freeSpace = disk_free_space('/');
        $totalSpace = disk_total_space('/');
        $percentFree = ($freeSpace / $totalSpace) * 100;

        if ($percentFree < 10) {
            $health['checks']['disk'] = 'warning';
            $health['status'] = 'warning';
        } else {
            $health['checks']['disk'] = 'ok';
        }

        http_response_code($health['status'] === 'ok' ? 200 : 500);
        echo json_encode($health);
    }
}
```

**Access health check**:

```bash
curl https://iskole.yourdomain.com/health
```

### 11.3 Log Rotation

**Configure Logrotate** (`/etc/logrotate.d/iskole`):

```
/var/log/apache2/iskole*.log {
    daily
    rotate 30
    compress
    delaycompress
    notifempty
    create 640 www-data adm
    sharedscripts
    postrotate
        systemctl reload apache2 > /dev/null
    endscript
}

/var/log/php/error.log {
    daily
    rotate 30
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}
```

---

## 12. Troubleshooting Production Issues

### 12.1 Common Production Issues

**Issue: High CPU Usage**

**Diagnosis**:

```bash
top
# Check which processes are consuming CPU
```

**Solutions**:

- Enable OPcache
- Optimize database queries
- Add database indexes
- Implement caching

---

**Issue: High Memory Usage**

**Diagnosis**:

```bash
free -h
# Check memory usage
```

**Solutions**:

- Reduce PHP `memory_limit`
- Optimize InnoDB buffer pool size
- Kill unused processes

---

**Issue: Slow Page Load**

**Diagnosis**:

```bash
# Check slow query log
sudo tail -f /var/log/mysql/slow.log

# Check Apache access log for slow requests
sudo tail -f /var/log/apache2/iskole_access.log
```

**Solutions**:

- Add database indexes
- Enable caching
- Optimize images
- Use CDN for static assets

---

**Issue: Database Connection Errors**

**Diagnosis**:

```bash
# Check MySQL status
sudo systemctl status mysql

# Check MySQL error log
sudo tail -f /var/log/mysql/error.log

# Check connections
mysql -u root -p -e "SHOW PROCESSLIST;"
```

**Solutions**:

- Increase `max_connections` in MySQL
- Check database credentials in `.env`
- Restart MySQL: `sudo systemctl restart mysql`

---

**Issue: 500 Internal Server Error**

**Diagnosis**:

```bash
# Check Apache error log
sudo tail -f /var/log/apache2/iskole_error.log

# Check PHP error log
sudo tail -f /var/log/php/error.log
```

**Solutions**:

- Check file permissions
- Verify `.htaccess` syntax
- Check PHP syntax errors
- Ensure all dependencies installed

---

## 13. Rollback Procedures

### 13.1 Application Rollback

**Keep Previous Version**:

```bash
# Before deploying new version
cd /var/www
sudo cp -r iskole iskole_backup_$(date +%Y%m%d)
```

**Rollback to Previous Version**:

```bash
cd /var/www
sudo rm -rf iskole
sudo mv iskole_backup_20240115 iskole
sudo systemctl restart apache2
```

### 13.2 Database Rollback

**Before Making Changes**:

```bash
# Create backup
mysqldump -u iskole_prod_user -p iskole_production > /tmp/pre_migration_backup.sql
```

**Rollback**:

```bash
# Drop current database
mysql -u root -p -e "DROP DATABASE iskole_production; CREATE DATABASE iskole_production;"

# Restore backup
mysql -u iskole_prod_user -p iskole_production < /tmp/pre_migration_backup.sql
```

---

## 14. CI/CD Pipeline

### 14.1 GitHub Actions Workflow

**`.github/workflows/deploy.yml`**:

```yaml
name: Deploy to Production

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v2

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: "8.1"

      - name: Run tests
        run: |
          # Add your test commands here
          echo "Running tests..."

      - name: Deploy to server
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.SERVER_HOST }}
          username: ${{ secrets.SERVER_USER }}
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            cd /var/www/iskole
            git pull origin main
            sudo chown -R www-data:www-data /var/www/iskole
            sudo systemctl reload apache2
```

**Add Secrets** in GitHub Repository Settings:

- `SERVER_HOST`: Your server IP/domain
- `SERVER_USER`: SSH username
- `SSH_PRIVATE_KEY`: Your SSH private key

---

## Summary

This deployment guide covers:

1. ✅ **Server Setup**: Apache, PHP, MySQL configuration
2. ✅ **SSL/TLS**: Let's Encrypt certificate
3. ✅ **Security**: Firewall, SSH hardening, Fail2Ban
4. ✅ **Performance**: OPcache, database optimization, caching
5. ✅ **Backup**: Automated database and file backups
6. ✅ **Monitoring**: Health checks, log monitoring
7. ✅ **Troubleshooting**: Common issues and solutions
8. ✅ **Rollback**: Safe deployment practices

For a successful deployment:

- Follow the **pre-deployment checklist**
- Use **staging environment** for testing
- Implement **backup strategy** before changes
- Monitor **logs and performance** after deployment
- Have a **rollback plan** ready

---

**Related Documentation**:

- [System Architecture](SYSTEM-ARCHITECTURE.md)
- [Development Guide](DEVELOPMENT-GUIDE.md)
- [Database Schema](DATABASE-SCHEMA.md)
- [API Documentation](API-DOCUMENTATION.md)
- [Routing Guide](ROUTING-GUIDE.md)
