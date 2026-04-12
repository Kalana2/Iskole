# IskolE

## The School Management System

## SECOND YEAR GROUP PROJECT REPORT

#### SCS2202 – Group Project

## CS Group: 38

#### Group Member Details

```
Index Number Full Name
23001879 Seniru D S Senaweera
23000821 R K K Jinendra
23000104 R. S. R. G. A. A. Ananda
23001542 S. K. Thasindu Ramsitha
```

#### Project Supervisor: Mr. Viraj Welgama

#### Project Co-Supervisor: Mr. Janitha Dissanayake

#### University of Colombo School of Computing

## Contents

- 1 Introduction
  - 1.1 Domain Description
  - 1.2 Current System and Limitations
  - 1.3 Goals & Objectives
  - 1.4 Assumptions
- 2 Feasibility Study
  - 2.1 Technical Feasibility
    - 2.1.1 Front-End Technologies
    - 2.1.2 Back-End Technologies
    - 2.1.3 Environmental Technologies
    - 2.1.4 Database Management
    - 2.1.5 Version Control and Collaboration
  - 2.2 Schedule Feasibility
    - 2.2.1 Project Timeline
    - 2.2.2 Deliverables
    - 2.2.3 Resource Availability
    - 2.2.4 Tools Used for Planning
  - 2.3 Resource Feasibility
    - 2.3.1 Human Resources
    - 2.3.2 Technical Resources
    - 2.3.3 Knowledge and Skills
  - 2.4 Operational Feasibility
  - 2.5 Economic Feasibility
  - 2.6 Legal & Ethical Feasibility
- 3 Requirements Specification
  - 3.1 Stakeholders / Actors
  - 3.2 Functional Requirements
    - 3.2.1 Parent
    - 3.2.2 Administrator
    - 3.2.3 Students
    - 3.2.4 Management Panel
    - 3.2.5 Teacher
  - 3.3 Non-functional Requirements
  - 3.4 In-Scope vs Out-of-Scope
    - 3.4.1 In-Scope
    - 3.4.2 Out-of-Scope
  - 3.5 Constraints & Limitations
- 4 System Architecture
  - 4.1 Components & Responsibilities
    - 4.1.1 Model (M)
    - 4.1.2 View (V)
    - 4.1.3 Controller (C)
  - 4.2 Key Modules & Interactions
- 5 System Design
  - 5.1 Use Case Diagram
  - 5.2 ER Diagram
  - 5.3 Class Diagram
  - 5.4 System Architecture Diagram
  - 5.5 Activity Diagrams
- 6 Completeness of the Project
  - 6.1 Functionalities Completed
    - 6.1.1 R K K Jinendra – Module Owner
    - 6.1.2 Seniru D S Senaweera – Module Owner
    - 6.1.3 S. K. Thasindu Ramsitha – Module Owner
    - 6.1.4 R. S. R. G. A. A. Ananda – Module Owner
  - 6.2 Functionalities Yet to Complete
- 7 Individual Contribution Analysis
- 8 Testing
  - 8.1 Testing Types
  - 8.2 Test Case Summary
- 9 Details of Project Supervisor and Co-Supervisor
- 10 Declaration
- 5.1 Administrator Use Case Diagram List of Figures
- 5.2 Management Panel Use Case Diagram
- 5.3 Parent Use Case Diagram
- 5.4 Student Use Case Diagram
- 5.5 Teacher Use Case Diagram
- 5.6 ER Diagram
- 5.7 Class Diagram
- 5.8 Admin Workflow
- 5.9 Manager Workflow
- 5.10 Parent Workflow
- 5.11 Student Workflow
- 5.12 Teacher Workflow
- 5.13 User Login and Password Reset Workflow
- 7.1 Individual Contribution of Team Members

# List of Tables

```
4.1 Key System Modules and Their Interactions................. 24
7.1 Individual Contribution Summary....................... 39
8.1 Testing Summary................................ 41
```

# Chapter 1

# Introduction

IskolE is a web-based School Management System (SMS) designed to streamline academic,
administrative, and communication workflows for Sri Lankan schools. It centralises core
activities such as marks/grade management, attendance & leave, timetabling, announce-
ments, learning materials, and performance analytics while enforcing role-based access for
Administrators, Management, Teachers, Parents, and Students.
This report consolidates the proposal into a complete software engineering document
covering feasibility, requirements, architecture (using the MVC pattern), system design,
implementation completeness, testing, and individual contributions.

