# 🔌 Iskole API Documentation

## Table of Contents

1. [Introduction](#introduction)
2. [Authentication](#authentication)
3. [API Endpoints](#api-endpoints)
4. [Request/Response Format](#request-response-format)
5. [Error Handling](#error-handling)
6. [Code Examples](#code-examples)

---

## 1. Introduction {#introduction}

The Iskole API provides RESTful endpoints for managing school data programmatically. All API endpoints are prefixed with `/api/` and require authentication.

### Base URL

```
http://localhost:8083/api/
```

### Content Type

All requests and responses use JSON format:

```
Content-Type: application/json
```

### HTTP Methods

- **GET**: Retrieve data
- **POST**: Create or update data
- **PUT**: Update existing data
- **DELETE**: Delete data

---

## 2. Authentication {#authentication}

### Session-Based Authentication

All API requests require an active session. Users must login through the web interface before making API calls.

**Login Flow:**

```javascript
// 1. Login through web interface
POST /login
Body: { email, password }

// 2. Session cookie is set automatically

// 3. Make API requests
GET /api/users?action=get&id=123
Headers: { Cookie: PHPSESSID=xxx }
```

### Authentication Errors

**401 Unauthorized:**

```json
{
  "success": false,
  "message": "Unauthorized - Please login"
}
```

---

## 3. API Endpoints {#api-endpoints}

### 3.1 Users API

**Base:** `/api/users`

#### Get User by ID

```http
GET /api/users?action=get&id={userID}
```

**Parameters:**

- `id` (required): User ID

**Response:**

```json
{
  "success": true,
  "data": {
    "userID": 123,
    "firstName": "John",
    "lastName": "Doe",
    "email": "john.doe@example.com",
    "phone": "0712345678",
    "gender": "Male",
    "dateOfBirth": "1990-01-15",
    "role": "Teacher",
    "address_line1": "123 Main St",
    "address_line2": "Colombo",
    "address_line3": "Western Province"
  }
}
```

---

#### Search Users

```http
GET /api/users?action=search&q={query}
```

**Parameters:**

- `q` (required): Search query (searches name, email, studentID)

**Response:**

```json
{
  "success": true,
  "count": 3,
  "users": [
    {
      "userID": 1,
      "firstName": "John",
      "lastName": "Smith",
      "email": "john.smith@example.com",
      "role": "Teacher",
      "studentID": null
    },
    {
      "userID": 5,
      "firstName": "Johnny",
      "lastName": "Doe",
      "email": "johnny@example.com",
      "role": "Student",
      "studentID": "STU2025001"
    }
  ]
}
```

---

#### Update User

```http
POST /api/users?action=update
Content-Type: application/json
```

**Request Body:**

```json
{
  "userID": 123,
  "firstName": "John",
  "lastName": "Doe",
  "email": "john.doe@example.com",
  "phone": "0712345678",
  "gender": "Male",
  "dateOfBirth": "1990-01-15",
  "address_line1": "123 Main St",
  "address_line2": "Colombo",
  "address_line3": "Western Province"
}
```

**Response:**

```json
{
  "success": true,
  "message": "User updated successfully"
}
```

---

#### Delete User (Soft Delete)

```http
POST /api/users?action=delete
Content-Type: application/json
```

**Request Body:**

```json
{
  "userID": 123
}
```

**Response:**

```json
{
  "success": true,
  "message": "User deleted successfully"
}
```

---

### 3.2 Attendance API

**Base:** `/api/attendance`

#### Get Student Attendance

```http
GET /api/attendance?action=getStudent&studentID={id}&month={YYYY-MM}
```

**Parameters:**

- `studentID` (required): Student ID
- `month` (optional): Month in YYYY-MM format (default: current month)

**Response:**

```json
{
  "success": true,
  "studentID": 10,
  "month": "2025-11",
  "summary": {
    "totalDays": 22,
    "present": 18,
    "absent": 2,
    "late": 1,
    "excused": 1,
    "percentage": 81.82
  },
  "records": [
    {
      "date": "2025-11-01",
      "status": "Present",
      "remarks": null
    },
    {
      "date": "2025-11-02",
      "status": "Absent",
      "remarks": "Sick"
    }
  ]
}
```

---

#### Mark Attendance

```http
POST /api/attendance?action=mark
Content-Type: application/json
```

**Request Body:**

```json
{
  "studentID": 10,
  "classID": 5,
  "date": "2025-11-21",
  "status": "Present",
  "remarks": ""
}
```

**Response:**

```json
{
  "success": true,
  "message": "Attendance marked successfully"
}
```

---

### 3.3 Marks API

**Base:** `/api/marks`

#### Get Student Marks

```http
GET /api/marks?action=getStudent&studentID={id}&examID={examID}
```

**Parameters:**

- `studentID` (required): Student ID
- `examID` (optional): Specific exam ID

**Response:**

```json
{
  "success": true,
  "studentID": 15,
  "marks": [
    {
      "examName": "Midterm 2025",
      "subjectName": "Mathematics",
      "marks": 85,
      "maxMarks": 100,
      "grade": "A",
      "percentage": 85.0
    },
    {
      "examName": "Midterm 2025",
      "subjectName": "Science",
      "marks": 78,
      "maxMarks": 100,
      "grade": "B+",
      "percentage": 78.0
    }
  ]
}
```

---

#### Enter Marks

```http
POST /api/marks?action=enter
Content-Type: application/json
```

**Request Body:**

```json
{
  "studentID": 15,
  "examID": 3,
  "subjectID": 8,
  "marks": 85,
  "maxMarks": 100,
  "grade": "A",
  "remarks": "Excellent performance"
}
```

**Response:**

```json
{
  "success": true,
  "message": "Marks entered successfully"
}
```

---

### 3.4 Classes API

**Base:** `/api/classes`

#### Get Class Details

```http
GET /api/classes?action=get&classID={id}
```

**Response:**

```json
{
  "success": true,
  "data": {
    "classID": 8,
    "className": "Grade 10 A",
    "grade": 10,
    "section": "A",
    "academicYear": "2025",
    "classTeacher": {
      "teacherID": 5,
      "name": "Mrs. Smith"
    },
    "studentCount": 32,
    "subjects": [
      {
        "subjectID": 1,
        "subjectName": "Mathematics",
        "teacher": "Mr. Johnson"
      },
      {
        "subjectID": 2,
        "subjectName": "Science",
        "teacher": "Dr. Williams"
      }
    ]
  }
}
```

---

#### Get Class Students

```http
GET /api/classes?action=getStudents&classID={id}
```

**Response:**

```json
{
  "success": true,
  "classID": 8,
  "count": 32,
  "students": [
    {
      "studentID": 10,
      "studentIDNumber": "STU2025010",
      "name": "Alice Johnson",
      "rollNumber": 1
    },
    {
      "studentID": 11,
      "studentIDNumber": "STU2025011",
      "name": "Bob Smith",
      "rollNumber": 2
    }
  ]
}
```

---

### 3.5 Timetable API

**Base:** `/api/timetable`

#### Get Class Timetable

```http
GET /api/timetable?action=getClass&classID={id}
```

**Response:**

```json
{
  "success": true,
  "classID": 8,
  "timetable": {
    "Monday": [
      {
        "period": 1,
        "startTime": "08:00",
        "endTime": "08:40",
        "subject": "Mathematics",
        "teacher": "Mr. Johnson",
        "room": "R-101"
      },
      {
        "period": 2,
        "startTime": "08:45",
        "endTime": "09:25",
        "subject": "Science",
        "teacher": "Dr. Williams",
        "room": "Lab-1"
      }
    ],
    "Tuesday": [...]
  }
}
```

---

### 3.6 Announcements API

**Base:** `/api/announcements`

#### Get Active Announcements

```http
GET /api/announcements?action=getActive&target={audience}
```

**Parameters:**

- `target` (optional): Filter by audience (All, Teachers, Students, Parents)

**Response:**

```json
{
  "success": true,
  "count": 5,
  "announcements": [
    {
      "announcementID": 10,
      "title": "Holiday Notice",
      "content": "School will be closed on November 25th...",
      "targetAudience": "All",
      "priority": "High",
      "publishDate": "2025-11-20 10:00:00",
      "createdBy": "Admin Office"
    }
  ]
}
```

---

#### Create Announcement

```http
POST /api/announcements?action=create
Content-Type: application/json
```

**Request Body:**

```json
{
  "title": "Sports Day Event",
  "content": "Annual sports day will be held on...",
  "targetAudience": "All",
  "priority": "Normal",
  "expiryDate": "2025-12-01"
}
```

**Response:**

```json
{
  "success": true,
  "message": "Announcement created successfully",
  "announcementID": 15
}
```

---

## 4. Request/Response Format {#request-response-format}

### Standard Success Response

```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": {
    /* actual data */
  }
}
```

### Standard Error Response

```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field1": "Error message for field1",
    "field2": "Error message for field2"
  }
}
```

### HTTP Status Codes

| Code | Meaning               | Usage                            |
| ---- | --------------------- | -------------------------------- |
| 200  | OK                    | Successful request               |
| 201  | Created               | Resource created successfully    |
| 400  | Bad Request           | Invalid input/missing parameters |
| 401  | Unauthorized          | Not logged in                    |
| 403  | Forbidden             | Insufficient permissions         |
| 404  | Not Found             | Resource doesn't exist           |
| 500  | Internal Server Error | Server-side error                |

---

## 5. Error Handling {#error-handling}

### Validation Errors

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": "Invalid email format",
    "phone": "Phone number is required",
    "dateOfBirth": "Invalid date format"
  }
}
```

### Authentication Error

```json
{
  "success": false,
  "message": "Unauthorized - Please login"
}
```

### Not Found Error

```json
{
  "success": false,
  "message": "User not found"
}
```

### Server Error

```json
{
  "success": false,
  "message": "An error occurred while processing your request"
}
```

---

## 6. Code Examples {#code-examples}

### JavaScript (Fetch API)

#### GET Request

```javascript
// Get user by ID
async function getUser(userId) {
  try {
    const response = await fetch(`/api/users?action=get&id=${userId}`);

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    if (data.success) {
      console.log("User:", data.data);
      return data.data;
    } else {
      console.error("Error:", data.message);
      return null;
    }
  } catch (error) {
    console.error("Fetch error:", error);
    return null;
  }
}

// Usage
const user = await getUser(123);
```

---

#### POST Request

```javascript
// Update user
async function updateUser(userData) {
  try {
    const response = await fetch("/api/users?action=update", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(userData),
    });

    const data = await response.json();

    if (data.success) {
      alert("User updated successfully!");
      return true;
    } else {
      alert("Error: " + data.message);
      return false;
    }
  } catch (error) {
    console.error("Error:", error);
    alert("An error occurred while updating the user");
    return false;
  }
}

// Usage
const result = await updateUser({
  userID: 123,
  firstName: "John",
  lastName: "Doe",
  email: "john@example.com",
  phone: "0712345678",
});
```

---

#### Search Request

```javascript
// Search users
async function searchUsers(query) {
  try {
    const response = await fetch(
      `/api/users?action=search&q=${encodeURIComponent(query)}`
    );

    const data = await response.json();

    if (data.success) {
      console.log(`Found ${data.count} users`);
      return data.users;
    } else {
      console.error("Search failed:", data.message);
      return [];
    }
  } catch (error) {
    console.error("Search error:", error);
    return [];
  }
}

// Usage
const users = await searchUsers("john");
console.table(users);
```

---

### jQuery

```javascript
// GET request with jQuery
$.ajax({
  url: "/api/users",
  method: "GET",
  data: {
    action: "get",
    id: 123,
  },
  dataType: "json",
  success: function (response) {
    if (response.success) {
      console.log("User:", response.data);
    }
  },
  error: function (xhr, status, error) {
    console.error("Error:", error);
  },
});

// POST request with jQuery
$.ajax({
  url: "/api/users?action=update",
  method: "POST",
  contentType: "application/json",
  data: JSON.stringify({
    userID: 123,
    firstName: "John",
    lastName: "Doe",
  }),
  dataType: "json",
  success: function (response) {
    if (response.success) {
      alert("User updated!");
    }
  },
  error: function (xhr, status, error) {
    console.error("Error:", error);
  },
});
```

---

### PHP (cURL)

```php
<?php
// GET request
function getUser($userId) {
    $url = "http://localhost:8083/api/users?action=get&id=" . $userId;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id());

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        return json_decode($response, true);
    }

    return null;
}

// POST request
function updateUser($userData) {
    $url = "http://localhost:8083/api/users?action=update";
    $jsonData = json_encode($userData);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ]);
    curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id());

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Usage
$user = getUser(123);
print_r($user);

