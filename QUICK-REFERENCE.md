# Iskole School Management System - Quick Reference

## 📚 Documentation Index

This is a quick reference guide to all Iskole documentation. Use this to quickly find what you need.

---

## Core Documentation

### 1. [README.md](README.md) - Project Overview

**What it covers:**

- Project introduction and features
- Technology stack
- Quick installation instructions
- Default credentials
- Basic usage

**When to use:**

- First time setup
- Understanding what Iskole does
- Quick start guide

---

### 2. [SYSTEM-ARCHITECTURE.md](SYSTEM-ARCHITECTURE.md) - System Design

**What it covers:**

- System architecture (3-tier)
- Design patterns (MVC, Front Controller, Singleton)
- Application layers (Presentation, Business Logic, Data Access)
- Directory structure
- Core components (App.php, Database, Session)
- Security architecture
- Role-based access control
- Module interactions
- Data flow diagrams

**When to use:**

- Understanding how the system works
- Planning new features
- Architectural decisions
- Onboarding new developers

**Key Sections:**

- Section 2: System Architecture Diagram
- Section 6: Core Components
- Section 7: Security Architecture
- Section 9: Module Interactions

---

### 3. [ROUTING-GUIDE.md](ROUTING-GUIDE.md) - URL Routing System

**What it covers:**

- Front Controller Pattern
- URL structure and parsing
- Controller/method resolution
- Authentication flow
- API routing
- Route examples
- Troubleshooting routing issues

**When to use:**

- Creating new controllers
- Understanding URL structure
- Debugging 404 errors
- Implementing new routes

**Key Sections:**

- Section 3: URL Structure
- Section 4: How Routing Works
- Section 6: Controller Resolution
- Section 8: Authentication and Authorization

---

### 4. [DATABASE-SCHEMA.md](DATABASE-SCHEMA.md) - Database Structure

**What it covers:**

- Complete database schema (20+ tables)
- Entity Relationship Diagrams (ERD)
- Table definitions with all columns
- Relationships (1:1, 1:N, N:M)
- Foreign keys and indexes
- Sample queries for common operations
- Backup and restore commands

**When to use:**

- Creating database migrations
- Understanding data relationships
- Writing complex queries
- Database optimization

**Key Sections:**

- Section 2: Tables Overview
- Section 3-20: Individual table schemas
- Section 21: Relationships
- Section 22: Sample Queries

---

### 5. [API-DOCUMENTATION.md](API-DOCUMENTATION.md) - RESTful API Reference

**What it covers:**

- Complete API endpoint reference
- Request/response formats
- Authentication requirements
- Error handling
- HTTP status codes
- Code examples (JavaScript, jQuery, PHP, Python, cURL)
- Best practices

**When to use:**

- Building frontend features with AJAX
- Integrating with external systems
- Mobile app development
- API testing

**Key Sections:**

- Section 3: Authentication
- Section 4-9: Endpoint categories
- Section 10: Error Handling
- Section 11: Code Examples

---

### 6. [DEVELOPMENT-GUIDE.md](DEVELOPMENT-GUIDE.md) - Development Workflow

**What it covers:**

- Development environment setup
- Coding standards (PSR-12)
- Git workflow and branching
- Creating new features (controllers, models, views)
- Database migrations
- Testing strategies
- Debugging techniques
- Common development tasks
- Best practices

**When to use:**

- Setting up development environment
- Learning project conventions
- Implementing new features
- Code review preparation
- Daily development work

**Key Sections:**

- Section 1: Environment Setup
- Section 3: Coding Standards
- Section 5: Creating New Features
- Section 8: Debugging
- Section 10: Best Practices

---

### 7. [DEPLOYMENT-GUIDE.md](DEPLOYMENT-GUIDE.md) - Production Deployment

**What it covers:**

- Pre-deployment checklist
- Server requirements
- Manual deployment (Apache, PHP, MySQL)
- Docker deployment
- Production configuration
- Security hardening (firewall, SSL, Fail2Ban)
- Performance optimization
- Backup strategy
- Monitoring and maintenance
- Troubleshooting production issues
- Rollback procedures
- CI/CD pipeline

**When to use:**

- Deploying to production
- Server configuration
- Security audits
- Performance tuning
- Disaster recovery planning

**Key Sections:**

- Section 1: Pre-Deployment Checklist
- Section 4: Manual Deployment
- Section 8: Security Hardening
- Section 9: Performance Optimization
- Section 10: Backup Strategy

---

### 8. [TROUBLESHOOTING.md](TROUBLESHOOTING.md) - Problem Solving

**What it covers:**

- Installation issues
- Server and Apache issues
- Database connection problems
- Authentication and session issues
- Routing and URL issues
- File upload problems
- Performance issues
- AJAX and API issues
- Frontend issues
- Docker issues
- Security issues
- Debugging techniques

**When to use:**

- Something isn't working
- Error messages
- Performance problems
- Quick fixes

**Key Sections:**

- Section 2: Server and Apache Issues
- Section 3: Database Issues
- Section 4: Authentication Issues
- Section 12: Debugging Techniques

