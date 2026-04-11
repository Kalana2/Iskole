# IskolE

## The School Management System

## INTRIM REPORT

## CS Group: 38

### Details of Project Supervisor and Co-Supervisor

**_Proposed Project Supervisor (Academic staff of UCSC)_**
_Name of the supervisor: Dr. Viraj Welgama
Signature of the supervisor:
Date:_
**_Proposed Project Co-Supervisor (Assigned by the course coordinator)_**
_Name of the co-supervisor: Mr. Janitha Disssanayake
Signature of the co-supervisor:
Date:_

##### 21.10.

## 1. Introduction

IskolE is a web‑based School Management System (SMS) designed to streamline academic,
administrative and communication workflows for Sri Lankan schools. It centralizes core activities
such as marks/grade management, attendance & leave, timetabling, announcements, learning
materials, and performance analytics while enforcing role‑based access for Administrators,
Management, Teachers, Parents and Students.
This report consolidates the proposal into a complete software engineering document covering
feasibility, requirements, architecture (using the MVC pattern), progress to date, and the plan for
completion.

### 1.1 Domain Description

The domain is K–12 school operations: academic record keeping; parent–teacher–student
communication; attendance and leave handling; class, exam and annual academic scheduling;
and dissemination of learning materials and announcements. The system’s stakeholders
interact via a secure web application with role‑scoped permissions.

### 1.2 Current System and Limitations

Many schools still rely on paper logs or ad‑hoc spreadsheets and verbal/noticeboard
communication. This leads to data entry errors, poor transparency of performance and
attendance, delayed reporting, manual compilation overhead for administrators, and fragmented
communication channels.

### 1.3 Goals & Objectives

- Improve communication via structured announcements and dashboards.
- Provide real‑time access to marks, ranks, and progress analytics for authorized users.
- Centralize documents and learning materials.
- Automate timetables and event scheduling with reminders.
- Support behavior/remarks logs and comprehensive student profiles.
- Enforce secure, role‑based access and privacy.
- Reduce administrative overhead through automation and reporting.

### 1.4 Assumptions

- Users have internet access and valid emails.
- One role per account; unique username/email; role‑scoped data access.
- Password complexity rules are enforced (≥8 chars with letters, numbers, symbols).
- Institutional adoption provides the required master data (classes, subjects, user rosters).

## 2. Feasibility Study

### 2.1 Technical Feasibility

In this section we measure how feasible our solution is in a real world scenario using selected
technologies and methods.
**Front-End Technologies**
● HTML, CSS, and JavaScript: These technologies will be used to create a responsive and
interactive user interface.
**Back-End Technologies**
● PHP: PHP will be used for handling server-side logic, managing user sessions,server
side requests and interacting with the MySQL datase.
● Docker **:** Docker will be used as the server environment to containerize our application.
This allows for easy deployment, scalability, and consistency across development and
production environments.
**Database Management**
● MySQL : MySQL will be used to manage databases and SQL queries will execute
viaPHP from the backend.
**Version Control and collaboration**
● Git/GitHub : We planned to use the git version control system and github to make it
easier for every group member to contribute.
● Jira : We will use Jira to collaborate with team members, allowing us to efficiently
schedule tasks and plan the development of our web solution
Most of the technologies mentioned above are freely available or open source. The team plans
to gain further knowledge and become more familiar with these technologies before starting the
deployment phase. We will use the free version of Jira for task management and team
collaboration.

### 2.2 Schedule Feasibility

Schedule feasibility focuses on whether the project can be successfully completed within the
given timeframe, using the available resources and technologies.
**Project Timeline:**
This project spans a full academic year, with development distributed across the first and
second semesters.
**Deliverables:**
An interim presentation will be delivered after the first semester examinations,showcasing all
finalized and implemented user interfaces of the system. The complete system will be
developed and finalized for submission after the second semester examinations.
**Resource Availability:**
All team members will be working on this project alongside their regular academic
responsibilities during both semesters. Time has been planned to ensure consistent progress
while balancing academic workload.
**Tools Used for Planning:**
We will use Jira to schedule tasks, assign responsibilities, and track progress throughout the
project lifecycle. This will help the team stay on track and meet all major deadlines.

