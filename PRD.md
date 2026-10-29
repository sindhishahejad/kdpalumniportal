# Product Requirements Document (PRD): K. D. Polytechnic Alumni Portal

*Document Version:* 2.0.0 (Production Release)  
*Institution:* K. D. Polytechnic, Patan (Estd. 1961)  
*Sole Lead Architect & Developer:* **Sindhi Shahejad**  
*Date of Final Release:* October 29, 2026  
*Status:* 100% Completed, Verified & Production Ready (315 Passing Automated Tests)  

---

## 1. Executive Summary & Problem Statement

### 1.1 Context
Kilachand Devchand Polytechnic (K. D. Polytechnic), Patan, established in 1961, is one of North Gujarat's premier state government polytechnics. Despite thousands of successful alumni graduating across Civil, Computer, Electrical, and Mechanical engineering, the institution lacked a unified digital infrastructure connecting alumni with current students and administration.

### 1.2 Core Objectives Solved
1. **D2D Transition Guidance:** Bridge the information gap for diploma graduates seeking admission to Degree Engineering (B.E./B.Tech) via ACPC D2D counseling.
2. **Industrial Apprenticeships:** Connect students directly with industrial zones (Patan, Mehsana, Sanand, Ahmedabad GIDCs) for mandatory 6-month diploma training.
3. **Institutional Heritage & Giving:** Provide tax-deductible giving (Section 80G), tool & drafter recycling, awards recognition, and real-time announcements.
4. **Alumni Connectivity:** Interactive geographic directory, batchmate lookup, and instant multi-channel alerts (Email & WhatsApp).

---

## 2. User Roles & Permission Matrix (RBAC)

| Role | Core Capabilities | Primary Dashboard Route |
| :--- | :--- | :--- |
| **Guest / Public** | View public landing page, alumni spotlight, academic calendar, campus history, public circulars, and AlumSmart search. | `/` |
| **Student** | Request D2D mentorship, apply for jobs/internships, request tools/drafters, take coding/MCQ challenges, generate student pass. | `/student/dashboard` |
| **Alumni** | Post job vacancies, mentor juniors, donate engineering equipment, vote in Annual Awards, generate Smart Digital I-Card, participate in discussions. | `/alumni/dashboard` |
| **Faculty** | Post department circulars, mentor batches, monitor student placement pipelines, review challenges, access alumni directory. | `/faculty/dashboard` |
| **Admin** | Full system governance, user approval/blocking, circular management, awards cycle control, noticeboard CRUD, multi-channel alert center (Email/WhatsApp), database audit registry. | `/admin/dashboard` |

---

## 3. Comprehensive Feature Scope (100% Implemented)

### Module 1: Authentication & Identity Management
- **Role-Based Registration & Login:** Email/password auth, role assignment (Student/Alumni), and administrative approval workflows.
- **Smart Digital I-Card:** Dynamic canvas-rendered ID card featuring profile picture, live verification badge, and dynamic department detection.
- **Profile Customization:** Social links, employer, designation, graduation cohort, city, and bio.

### Module 2: Searchable Alumni Directory & Geographic Mapping
- **Multi-Filter Directory:** Search by keyword, graduation year, engineering branch, employer, and city.
- **Batchmates Filter:** Instant 1-click filter targeting exact graduation cohort and branch.
- **Interactive Maps:** Leaflet.js interactive maps of Gujarat and worldwide chapters without third-party watermarks or attribution clutter.

### Module 3: Knowledge Feed & Interactive Coding Challenges
- **Algorithmic & Technical Discussions:** Threaded community discussions with Markdown syntax highlighting.
- **Dynamic Challenge System:**
  - **MCQ Format:** Configurable number of options with instant answer validation.
  - **Fill-in-the-Blanks:** Automated keyword matching and precision scoring.
  - **Coding Challenges:** Integrated code testing with input/output validation.
  - **Image & Diagram Uploads:** Support for attaching technical diagrams, circuit schematics, and lab photos.
- **Challenge Moderation Hub:** Admin moderation panel for reviewing community submissions and removing flagged entries.

### Module 4: Career, Mentorship & GIDC Connect
- **Job Board:** Full-time vacancies and mandatory 6-month diploma apprenticeships.
- **Direct Candidate Pipeline:** Applicant tracking with resume downloads and 1-click direct WhatsApp contact buttons.
- **SARTHI Mentorship Program:** Structured 1-on-1 mentorship applications, acceptance, and status tracking.

### Module 5: Giving Back, Tool Bank & Statutory Receipts
- **Equipment & Book Donation Bank:** Senior alumni donate engineering drafters, scientific calculators, and technical books to junior students.
- **Financial Contributions & Section 80G Receipts:** Integrated payment processing with automatic generation of Section 80G tax-deductible receipts delivered via PDF and WhatsApp.

### Module 6: Institutional Heritage & Recognition
- **Annual Alumni Awards:** Dynamic 4-stage stepper (Open Nominations $\rightarrow$ Jury Shortlisting $\rightarrow$ Live Voting $\rightarrow$ Convocation Enshrinement).
- **Virtual Hall of Fame:** Permanent digital gallery celebrating distinguished alumni.
- **Campus Time-Machine:** Interactive historical timeline and landmark spot exploration.

### Module 7: Multi-Channel Alert Center & Automated Communications
- **Live Gmail SMTP Delivery:** Real-time email dispatch via Google SMTP with synchronous instant delivery.
- **Direct 1-Click WhatsApp Dispatcher:** 100% free, zero-cost WhatsApp broadcast tool utilizing universal `wa.me` links with automatic phone sanitization (`+91`).
- **Weekly Newsletter & Campus Digest:** Automated cron command (`newsletter:send`) compiling and emailing weekly digests every Monday at 8:00 AM.
- **Dispatch Audit Registry:** Comprehensive database logging of every outgoing communication.

### Module 8: AI Assistants & Intelligent Search
- **AlumSmart (Ctrl+K):** Real-time natural language query concierge indexing 24+ services and site resources.
- **KDP Mitra AI Counselor:** Unrestricted 24/7 universal assistant powered by Groq LLaMA 3.3 for syllabus, admissions, transcripts, and technical inquiries.

---

## 4. Technical Architecture Specifications

| Category | Specification |
| :--- | :--- |
| **Language & Framework** | PHP 8.2+, Laravel 11.x |
| **Frontend Architecture** | Laravel Blade, Tailwind CSS 3.x, Alpine.js 3.x |
| **Database** | MySQL 8.0+ / SQLite |
| **Queue & Cache** | Sync / Database Queue, Redis/File Cache |
| **Mailing Protocol** | SMTP (Gmail TLS via port 587) |
| **Messaging Protocol** | Universal WhatsApp `wa.me` Protocol + Meta Cloud API Architecture |
| **Security Protocols** | CSRF Protection, PDO Prepared Statements, Bcrypt Hashing, Custom Role Middleware, Strict Input Validation |
| **Branding Standards** | KDP Institutional Colors (`#1e3a8a`, `#f97316`), zero external advertising/watermarks |

---

## 5. Quality Assurance & Verification Metrics

* **Total Automated Tests:** **315 Passed**
* **Total Assertions:** **1,222 Assertions**
* **Failure Rate:** **0%**
* **Regression Testing:** Automated CI test suite validating authentication, RBAC, CRUD, email mailables, WhatsApp dispatch, API endpoints, and response headers.

---

## 6. Project Sign-Off

* **Lead Architect & Developer:** Sindhi Shahejad
* **Institution:** K. D. Polytechnic, Patan
* **Final Release State:** Approved for Production Deployment.