$result = updateUser([
    'userID' => 123,
    'firstName' => 'John',
    'lastName' => 'Doe'
]);
print_r($result);
?>
```

---

### Python (requests)

```python
import requests

# Base URL
BASE_URL = "http://localhost:8083/api"

# Create session to maintain cookies
session = requests.Session()

# Login first (to get session cookie)
login_response = session.post(
    "http://localhost:8083/login",
    data={"email": "user@example.com", "password": "password"}
)

# GET request
def get_user(user_id):
    response = session.get(
        f"{BASE_URL}/users",
        params={"action": "get", "id": user_id}
    )

    if response.status_code == 200:
        data = response.json()
        if data['success']:
            return data['data']

    return None

# POST request
def update_user(user_data):
    response = session.post(
        f"{BASE_URL}/users?action=update",
        json=user_data,
        headers={"Content-Type": "application/json"}
    )

    if response.status_code == 200:
        return response.json()

    return None

# Usage
user = get_user(123)
print(user)

result = update_user({
    "userID": 123,
    "firstName": "John",
    "lastName": "Doe",
    "email": "john@example.com"
})
print(result)
```

---

### cURL (Command Line)

```bash
# GET request
curl -X GET "http://localhost:8083/api/users?action=get&id=123" \
  -H "Cookie: PHPSESSID=your-session-id" \
  -H "Content-Type: application/json"

