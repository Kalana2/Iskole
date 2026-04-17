# IskolE – School Management System

## 20-Minute Group Demonstration Speech Script

### SCS2202 | CS Group 38 | University of Colombo School of Computing

---

> **Estimated Duration:** ~20 minutes
> **Speakers:** 4 Group Members
> **Format:** Live Demo Narration Style

---

## 🎤 SPEAKER 1 — System Overview, Authentication & School Structure Setup

**[Approx. 5 minutes]**

---

My name is [Speaker 1 Name], and I will be walking you through the overall system, and the very first steps that bring IskolE to life — logging in and setting up the school's academic structure.

---

### Opening: Setting the Scene

Before we dive in, let us quickly describe what you are about to see.

IskolE is a fully integrated platform that serves **five types of users** — the **Administrator**, the **Management Panel**, **Teachers**, **Students**, and **Parents**. Each of them interacts with the system differently, and each sees only what they are permitted to see. That is the power of our role-based access control.

The system covers everything from managing student records and tracking attendance, to publishing exam results, broadcasting announcements, uploading learning materials, and managing teacher timetables — all from a single, centralised web application.

Now, let us begin the demonstration.

---

### User Authentication — The Entry Point

The very first thing any user does is **log in**.

_(Navigate to the IskolE login page at iskole.ct.ws)_

Here is the IskolE login page. The system requires every user to enter a valid email address and password. Behind the scenes, the system validates these credentials securely against the database — passwords are stored using **hashing**, so they are never saved in plain text.

If the credentials are wrong, the system shows a clear error message — "Invalid email or password" — and access is denied.

Once login is successful, the system reads the user's **role** and routes them to the correct dashboard. An admin sees the admin panel. A parent sees the parent portal. A student sees the student dashboard. There is no way for a user to access another role's data.

There is also a **password reset** feature. If a user forgets their password, they can reset it securely by receiving a one-time password to their registered email.

---

### Administrator Dashboard

_(Log in as Administrator)_

Here is the Administrator dashboard — the engine room of IskolE. The admin has the broadest management access in the system. Everything the admin needs is right here: staff records, teacher records, student records, parent records, and school structure management.

The very first thing an admin does when setting up IskolE for a school is define the **academic structure** — the grades, classes, and subjects.

---

### Grade & Class Management

_(Navigate to Grade/Class management)_

The admin can **create new grades** — for example, Grade 10 or Grade 11. Within each grade, they can create **class sections** — such as Class A or Class B. These can be extended as the school grows.

This foundational step ensures that every other module — attendance, marks, timetables — has the correct structure to operate within.

---

### Subject Management

_(Navigate to Subjects)_

Next, the admin creates **subjects** — Mathematics, Science, English, and so on. These subjects will later be assigned to teachers, linked to timetables, and used when entering exam marks.

---

### Management Staff Records

_(Navigate to Management Staff section)_

The admin can also create, view, update, and delete records for **management staff** — the principal, vice principal, and other senior figures. Each staff member is given a role-based account, allowing them to log in and access the Management Panel.

With the academic structure and staff accounts in place, the system is ready to receive teachers, students, and parents — which my next speaker will now walk you through.

---

### Timetables Module

_(Navigate to Timetable management — log in as Management)_

Now let me show you the timetable module. Management can **create and manage both student class timetables and teacher timetables**.

Management creates a full weekly timetable for a class — assigning subjects and teachers to specific periods on each day. Once ready, they can **publish it**, making it immediately visible to students and teachers. They can also **unpublish** it if a revision is needed.

_(Navigate to Teacher Timetable)_

Teacher timetables work the same way — each teacher can see their own schedule clearly. This also feeds into the **relief teacher substitution module**, where management can assign a substitute teacher when the regular teacher is on approved leave.

---

### Transition to Speaker 2

Let me now hand over to [Speaker 2 Name], who will demonstrate how **teacher, student, and parent records** are managed inside the Administrator module.

---

---

## 🎤 SPEAKER 2 — Teacher, Student & Parent Records Management

**[Approx. 5 minutes]**

---

Thank you, [Speaker 1 Name]. Good morning, panel members.

I am [Speaker 2 Name], and I will continue inside the Administrator module to show you how the people in the school — the teachers, students, and parents — are registered and linked together in IskolE.