### 1.1 Domain Description

The domain is school operations: academic record keeping; parent–teacher–student com-
munication; attendance and leave handling; class, exam, and annual academic scheduling;
and dissemination of learning materials and announcements. The system’s stakeholders
interact via a secure web application with role-scoped permissions.

### 1.2 Current System and Limitations

Many schools still rely on paper logs or ad-hoc spreadsheets and verbal/noticeboard com-
munication. This leads to:

- Data entry errors
- Poor transparency of performance and attendance
- Delayed reporting

- Manual compilation overhead for administrators
- Fragmented communication channels

### 1.3 Goals & Objectives

- Improve communication via structured announcements and dashboards.
- Provide real-time access to marks, ranks, and progress analytics for authorised users.
- Centralise documents and learning materials.
- Automate timetables and event scheduling with reminders.
- Support behaviour/remarks logs and comprehensive student profiles.
- Enforce secure, role-based access and privacy.
- Reduce administrative overhead through automation and reporting.

### 1.4 Assumptions

- Users have internet access and valid emails.
- One role per account; unique email; role-scoped data access.
- The school must provide the foundation data, and the system builds everything else
  on top of it.

# Chapter 2

# Feasibility Study

### 2.1 Technical Feasibility

In this section we measure how feasible our solution is in a real-world scenario using
selected technologies and methods.

#### 2.1.1 Front-End Technologies

- **HTML, CSS, and JavaScript:** These technologies are used to create a responsive
  and interactive user interface.

#### 2.1.2 Back-End Technologies

- **PHP:** PHP is used for handling server-side logic, managing user sessions, server-side
  requests, and interacting with the MySQL database.

#### 2.1.3 Environmental Technologies

- **Docker:** Docker is used for containerisation and deployment of the application.
  This allows for easy deployment, scalability, and consistency across development
  and production environments.

#### 2.1.4 Database Management

- **MySQL:** MySQL is used to manage databases; SQL queries execute via PHP from
  the backend.

#### 2.1.5 Version Control and Collaboration

- **Git/GitHub:** The Git version control system and GitHub are used to facilitate
  collaborative contributions from every group member.
- **Jira:** Jira is used for task management and team collaboration, allowing efficient
  scheduling and development planning.
  Most of the technologies mentioned above are freely available or open source. The team
  gained further knowledge and became more familiar with these technologies during the
  development phase. The free version of Jira was used for task management and team
  collaboration.

### 2.2 Schedule Feasibility

Schedule feasibility focuses on whether the project can be successfully completed within
the given timeframe, using the available resources and technologies.

#### 2.2.1 Project Timeline

This project spans a full academic year, with development distributed across the first and
second semesters.

#### 2.2.2 Deliverables

An interim presentation was delivered after the first semester examinations, showcasing
all finalised and implemented user interfaces of the system. The complete system was
developed and finalised for submission after the second semester examinations.

#### 2.2.3 Resource Availability

All team members worked on this project alongside their regular academic responsibilities
during both semesters. Time was planned to ensure consistent progress while balancing
academic workload.

#### 2.2.4 Tools Used for Planning

Jira was used to schedule tasks, assign responsibilities, and track progress throughout the
project lifecycle, helping the team stay on track and meet all major deadlines.

### 2.3 Resource Feasibility

#### 2.3.1 Human Resources

The project team consists of four dedicated members who worked collaboratively through-
out the first and second semesters. Each member contributed their time and effort along-
side academic responsibilities, ensuring steady progress during the academic year.

#### 2.3.2 Technical Resources

Development and testing were carried out using the team’s personal computers, which
are adequately equipped for the project’s needs.

#### 2.3.3 Knowledge and Skills

The team gained foundational knowledge of relevant technologies during the first year
of study and continued to expand understanding and practical skills as the project pro-
gressed, ensuring readiness for implementation and deployment phases.

### 2.4 Operational Feasibility

Focuses on efficiently managing resources, optimising workflows, mitigating risks, and
maintaining accurate documentation to streamline project execution.