### 2.3 Resource Feasibility

**Human Resources:**
The project team consists of dedicated members who will work collaboratively throughout the
first and second semesters. Each member will contribute their time and effort alongside
academic responsibilities, ensuring steady progress during the academic year.
**Technical Resources:**
Development and testing will be carried out using the team’s personal computers, which are
adequately equipped for the project's needs.
**Knowledge and Skills:**
The team has already gained foundational knowledge of relevant technologies during the first
year of study. We are continuing to expand our understanding and practical skills as the project
progresses, ensuring we are well-prepared for implementation and deployment phases.

### 2.3 Operational Feasibility

Focuses on efficiently managing resources, optimizing workflows, mitigating risks, and
maintaining accurate documentation to streamline project execution.
**Resource Allocation:**
Efficiently managing resources such as time, budget and Equipment.
**Workflow Management:**
Streamlining processes to maximize productivity.
**Risk Management:**
Identifying and reducing risks that could impact project success.
**Documentation and Reporting:**
Ensuring accurate and timely documentation of project progress and outcomes.

### 2.4 Economic Feasibility

Manages project costs within budget constraints, ensures financial sustainability and return on
investment, and conducts cost-benefit analyses to justify project expenditures.
**Cost Management:** Controlling project expenses within planned budgets.
**Financial Sustainability:** Ensuring long-term financial possibility and funding for the project.
**Return on Investment:** estimating the benefits and value generated by the project.

### 2.5 Legal & Ethical Feasibility

This section ensures that the project strictly adheres to school policies, ethical guidelines, and
relevant legal regulations, maintaining integrity throughout the project lifecycle by addressing
key areas:

**Compliance:**
The project will comply with all applicable school policies, ethical standards, and legal
requirements related to software development and data management.
**Intellectual Property:**
Managing ownership and rights related to project outcomes and innovations.
**Data Protection:**
Sensitive data collected or generated during the project, such as student information and login
credentials, will be securely handled to protect privacy and prevent unauthorized Access.
**Research Ethics:**
Upholding ethical standards in data collection, analysis, and reporting. Conflicts of Interest:
Managing conflicts of interest that may arise among project stakeholders.
**Transparency:**
Maintaining transparency in decision-making processes and outcomes.

## 3. Requirements

### 3.1 Stakeholders / Actors

- Administrator
- Management Panel
- Teacher
- Parent
- Student

### 3.2 Functional Requirements

#### 3.2.1 Parent

**1 Exam and Report Management**
● The system allows teachers to input student exam marks after each exam.
● Upon mark entry, the system automatically:
○ Calculates total marks.
○ Calculates average marks.
○ Determines student rank.
● The system generates a comprehensive student report.
● Only authorized users (teachers, respective students, their parents, and management) can
view the student reports. Unauthorized users are restricted from accessing these reports.
**2 Attendance and Absence Management**
● Parents can submit pre-absence requests to inform teachers in advance about a child's
absence.
● The submitted absence requests are visible to the relevant class or subject teacher.
● Parents can view their child’s complete attendance history, including present, absent,and
excused days.
**4 Announcement Viewing**
● Teachers and the management panel can publish announcements through the system.
● Users can view these announcements in the announcement preview panel.

● Announcements may include exam notifications, school events, holiday notices, and other
important messages
**5 Subject Teacher Information**
● Parents can view a list of teachers responsible for each subject their child studies.
● The system displays relevant contact information (e.g., name, email, phone) for each subject
teacher.
**6 User Authentication**
● Parents can log into the system using secure credentials.
● These credentials are generated and distributed by the management panel.
● Unauthorized access is restricted through role-based access control
**7 Student Behavior Records**
● Teachers can log behavioral notes and observations about students into the system.
● Parents can view these behavior records via their portal.

#### 3.2.2 Administrator

