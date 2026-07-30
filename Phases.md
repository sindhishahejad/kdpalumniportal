# KD Polytechnic Alumni Portal - Project Roadmap & Development Phases

## 📋 Project Context & Constraints
- **Project:** KD Polytechnic Alumni Portal (5th-Semester Minor Project)
- **Team Size:** 3 Diploma Computer Engineering students
- **Tech Stack:** Laravel 11, Blade Templates, Tailwind CSS, Alpine.js, MySQL
- **Workflow:** Git Feature Branch workflow (`main` branch is protected)

---

## 🔄 Agile Rituals: Daily Stand-up Rules
To ensure alignment and unblock issues quickly, the team will hold a brief (10-15 minute) daily stand-up. 
Each team member must answer three questions:
1. **What did I accomplish yesterday?** (Brief update on completed tasks and PRs)
2. **What will I work on today?** (Focus for the current day)
3. **Are there any blockers?** (Highlight any technical, logistical, or integration issues preventing progress)

*Scrum Master Rule:* Keep it technical, concise, and action-oriented. No deep problem-solving discussions during the stand-up. Take complex issues "offline" after the meeting.

---

## 🚀 Development Phases & Timelines

### Phase 1: Foundation & The 20% Milestone
**Deadline:** August 1  
**Goal:** A working local demo (`php artisan serve`) proving the core architecture, database connection, and basic authentication.

**Tasks:**
- [ ] Initialize Laravel project and configure `.env` for local MySQL database.
- [ ] Install and configure Laravel Breeze for authentication.
- [ ] Modify the Breeze registration form and `users` table migration to include a `role` field (Student, Alumni, Admin).
- [ ] Create the `profiles` table migration (linked to users).
- [ ] Build the basic Alumni Search Directory (a simple UI grid/table fetching registered alumni from the database).

**Team Delegation:**
- **Developer 1 (Backend Architect):** Database migrations, Eloquent relationships, routing.
- **Developer 2 (Frontend UI):** Styling the Breeze templates and Directory view with Tailwind CSS.
- **Developer 3 (Integration & QA):** Git repository management, peer review, and ensuring `php artisan serve` runs flawlessly for the presentation.

**Definition of Done (DoD):** 
- Feature branches are successfully merged to the protected `main` branch via Pull Request.
- The application runs locally without errors.
- Users can register with specific roles.
- The directory view successfully queries and displays registered user data from the database.

---

### Phase 2: Core Networking & Engagement
**Timeline:** August  
**Goal:** Deliver the 40% completion mark by implementing the unique interaction features.

**Tasks:**
- [ ] Build "The Knowledge Feed & Challenge Board" (Twitter/StackOverflow style threaded text/code posts).
- [ ] Integrate a lightweight Markdown parser for formatting code snippets on the frontend.
- [ ] Develop the dynamic Profile Management dashboard so users can update their current city, job, or higher education status.
- [ ] Implement the "Alumni Nearby" and "Batchmates" filtering logic on the Directory page.

**Definition of Done (DoD):** 
- Users can successfully create, view, and interact with feed posts. 
- Markdown renders correctly on posts.
- Profile dashboard allows users to save and retrieve updated information without data loss. 
- Directory filters accurately query the database based on location and batch parameters.

---

### Phase 3: The KD Poly Exclusives
**Timeline:** September  
**Goal:** Hit the 70% completion mark by adding the specialized diploma-centric modules.

**Tasks:**
- [ ] Build the Diploma-to-Degree (D2D) Navigator (mentorship request forms and status tracking).
- [ ] Build the MOOC & Exam Resource Vault (peer-to-peer file sharing/links).
- [ ] Build the Local Apprenticeship & GIDC Connect job board (CRUD operations for job postings).

**Definition of Done (DoD):** 
- All specific modules (D2D, Resource Vault, Job Board) are fully functional with complete CRUD capabilities. 
- Forms include proper server-side and client-side validation. 
- UI components match the established Tailwind CSS design system and are responsive.

---

### Phase 4: Polish, Admin Controls & Production Deployment
**Timeline:** October/November  
**Goal:** 100% completion and final semester submission.

**Tasks:**
- [ ] Build the Admin Dashboard (manage users, post global notices, approve/delete posts).
- [ ] Generate the Smart Digital I-Card (dynamic HTML to PDF or visual badge generation).
- [ ] Final QA testing (form validation, security checks, broken link testing).
- [ ] Production Deployment: Migrate the codebase and database to the Hostinger Shared Web Hosting environment (`kdpalumni.scrapeguru.com`).

**Definition of Done (DoD):** 
- Admin role has full oversight and control over platform content via a secure dashboard. 
- Zero critical or high-priority bugs exist in the backlog. 
- The application is successfully deployed, live, and accessible publicly on `kdpalumni.scrapeguru.com` with a stable, secure database connection.added new phase
