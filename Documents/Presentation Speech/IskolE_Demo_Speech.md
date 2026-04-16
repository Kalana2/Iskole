# IskolE – School Management System

## 20-Minute Group Demonstration Speech Script

### SCS2202 | CS Group 38 | University of Colombo School of Computing

---

> **Estimated Duration:** ~20 minutes
> **Speakers:** 4 Group Members
> **Format:** Live Demo Narration Style

---

## 🎤 SPEAKER 1 — System Overview, Architecture & Authentication

**[Approx. 5 minutes]**

---

My name is [Speaker 1 Name], and I will be walking you through the overall system, the architecture that powers it, and the very first step every user takes — logging in.

---

### Opening: Setting the Scene

Before we dive in, let us quickly describe what you are about to see.

IskolE is a fully integrated platform that serves **five types of users** — the **Administrator**, the **Management Panel**, **Teachers**, **Students**, and **Parents**. Each of them interacts with the system differently, and each sees only what they are permitted to see. That is the power of our role-based access control.

The system covers everything from managing student records and tracking attendance, to publishing exam results, broadcasting announcements, uploading learning materials, and managing teacher timetables — all from a single, centralised web application.

Now, let's talk about _how_ the system is built before we show you _what_ it does.

---

### User Authentication — The Entry Point

Now, let us begin the actual demonstration with the very first thing any user does: **logging in**.

_(Navigate to the IskolE login page at iskole.ct.ws)_

Here is the IskolE login page. The system requires every user to enter a valid email address and password. Behind the scenes, the system validates these credentials securely against the database — passwords are stored using **hashing**, so they are never saved in plain text.

If the credentials are wrong, the system shows a clear error message — "Invalid email or password" — and access is denied.

Once login is successful, the system reads the user's **role** and routes them to the correct dashboard. An admin sees the admin panel. A parent sees the parent portal. A student sees the student dashboard. There is no way for a user to access another role's data.

There is also a **password reset** feature. If a user forgets their password, they can reset it securely by getting an OTP to their registered mail..

---

### Transition to Speaker 2

Now that you have seen how users enter the system, let me hand over to [Speaker 2 Name], who will walk you through the **Administrator module** and the **core data management** that forms the foundation of everything in IskolE.

---

---

## 🎤 SPEAKER 2 — Administrator Module & Records Management

**[Approx. 5 minutes]**

---

Thank you, [Speaker 1 Name]. Good morning, panel members.

I am [Speaker 2 Name], and I will now show you the **Administrator module** — the engine room of IskolE. The administrator is the person responsible for setting up the entire school's data structure. Without the admin's work, nothing else in the system can function.

---

### Administrator Dashboard

_(Log in as Administrator)_

Here is the Administrator dashboard. The admin has the broadest management access in the system. Notice the clean navigation menu — everything the admin needs is right here: staff records, teacher records, student records, parent records, and school structure management.

Let us go through each of these systematically.

---

### Grade, Class & Subject Management

The very first thing an admin does when setting up IskolE for a school is define the **academic structure** — the grades, classes, and subjects.

_(Navigate to Grade/Class management)_

The admin can **create new grades** — for example, Grade 10 or Grade 11. Within each grade, they can create **class sections** — such as Class A or Class B. These can be extended as the school grows.

_(Navigate to Subjects)_

Next, the admin creates **subjects** — Mathematics, Science, English, and so on. These subjects will later be assigned to teachers, linked to timetables, and used when entering exam marks.

This foundational step ensures that every other module — attendance, marks, timetables — has the correct structure to operate within.

---

### Management Staff Records

_(Navigate to Management Staff section)_

The admin can create, view, update, and delete records for **management staff** — that includes the principal, vice principal, and other senior figures. Each staff member is given a role-based account in the system, allowing them to log in and access the Management Panel.

---

### Teacher Records

_(Navigate to Teacher management)_

Here, the admin manages **teacher profiles**. When a new teacher joins the school, the admin enters their full name, NIC number, contact details, email address, and subject assignment. The system then creates a teacher account, and — as mentioned — the phone number becomes their initial login password.

A teacher is assigned **one primary subject** they teach. The admin also has the ability to **assign a teacher as a class teacher** for a specific class — this is important because class teachers have special responsibilities in the system, such as monitoring their class's attendance.

---

### Student Records

_(Navigate to Student management)_

Student records are just as straightforward. The admin adds each student — their name, grade, class, date of birth, and other details. Notice that each student belongs to **exactly one class**. The system enforces this to keep data clean and consistent.

---

### Parent / Guardian Records

_(Navigate to Parent management)_

Here is an important design decision in IskolE: **a parent account can only be created after the student already exists in the system.** This enforces a strict one-to-one relationship between a parent and a student.

Once the student record is in place, the admin links the parent or guardian to that student. The parent then receives their login credentials and can access their child's data through the Parent Portal.

---