**1 CRUD – Management Staff**
● Allows the admin to Create, Read, Update, and Delete records of the principal, vice
principal, and other management staff.
● Admin logs into the system.
● Navigates to the management panel.
● Performs the desired CRUD operations on staff records.
● Management staff records are updated in the system.
**2 CRUD – Teachers**
● Enables the admin to manage teacher profiles by performing CRUD operations.
● Admin logs in and accesses the teacher management section.
● Performs Create, Read, Update, or Delete operations as needed.
● The teacher database is updated accordingly.
**3 CRUD – Students**

● Allows the admin or authorized class teacher to manage student records.
● Authorized user selects the student section.
● Performs CRUD operations (add, view, update, delete).
● Student information is updated and maintained in the system database.
**4 CRUD – Parents**
● Enables the admin or teacher to manage parent/guardian information linked to
students.
● Authorized user accesses the parent section.
● Executes Create, Read, Update, or Delete actions as needed.
● Parent data is accurately stored and kept up to date in the system.
**5 Define User Roles and Permissions**
● Admin defines and manages roles (e.g., student, teacher, parent) and assigns
appropriate
permissions.
● Admin accesses the Role Management module.
● Creates new roles or updates existing roles.
● Assigns permissions to users based on their responsibilities.
● Role-based access control is applied throughout the platform

#### 3.2.2 Students

**1. View Marks**
● The system shall allow students to view their subject-wise marks.
● The system shall display final examination marks.
● The system shall provide an option to download or print the marks
**2. View Timetable**
● The system shall allow students to view their daily and weekly timetables.
● The system shall update timetable changes in real-time.
**3. Check Academic Progress**
● The system shall enable students to view their academic progress.
**4. View Announcements**
● The system shall allow students to view announcements posted by school
administration and teachers.
**5. View Academic Calendar**
● The system shall provide access to an up-to-date academic calendar.
● The system shall highlight key dates such as exams, holidays, and parent-teacher
meetings

**6. View Absent Reason**
● The system shall allow parents/students to view the status of their submitted absence
reasons.
**7 Login**
● The system shall require valid login credentials (username and password) to access
the
portal.
● The system shall authenticate user credentials against the database.
**8. View Result Report**
● The system shall allow students to view detailed result reports including grades and
rank.
● The system shall allow students to download result reports as PDFs.
**9. View Attendance**
● The system shall allow students to view their attendance records.
● The system shall calculate and display the attendance percentage.

#### 3.2.2 Management Panel

#### 1 User Login

● User Authentication
The system shall allow management panel users to log in using a valid username
and password.
● Credential Verification
The system shall validate the entered credentials against stored user data in the
database.
● Access Control
The system shall grant access to the management panel dashboard only if the
credentials are correct.
●Error Handling for Invalid Login
The system shall display an appropriate error message if the credentials are
invalid (e.g.,"Invalid username or password").
**2 CRUD – Student Records**
● The management panel user can search for a specific student and edit their records,
including personal details, grades, and attendance.
● The primary actor (management panel user) logs into the system using valid
credentials.

● The user initiates a search by entering identifying information (student ID or name).
● The system displays a list of matching student records and the user selects a student
and modifies preferred fields.
● The user submits the changes.
● The system validates the input data (e.g., data type, required fields).
● The student’s record is updated in the database with the validated changes, and the
user is notified of the successful update.
**3 CRUD – Parent Records**
● Primary actors log into the system, navigate to a student’s profile, and view the
parent’s contact details and relevant information linked to that student.
● The primary actor logs into the system using valid credentials.The system
authenticates the user and grants access to the management dashboard.
● The user searches for and selects a specific student’s profile.
● The user selects the option to view the parent or guardian information associated with
that student.
● The system retrieves the parent’s contact details and other relevant information from
the database.
● The system displays the parent information clearly on the screen for the user.
● The system successfully displays accurate and complete parent/guardian information
linked to the selected student, enabling the user to view or verify contact and related
Details
**4 CRUD – Staff Records**
● A management panel user logs into the system, accesses staff records, reviews
details, and updates staff information such as name, role, contact info, and employment
history.
● The user logs into the system with valid credentials.

