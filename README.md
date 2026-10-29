# K. D. Polytechnic, Patan — Alumni Association & Heritage Portal

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel 11">
</p>

<p align="center">
  <strong>Government Polytechnic, Patan • Established 1961 • Affiliated to GTU & Approved by AICTE</strong><br>
  <em>A modern, high-performance institutional portal connecting diploma students, alumni, faculty, and administrators.</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=flat-square&logo=alpine.js&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/Tests-315%20Passed%20(1222%20Assertions)-success?style=flat-square&logo=checkmarx&logoColor=white" alt="Tests 315 Passed">
  <img src="https://img.shields.io/badge/Database-MySQL%20%2F%20SQLite-00758F?style=flat-square&logo=mysql&logoColor=white" alt="Database">
  <img src="https://img.shields.io/badge/Developer-Sindhi%20Shahejad-1e3a8a?style=flat-square" alt="Sole Lead Developer">
</p>

---

## 👨‍💻 Engineering Leadership & Authorship

* **Sole Lead Architect & Full-Stack Developer:** **Sindhi Shahejad**
* **Institution:** K. D. Polytechnic, Patan (Department of Computer Engineering)
* **Project Status:** 100% Production Ready & Feature Complete
* **Verification:** Comprehensive Automated Test Suite (315 Feature & Unit Tests, 1,222 Assertions)

---

## 🏛️ Project Vision & Purpose

Founded in 1961, **Kilachand Devchand Polytechnic (K. D. Polytechnic), Patan** has produced thousands of engineers serving industry and academia across Gujarat and the globe. 

This portal provides a unified digital backbone bridging the multi-decade alumni network with current diploma students:
* **Diploma-to-Degree (D2D) Pathways:** Connecting diploma candidates with senior alumni pursuing B.E./B.Tech degrees at premier universities.
* **GIDC Apprenticeship & Job Placement:** Direct hiring pipelines for mandatory 6-month industrial internships across Gujarat Industrial Development Corporation (GIDC) estates.
* **SARTHI 1-on-1 Mentorship:** Structured guidance pairings between diploma students and industry alumni.
* **Digital Heritage & Recognition:** Annual Alumni Awards with live community voting, Hall of Fame registry, and Campus Time-Machine.
* **Tool & Drafter Donation Bank:** Senior alumni donating engineering instruments, drafters, calculators, and lab tools to juniors with statutory Section 80G tax receipts and instant WhatsApp delivery confirmation.
* **Smart Alumni I-Card:** Cryptographically verified digital alumni ID cards featuring instant verification.

---

## 🚀 Key Feature Modules

### 1. Identity, Access & Dynamic Dashboards
* **Multi-Role RBAC:** Dedicated dashboards and permission boundaries for **Students**, **Alumni**, **Faculty**, and **Administrators**.
* **Smart Digital I-Card:** Instant photo, QR verification, dynamic branch resolution (CIVIL, COMPUTER, ELECTRICAL, MECHANICAL), and downloadable printable passes.
* **Dev Quick Fill & Authentication:** Secure session-based authentication with dev-login helpers for seamless review.

### 2. Searchable Alumni Directory & Geographic Mapping
* **Search & Filters:** Search by name, graduation batch year, engineering branch, employer, and city.
* **Batchmates Shortcut:** One-click directory filtering for exact graduation cohort.
* **Interactive Geographic Map:** Clean, high-performance Leaflet-powered maps plotting alumni distribution across Gujarat and global chapters without third-party watermarks.

### 3. Knowledge Feed & Interactive Challenge Board
* **Algorithmic & Technical Discussions:** Threaded community feed supporting Markdown code formatting and rich media.
* **Interactive Problem Types:**
  * **MCQs (Multiple Choice Questions):** Configurable number of options with instant automated answer evaluation.
  * **Fill-in-the-Blanks:** Automated keyword matching and precision answer checking.
  * **Code Verification:** Coding challenges with expected output verification and student test runs.
  * **Image & Diagram Attachments:** Faculty, alumni, and admins can attach technical schematics, lab photos, and problem diagrams.
* **Content Moderation Hub:** Admin moderation pipeline to review discussions and prune inappropriate replies.

### 4. Career & Industry Placement (GIDC Connect)
* **Job Board & Apprenticeship Pipeline:** Full-time roles and 6-month mandatory diploma apprenticeships.
* **Direct Alumni Referrals:** Students request internal referrals directly from alumni working at target firms.
* **Candidate Tracking & WhatsApp Integration:** 1-click WhatsApp buttons in candidate review pipelines.

