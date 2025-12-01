# Iskole School Management System

A comprehensive web-based School Management System built with PHP and MySQL, designed to streamline school operations for administrators, teachers, students, and parents.

## 📚 Overview

**Iskole** is a modern, secure, and scalable school management platform that handles:

- **User Management**: Multi-role support (Management Portal, Admin, Teacher, Student, Parent)
- **Attendance Tracking**: Real-time attendance marking and reporting
- **Marks/Grades Management**: Exam results, report cards, and performance analytics
- **Timetable Management**: Class schedules and exam timetables
- **Announcements**: School-wide and role-specific announcements
- **RESTful API**: AJAX-powered operations for smooth user experience

## 📖 Documentation

All project documentation is now organized in the **[`/documentation`](./documentation/)** folder:

- 📘 **[Documentation Index](./documentation/README.md)** - Start here for all documentation
- 🚀 **[Quick Reference](./documentation/QUICK-REFERENCE.md)** - Common tasks and commands
- 💻 **[Development Guide](./documentation/DEVELOPMENT-GUIDE.md)** - Setup and development
- 🏗️ **[System Architecture](./documentation/SYSTEM-ARCHITECTURE.md)** - Technical overview
- 🎨 **[CSS Variables Guide](./documentation/CSS-VARIABLES-COMPLETE-SUMMARY.md)** - Styling system
- 🔌 **[API Documentation](./documentation/API-DOCUMENTATION.md)** - REST API reference
- 🚢 **[Deployment Guide](./documentation/DEPLOYMENT-GUIDE.md)** - Production deployment
- 🔧 **[Troubleshooting](./documentation/TROUBLESHOOTING.md)** - Common issues & solutions

**Total Documentation:** 17 comprehensive guides | **Last Updated:** December 2, 2025

## ✨ Key Features

### For Administrators

- User directory management (add, edit, delete users)
- Upload and manage exam timetables
- Create and broadcast announcements
- Generate reports and analytics
- System configuration and settings

### For Teachers

- Mark student attendance (Present/Absent/Late)
- Enter and update exam marks
- View class rosters
- Communicate with students and parents
- Access teaching resources

### For Students

- View attendance records
- Check exam marks and report cards
- Access exam timetables
- Read announcements
- View class schedules

### For Parents

- Monitor child's attendance
- View exam results
- Check timetables and schedules
- Receive school announcements
- Communicate with teachers

## 🚀 Technology Stack

- **Backend**: PHP 7.4+ (Custom MVC Framework)
- **Database**: MySQL 8.0+ / MariaDB 10.5+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Web Server**: Apache 2.4 with mod_rewrite
- **Deployment**: Docker, Docker Compose
- **Security**: Session-based authentication, prepared statements, input sanitization

## 📋 Requirements

### Minimum Requirements

- PHP 7.4 or higher
- MySQL 8.0 or higher
- Apache 2.4 with mod_rewrite enabled
- 4 GB RAM
- 20 GB disk space

### PHP Extensions

```bash
php-mysql
php-pdo
php-mbstring
php-json
php-curl
php-xml
php-gd
php-zip
```

## 🛠️ Installation

### Quick Start (Docker)

1. **Clone the repository**:

   ```bash
   git clone https://github.com/yourusername/iskole.git
   cd iskole
   ```

2. **Configure environment**:

   ```bash
   cp .env.example .env
   # Edit .env with your database credentials
   ```

3. **Start with Docker**:

   ```bash
   docker-compose up -d
   ```

4. **Access the application**:
   - Web: http://localhost:8080
   - phpMyAdmin: http://localhost:8081

### Manual Installation

1. **Clone the repository**:

   ```bash
   git clone https://github.com/yourusername/iskole.git
   cd iskole
   ```