● The system authenticates the user and grants access to the management dashboard.
● The user navigates to the staff records section.
● The system retrieves and displays a list of staff members with details including name,
role, contact information.
● The user selects a staff member to review or edit their information.
● The system displays the selected staff member’s full record with editable fields.
● The user modifies one or more fields as needed.
● The user submits the updated information.
● The system validates the input data for correctness and completeness.
● Upon successful validation, the system updates the staff record in the database.
The system confirms the update by displaying a success message to the user.
● The staff member’s record is accurately updated in the database, and the user
receives confirmation of the successful change.
**5 Broadcast announcements**
● User Authentication
The system shall allow users with Management Panel or Administrator roles to log in
using valid credentials.
● Access Announcement Module
The system shall provide authenticated users access to the announcement
module/dashboard.
● Create Announcement
The system shall allow the user to create a new announcement by entering the following
details:
● Announcement title (mandatory)
● Message content (mandatory)
● Target audience selection (e.g., students, staff, parents) (mandatory)
● Input Validation

The system shall validate all announcement input fields for completeness and
correctness before saving.
● Save Announcement
The system shall save the announcement details to the database upon successful
validation.
● Broadcast Announcement
The system shall broadcast the announcement to all users specified in the target
audience
across the platform.
● User Notification
The system shall notify the user who created the announcement with a confirmation
message indicating successful creation and broadcasting.
● Display Announcement
The system shall display the newly created announcement in the announcement module
list for future reference and review.
Monitor Attendance
● The user logs into the system and opens the attendance module.
● Then select a student or teacher from the list, and the system retrieves and
displays
detailed attendance records, showing dates and attendance status (e.g., present,
absent) for review.
**8 Leaving requests**
● User logs in as a Management Panel member.
● System displays all leave requests submitted by teachers.
● Leave request details shown include:
○ Requester name
○ Duration (from/to dates)
○ Current status (Pending, Approved, Rejected)
● User reviews requests for potential further action.
**9 View Performance trends**
● User logs in as a Management Panel member.
● User navigates to the academic performance module.

```
● User selects a student or group of students from the list.
● System retrieves academic records, including:
○ Grades
○ Exam results
○ Progress reports
● System displays the retrieved data for analysis and monitoring.
```

#### 3.2.2 Teacher

**1. Teacher Authentication**
● The system shall provide a login interface for teachers.
● The system shall verify teacher credentials (username and password).
● The system shall grant access to authorized features upon successful login.
**2. View Student Reports**
● The system shall allow teachers to select a student from their class list.
● The system shall display student reports including:
○ Academic marks
○ Attendance records
○ Teacher remarks
**3. Post Announcements**
● The system shall allow teachers to post announcements to students and/or
parents.
● The system shall provide a module for teachers to type and submit
announcements.
● The system shall publish the announcement to the intended audience.
**4. Submit Leave Requests**
● The system shall provide a leave application form for teachers.
● The system shall allow teachers to submit leave requests to management.
**5. Track Leave Request Status**
● The system shall allow teachers to check the approval status of their leave
requests.
● The system shall display the current status: pending, approved, or rejected.
**6. Manage Announcements**
● The system shall allow teachers to edit or delete previously posted
announcements.

**7. Add Student Remarks**
● The system shall allow teachers to select a student and add remarks to their
record.
● The system shall store and display the submitted remarks appropriately.
**8. Submit Daily Teaching Activities**
● The system shall allow teachers to enter and submit daily lesson plans or
activities.
● The system shall link each submission with a specific date and class.
**9. Upload Learning Materials for Absent Students**
● The system shall allow teachers to select students who were marked absent.
● The system shall allow uploading of worksheets, notes, or learning content.
● The system shall make uploaded materials available to the selected student or
their parent.
**10. Manage Student Marks**
● The system shall allow teachers to add or update student marks for
assessments.
● The system shall validate the entered marks before saving.
● The system shall ensure that each student can only view their own marks, not
others.

