# Exam Time Table CRUD System Documentation

## Table of Contents

1. [Overview](#overview)
2. [Database Schema](#database-schema)
3. [File Structure](#file-structure)
4. [Model Layer (ExamTimeTableModel)](#model-layer)
5. [Controller Layer (ExamTimeTableController)](#controller-layer)
6. [View Layer](#view-layer)
7. [API Endpoints](#api-endpoints)
8. [Usage Examples](#usage-examples)
9. [Security Features](#security-features)
10. [File Upload System](#file-upload-system)
11. [Troubleshooting](#troubleshooting)

---

## Overview

The Exam Time Table CRUD system allows administrators to manage exam timetables for different grades (6-9). The system supports:

- **Create**: Upload new exam timetable images
- **Read**: View uploaded timetables by grade
- **Update**: Replace existing timetable images
- **Delete**: Remove old files when uploading new ones
- **Visibility Control**: Show/hide timetables for students and parents

### Key Features

- Grade-based timetable management (Grades 6, 7, 8, 9)
- Image file upload with validation
- Visibility toggle (show/hide from students/parents)
- Automatic file cleanup when updating
- Responsive design for all user roles
- Database-driven storage

---

## Database Schema

### Table: `examTimeTable`

```sql
CREATE TABLE examTimeTable (
    timeTableID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    visibility TINYINT(1) DEFAULT 1,   -- 1 = visible, 0 = hidden
    grade VARCHAR(10) NOT NULL,
    title VARCHAR(255) NOT NULL,
    file VARCHAR(255) NOT NULL
);
```

#### Column Descriptions

- `timeTableID`: Auto-incrementing primary key
- `userID`: ID of the user who uploaded the timetable
- `visibility`: Controls if timetable is visible to students/parents (1=visible, 0=hidden)
- `grade`: Grade level (6, 7, 8, 9)
- `title`: Title of the timetable (default: "Exam Timetable")
- `file`: Relative path to the uploaded image file

---

## File Structure

```
app/
├── Controllers/
│   └── ExamTimeTableController.php     # Handles CRUD operations
├── Model/
│   └── ExamTimeTableModel.php          # Database interactions
└── Views/
    ├── admin/
    │   └── examTimeTable.php           # Admin wrapper
    ├── student/
    │   └── examTimeTable.php           # Student view
    ├── parent/
    │   └── parentExamTimeTable.php     # Parent view
    └── templates/
        └── examTimeTable.php           # Admin management template

public/assets/exam_timetable_images/    # Upload directory
├── exam_tt_grade_6_1234567890.jpg
├── exam_tt_grade_7_1234567891.png
└── ...
```

---

## Model Layer (ExamTimeTableModel)

### Class: `ExamTimeTableModel`

Location: `app/Model/ExamTimeTableModel.php`

#### Constructor

```php
public function __construct()
```

Initializes database connection using Database singleton.

#### Methods

##### Create Operation

```php
public function create($grade, $filePath, $userID = null, $title = 'Exam Timetable')
```

**Parameters:**

- `$grade` (string): Grade level (6, 7, 8, 9)
- `$filePath` (string): Relative path to uploaded file
- `$userID` (int, optional): User ID (defaults to session user_id)
- `$title` (string, optional): Timetable title

**Returns:** `boolean` - Success status

**Example:**

```php
$model = new ExamTimeTableModel();
$success = $model->create('6', '/assets/exam_timetable_images/exam_tt_grade_6_1234567890.jpg');
```

##### Read Operation

```php
public function getByGrade($grade)
```

**Parameters:**

- `$grade` (string): Grade level to retrieve

**Returns:** `array|false` - Timetable data or false if not found

**Example:**

```php
$entry = $model->getByGrade('6');
$imagePath = $entry['file'] ?? null;
$isVisible = $entry['visibility'] ?? 0;
```

##### Update Operation

```php
public function update($grade, $filePath, $userID = null, $title = 'Exam Timetable')
```

Updates existing timetable entry for specified grade.

**Parameters:** Same as `create()`
**Returns:** `boolean` - Success status

##### Visibility Toggle

```php
public function toggleVisibility($grade, $visibility)
```

**Parameters:**

- `$grade` (string): Grade level
- `$visibility` (int): 1 for visible, 0 for hidden

**Returns:** `boolean` - Success status

##### Existence Check

```php
public function exists($grade)
```

**Parameters:**

- `$grade` (string): Grade level to check

**Returns:** `boolean` - True if timetable exists for grade

---

## Controller Layer (ExamTimeTableController)

### Class: `ExamTimeTableController`

Location: `app/Controllers/ExamTimeTableController.php`

#### Methods

##### Upload Handler

```php
public function upload()
```

Handles both file uploads and visibility toggles via POST requests.

**Form Actions:**

- `action=upload`: File upload operation
- `action=toggle`: Visibility toggle operation

**POST Parameters:**

- `grade`: Target grade (required)
- `exam_image`: File upload (for upload action)
- `hidden`: Current visibility state (for toggle action)

**File Validation:**

- Allowed types: `image/jpeg`, `image/jpg`, `image/png`, `image/gif`, `image/webp`
- Error handling for invalid files
- Automatic file size and type validation

**File Management:**

- Creates upload directory if not exists
- Generates unique filenames: `exam_tt_grade_{grade}_{timestamp}.{ext}`
- Deletes old files when uploading new ones
- Stores relative paths in database

**Response:**

- Sets session messages for user feedback
- Redirects to admin panel with grade parameter

---

## View Layer

### Admin View (templates/examTimeTable.php)

**Features:**

- Grade selection dropdown
- File upload form
- Visibility toggle button
- Image preview (always visible in admin)
- Status badges (visible/hidden)

**Form Structure:**

```html
<!-- Grade Selection (GET request) -->
<select
  onchange="window.location.href='/index.php?url=Admin&tab=Exam Time Table&grade=' + this.value"
>
  <!-- Upload Form (POST request) -->
  <form
    method="POST"
    action="/index.php?url=ExamTimeTable/upload"
    enctype="multipart/form-data"
  >
    <input type="hidden" name="grade" value="<?= $selectedGrade ?>" />
    <input type="file" name="exam_image" accept="image/*" />
    <button type="submit" name="action" value="upload">Upload</button>
  </form>

  <!-- Visibility Toggle Form (POST request) -->
  <form method="POST" action="/index.php?url=ExamTimeTable/upload">
    <input type="hidden" name="grade" value="<?= $selectedGrade ?>" />
    <input type="hidden" name="hidden" value="<?= $hidden ? '0' : '1' ?>" />
    <button type="submit" name="action" value="toggle">
      Show/Hide Timetable
    </button>
  </form>
</select>
```

### Student View (student/examTimeTable.php)

**Features:**

- Automatic grade detection from student profile
- Read-only view
- Respects visibility settings
- Responsive image display

**Access Control:**

- Only shows visible timetables (`visibility = 1`)
- Student cannot modify or upload

### Parent View (parent/parentExamTimeTable.php)

**Features:**

- Shows child's exam timetable
- Same visibility rules as student view
- Parent-friendly messaging

---

## API Endpoints

### Upload/Toggle Endpoint

```
POST /index.php?url=ExamTimeTable/upload
```

**Content-Type:** `multipart/form-data`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `action` | string | Yes | `upload` or `toggle` |
| `grade` | string | Yes | Grade level (6,7,8,9) |
| `exam_image` | file | Yes* | Image file (*required for upload action) |
| `hidden` | string | Yes* | Current visibility (*required for toggle action) |

**Response:**

- Success: Redirect to admin panel with success message
- Error: Redirect with error message in session

### View Endpoints

```
GET /index.php?url=Admin&tab=Exam Time Table&grade={grade}   # Admin view
GET /index.php?url=Student&tab=Exam Time Table               # Student view
GET /index.php?url=Parent&tab=Exam Time Table                # Parent view
```

---

## Usage Examples

### Admin: Upload New Timetable

```php
// 1. Navigate to Admin > Exam Time Table
// 2. Select grade from dropdown
// 3. Choose image file
// 4. Click "Upload" button
// Result: File uploaded, old file deleted, database updated
```

### Admin: Toggle Visibility

```php
// 1. Navigate to Admin > Exam Time Table
// 2. Select grade from dropdown
// 3. Click "Show Timetable" or "Hide Timetable" button
// Result: Visibility toggled in database
```

### Student: View Timetable

```php
// 1. Navigate to Student > Exam Time Table
// 2. System automatically detects student's grade
// 3. Shows timetable if visible, or "not available" message
```

### Parent: View Child's Timetable

```php
// 1. Navigate to Parent > Exam Time Table
// 2. System shows child's grade timetable
// 3. Same visibility rules as student view
```

### Programmatic Usage

```php
// Create new timetable
$model = new ExamTimeTableModel();
$success = $model->create('6', '/assets/exam_timetable_images/exam_tt_grade_6_1701234567.jpg', 1, 'Mid-Term Exam Schedule');

// Get timetable
$timetable = $model->getByGrade('6');
if ($timetable) {
    echo "File: " . $timetable['file'];
    echo "Visible: " . ($timetable['visibility'] ? 'Yes' : 'No');
}

// Update visibility
$model->toggleVisibility('6', 0); // Hide timetable

// Check if exists
if ($model->exists('7')) {
    echo "Grade 7 timetable exists";
}
```

---

## Security Features

### File Upload Security

- **File Type Validation**: Only allows image files (JPEG, PNG, GIF, WebP)
- **MIME Type Checking**: Validates actual file content, not just extension
- **Upload Directory**: Files stored outside web root initially, then moved to public assets
- **Unique Filenames**: Prevents file collision and direct file access guessing
- **File Size Limits**: Configurable via PHP settings

### Input Sanitization

```php
// Grade sanitization
$grade = preg_replace('/[^0-9A-Za-z_-]/', '', $_POST['grade']);

// HTML output escaping
<?= htmlspecialchars($imagePath) ?>
```

### Access Control

- **Admin Only**: Upload/modify operations restricted to admin users
- **Visibility Control**: Students/parents only see visible timetables
- **Session Validation**: All operations require valid user session

### SQL Injection Prevention

- **Prepared Statements**: All database queries use parameterized statements
- **Parameter Binding**: User input properly escaped and bound

---

## File Upload System

### Directory Structure

```
public/assets/exam_timetable_images/
├── exam_tt_grade_6_1701234567.jpg
├── exam_tt_grade_7_1701234568.png
├── exam_tt_grade_8_1701234569.gif
└── exam_tt_grade_9_1701234570.webp
```

### Filename Convention

```
exam_tt_grade_{GRADE}_{TIMESTAMP}.{EXTENSION}
```

### Upload Process

1. **Validation**: Check file type and size
2. **Directory Creation**: Create upload directory if not exists
3. **Filename Generation**: Create unique filename with timestamp
4. **Old File Cleanup**: Delete existing file for the grade
5. **File Move**: Move uploaded file to final location
6. **Database Update**: Store file path in database
7. **Cleanup on Error**: Remove uploaded file if database operation fails

### File Paths

- **Physical Path**: `/path/to/project/public/assets/exam_timetable_images/filename.ext`
- **Database Path**: `/assets/exam_timetable_images/filename.ext`
- **Web Access**: `http://domain.com/assets/exam_timetable_images/filename.ext`

---

## Troubleshooting

### Common Issues

#### 1. File Upload Fails

**Symptoms:** "Failed to upload file" message
**Causes:**

- Directory permissions
- PHP upload limits
- Disk space

**Solutions:**

```bash
# Check directory permissions
chmod 755 public/assets/exam_timetable_images/

# Check PHP settings
php -i | grep -i upload

# Increase upload limits in php.ini
upload_max_filesize = 10M
post_max_size = 10M
```

#### 2. Database Errors

**Symptoms:** "Failed to save timetable to database"
**Causes:**

- Database connection issues
- Table doesn't exist
- Column name mismatch

**Solutions:**

```sql
-- Check table exists
SHOW TABLES LIKE 'examTimeTable';

-- Check table structure
DESCRIBE examTimeTable;

-- Verify connection
SELECT 1;
```

#### 3. Grade Selection Issues

**Symptoms:** Redirects to login page when changing grades
**Causes:**

- Incorrect URL routing
- Missing session authentication
- Wrong redirect URL

**Solutions:**

```php
// Check redirect URL in examTimeTable.php
window.location.href='/index.php?url=Admin&tab=Exam Time Table&grade=' + this.value

// Verify session is active
var_dump($_SESSION);
```

#### 4. Images Not Displaying

**Symptoms:** Broken image links
**Causes:**

- Incorrect file paths
- File permissions
- Missing files

**Solutions:**

```bash
# Check file exists
ls -la public/assets/exam_timetable_images/

# Check web server access
curl http://localhost/assets/exam_timetable_images/filename.jpg

# Verify database paths
SELECT file FROM examTimeTable WHERE grade = '6';
```

### Debug Mode

Enable debugging by adding to your PHP files:

```php
// At top of controller
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log upload attempts
error_log("Upload attempt for grade: " . $grade);
error_log("File info: " . print_r($_FILES, true));
```

### Database Debugging

```php
// In ExamTimeTableModel
try {
    $stmt = $this->db->prepare($sql);
    $result = $stmt->execute($params);
    if (!$result) {
        error_log("SQL Error: " . print_r($stmt->errorInfo(), true));
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
}
```

---

## Performance Considerations

### File Storage

- **Image Optimization**: Consider implementing automatic image compression
- **CDN Integration**: For high-traffic sites, serve images from CDN
- **Cleanup Jobs**: Implement scheduled cleanup of orphaned files

### Database Optimization

```sql
-- Add indexes for better performance
CREATE INDEX idx_examtt_grade ON examTimeTable(grade);
CREATE INDEX idx_examtt_visibility ON examTimeTable(visibility);
CREATE INDEX idx_examtt_user ON examTimeTable(userID);
```

### Caching

- **File Caching**: Cache file existence checks
- **Database Caching**: Cache timetable queries for frequently accessed grades
- **HTTP Caching**: Set appropriate cache headers for image files

---

## Future Enhancements

### Potential Features

1. **Multiple File Support**: Upload multiple timetable versions
2. **File Versioning**: Keep history of previous timetables
3. **Bulk Upload**: Upload timetables for all grades at once
4. **PDF Support**: Support PDF timetables in addition to images
5. **Approval Workflow**: Require approval before making timetables visible
6. **Notifications**: Email/SMS notifications when new timetables are uploaded
7. **Analytics**: Track timetable view statistics
8. **Mobile App API**: REST API for mobile application integration

### Technical Improvements

1. **File Validation Enhancement**: More robust file type checking
2. **Error Handling**: Better error messages and recovery options
3. **Audit Logging**: Track all CRUD operations with timestamps
4. **Backup System**: Automatic backup of uploaded files
5. **Configuration**: Make grades and file types configurable
6. **Unit Tests**: Comprehensive test coverage
7. **API Documentation**: OpenAPI/Swagger documentation

---

## Conclusion

The Exam Time Table CRUD system provides a comprehensive solution for managing exam timetables across different grades. It includes robust security features, proper error handling, and a user-friendly interface for administrators, students, and parents.

For additional support or feature requests, please contact the development team or refer to the project repository documentation.

---

**Document Version:** 1.0  
**Last Updated:** November 24, 2024  
**Author:** Development Team