---

### Teacher Records

_(Navigate to Teacher management)_

Here, the admin manages **teacher profiles**. When a new teacher joins the school, the admin enters their full name, NIC number, contact details, email address, and subject assignment. The system then creates a teacher account.

One important detail — the teacher's **phone number becomes their initial login password**, which they can change after their first login.

A teacher is assigned **one primary subject** they teach. The admin also has the ability to **assign a teacher as a class teacher** for a specific class — this is important because class teachers have special responsibilities in the system, such as monitoring their class's attendance.

---

### Assign Class Teacher

_(Navigate to Assign Class Teacher)_

Here the admin assigns **class teachers to their respective classes**. This creates the direct link between a teacher and a class, which is then used across attendance monitoring, remarks, and learning materials throughout the system.

---

### Student Records

_(Navigate to Student management)_

Student records are equally straightforward. The admin adds each student — their name, grade, class, date of birth, and other details. Notice that each student belongs to **exactly one class**. The system enforces this to keep data clean and consistent.

---

### Parent / Guardian Records

_(Navigate to Parent management)_

Here is an important design decision in IskolE: **a parent account can only be created after the student already exists in the system.** This enforces a strict one-to-one relationship between a parent and a student.

Once the student record is in place, the admin links the parent or guardian to that student. The parent then receives their login credentials and can access their child's data through the Parent Portal.

With all of this in place — the grades, classes, subjects, staff, teachers, students, and parents — the entire school's data structure is complete. Every other module now has the information it needs to operate.

---

### Management Panel Dashboard

_(Log in as Management Panel user)_

Before I hand over, let me briefly show you the **Management Panel** — the view available to the school's senior leadership, such as the principal or vice principal.

Their dashboard gives them a bird's-eye view of the school's operations. From here, management can **monitor teacher attendance**, **review and approve leave requests**, **publish announcements**, and **view student academic performance trends**.

---

### Announcements Module

_(Navigate to Announcements)_

This is one of the most frequently used features in IskolE. Management — as well as admin and teachers — can publish announcements to the entire school community.

When creating an announcement, the user provides a **title**, the **message content**, and selects the **target audience** — for example, all students, all parents, or a specific grade. Once published, the announcement immediately appears on the dashboard of every user in the selected audience.

Management can also **edit or delete** existing announcements if information changes.

---

### Transition to Speaker 3

Now let me pass the demonstration over to [Speaker 3 Name], who will take you through the **Teacher's daily workflow** — attendance, leave management, student remarks, and the timetable system.

---

---

## 🎤 SPEAKER 3 — Teacher Workflow: Attendance, Leave, Remarks & Timetables

**[Approx. 5 minutes]**

---

Thank you, [Speaker 2 Name].

I am [Speaker 3 Name], and I will now step into the teacher's daily experience in IskolE — the tools they rely on to run their classroom, communicate with management, and keep students and parents informed.

---

### Teacher Dashboard

_(Log in as a Teacher)_

Here is the teacher dashboard. Teachers have a focused set of tools designed around what they need every day: mark attendance, submit leave, manage timetables, log student behaviour, and communicate with students and parents.

---

### Student Attendance — Taking Roll

_(Navigate to Attendance module)_

One of the teacher's primary daily tasks is marking student attendance.

The teacher selects the class and date, and the system displays the full student list. The teacher marks each student as **Present** or **Absent**. Once submitted, these records are stored and immediately become accessible to parents, the management panel, and the admin.

If a student is absent, the teacher can also upload **learning materials or worksheets** specifically for those absent students — ensuring they do not fall behind.

---

### Leave Requests — Teacher Submitting

_(Navigate to Leave Request form)_

When a teacher needs to take leave, they fill in a simple form — selecting the start date, end date, and providing a reason. The request is submitted and immediately enters the management panel's approval queue.

_(Switch briefly to Management Panel — Leave Requests)_

And here is how it looks from the management side. The management panel sees the **teacher's name**, the **duration** of the leave, and the **current status** — Pending, Approved, or Rejected — and can act on it accordingly.

_(Switch back to Teacher view)_

The teacher can also **track the status** of their submitted request right here — whether it has been approved or rejected by management.

---

### Student Behaviour and Remarks

_(Navigate to Remarks module)_