---

## Quick Reference Charts

### Role Capabilities Matrix

| Feature          | MP  | Admin | Teacher | Student  | Parent     |
| ---------------- | --- | ----- | ------- | -------- | ---------- |
| User Management  | ✅  | ✅    | ❌      | ❌       | ❌         |
| Timetable Upload | ❌  | ✅    | ❌      | ❌       | ❌         |
| Mark Attendance  | ❌  | ✅    | ✅      | ❌       | ❌         |
| Enter Marks      | ❌  | ✅    | ✅      | ❌       | ❌         |
| View Attendance  | ✅  | ✅    | ✅      | ✅ (own) | ✅ (child) |
| View Marks       | ✅  | ✅    | ✅      | ✅ (own) | ✅ (child) |
| Announcements    | ✅  | ✅    | ❌      | ❌       | ❌         |
| Reports          | ✅  | ✅    | ✅      | ✅ (own) | ✅ (child) |

---

### Controller to URL Mapping

| Controller        | URL Pattern  | Role Required          |
| ----------------- | ------------ | ---------------------- |
| LoginController   | `/login`     | Public                 |
| MpController      | `/mp/*`      | mp                     |
| AdminController   | `/admin/*`   | admin                  |
| TeacherController | `/teacher/*` | teacher                |
| StudentController | `/student/*` | student                |
| ParentController  | `/parent/*`  | parent                 |
| ApiController     | `/api/*`     | Varies (authenticated) |
| HomeController    | `/home`      | Any authenticated      |

---

### Database Tables Quick Reference

| Table         | Purpose           | Key Columns                         |
| ------------- | ----------------- | ----------------------------------- |
| users         | All system users  | user_id, email, role, password      |
| classes       | Class definitions | class_id, class_name, grade         |
| attendance    | Daily attendance  | student_id, date, status            |
| marks         | Exam results      | student_id, subject_id, marks       |
| subjects      | Course subjects   | subject_id, subject_name            |
| timetable     | Class schedules   | class_id, day, period, subject_id   |
| announcements | School notices    | title, content, target_role         |
| teachers      | Teacher details   | teacher_id, user_id, specialization |
| students      | Student details   | student_id, user_id, class_id       |
| parents       | Parent details    | parent_id, user_id, student_id      |

---

### API Endpoints Quick Reference

| Endpoint                | Method | Purpose                | Auth Required |
| ----------------------- | ------ | ---------------------- | ------------- |
| `/api/getUsers`         | GET    | Fetch all users        | Yes           |
| `/api/getUserById`      | POST   | Get user by ID         | Yes           |
| `/api/saveUser`         | POST   | Create/update user     | Yes (admin)   |
| `/api/deleteUser`       | POST   | Delete user            | Yes (admin)   |
| `/api/saveAttendance`   | POST   | Mark attendance        | Yes (teacher) |
| `/api/getAttendance`    | POST   | Get attendance records | Yes           |
| `/api/saveMarks`        | POST   | Save exam marks        | Yes (teacher) |
| `/api/getMarks`         | POST   | Get marks              | Yes           |
| `/api/getAnnouncements` | GET    | Fetch announcements    | Yes           |

---

### Common File Locations

```
Iskole/
├── .env                              # Environment config
├── README.md                         # Project overview
├── SYSTEM-ARCHITECTURE.md            # System design
├── ROUTING-GUIDE.md                  # URL routing
├── DATABASE-SCHEMA.md                # Database structure
├── API-DOCUMENTATION.md              # API reference
├── DEVELOPMENT-GUIDE.md              # Dev workflow
├── DEPLOYMENT-GUIDE.md               # Production deployment
├── TROUBLESHOOTING.md                # Problem solving
│
├── app/
│   ├── init.php                      # Bootstrap
│   ├── Core/
│   │   ├── App.php                   # Router (Front Controller)
│   │   ├── Controller.php            # Base controller
│   │   ├── Database.php              # DB connection
│   │   └── Session.php               # Session manager
│   │
│   ├── Controllers/
│   │   ├── LoginController.php       # Authentication
│   │   ├── ApiController.php         # API endpoints
│   │   ├── MpController.php          # MP dashboard
│   │   ├── AdminController.php       # Admin dashboard
│   │   ├── TeacherController.php     # Teacher dashboard
│   │   ├── StudentController.php     # Student dashboard
│   │   └── ParentController.php      # Parent dashboard
│   │
│   ├── Model/
│   │   ├── UserModel.php             # User operations
│   │   ├── TeacherModel.php          # Teacher operations
│   │   ├── StudentModel.php          # Student operations
│   │   └── AnnouncementModel.php     # Announcements
│   │
│   └── Views/
│       ├── login.php                 # Login page
│       ├── mp/                       # MP views
│       ├── admin/                    # Admin views
│       ├── teacher/                  # Teacher views
│       ├── student/                  # Student views
│       └── parent/                   # Parent views
│
├── public/                           # Web root
│   ├── index.php                     # Entry point
│   ├── .htaccess                     # URL rewriting
│   ├── css/                          # Stylesheets
│   ├── js/                           # JavaScript
│   └── assets/                       # Uploads
│
└── database/
    ├── schema.sql                    # Database schema
    └── migrations/                   # Migration files
```