- **Resource Allocation:** Efficiently managing resources such as time, budget, and
  equipment.
- **Workflow Management:** Streamlining processes to maximise productivity.
- **Risk Management:** Identifying and reducing risks that could impact project suc-
  cess.

- **Documentation and Reporting:** Ensuring accurate and timely documentation
  of project progress and outcomes.

### 2.5 Economic Feasibility

Manages project costs within budget constraints, ensures financial sustainability and re-
turn on investment, and conducts cost-benefit analyses to justify project expenditures.

- **Cost Management:** Controlling project expenses within planned budgets.
- **Financial Sustainability:** Ensuring long-term financial viability and funding for
  the project.
- **Return on Investment:** Estimating the benefits and value generated by the
  project.

### 2.6 Legal & Ethical Feasibility

This section ensures that the project strictly adheres to school policies, ethical guidelines,
and relevant legal regulations, maintaining integrity throughout the project lifecycle by
addressing key areas:

- **Compliance:** The project complies with all applicable school policies, ethical stan-
  dards, and legal requirements related to software development and data manage-
  ment.
- **Intellectual Property:** Managing ownership and rights related to project out-
  comes and innovations.
- **Data Protection:** Sensitive data collected or generated during the project, such
  as student information and login credentials, is securely handled to protect privacy
  and prevent unauthorised access.
- **Research Ethics:** Upholding ethical standards in data collection, analysis, and
  reporting.
- **Conflicts of Interest:** Managing conflicts of interest that may arise among project
  stakeholders.

- **Transparency:** Maintaining transparency in decision-making processes and out-
  comes.

# Chapter 3

# Requirements Specification

### 3.1 Stakeholders / Actors

The system identifies the following primary stakeholders:

1. Administrator
2. Management Panel
3. Teacher
4. Parent
5. Student

### 3.2 Functional Requirements

#### 3.2.1 Parent

**1. Exam and Report Management** - The system allows teachers to input student exam marks after each exam. - Upon mark entry, the system automatically:
**-** Calculates total marks.
**-** Calculates average marks.
**-** Determines student rank. - The system generates a comprehensive student report.

- Only authorised users (teachers, respective students, their parents, and manage-
  ment) can view the student reports. Unauthorised users are restricted from access-
  ing these reports.
  **2. Attendance and Absence Management**
- Parents can submit pre-absence requests to inform teachers in advance about a
  child’s absence.
- The submitted absence requests are visible to the relevant class or subject teacher.
- Parents can view their child’s complete attendance history, including present, ab-
  sent, and excused days.
  **3. Announcement Viewing**
- Admin, Teachers and the management panel can publish announcements through
  the system.
- Users can view these announcements in the announcement preview panel.
- Announcements may include exam details, school events, holiday notices, and other
  important messages.
  **4. Subject Teacher Information**
- Parents can view a list of teachers responsible for each subject their child studies.
- The system displays relevant contact information (e.g., name, email, phone) for each
  subject teacher.
  **5. User Authentication**
- Parents can log into the system using initial credentials.
- These credentials are generated and distributed by the management panel.
- Unauthorised access is restricted through role-based access control.
  **6. Student Behaviour Records**
- Teachers can log behavioural notes and observations about students into the system.
- Parents can view these behaviour records via their portal.

#### 3.2.2 Administrator

**1. CRUD – Management Staff** - Allows the admin to Create, Read, Update, and Delete records of the principal, vice
principal, and other management staff. - Admin logs into the system, navigates to the management panel, and performs the
desired CRUD operations on staff records. - Management staff records are updated in the system.
**2. CRUD – Teachers** - Enables the admin to manage teacher profiles by performing CRUD operations. - Admin logs in and accesses the teacher management section. - Performs Create, Read, Update, or Delete operations as needed. - The teacher database is updated accordingly.
**3. CRUD – Students** - Allows the admin or authorised class teacher to manage student records. - Authorised user selects the student section and performs CRUD operations (add,
view, update, delete). - Student information is updated and maintained in the system database.
**4. CRUD – Parents** - Enables the admin or teacher to manage parent/guardian information linked to
students. - Authorised user accesses the parent section and executes Create, Read, Update, or
Delete actions as needed. - Parent data is accurately stored and kept up to date in the system.
**5. Grade, Class, and Subject Management** - Administrator can create and manage grades, classes, and subjects within the sys-
tem.