### 3.3 Non ‑ functional Requirements

**Security**
● The system will implement **password hashing** to protect user credentials.
● **Role-Based Access Control (RBAC)** will be applied to restrict access based on user
roles and permissions.
● **Session timeouts** will be configured to automatically log out inactive users and prevent
unauthorized access.
**Reliability**

● The system will ensure **daily database backups** to maintain data integrity and
continuity.
● The **Recovery Point Objective (RPO)** will be maintained at **≤ 24 hours** , ensuring that
data loss in case of failure does not exceed one day.
**Performance**
● Typical **page load times** will be under **2 seconds** on standard broadband connections.
● System-generated **reports** will be produced in less than **5 seconds** for cohorts of up to
**100 students**.
**Usability**
● The user interface will maintain **consistent navigation** and follow **WCAG-inspired**
accessibility standards for appropriate contrast, labels, and semantics.
● The system will be intuitive and easy to use for all user roles, including students,
teachers, and administrators.
**Maintainability**
● The system will follow the **Model-View-Controller (MVC)** architectural pattern for clear
code separation.
● Code will be **readable and well-documented** , with **unit and integration tests** ensuring
software quality.
● A **Continuous Integration (CI)** process will be adopted to automate testing and improve
development efficiency.
**Portability**
● The application will be **deployable on a standard LAMP stack (Linux, Apache,
MySQL, PHP)**.
● Containerization using **Docker** will further enhance portability across different
environments.

### 3.4 In ‑ scope vs Out ‑ of ‑ scope

**In-Scope**
The following features and modules are included within the scope of the initial system
development:
● **Academic Records Management:** Handling student profiles, grades, and academic
history.
● **Attendance and Leave Management:** Recording student and staff attendance, and
managing leave requests and approvals.
● **Announcements:** Providing a centralized platform for school-wide communications and
updates.
● **Learning Materials:** Uploading and accessing course content, assignments, and
educational resources.
● **Role-Based Access Control (RBAC):** Ensuring secure access based on user roles
such as administrators, teachers, students, and parents.
● **Reporting and Analytics:** Generating reports on attendance and performance.
**Out-of-Scope**
The following functionalities are not included in the first phase of development but may be
considered for future releases:
● **Payroll and Finance Management**
● **Library and Inventory Systems**
● **Transport Management**
● **Health and Medical Records**
● **Non-Academic Staff Modules**

### 3.5 Constraints & Limitations

**System Constraints**
● Each user is allowed to maintain **only one account** , and each account is assigned **a
single role** (e.g., student, teacher, parent, administrator, or management panel).
● All users are required to possess a **valid and unique email address** for account
creation, verification, and communication.
● The **server and database** are expected to remain available during normal operations,
with minimal downtime.
● The **fingerprint attendance module** is a **simulated feature** for demonstration
purposes. It is assumed that the fingerprint input correctly maps to the corresponding
UserID.
● **Phone number as initial password:** User's phone number is used as the default
password for new accounts
● **NIC format:** Either 12-digit numeric or 10-digit + V/W format
● **Fixed grade range:** Grades 6-9 or 1-11 depending on context
● **One class per student:** Students belong to exactly one class
● **Student must exist first:** Parents can only be added if student already exists in system
● **NIC required:** All teachers, managers and parents must have a National Identity Card
number
● **One class responsibility:** Teachers are assigned to one primary grade and class.
● **Subject assignment:** Each teacher has one primary subject they teach.
● **File types:** Supports PDF, DOC, PPT.
● **Class-based tracking:** Attendance marked for entire class at once.

● The system **does not include an automated notification system** (such as email, SMS,
or push notifications) in this phase.In future deployment stages, **email or SMS-based
notifications** can be added once the project moves to a **scalable, funded
environment**.
● **One-way communication:** Announcements are broadcast only(no replies/comments)
● **No multi-school support:** System assumes single school deployment
● **Class options:** Hardcoded as A, B in various form for demonstration we can extend
later.
● The system is currently deployed and tested on the **demo environment** at
https://iskole.ct.ws/