2. **Set up database**:

   ```bash
   mysql -u root -p
   ```

   ```sql
   CREATE DATABASE iskole_dev;
   CREATE USER 'iskole_user'@'localhost' IDENTIFIED BY 'your_password';
   GRANT ALL PRIVILEGES ON iskole_dev.* TO 'iskole_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

3. **Import schema**:

   ```bash
   mysql -u iskole_user -p iskole_dev < database/schema.sql
   ```

4. **Configure environment**:

   ```bash
   cp .env.example .env
   nano .env
   ```

   Update database credentials in `.env`:

   ```properties
   MYSQL_HOST=localhost
   MYSQL_PORT=3306
   MYSQL_DB=iskole_dev
   MYSQL_USER=iskole_user
   MYSQL_PASSWORD=your_password
   ```

5. **Configure Apache virtual host**:

   ```apache
   <VirtualHost *:80>
       ServerName iskole.local
       DocumentRoot /path/to/iskole/public

       <Directory /path/to/iskole/public>
           Options -Indexes +FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

6. **Enable site and restart Apache**:

   ```bash
   sudo a2ensite iskole.conf
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

7. **Set permissions**:

   ```bash
   sudo chown -R www-data:www-data /path/to/iskole
   sudo chmod -R 755 /path/to/iskole
   sudo chmod -R 775 /path/to/iskole/public/assets
   ```

8. **Access the application**:
   - Add `127.0.0.1 iskole.local` to `/etc/hosts`
   - Navigate to http://iskole.local

## 🏗️ Architecture

Iskole follows a **custom MVC (Model-View-Controller)** architecture with a **Front Controller Pattern**:

```
Request → .htaccess → index.php → App.php (Router)
    ↓
Controller (Business Logic)
    ↓
Model (Data Access)
    ↓
View (Presentation)
    ↓
Response
```

### Key Components

- **App.php**: Front controller and router
- **Controllers**: Handle HTTP requests, business logic
- **Models**: Database operations, data validation
- **Views**: HTML templates, UI rendering
- **Database**: Singleton PDO connection manager
- **Session**: Singleton session management

## 📖 Documentation

Comprehensive documentation is available in the following guides:

| Document                                          | Description                                                |
| ------------------------------------------------- | ---------------------------------------------------------- |
| [**System Architecture**](SYSTEM-ARCHITECTURE.md) | System design, design patterns, security model             |
| [**Routing Guide**](ROUTING-GUIDE.md)             | URL routing, controller resolution, authentication flow    |
| [**Database Schema**](DATABASE-SCHEMA.md)         | Complete database structure, relationships, sample queries |
| [**API Documentation**](API-DOCUMENTATION.md)     | RESTful API endpoints, request/response formats, examples  |
| [**Development Guide**](DEVELOPMENT-GUIDE.md)     | Setup, coding standards, debugging, best practices         |
| [**Deployment Guide**](DEPLOYMENT-GUIDE.md)       | Production deployment, security, monitoring, backups       |

## 🔐 Security Features

- **Authentication**: Session-based login system
- **Authorization**: Role-based access control (RBAC)
- **SQL Injection Protection**: Prepared statements with PDO
- **XSS Prevention**: Output escaping with `htmlspecialchars()`
- **CSRF Protection**: Token-based validation (recommended)
- **Password Security**: bcrypt hashing with `password_hash()`
- **File Upload Validation**: MIME type checking, size limits
- **Session Security**: HttpOnly cookies, secure flags, strict mode

## 🗂️ Project Structure

```
iskole/
├── app/
│   ├── Controllers/        # Application controllers
│   ├── Core/               # Core framework files
│   ├── Model/              # Data models
│   └── Views/              # View templates
├── public/                 # Web root (document root)
│   ├── assets/             # Uploaded files
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript files
│   └── index.php           # Entry point
├── database/               # Database files
│   ├── schema.sql          # Database schema
│   └── migrations/         # Migration files
├── docker/                 # Docker configurations
├── scripts/                # Utility scripts
├── .env                    # Environment configuration
├── docker-compose.yml      # Docker Compose config
└── README.md               # This file
```

## 🌐 Default Credentials

**After installation, use these default credentials**:

| Role  | Email            | Password |
| ----- | ---------------- | -------- |
| Admin | admin@iskole.com | admin123 |

⚠️ **Important**: Change default credentials immediately after first login!

## 🧪 Testing

### Manual Testing

1. Navigate to different user roles (MP, Admin, Teacher, Student, Parent)
2. Test CRUD operations (Create, Read, Update, Delete)
3. Verify authentication and authorization
4. Test API endpoints with browser DevTools

### API Testing (cURL)

```bash
# Login
curl -X POST http://iskole.local/login \
  -d "email=admin@iskole.com&password=admin123"