- Administrator can assign teachers to respective classes.

#### 3.2.3 Students

1. **View Marks:** The system allows students to view their subject-wise marks, final
   examination marks.
2. **View Timetable:** The system allows students to view their daily and weekly
   timetables with real-time updates for schedule changes.
3. **Check Academic Progress:** The system enables students to view their academic
   progress.
4. **View Announcements:** The system allows students to view announcements posted
   by school administration and teachers.
5. **View Exam Time Table:** Student is able to view exam timetable that was up-
   loaded by Management pannel or admin
6. **View Absent Reason:** The system allows parents/students to view the status of
   their submitted absence reasons.
7. **Login:** The system requires valid login credentials (email and password) to access
   the portal, authenticating user credentials against the database.
8. **View Result Report:** The system allows students to view detailed result reports
   including grades and rank.
9. **View Attendance:** The system allows students to view their attendance records
   with calculated attendance percentage.

#### 3.2.4 Management Panel

**1. User Login** - **User Authentication:** The system allows management panel users to log in using
a valid email and password. - **Credential Verification:** The system validates the entered credentials against
stored user data in the database.

- **Access Control:** The system grants access to the management panel dashboard
  only if the credentials are correct.
- **Error Handling for Invalid Login:** The system displays an appropriate error
  message if the credentials are invalid (e.g., “Invalid email or password”).
  **2. CRUD – Student Records**
- The management panel user can search for a specific student and edit their records,
  including personal details, grades.
- The user initiates a search by entering identifying information (student ID or name).
- The system displays a list of matching student records; the user selects a student
  and modifies preferred fields.
- The system validates the input data (e.g., data type, required fields).
- The student’s record is updated in the database with the validated changes, and
  the user is notified of the successful update.
  **3. CRUD – Parent Records**
- The system retrieves the parent’s contact details and other relevant information
  from the database and displays it clearly.
  **4. CRUD – Staff Records**
- A management panel user logs into the system, accesses staff records, reviews details,
  and updates staff information such as name, role, contact info, and employment
  history.
- The system validates the input data for correctness and completeness. Upon suc-
  cessful validation, the system updates the staff record in the database and confirms
  the update.
  **5. Broadcast Announcements**
- The system allows users with Management Panel or Administrator roles to create
  announcements with a title (mandatory), message content (mandatory), and target
  audience selection (mandatory).

- Input validation is performed before saving. The announcement is broadcast to all
  users specified in the target audience across the platform.
- The newly created announcement is displayed in the announcement module list for
  future reference.
  **6. Monitor Attendance**
- The user logs into the system, opens the attendance module, selects a teacher from
  the list, and the system retrieves and displays detailed attendance records showing
  dates and attendance status for review.
  **7. Leave Requests**
- The system displays all leave requests submitted by teachers with details including
  requester name, duration (from/to dates), and current status (Pending, Approved,
  Rejected).
- The user reviews requests for potential further action.
  **8. View Performance Trends**
- User navigates to the academic performance module, selects a student or group of
  students, and the system retrieves academic records including grades, exam results,
  and progress reports for analysis and monitoring.

#### 3.2.5 Teacher

1. **Teacher Authentication:** The system provides a login interface for teachers and
   verifies teacher credentials (email and password), granting access to authorised fea-
   tures upon successful login.
2. **View Student Reports:** The system allows teachers to select a student from
   their class list and displays student reports including academic marks, attendance
   records, and teacher remarks.
3. **Post Announcements:** The system allows teachers to post announcements to
   students and/or parents via a submission module.
4. **Submit Leave Requests:** The system provides a leave application form for teach-

```
ers to submit leave requests to management.
```

5. **Track Leave Request Status:** The system allows teachers to check the approval
   status of their leave requests (pending, approved, or rejected).
6. **Manage Announcements:** The system allows teachers to edit or delete previously
   posted announcements.
7. **Add Student Remarks:** The system allows teachers to select a student and add
   remarks to their record.
8. **Upload Learning Materials for Absent Students:** The system allows teachers
   to select students marked absent and upload worksheets, notes, or learning content.