### Assign Class Teacher

_(Navigate to Assign Class Teacher)_

Finally, the admin assigns **class teachers to their respective classes**. This creates the link between a teacher and a class, which is then used across attendance monitoring, remarks, and learning materials.

With all of this in place — the grades, classes, subjects, teachers, students, and parents — the entire school's data structure is ready. Every other module now has the information it needs to operate.

---

### Transition to Speaker 3

The admin has built the foundation. Now let me pass the demonstration over to [Speaker 3 Name], who will show you how the **Management Panel, Teachers, and the Attendance and Announcement modules** work in action.

---

---

## 🎤 SPEAKER 3 — Management Panel, Teachers, Attendance & Announcements

**[Approx. 5 minutes]**

---

Thank you, [Speaker 2 Name].

I am [Speaker 3 Name], and I will now take you through two of the most active roles in IskolE — the **Management Panel user** and the **Teacher** — and demonstrate the day-to-day features they rely on most: attendance tracking, announcements, leave requests, and learning materials.

---

### Management Panel Dashboard

_(Log in as Management Panel user)_

The Management Panel represents the school's senior leadership — the principal or vice principal. Their dashboard gives them a bird's-eye view of the school's operations.

From here, management can **monitor teacher attendance**, **review and approve leave requests**, **view student academic performance trends**, and **broadcast school-wide announcements**.

Let's look at each of these.

---

### Announcements Module

_(Navigate to Announcements)_

This is one of the most frequently used features in IskolE. The management panel — as well as admin and teachers — can publish announcements to the entire school community.

When creating an announcement, the user provides a **title**, the **message content**, and selects the **target audience** — for example, all students, all parents, or a specific grade. The system validates the input before saving.

Once published, the announcement immediately appears on the dashboard of every user in the selected audience. This replaces physical notice boards and manual communication entirely.

Management can also **edit or delete** existing announcements if information changes.

---

### Teacher Attendance

_(Navigate to Teacher Attendance)_

The management panel can view and monitor **teacher attendance records**. The system shows a clear log of which teachers were present on which dates, with their attendance status displayed. This gives management full visibility without having to chase paper registers.

---

### Leave Request Approval

_(Navigate to Leave Requests)_

When a teacher submits a leave request — which I will show you shortly from the teacher's side — it appears here in the management panel's leave request list.

Management can see the **teacher's name**, the **duration** of the leave (from date and to date), and the **current status** — whether it is Pending, Approved, or Rejected. Management can then act on it accordingly.

---

### Teacher Dashboard

_(Log out and log in as a Teacher)_

Now let us step into the teacher's experience.

Here is the teacher dashboard. Teachers have a focused set of tools designed around what they need to do every day: mark attendance, enter exam results, manage their timetable, upload learning materials, and communicate with students and parents.

---

### Student Attendance — Taking Roll

_(Navigate to Attendance module)_

One of the teacher's primary daily tasks is marking student attendance.

The teacher selects the class and date, and the system displays the full student list. The teacher marks each student as Present, Absent. Once submitted, these records are stored and immediately become accessible to parents, the management panel, and the admin.

If a student is absent, the teacher can also upload **learning materials or worksheets** specifically for those absent students — ensuring they do not fall behind.

---

### Leave Requests — Teacher Submitting

_(Navigate to Leave Request form)_

When a teacher needs to take leave, they fill in a simple form — selecting the start date, end date, and providing a reason. The request is submitted and immediately enters the management panel's approval queue as we just saw.

The teacher can also **track the status** of their submitted request right here — whether it is still Pending, or has been Approved or Rejected by management.

---

### Student Behaviour and Remarks

_(Navigate to Remarks module)_

Teachers can log **behavioural observations and remarks** for individual students. They select the student from their class list and enter the note. This record is stored securely and becomes visible to the student's parents through the parent portal.

This feature replaces informal verbal updates and creates a documented, transparent record of student behaviour for parents and management to review.

---

### Announcements — Teacher Creating

_(Navigate to Announcements)_

Just as management can post announcements, teachers can also create them — for example, to notify students of an upcoming assignment deadline or a class activity. They can also **edit or delete** their own announcements.

---

### Transition to Speaker 4

Now that we have seen how management and teachers operate the system, let me pass the floor to [Speaker 4 Name], who will demonstrate the most student- and parent-facing features of IskolE — the **marks and results module, timetables, and the student and parent portals**.

---

---

## 🎤 SPEAKER 4 — Marks, Timetables, Student & Parent Portals + Closing

**[Approx. 5 minutes]**

---

Thank you, [Speaker 3 Name].

I am [Speaker 4 Name], and I will now bring the demonstration full circle by showing you the experience from the perspective of the **student** and the **parent** — the people at the heart of this entire system.

But before that, let me walk you through two powerful modules that directly feed into what students and parents see: **Marks & Results** and **Timetables**.

---