# Get users (with session)
curl -X GET http://iskole.local/api/getUsers \
  -b cookies.txt
```

## 🚢 Deployment

### Production Deployment

1. **Prepare server** (Ubuntu 20.04/22.04 recommended)
2. **Install LAMP stack** (Apache, MySQL, PHP)
3. **Clone repository** to `/var/www/iskole`
4. **Configure environment** (`.env` with production credentials)
5. **Set up database** (create production database, import schema)
6. **Configure Apache** (virtual host, SSL/TLS)
7. **Set permissions** (755 for directories, 644 for files)
8. **Enable SSL** (Let's Encrypt recommended)

For detailed deployment instructions, see [**DEPLOYMENT-GUIDE.md**](DEPLOYMENT-GUIDE.md).

### Docker Production Deployment

```bash
# Build and start containers
docker-compose -f docker-compose.prod.yml up -d

# Check status
docker-compose ps

# View logs
docker-compose logs -f web
```

## 🐛 Troubleshooting

### Common Issues

**Blank white page**:

- Enable error display: `ini_set('display_errors', 1);`
- Check Apache error log: `sudo tail -f /var/log/apache2/error.log`

**404 Not Found on routes**:

- Enable mod_rewrite: `sudo a2enmod rewrite`
- Verify `.htaccess` exists in `public/`
- Check Apache AllowOverride: `AllowOverride All`

**Database connection failed**:

- Verify `.env` credentials
- Check database exists: `SHOW DATABASES;`
- Test connection: `mysql -u user -p database`

**Session not persisting**:

- Ensure `session_start()` in `index.php`
- Check session save path writable
- Verify cookies enabled in browser

For more troubleshooting tips, see [**DEVELOPMENT-GUIDE.md**](DEVELOPMENT-GUIDE.md#troubleshooting-development-issues).

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Commit changes: `git commit -m "feat: Add new feature"`
4. Push to branch: `git push origin feature/your-feature`
5. Submit a Pull Request

### Coding Standards

- Follow **PSR-12** coding standards
- Use meaningful variable/function names
- Comment complex logic
- Write secure code (sanitize inputs, use prepared statements)

## 📝 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

## 👥 Authors

- **Your Name** - _Initial work_ - [@yourusername](https://github.com/yourusername)

## 🙏 Acknowledgments

- Inspired by modern school management systems
- Built with ❤️ for educational institutions
- Thanks to the PHP and open-source community

## 📞 Support

For support, please:

- Open an issue on [GitHub Issues](https://github.com/yourusername/iskole/issues)
- Email: support@iskole.com
- Documentation: [System Documentation](SYSTEM-ARCHITECTURE.md)

## 🗓️ Changelog

### Version 2.0.0 (2025-12-02)

- ✅ **Documentation Organization** - Moved all docs to `/documentation` folder
- ✅ **CSS Variables System** - Implemented 67 centralized CSS variables
- ✅ **System-wide Styling** - Updated 17+ CSS files to use variables
- ✅ **Design System** - Professional theming with single source of truth
- ✅ **Documentation** - 4 new CSS variables guides added

### Version 1.0.0 (2024-01-15)

- ✅ Initial release
- ✅ User management (MP, Admin, Teacher, Student, Parent)
- ✅ Attendance tracking
- ✅ Marks/grades management
- ✅ Timetable management
- ✅ Announcements system
- ✅ RESTful API
- ✅ Comprehensive documentation

---

**Built with PHP | Designed for Education | Powered by Innovation**