9. **Manage Student Marks:** The system allows teachers to add or update student
   marks for assessments with validation before saving.

### 3.3 Non-functional Requirements

- **Security:**
  **-** Password hashing to protect user credentials.
  **-** Role-Based Access Control (RBAC) to restrict access based on user roles and
  permissions.
  **-** Session timeouts to automatically log out inactive users and prevent unautho-
  rised access.
- **Reliability:**
  **-** The system ensures high availability and minimal downtime during normal
  operations.
  **-** Data consistency is maintained across all modules through transactional in-
  tegrity.
  **-** Error handling mechanisms are implemented to gracefully manage unexpected
  failures.
  **-** Backup and recovery mechanisms are supported to prevent data loss.

- **Performance:**
  **-** Typical page load times under 2 seconds on standard broadband connections.
- **Usability:**
  **-** Intuitive and easy-to-use interface for all user roles.
- **Maintainability:**
  **-** Model-View-Controller (MVC) architectural pattern for clear code separation.
  **-** Readable and well-documented code with unit and integration tests.
  **-** Continuous Integration (CI) process to automate testing and improve develop-
  ment efficiency.
- **Portability:**
  **-** Deployable on a standard LAMP stack (Linux, Apache, MySQL, PHP).
  **-** Containerisation using Docker for enhanced portability across environments.

### 3.4 In-Scope vs Out-of-Scope

#### 3.4.1 In-Scope

The following features and modules are included within the scope of the system develop-
ment:

- **Academic Records Management:** Handling student profiles, grades, and aca-
  demic history.
- **Attendance and Leave Management:** Recording student and staff attendance,
  and managing leave requests and approvals.
- **Announcements:** Providing a centralised platform for school-wide communica-
  tions and updates.
- **Learning Materials:** Uploading and accessing course content, assignments, and
  educational resources.

- **Role-Based Access Control (RBAC):** Ensuring secure access based on user
  roles such as administrators, teachers, students, and parents.
- **Reporting and Analytics:** Generating reports on attendance and performance.

#### 3.4.2 Out-of-Scope

The following functionalities are not included in the first phase of development but may
be considered for future releases:

- Payroll and Finance Management
- Library and Inventory Systems
- Transport Management
- Health and Medical Records
- Non-Academic Staff Modules

### 3.5 Constraints & Limitations

- Each user is allowed to maintain only one account, and each account is assigned a
  single role (e.g., student, teacher, parent, administrator, or management panel).
- All users are required to possess a valid and unique email address for account cre-
  ation, verification, and communication.
- The server and database are expected to remain available during normal operations,
  with minimal downtime.
- The fingerprint attendance module is a simulated feature for demonstration pur-
  poses. It is assumed that the fingerprint input correctly maps to the corresponding
  UserID.
- Phone number is used as the initial default password for new accounts.
- NIC format: Either 12-digit numeric.
- One class per student: Students belong to exactly one class.

- Student must exist first: Parents can only be added if the student already exists in
  the system.
- NIC required: All teachers, managers, and parents must have a National Identity
  Card number.
- Subject assignment: Each teacher has one primary subject they teach.
- The system does not include an automated notification system (such as email,
  SMS, or push notifications) in this phase. In future deployment stages, email or
  SMS-based notifications can be added once the project moves to a scalable, funded
  environment.
- One-way communication: Announcements are broadcast only (no replies/comments).
- No multi-school support: System assumes single school deployment.
- Class options: Configurable class sections (e.g., A, B) with extensibility for future
  expansion.
- A parent and student have a strict one-to-one relationship (one parent per student).
- The system is currently deployed and tested on the demo environment at https:
  //iskole.ct.ws/.

# Chapter 4

# System Architecture

The IskolE system follows the **Model-View-Controller (MVC)** architectural pattern,
which separates concerns into three distinct layers to promote modularity, maintainability,
and scalability.

### 4.1 Components & Responsibilities

#### 4.1.1 Model (M)

**Role:** Handles data and business logic.
**Responsibilities:**

- Interacts with the database (e.g., fetch, insert, update, delete records).
- Defines data structures (like Students, Teachers, Attendance, etc.).
- Implements application logic (e.g., calculating averages, rankings, attendance per-
  centages).