---

### Environment Variables (.env)

```properties
# Database Configuration
MYSQL_HOST=localhost              # Database host
MYSQL_PORT=3306                   # Database port
MYSQL_DB=iskole_production        # Database name
MYSQL_USER=iskole_user            # Database username
MYSQL_PASSWORD=secret             # Database password

# Application Settings
APP_ENV=production                # Environment (development/production)
APP_DEBUG=false                   # Debug mode (true/false)
APP_URL=https://iskole.com        # Application URL
```

---

### Command Cheat Sheet

#### Git Commands

```bash
git checkout -b feature/new-feature    # Create feature branch
git add .                              # Stage all changes
git commit -m "feat: Add feature"      # Commit with message
git push origin feature/new-feature    # Push to remote
```

#### Database Commands

```bash
mysql -u user -p database              # Connect to database
mysql -u user -p db < schema.sql       # Import SQL file
mysqldump -u user -p db > backup.sql   # Export database
```

#### Apache Commands

```bash
sudo systemctl restart apache2         # Restart Apache
sudo a2enmod rewrite                   # Enable mod_rewrite
sudo a2ensite iskole.conf              # Enable site
apache2ctl configtest                  # Test configuration
```

#### Docker Commands

```bash
docker-compose up -d                   # Start containers
docker-compose down                    # Stop containers
docker-compose ps                      # List containers
docker-compose logs -f web             # View logs
```

#### Permission Commands

```bash
sudo chown -R www-data:www-data /var/www/iskole
sudo chmod -R 755 /var/www/iskole
sudo chmod -R 775 /var/www/iskole/public/assets
```

---

## Common Tasks Quick Links

### For New Developers

1. Read [README.md](README.md) for overview
2. Follow [DEVELOPMENT-GUIDE.md § 1-2](DEVELOPMENT-GUIDE.md#development-environment-setup) for setup
3. Review [SYSTEM-ARCHITECTURE.md § 2](SYSTEM-ARCHITECTURE.md#system-architecture) for architecture
4. Study [ROUTING-GUIDE.md § 4](ROUTING-GUIDE.md#how-routing-works-step-by-step) for routing

### For Creating New Features

1. [DEVELOPMENT-GUIDE.md § 5](DEVELOPMENT-GUIDE.md#creating-new-features) - Feature creation
2. [ROUTING-GUIDE.md § 6](ROUTING-GUIDE.md#controller-resolution) - Adding routes
3. [DATABASE-SCHEMA.md § 22](DATABASE-SCHEMA.md#sample-queries) - Database queries
4. [API-DOCUMENTATION.md § 11](API-DOCUMENTATION.md#code-examples) - API integration

### For Debugging Issues

1. [TROUBLESHOOTING.md § 12](TROUBLESHOOTING.md#debugging-techniques) - Debugging techniques
2. [DEVELOPMENT-GUIDE.md § 8](DEVELOPMENT-GUIDE.md#debugging) - Debugging tools
3. Check error logs: `/var/log/apache2/error.log`
4. Enable error display (dev only)

### For Deployment

1. [DEPLOYMENT-GUIDE.md § 1](DEPLOYMENT-GUIDE.md#pre-deployment-checklist) - Checklist
2. [DEPLOYMENT-GUIDE.md § 4](DEPLOYMENT-GUIDE.md#manual-deployment) - Manual deployment
3. [DEPLOYMENT-GUIDE.md § 8](DEPLOYMENT-GUIDE.md#security-hardening) - Security
4. [DEPLOYMENT-GUIDE.md § 10](DEPLOYMENT-GUIDE.md#backup-strategy) - Backups

### For API Development

1. [API-DOCUMENTATION.md § 3](API-DOCUMENTATION.md#authentication) - Authentication
2. [API-DOCUMENTATION.md § 4-9](API-DOCUMENTATION.md#api-endpoints) - Endpoints
3. [API-DOCUMENTATION.md § 11](API-DOCUMENTATION.md#code-examples) - Code examples
4. [DEVELOPMENT-GUIDE.md § 5.2](DEVELOPMENT-GUIDE.md#adding-a-new-api-endpoint) - Creating endpoints

---

## Support and Resources

### Documentation

- 📖 All documentation in project root
- 📚 Inline code comments
- 📝 Git commit history

### Getting Help

1. Check [TROUBLESHOOTING.md](TROUBLESHOOTING.md) first
2. Search GitHub Issues
3. Review relevant documentation
4. Ask team/community

### Contributing

1. Read [DEVELOPMENT-GUIDE.md § 4](DEVELOPMENT-GUIDE.md#development-workflow)
2. Follow coding standards
3. Write tests
4. Submit pull request

---

## Documentation Version

**Version**: 1.0.0  
**Last Updated**: 2024-01-15  
**Maintained By**: Iskole Development Team

---

**Happy Coding! 🚀**