Teachers can log **behavioural observations and remarks** for individual students. They select the student from their class list and enter the note. This record is stored securely and becomes visible to the student's parents through the parent portal — creating a documented, transparent record of student behaviour.

---

### Exam Timetable

_(Navigate to Exam Timetable)_

Separately, management can **upload the examination timetable** — typically as an image — which students and parents can view directly through their portals. This is one of the most accessed features in the lead-up to exam season.

---

### Transition to Speaker 4

With the school fully set up and teachers actively managing their day-to-day work, let me now pass the floor to [Speaker 4 Name], who will show you the experience from the **student and parent side** — and close our demonstration.

---

---

## 🎤 SPEAKER 4 — Marks & Results, Student Portal, Parent Portal & Closing

**[Approx. 5 minutes]**

---

Thank you, [Speaker 3 Name].

I am [Speaker 4 Name], and I will now bring the demonstration full circle by showing you the experience from the perspective of the **student** and the **parent** — the people at the heart of this entire system.

---

### Marks & Exam Results Module

_(Log in as Teacher, navigate to Marks module)_

After examinations, teachers use this module to **enter student marks subject by subject**. The teacher selects the exam, the class, and begins entering each student's score.

What makes this module intelligent is what happens automatically the moment marks are saved. The system **calculates the total marks**, computes the **average**, and determines each student's **rank within the class**. No manual calculation is needed.

These results are locked into a **comprehensive student result report**, instantly accessible to the student, their parent, and the management panel — but **no one else**. Role-based access control ensures that unauthorised users cannot view another student's results.

_(Show the result report view)_

Here is what a result report looks like — subject-wise marks, totals, averages, and class rank, all in one clean view.

---

### Student Portal

_(Log out and log in as Student)_

Now let us see everything through a student's eyes.

Here is the student dashboard — clean, intuitive, and focused. From here, a student can:

- **View their marks and result reports** — their subject scores, total, average, and rank.
- **Check their attendance record** — including their overall attendance percentage, and the status of any absence reasons submitted.
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
- **Submit a pre-absence request** — if a parent knows their child will be absent in advance, they can submit a reason directly through the portal. The teacher sees it immediately on their end, eliminating the need for phone calls.
- **View the class teacher's remarks** about their child — behavioural observations logged by the teacher are accessible right here.
- **View subject teacher information** — the parent can see the name and contact details of every teacher responsible for their child's subjects.
- **Read all published announcements** — school-wide communications land directly on the parent's dashboard.

---

### Closing: The Full Picture

_(Return to home screen)_

Let us take a moment to appreciate the full workflow we have just demonstrated.

The **Administrator** sets up the school's entire data structure — grades, classes, subjects, staff, teachers, students, and parents.

The **Management Panel** monitors operations, approves leave, publishes timetables and exam schedules, and broadcasts announcements.

**Teachers** take daily attendance, enter exam marks, upload learning materials, log student behaviour, and submit leave requests.

**Students** log in to view their results, timetables, attendance, and announcements — all in one place.

**Parents** stay informed about their child's academic progress, attendance, and behaviour — and can proactively communicate absence reasons through the portal.

Every one of these interactions flows through a secure, role-based system — meaning the right people always see the right information, and nothing more.

IskolE is a system of **16 fully implemented modules**, tested across unit, functional, integration, and role-based access control testing — and **all test cases passed successfully**.

---

### Closing Statement

We are proud to present IskolE as a complete, functional, and practical solution for modern school management. It has been a privilege to build this system, and we are happy to answer any questions the panel may have.

Thank you very much.

---

_— End of Demonstration Speech —_

---

> **Speaker Summary:**
> | Speaker | Section | Key Topics | Duration |
> |---|---|---|---|
> | Speaker 1 | System Overview, Auth & School Structure | Login, Password Reset, Grades, Classes, Subjects, Management Staff | ~5 min |
> | Speaker 2 | Records Management & Management Panel | Teacher/Student/Parent Records, Assign Class Teacher, Announcements | ~5 min |
> | Speaker 3 | Teacher Workflow & Timetables | Attendance, Leave Requests, Remarks, Student/Teacher/Exam Timetables | ~5 min |
> | Speaker 4 | Marks, Student & Parent Portals + Closing | Marks & Results, Student Portal, Parent Portal, Summary | ~5 min |