- Notifies the Controller when data changes.

#### 4.1.2 View (V)

**Role:** Manages the user interface (UI) and presentation layer.
**Responsibilities:**

- Displays data from the Model to the user in a readable format (HTML, CSS).
- Provides forms and interfaces for user input.
- Contains no business logic — only handles what the user sees.

#### 4.1.3 Controller (C)

**Role:** Acts as a bridge between the Model and the View.
**Responsibilities:**

- Receives input from the user (e.g., form submission, button click).
- Processes the input and requests the appropriate data from the Model.
- Selects which View to display based on the result.
- Controls the overall flow of data and user navigation.

### 4.2 Key Modules & Interactions

```
Table 4.1: Key System Modules and Their Interactions
Module Main Interactions / Purpose
Authentication Module Handles user login, logout, session management, and pass-
word reset functionality. Interacts with User Model and
Role Controller.
Student Management Module Stores student profiles, academic data, and attendance
records. Interacts with Teacher and Management mod-
ules.
Teacher Management Module Manages teacher profiles, schedules, and subject assign-
ments. Communicates with Admin and Class modules.
Attendance Module Takes input (manual or simulated fingerprint), validates
with Student Model, and updates attendance tables.
Marks & Performance Module Allows teachers to enter marks; system calculates averages
and ranks. Used by Students, Parents, and Management
modules.
Announcement Module Enables admin, teachers or management to post announce-
ments; students and parents view them via dashboard.
```

Table 4.1 – continued from previous page
**Module Main Interactions / Purpose**

Timetable & Calendar Module Displays schedules and events; data managed by Manage-
ment panel.
Behaviour & Remarks Module Teachers record student behaviour; visible to parents and
management.
Admin Module Handles role creation, CRUD operations, and overall sys-
tem settings such as class, grade, subjects, etc.
Grade & Class Management Module Create and delete grades and classes. Managed by Admin-
istrator.
Subject Management Module Create and delete subjects. Handled by R. S. R. G. A. A.
Ananda.
Assign Class Teacher Module Assign teachers to their respective classes. Handled by
Seniru D S Senaweera.

# Chapter 5

# System Design

This chapter presents the key design diagrams of the IskolE system, illustrating the struc-
tural and behavioural aspects of the software architecture.

### 5.1 Use Case Diagram

The use case diagrams illustrate the interactions between the system’s primary actors
(Administrator, Management Panel, Teacher, Student, and Parent) and the functional
modules of the system.

### 5.2 ER Diagram

The Entity-Relationship diagram depicts the database schema, showing all entities, their
attributes, and the relationships between them. The system is centred on the USER entity,
with specialisations for Student, Teacher, and Parent roles.

### 5.3 Class Diagram

The class diagram presents the MVC-based class structure of the system, showing the
relationships between Controllers, Models, and Entity classes.

### 5.4 System Architecture Diagram

The system architecture diagram provides a high-level overview of the MVC architecture,
illustrating how the front-end, back-end, and database layers interact within the system.

Figure 5.1: Administrator Use Case Diagram

Figure 5.2: Management Panel Use Case Diagram

Figure 5.3: Parent Use Case Diagram

Figure 5.4: Student Use Case Diagram

Figure 5.5: Teacher Use Case Diagram

```
Figure 5.6: ER Diagram
```

Figure 5.7: Class Diagram

### 5.5 Activity Diagrams

The activity diagrams depict the flow of operations and interactions for various core
functionalities and user roles within the system.

```
Figure 5.8: Admin Workflow
```

```
Figure 5.9: Manager Workflow
```

```
Figure 5.10: Parent Workflow
```

Figure 5.11: Student Workflow

```
Figure 5.12: Teacher Workflow
```

Figure 5.13: User Login and Password Reset Workflow

# Chapter 6

# Completeness of the Project

### 6.1 Functionalities Completed

All functionalities defined in the project proposal and interim report have been **100%
completed**. The system consists of **16 CRUD-based functional modules** , distributed
among four group members as follows:

#### 6.1.1 R K K Jinendra – Module Owner

1. Management Staff Records (CRUD)
2. Teacher Records (CRUD)
3. Student Records (CRUD)
4. Parent/Guardian Records (CRUD)
5. Student Absence Reason Management (CRUD)