## 4. Proposed System Architecture (MVC)

### 4.1 Components & Responsibilities

#### 1. Model (M)

**Role:** Handles **data and business logic**.
**Responsibilities:**
● Interacts with the **database** (e.g., fetch, insert, update, delete records).
● Defines **data structures** (like Students, Teachers, Attendance, etc.).
● Implements **application logic** (e.g., calculating averages, rankings, attendance
percentages).
● Notifies the Controller when data changes.
**Example in IskolE:**
The _Student_ model fetches marks and attendance from the database and calculates averages
or ranks.

#### 2. View (V)

**Role:** Manages the **user interface (UI)** and **presentation layer**.
**Responsibilities:**
● Displays data from the Model to the user in a readable format (HTML, CSS).
● Provides forms and interfaces for user input.
● Contains **no business logic** — only handles what the user sees.
**Example in IskolE:**
The _Student Dashboard_ or _Announcement Page_ showing marks, attendance, and updates.

#### 3. Controller (C)

**Role:** Acts as a **bridge** between the Model and the View.
**Responsibilities:**
● Receives input from the user (e.g., form submission, button click).
● Processes the input and requests the appropriate data from the Model.
● Selects which View to display based on the result.
● Controls the overall **flow of data and user navigation**.
**Example in IskolE:**
When a teacher submits marks, the controller validates the input, updates the Model
(database), and refreshes the View (student’s mark page).

### 4.2 Key Modules & Interactions

```
Module Main Interactions / Purpose
Authentication
Module
Handles user login, logout, and session management. Interacts
with User Model and Role Controller.
Student Management
Module
Stores student profiles, academic data, and attendance records.
Interacts with Teacher and Management modules.
Teacher Management
Module
Manages teacher profiles, schedules, and subject assignments.
Communicates with Admin and Class modules.
Attendance Module Takes input (manual or simulated fingerprint), validates with
Student Model, and updates attendance tables.
Marks & Performance
Module
Allows teachers to enter marks; system calculates averages and
ranks. Used by Students, Parents, and Management modules.
```

**Announcement
Module**
Enables teachers or management to post announcements;
students and parents view them via dashboard.
**Timetable & Calendar
Module**
Displays schedules and events; data managed by Management
panel.
**Behavior & Remarks
Module**
Teachers record student behavior; visible to parents and
management.
**Admin Module** Handles role creation, CRUD operations, and overall system
settings.

### 4.3 Reference Diagrams

**4.3.1 ER Diagram**
·

**Core identity**
o **USER** is the hub table (userId, gender, email/phone, DoB, name, username,
password, active).
o **USER_NAME** and **USER_ADDRESS** hold optional profile details split out
from USER.
o **LOGIN_CREDENTIALS** stores the login email + password hash (for
authentication).
· **Roles & access**
o **ROLE** lists available roles (Admin, MP, Teacher, Parent, Student).
o **USER_ROLES** maps users ↔ roles (many-to-many), enabling RBAC.
· **School community (specializations of USER)**
o **STUDENT** (studentId, userId, classId, grade) links a user to a class.
o **PARENT** (parentId, userId, relationship, studentId, NIC) ties a parent user to a
specific student.
o **TEACHER** (teacherId, userId, subjectId, classId) ties a teacher user to the
subjects/classes they cover.
· **Academic structure**
o **SCHOOL_CLASS** (classId, grade, class) defines classes/year groups.
o **SUBJECT** (subjectId, subjectName) lists subjects and is assigned to
teachers.
· **Learning resources**
o **MATERIAL** (materialId, teacherId, classId, subjectId, title, description, file,
visibility, timestamps) are files uploaded by teachers for a class/subject.
· **Communication & events**
o **ANNOUNCEMENT** (announcementId, title, content, publishedBy → USER,
targetAudience, createdAt) are notices targeted to groups.