### Marks & Exam Results Module

_(Log in as Teacher, navigate to Marks module)_

After examinations, teachers use this module to **enter student marks subject by subject**. The teacher selects the exam, the class, and begins entering each student's score.

What makes this module intelligent is what happens automatically the moment marks are saved. The system **calculates the total marks**, computes the **average**, and determines each student's **rank within the class**. No manual calculation is needed.

These results are then locked into a **comprehensive student result report**, which is instantly accessible to the student, their parent, and the management panel — but **no one else**. Role-based access control ensures that unauthorised users cannot view another student's results.

_(Show the result report view)_

Here is what a result report looks like. Subject-wise marks, totals, averages, and class rank — all in one clean view.

---

### Timetables Module

_(Navigate to Timetable management — log in as Management)_

The timetable module allows management to **create and manage both student class timetables and teacher timetables**.

Management can create a full weekly timetable for a class — assigning subjects and teachers to specific periods on each day. Once the timetable is ready, management can **publish it**, making it immediately visible to students and teachers.

_(Navigate to Teacher Timetable)_

Teacher timetables work in a similar way — each teacher can see their own schedule clearly. This also feeds into the **relief teacher / substitution management** module, where management can assign a substitute teacher when the regular teacher is on approved leave.

---

### Exam Timetable

_(Navigate to Exam Timetable)_

Separately, management can **upload the examination timetable** — typically as an image, which students and parents can view directly through their portals. This is one of the most accessed features in the lead-up to exam season.

---

### Student Portal

_(Log out and log in as Student)_

Now let us see everything through a student's eyes.

Here is the student dashboard. It is designed to be clean, intuitive, and focused. From here, a student can:

- **View their marks and result reports** — their subject scores, total, average, and rank.
- **Check their attendance record** — including their overall attendance percentage, and the status of any absence reasons they or their parents have submitted.
- **View their class timetable** — including any updates management has made.
- **View the exam timetable** — uploaded by management.
- **Read announcements** — from admin, teachers, or management, all appearing in one place.

_(Navigate to Marks view)_

Notice how the student sees their own data only. The system is precise — a student cannot see another student's results or records.

_(Navigate to Attendance)_

The attendance view shows each date, the attendance status, and the overall percentage — giving students a clear picture of where they stand.

---

### Parent Portal

_(Log out and log in as Parent)_

Now, the parent portal — arguably the most impactful feature for families.

Parents log in using credentials provided by the school's management. Once inside, they can:

- **View their child's marks and result reports** — the same comprehensive report the student sees.
- **View their child's attendance history** — present days, absent days, and excused days.
- **Submit a pre-absence request** — if a parent knows their child will be absent in advance, they can submit a reason directly through the portal. This request is then visible to the relevant class teacher, eliminating the need for phone calls.

_(Navigate to Absence Request)_

Here is the pre-absence submission form. The parent selects the date, enters the reason, and submits. The teacher sees it immediately on their end.

- **View the class teacher's remarks** about their child — behavioural observations logged by the teacher are accessible right here.
- **View subject teacher information** — the parent can see the name and contact details of every teacher responsible for their child's subjects. This keeps communication transparent and accessible.
- **Read all published announcements** — school-wide communications land directly on the parent's dashboard.

---

### Closing: The Full Picture

_(Return to a summary slide or the home screen)_

Let us take a moment to appreciate the full workflow we have just demonstrated.

The **Administrator** sets up the school's entire data structure — grades, classes, subjects, teachers, students, and parents.

The **Management Panel** monitors operations, approves leave, publishes timetables and exam schedules, and broadcasts announcements.

**Teachers** take daily attendance, enter exam marks, upload learning materials, log student behaviour, submit leave requests, and communicate through announcements.

**Students** log in to view their results, timetables, attendance, and announcements — all in one place.

**Parents** stay informed about their child's academic progress, attendance, and behaviour — and can proactively communicate absence reasons through the portal.

Every one of these interactions flows through a secure, role-based system built on the MVC architecture — meaning the right people always see the right information, and nothing more.

IskolE is a system of **16 fully implemented, CRUD-based modules**, tested rigorously across unit, functional, integration, and role-based access control testing — and **all test cases passed successfully**.

---

### Closing Statement

We are proud to present IskolE as a complete, functional, and practical solution for modern school management. It has been a privilege to build this system, and we are happy to answer any questions the panel may have.

Thank you very much.

---

_— End of Demonstration Speech —_

---

> **Speaker Summary:**
> | Speaker | Section | Duration |
> |---|---|---|
> | Speaker 1 | System Overview, Architecture & Authentication | ~5 min |
> | Speaker 2 | Administrator Module & Records Management | ~5 min |
> | Speaker 3 | Management Panel, Teacher, Attendance & Announcements | ~5 min |
> | Speaker 4 | Marks, Timetables, Student & Parent Portals + Closing | ~5 min |