#### 6.1.2 Seniru D S Senaweera – Module Owner

1. Announcements (CRUD)
2. Learning Materials Management (Upload/Update/Delete/Download)
3. Teacher Attendance Records (CRUD)
4. Student Attendance Records (CRUD)

#### 6.1.3 S. K. Thasindu Ramsitha – Module Owner

1. Marks / Exam Results Management (Create/Read/Update)

2. Student Timetables (CRUD + publish/unpublish)
3. Teacher Timetables (CRUD + publish/unpublish)
4. Relief / Teacher Substitution Management (CRUD)

#### 6.1.4 R. S. R. G. A. A. Ananda – Module Owner

1. Classes Management (CRUD)
2. Exam Timetables (Upload/Update/Delete + image handling)
3. Teacher Leave Requests (CRUD + approval workflow)
4. Student Behaviour / Remarks (CRUD)
5. Subjects Management (CRUD)

### 6.2 Functionalities Yet to Complete

There are **no incomplete functionalities**. All planned modules have been successfully
implemented and tested.

# Chapter 7

# Individual Contribution Analysis

All team members contributed equally (25% each) in the following areas:

- System design
- Database design
- Frontend development
- Backend development
- Testing
- Integration

##### 25 25

##### 25 25

```
R K K Jinendra – 25%Seniru D S Senaweera – 25%
S. K. Thasindu Ramsitha – 25%R. S. R. G. A. A. Ananda – 25%
```

```
Figure 7.1: Individual Contribution of Team Members
```

```
Table 7.1: Individual Contribution Summary
```

**Member Contribution (%) Primary Modules**
R K K Jinendra 25% Staff, Teacher, Student, Parent
Records; Absence Reasons
Seniru D S Senaweera 25% Announcements, Learning
Materials, Attendance Records
S. K. Thasindu Ramsitha 25% Marks/Results, Timetables, Relief
Management
R. S. R. G. A. A. Ananda 25% Classes, Exam Timetables, Leave
Requests, Behaviour, Subjects

# Chapter 8

# Testing

The system testing was conducted using Google Sheets-based test case documentation.
The testing process covered multiple levels to ensure system reliability and correctness.

### 8.1 Testing Types

The following testing methodologies were employed:

1. **Unit Testing:** Individual functions and methods within each module were tested
   in isolation to verify correctness of business logic and data processing.
2. **Functional Testing:** End-to-end testing of each functional module to ensure that
   all features operate as specified in the requirements.
3. **CRUD Operation Testing:** Comprehensive testing of all Create, Read, Update,
   and Delete operations across all 16 functional modules.
4. **Integration Testing:** Testing the interactions between interconnected modules to
   ensure seamless data flow and consistency.
5. **Role-Based Access Control Testing:** Verification that each user role (Admin-
   istrator, Management Panel, Teacher, Student, Parent) has access only to their
   permitted features and data.

### 8.2 Test Case Summary

```
Table 8.1: Testing Summary
Aspect Details
Total Functional Units 16 modules
Testing Approach Module-level and integration testing
CRUD Operations All operations validated for correctness
Role-Based Access Control Verified for all 5 user roles
Test Documentation Google Sheets-based test case tracking
Test Result All test cases passed successfully
```

_Note: The complete Google Sheets test case document is attached as an appendix/reference
to this report._

# Chapter 9

# Details of Project Supervisor and Co-

# Supervisor

## Proposed Project Supervisor (Academic Staff of UCSC)

Name of the supervisor: Mr. Viraj Welgama

Signature of the supervisor:

Date:

## Proposed Project Co-Supervisor (Assigned by the Course

## Coordinator)

Name of the co-supervisor: Mr. Janitha Dissanayake

Signature of the co-supervisor:

Date:

# Chapter 10

# Declaration

We, as members of the project titled **IskolE** , certify that we will carry out this project
according to the guidelines provided by the coordinators and supervisors of the course,
as well as that we will not incorporate, without acknowledgment, any material previously
submitted for a degree or diploma in any university.
To the best of our knowledge and belief, the project work will not contain any material
previously published or written by another person or ourselves except where due reference
is made in the text at appropriate places.