o **EVENT** (eventId, targetRole, createdBy, title, message, eventDate,
publishedDate) captures scheduled items/activities and can be aimed at a
role.
· **Attendance/leave**
o **LEAVE_REQUEST** (leaveId, studentId, details, reason, from_date, to_date,
status) records student leave requests; parents are associated to the student
who submits.
**Key relationships (at a glance)**
· USER ↔ USER_ROLES ↔ ROLE (many-to-many).
· USER → STUDENT / TEACHER / PARENT (one user specializes into exactly one of
these, depending on role).
· STUDENT → SCHOOL_CLASS (many students per class).
· TEACHER → SUBJECT and TEACHER → SCHOOL_CLASS (teachers cover
subjects/classes).
· MATERIAL → TEACHER, SUBJECT, CLASS (materials are owned by a teacher and
scoped to a subject/class).
· PARENT → STUDENT (each parent is linked to their child).
· ANNOUNCEMENT → USER (publishedBy), often targeted by role.
· EVENT → USER (createdBy) and → targetRole (who it’s for).
· LEAVE_REQUEST → STUDENT (who requests the leave).
Overall, it’s a **role-based school management schema** centered on USER, with clean

##### separations for academic structure, resources, communications, and leave workflows.

##### 4.3.2 Activity Diagram

_Figure 2: Activity/Workflow Overview_

##### · The flow starts with a user reaching the system and logging in.

##### · A decision splits the path by role → Administrator , Teacher , Student , Parent , or

##### Management Panel.

##### · Each role lands on its panel and performs its typical actions:

##### o Administrator: manage users/roles/permissions and publish system-wide

##### announcements.

##### o Teacher: upload learning materials, enter marks/daily work, and post class

##### notices.

##### o Student: view timetable, announcements, results/progress, and

##### access/download materials.

##### o Parent: view child’s timetable, results and attendance/behaviour notes;

##### submit/track leave reasons.

##### o Management Panel: monitor staff & student records, review/approve requests,

##### schedule events/exams/relief, and see academic summaries.

##### · Actions loop back to the panel (or logout), and the navigation bar lets users move

##### across their permitted features.

**1 Administrator**

**2 Management Pannel**

**3 Teacher**

**_4 Student_**

**5 Parent**

**6** **_Sign-in (Login)_**

**7 Sign-out (Logout)**

**4.3.3 Class Diagram**
_Figure 3: Class & Controller Relationships (MVC)_
**_Pattern:_** _MVC.
·_ **_Controllers (top row):_** _classes such as UserController, SearchUserController,
GetUsersApiController, DeleteUserController, DeactivateUserController,
ActivateUserController, LoginController, ViewUserController, AddNoticeController,
EditUserController, StudentController, MaterialController, etc. Each exposes actions
(create/update/delete/fetch) and orchestrates requests, validation, and authorization.
·_ **_Models (bottom/side blocks):_** _UserModel, LoginModel, StudentModel,
TeacherModel, ParentModel, MaterialModel, MPModel (Management Panel),
LeaveReqModel, PasswordModel, etc. These encapsulate DB access (e.g.,
getUserById(), saveMaterial(), approveLeave()), business rules, and data mapping.
·_ **_Entities (center):_** _User is the core domain class (id, name, email/username, role,
status, login methods). Actor classes—Administrator, ManagementPanel, Teacher,_

_Student, Parent—are linked to User (specialization/association) to represent
role-specific behavior and data._
**_Key interactions:_**

_1._ **_Controllers_** _receive HTTP requests → call the relevant_ **_Model_** _methods. 2._ **_Models_** _query/update the database and return domain objects (User, Student,_
_Material, LeaveRequest, etc.). 3. Results are rendered to_ **_Views_** _(not pictured) based on the user’s_ **_role_**_._
**_Feature groupings visible:_**
_·_ **_User & Access:_** _creation, search, edit, activate/deactivate, password reset, and
login.
·_ **_Academics & Content:_** _teacher uploads (materials/worksheets), student data
retrieval, report viewing.
·_ **_Operations:_** _management-panel functions (staff/student oversight),
announcements/notices.
·_ **_Attendance/Leave:_** _leave request controller/model connecting students/parents with
approvals._

