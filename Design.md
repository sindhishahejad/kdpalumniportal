# Design System & UI/UX Specification: K. D. Polytechnic Alumni Portal

**Document Version:** 1.0.0 (Production Design System)  
**Lead Designer & Software Architect:** **Sindhi Shahejad**  
**Institution:** K. D. Polytechnic, Patan (Estd. 1961)  
**Date:** October 29, 2026  

---

## 1. Design Philosophy & Visual Identity

The design system of the **K. D. Polytechnic Alumni Portal** reflects the prestige, industrial heritage, and technical excellence of Government Polytechnic, Patan (Estd. 1961).

The aesthetic is reverse-engineered from official Gujarat government polytechnic standards ([kdppatan.ac.in](https://www.kdppatan.ac.in)) and elevated with modern SaaS design principles:
* **Academic Authority:** Deep navy blues and rich contrast conveying institutional trust.
* **Gujarat Heritage:** Saffron / Indian Orange accents representing vitality, innovation, and national pride.
* **Utility & High Ergonomics:** Clean, card-based modular layouts built with Tailwind CSS and Alpine.js, minimizing visual friction.
* **Uncluttered & Pure:** Strict zero-watermark policy. All maps, widgets, and dialogs are free of vendor advertising.

---

## 2. Color Palette & Design Tokens

### 2.1 Core Institutional Colors

| Color Token | Hex Code | Tailwind Equivalent | Purpose / Application |
| :--- | :--- | :--- | :--- |
| **KDP Navy Blue** | `#1e3a8a` | `bg-blue-900` / `#1e3a8a` | Primary navigation bar, main action buttons, hero backgrounds, headings. |
| **KDP Deep Dark** | `#0f2042` | `bg-[#0f2042]` | High-contrast hero containers, dark footer, primary card accents. |
| **KDP Saffron/Orange**| `#f97316` / `#ea580c`| `bg-orange-500` / `#f97316` | Key CTAs, active tab indicators, hover states, notification badges. |
| **KDP Royal Blue** | `#3b82f6` | `bg-blue-500` | Sub-headings, links, focus rings, info highlights. |
| **Institutional Dark**| `#111827` | `bg-slate-900` | High-contrast typography, footer background. |
| **Background Light** | `#f8fafc` / `#f1f5f9` | `bg-slate-50` / `bg-slate-100` | Global page background, secondary cards, input surfaces. |

### 2.2 Semantic & Status Colors
* **Verified / Success:** Emerald (`#10b981` / `bg-emerald-600`) — verified alumni badge, live status indicators, completed steps.
* **Warning / In Review:** Amber (`#f59e0b` / `bg-amber-500`) — pending verifications, stage in progress.
* **Critical / Danger:** Rose / Crimson (`#e11d48` / `bg-rose-600`) — rejection, destructive actions, emergency alerts.

---

## 3. Typography & Type Scale

The portal uses Google Fonts loaded asynchronously with fallback system font stacks:
* **Headings & Titles:** `Poppins`, `Inter`, `sans-serif` (Weights: `600`, `700`, `800`)
* **Body & UI Elements:** `Open Sans`, `Inter`, `system-ui` (Weights: `400`, `500`, `600`)
* **Formal Honors & Heritage Titles:** Serif font (`font-serif`, `Merriweather`, `Georgia`) for Annual Alumni Awards.
* **Code & Technical Data:** Monospace stack (`JetBrains Mono`, `Consolas`, `Monaco`, `monospace`) for code challenges, terminal previews, and JSON payloads.

---

## 4. Layout Architecture & Component Hierarchy

### 4.1 Master Page Shell
* **Top Institutional Bar:** Displaying official Gujarati title (*કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ*), contact info, AlumSmart search bar (`Ctrl+K`), and multilingual switcher.
* **Primary Navigation:** Sticky header with signature saffron underline animation on hover and active routes.
* **Content Container:** Standardized max widths (`max-w-7xl mx-auto px-4 sm:px-6 lg:px-8`) preventing wide-screen horizontal stretching.
* **Institutional Footer:** Comprehensive sitemap, statutory approvals, department directory, and copyright notices.

### 4.2 Signature UI Components

#### 1. Smart Digital I-Card
* Dimensions: 3.375" x 2.125" aspect ratio (standard CR80 credit card form factor).
* Gradients: Sleek metallic slate-to-navy background with subtle glassmorphism overlay.
* Dynamic Elements: Profile picture with green verification checkmark, ID number, graduation year, and dynamically mapped department tag (e.g. `COMPUTER ENGINEERING`, `CIVIL ENGINEERING`).

#### 2. Annual Awards 4-Stage Stepper
* Visual Progression:
  * **Step 1:** Open Nominations
  * **Step 2:** Jury Shortlisting
  * **Step 3:** Live Voting
  * **Step 4:** Convocation Enshrinement
* Dynamic Highlights: Active stage highlighted with vibrant saffron background and pulsing indicator; completed steps rendered in muted emerald; upcoming steps in slate border.

#### 3. Live Smartphone WhatsApp Preview Container
* Realistic smartphone chassis (`bg-[#0b141a]` with curved rounded edges `rounded-[36px]`).
* Dynamic preview bubble updating in real-time as the administrator types an alert subject, message, and URL.

#### 4. Interactive Challenge Board UI
* MCQ Questions: Option selector cards with clean radio buttons and instant green/red validation states.
* Fill-in-the-Blanks: Inline text input fields with real-time feedback.
* Code Verification: Formatted code viewer with dark IDE theme and test output verification console.

---

## 5. Watermark & Branding Policy

To maintain the highest level of institutional dignity:
1. **Suppression of Third-Party Map Watermarks:** Leaflet map attribution controls are disabled globally via CSS (`.leaflet-control-attribution { display: none !important; }`) and initialization parameters (`{ attributionControl: false }`).
2. **AI Chatbot Branding:** Groq inference API watermarks have been removed from the full-page counselor and floating widget in favor of clean institutional identifiers (`KDP Mitra • AI Universal Guide`).
3. **Institutional Badges:** All pages prominently feature the authentic motto and year of establishment: *Estd. 1961*.

---

## 6. Accessibility & Responsiveness (WCAG 2.1 AA)

* **Contrast Ratios:** Minimum 4.5:1 text-to-background contrast ratio maintained across all viewports.
* **Keyboard Navigation:** Full support for `Tab` focusing, with `Ctrl+K` global keyboard shortcut to launch AlumSmart.
* **Responsive Breakpoints:**
  * Mobile: `< 640px` (single-column cards, slide-over mobile navigation)
  * Tablet: `640px - 1024px` (two-column dashboard layouts)
  * Desktop: `> 1024px` (three-column and multi-card grids)