# POST request
curl -X POST "http://localhost:8083/api/users?action=update" \
  -H "Cookie: PHPSESSID=your-session-id" \
  -H "Content-Type: application/json" \
  -d '{
    "userID": 123,
    "firstName": "John",
    "lastName": "Doe",
    "email": "john@example.com"
  }'

# Search request
curl -X GET "http://localhost:8083/api/users?action=search&q=john" \
  -H "Cookie: PHPSESSID=your-session-id"

# Delete request
curl -X POST "http://localhost:8083/api/users?action=delete" \
  -H "Cookie: PHPSESSID=your-session-id" \
  -H "Content-Type: application/json" \
  -d '{"userID": 123}'
```

---

## Rate Limiting

Currently, there is no rate limiting implemented. However, it's recommended to:

- Avoid making excessive requests in short periods
- Implement client-side caching where appropriate
- Use batch operations when available

## API Best Practices

1. **Always check `success` field** in response
2. **Handle errors gracefully** with try-catch blocks
3. **Validate input** before sending requests
4. **Use appropriate HTTP methods** (GET for retrieval, POST for mutations)
5. **Include Content-Type header** for POST requests
6. **Log errors** for debugging
7. **Cache responses** when appropriate
8. **Use HTTPS** in production

---

**Last Updated:** November 21, 2025
**Version:** 1.0.0