**4.3.4 Use case Diagram**
**_1 Parent_**

**_2 Student_**

**_3 Administrator_**

**4 Management Pannel**

**5 Teacher**

## 5. Current Progress

### 5.1 Against Requirements

\_- UI/UX: initial design implemented across primary dashboards and forms.

- CRUD: four core CRUD flows implemented (e.g., users/teachers/students/parents or
  equivalent entity set).
- Authentication : base login and role checks scaffolded.\_

### 5.2 Estimated Completion

_Given the requirement breadth, the system is approximately 35–40% complete: UI scaffolding +
4 CRUDs + base auth. Back_ ‑ _end business logic, analytics, and advanced modules remain._

### 5.3 Remaining Tasks

\_- Graph generation (progress trends, rank distributions, attendance summaries).

- Additional CRUD operations (materials, events/timetables, announcements with targeting,
  classes/subjects).
- Attendance management workflow (daily entry, validation, reports).
- Relief operation (teacher substitution assignments with notifications).
- Leave management end* ‑ \_to* ‑ \_end (submission → approval → audit).
- Document/material uploads for absent students.
- Role\_ ‑ \_targeted notifications & email templates.
- Testing (unit, integration, UAT) and deployment hardening.\_

### 5.4 Work Allocation & Contributions

\_- Admin (shared): all members contribute to administration features and global configuration.

- Management Panel – Kalana: scheduling (events/exams/annual calendar), staff CRUD,
  attendance monitoring, relief management.
- Teacher – Seniru: marks management, announcements, remarks, leave submission,
  materials/worksheets.
- Parent – Thasindu: pre\_ ‑ \_absence submissions, announcement & timetable views, contact info
  access, behavior acknowledgment.
- Student – Aditha: marks/results/attendance/progress dashboards, calendar and
  announcements.\_

## 6. Testing Strategy

_Unit tests for controllers and model utilities; integration tests for critical flows (auth, RBAC,
CRUD, marks calc); UAT checklists for each actor persona; performance smoke tests for
reporting endpoints; data migration/rollback tests._

## 7. Deployment & Security Considerations

_Deploy on hardened LAMP stack with HTTPS, secure cookie flags, CSRF tokens, prepared
statements/ORM, hashed passwords (bcrypt/argon2), rate_ ‑ _limiting on auth, and nightly DB
backups with offsite retention._

## 8. Delivery Roadmap

_Milestone M1 (UI & Base CRUD): Done.
Milestone M2 (Attendance/Leave + Scheduling): implement and test.
Milestone M3 (Graphs/Reports/Analytics): trend charts and cohort reports.
Milestone M4 (UAT & Hardening): finalize, fix, and prepare demo data.
Milestone M5 (Release & Handover): deployment docs, admin guide, and training material._

## Appendix A – Entity Catalogue

_User, Role, Student, Parent, Teacher, Class, Subject, Marks, Attendance, LeaveRequest,
Announcement, Material, Event, Timetable._

## Appendix B – Sample RBAC Matrix (excerpt)

_Students: READ self marks/attendance/timetable/announcements.
Parents: READ child marks/attendance/timetable; CREATE pre_ ‑ _absence reason.
Teachers: CREATE/UPDATE marks, remarks, announcements, materials; READ assigned
students.
Management: CRUD student/parent/staff; SCHEDULE events/exams; APPROVE leaves;
MONITOR attendance.
Administrator: global CRUD + role/permission and settings._

### Declaration

**_We as members of the project titled IskolE , Certify that we will carry out
this project according to the guidelines provided by the coordinators and
supervisors of the course as well as that we will not incorporate, without
acknowledgment, any material previously submitted for a degree or diploma in any
university. To the best of our knowledge and brief, the project work will not contain
any material previously published or written by another person or ourselves except
where due reference is made in the text of appropriate places_**
