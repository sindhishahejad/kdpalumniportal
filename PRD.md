# Product Requirements Document (PRD): KD Polytechnic Alumni Portal

*Document Version:* 1.0  
*Role:* Principal Technical Product Manager & Software Architect  
*Date:* July 25, 2026  

---

## 1. Project Context & Overview

### 1.1 Executive Summary
The *KD Polytechnic Alumni Portal* is a dedicated web platform designed to bridge the gap between current diploma students, alumni, faculty, and college administrators at KD Polytechnic, Patan (Diploma Computer Engineering). Inspired by premium alumni networks like the IIT Delhi Alumni Association, this portal is specialized for the practical realities of diploma students, focusing on Diploma-to-Degree (D2D) transitions, local industrial GIDC apprenticeships, and collaborative peer learning.

### 1.2 Target Audience
- *Diploma Students:* Seeking mentorship, D2D guidance, study resources, and apprenticeships.
- *Alumni:* Looking to give back, recruit talent, network with peers, and stay updated on campus events.
- *Faculty & Administrators:* Managing events, notices, transcripts, and monitoring alumni engagement.

### 1.3 Team & Methodology
- *Team Structure:* 3-member student team.
- *Methodology:* Agile/Scrum.
- *Workflow:* Git Feature Branch workflow (main <- feature/*).

---

## 2. Technical Specifications & Architecture

| Component | Technology / Stack | Details |
| :--- | :--- | :--- |
| *Frontend* | Laravel Blade, HTML5, CSS3, Tailwind CSS, Alpine.js | Utilizing Laravel Breeze UI for rapid frontend scaffolding. |
| *Backend* | Laravel 11 (PHP) | Strict Model-View-Controller (MVC) architecture. |
| *Database* | MySQL | Managed locally via XAMPP/WAMP; deployed to phpMyAdmin on Hostinger. |
| *Authentication* | Laravel Breeze | Bcrypt hashing, session-based auth, role-based middleware. |
| *Version Control*| Git & GitHub | Feature branch workflow ensuring stable main branch. |
| *Deployment* | Local (php artisan serve) & Hostinger | Production domain: kdpalumni.scrapeguru.com |

---

## 3. Feature Modules & Scope

### 3.1 Module 1: Identity & Access Management (IAM) 🔴 [MILESTONE 1 PRIORITY]
- *Role-Based Authentication:* Distinct RBAC for Student, Alumni, and Admin.
- *Registration & Secure Login:* Bcrypt hashed credentials, email validation, role selection upon signup.
- *Dynamic Profile Management:* Editable fields including Enrollment Year, Branch, Current City, Current Employer / Higher Education Status.
- *Smart Digital I-Card:* Auto-generated visual digital ID badge for verified alumni with scannable verification elements.

### 3.2 Module 2: Directory & Networking (Core Engine) 🔴 [MILESTONE 1 PRIORITY]
- *Alumni Search Directory:* Filterable database by name, enrollment batch, branch, and current city.
- *Batchmates Filter:* One-click shortcut to view peers who graduated in the exact same year and branch.
- *Alumni Nearby:* Geographic filtering system to locate alumni in specific cities or industrial regions.

### 3.3 Module 3: KD Poly Exclusives & Interactive Community
- *The Knowledge Feed & Challenge Board:* Threaded, Twitter/StackOverflow-style text and code snippet feed.
  - Alumni post algorithmic logic puzzles, number sequences, or spatial challenges.
  - Students reply directly in threaded code blocks (Markdown rendered).
  - General updates and technical advice text-only posts.
- *Diploma-to-Degree (D2D) Navigator:* Mentorship matching algorithm/directory connecting diploma students with alumni in B.E/B.Tech programs.
- *MOOC & Exam Resource Vault:* Peer-to-peer repository for sharing study PDFs, exam prep notes, and skill courses.
- *Local Apprenticeship & GIDC Connect:* Dedicated job/internship board focused on 6-month mandatory industrial training in local industrial zones.

### 3.4 Module 4: Community & Events
- *Event Management:* Registration and ticketing capabilities for tech fests, reunions, and webinars.
- *Media Gallery:* Official admin-curated photo/video archives from campus events.

### 3.5 Module 5: Career & Entrepreneurship
- *General Job Board:* Portal to post and apply for full-time opportunities.
- *Mentorship (SARTHI):* Structured 1-on-1 guidance program.
- *Business Showcase:* Directory of alumni-owned businesses and local startups.

### 3.6 Module 6: Campus Services, Giving Back & Governance
- *Donation Gateway & Targeted Fundraisers:* Crowdfunding pipelines for scholarships and campus facilities.
- *Campus Visit Permissions & Transcripts:* Streamlined administrative request forms.
- *News, Noticeboard & Newsletters:* Centralized admin communication feed.

---

## 4. User Stories & Acceptance Criteria Matrix

| User Role | User Story | Acceptance Criteria |
| :--- | :--- | :--- |
| *Guest* | As a guest, I want to register for an account by selecting my role (Student/Alumni) so I can access the platform. | Sign-up form displays role dropdown. Passwords are encrypted. Redirects to profile setup on success. |
| *Alumni* | As an alumni, I want to search for other alumni by batch and city to network locally. | Search bar and filters (Year, City) return accurate paginated results from the database. |
| *Student* | As a student, I want to view the GIDC Connect board to find mandatory 6-month internships. | Job board specifically filters and displays "Apprenticeship" tags. One-click apply functional. |
| *Alumni* | As an alumni, I want to post code challenges in the Knowledge Feed for students to solve. | Editor supports Markdown and code block formatting. Threads support nested replies. |
| *Admin* | As an admin, I want to approve/reject alumni verification requests. | Dashboard shows pending verifications. Approval triggers Digital I-Card generation. |

---

## 5. Database Schema Architecture (High-Level)

The relational database uses a normalized schema optimized for read-heavy operations in the directory.

| Table Name | Primary Key (PK) | Foreign Keys (FK) & Key Columns | Description |
| :--- | :--- | :--- | :--- |
| users | id | role_id (FK) | Core authentication, email, password hash. |
| roles | id | - | Defines Admin, Alumni, Student. |
| profiles | id | user_id (FK) | Enrollment Year, Branch, City, Employment Status. |
| posts | id | user_id (FK) | Knowledge Feed/Challenges content, Markdown text. |
| comments | id | post_id (FK), user_id (FK) | Threaded replies to challenges. |
| jobs | id | posted_by (FK -> users.id) | Job board / GIDC Apprenticeship listings. |
| resources| id | uploaded_by (FK -> users.id)| PDF/Link vault for exams and MOOCs. |

---

## 6. Development Plan & Milestones

### Milestone 1: August 1 Demo (20% Baseline Requirement)
- *Target:* Working local demo (php artisan serve). *Zero PPTs, strictly live code.*
- *Deliverables:*
  - Fully functional authentication (Laravel Breeze).
  - Core database migrations.
  - Basic Alumni Search Directory UI connected to MySQL.
  - IAM Module & Role setup.

### Milestone 2: 40% Completion
- *Deliverables:*
  - Interactive Knowledge Feed & Challenge Board (Markdown support).
  - Comprehensive Profile management pages.
  - Smart Digital I-Card generation.

### Milestone 3: 70% Completion
- *Deliverables:*
  - D2D Navigator (Mentorship directory).
  - MOOC & Exam Resource Vault.
  - Local Apprenticeship & GIDC Connect job board.

### Final Production Release: 100% Completion (Final Semester Submission)
- *Deliverables:*
  - Full deployment to Hostinger shared hosting (kdpalumni.scrapeguru.com).
  - Complete admin controls and governance modules.
  - End-to-end testing, bug squashing, and final documentation report.

---
Prepared by the Technical Product Management & Architecture Team. Approved for Development Sprint 1.