### 5. Multi-Channel Alert Center (Email & WhatsApp)
* **Live Gmail SMTP Delivery:** Real-time email dispatch using Google SMTP with instant synchronous delivery mode (`QUEUE_CONNECTION=sync`).
* **1-Click Smart Direct WhatsApp Dispatch (`wa.me`):** 100% free, zero-cost WhatsApp broadcast tool. Normalizes Indian phone numbers (`+91`), encodes institutional announcements, and opens WhatsApp Web/Mobile ready to send with 1 click.
* **Audit Registry:** Every broadcast is permanently logged to the `whats_app_broadcast_logs` database table.
* **Weekly Newsletter & Campus Digest:** Automated digest compiled every Monday at 8:00 AM featuring recent stories, job openings, and circulars.

### 6. AlumSmart & KDP Mitra AI Counselors
* **AlumSmart (Ctrl+K):** Lightning-fast natural-language search indexing 24+ portal services, alumni profiles, jobs, notices, and tool items.
* **KDP Mitra AI Universal Counselor:** Integrated AI assistant providing 24/7 academic counseling, GTU rules, D2D guidance, syllabus help, and general inquiries without restrictive guardrails.

### 7. Giving Back & Section 80G Statutory Receipts
* **Tool & Book Bank:** Peer-to-peer donation of engineering drafters, scientific calculators, and technical textbooks.
* **Online Giving & Tax Deductible Receipts:** Razorpay payment gateway integration with instant downloadable Section 80G tax exemption receipts delivered automatically via WhatsApp and PDF.

---

## 🛠️ Technology Stack

| Layer | Technologies |
| :--- | :--- |
| **Backend Framework** | Laravel 11.x (PHP 8.2+) |
| **Frontend UI** | Laravel Blade Engine, Tailwind CSS 3.x, Alpine.js 3.x |
| **Database** | MySQL 8.0+ / SQLite (Configurable via `.env`) |
| **Authentication** | Laravel Breeze with custom Role-Based Access Control (RBAC) |
| **Styling & Theme** | Reverse-engineered official KDP Patan aesthetic (Navy Blue `#1e3a8a`, Saffron `#f97316`) |
| **Email Protocol** | SMTP (Gmail TLS via port 587) & Laravel Mailable |
| **Messaging** | Universal WhatsApp `wa.me` protocol + Meta Cloud API ready |
| **Asset Pipeline** | Vite, PostCSS, Autoprefixer |
| **Testing** | PHPUnit / Pest (315 passing automated test suites) |

---

## ⚙️ Installation & Local Setup

### Prerequisites
* PHP 8.2 or higher (with `pdo`, `mbstring`, `openssl`, `curl` extensions)
* Composer 2.x
* Node.js 18+ & NPM
* MySQL or SQLite

### Setup Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/kdpalumniportal.git
   cd kdpalumniportal
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Migration & Seeding:**
   ```bash
   php artisan migrate --seed
   ```

5. **Configure Email (Gmail SMTP):**
   In `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-google-app-password
   MAIL_FROM_ADDRESS="your-email@gmail.com"
   MAIL_FROM_NAME="K. D. Polytechnic, Patan Alumni Association"
   QUEUE_CONNECTION=sync
   ```

6. **Compile Assets & Start Local Server:**
   ```bash
   npm run build
   php artisan serve
   ```
   Open your browser and navigate to **`http://localhost:8000`**.

---

## 🧪 Automated Testing Suite

The portal includes an exhaustive test suite covering all security, controllers, authentication, business logic, and UI workflows.

Run all tests:
```bash
php artisan test
```

### Verified Test Results:
```text
Tests:    315 passed (1222 assertions)
Duration: ~30-40 seconds
Status:   100% Green
```

---

## 👨‍💻 Authorship & Technical Credits

This portal was designed, architected, and engineered by:
* **Sindhi Shahejad** — *Sole Lead Architect & Full-Stack Developer*
* Technical credits are permanently embedded in:
  * HTTP Response Headers (`X-Developer`, `X-Lead-Architect`, `X-Portal-Author`)
  * Master Layout Metadata (`<meta name="author" content="Sindhi Shahejad">`)
  * Web Standards Registry (`/humans.txt` & `robots.txt`)
  * Browser Developer Console Signature (`F12 -> Console`)
  * Screen-Reader DOM Microdata

---

## 📄 License

Developed for **K. D. Polytechnic, Patan**. All rights reserved © 2026.
