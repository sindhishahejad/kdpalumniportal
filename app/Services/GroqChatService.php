<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqChatService
{
    /**
     * Send a conversation message to Groq API or return intelligent fallback response.
     */
    public function chat(array $history, string $userMessage, ?User $user = null): array
    {
        $apiKey = config('services.groq.api_key');
        $model = config('services.groq.model', 'openai/gpt-oss-120b');
        $endpoint = config('services.groq.endpoint', 'https://api.groq.com/openai/v1/chat/completions');
        $temperature = config('services.groq.temperature', 0.5);
        $maxTokens = config('services.groq.max_tokens', 1200);

        // If no API key is set, immediately provide the built-in intelligent institutional counselor
        if (empty($apiKey)) {
            return $this->getInstitutionalFallback($userMessage, $user, 'API key not configured in .env');
        }

        try {
            $systemPrompt = $this->buildSystemPrompt($user);

            // Construct messages payload for OpenAI-compatible endpoint
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt]
            ];

            // Append sanitized conversation history (limit to last 10 messages for speed & token hygiene)
            $recentHistory = array_slice($history, -10);
            foreach ($recentHistory as $msg) {
                if (isset($msg['role'], $msg['content']) && in_array($msg['role'], ['user', 'assistant'])) {
                    $messages[] = [
                        'role' => $msg['role'],
                        'content' => (string) $msg['content']
                    ];
                }
            }

            // Append current message
            $messages[] = [
                'role' => 'user',
                'content' => $userMessage
            ];

            $candidateModels = array_unique(array_filter([
                $model,
                'openai/gpt-oss-120b',
                'qwen/qwen3.8-27b',
                'openai/gpt-oss-20b',
                'llama-3.3-70b-versatile',
                'llama-3.1-8b-instant',
            ]));

            foreach ($candidateModels as $candidateModel) {
                $response = Http::withToken($apiKey)
                    ->timeout(25)
                    ->post($endpoint, [
                        'model' => $candidateModel,
                        'messages' => $messages,
                        'temperature' => (float) $temperature,
                        'max_tokens' => (int) $maxTokens,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $reply = $data['choices'][0]['message']['content'] ?? null;

                    if (!empty($reply)) {
                        return [
                            'success' => true,
                            'reply' => trim($reply),
                            'model' => $candidateModel,
                            'source' => 'groq',
                        ];
                    }
                }

                // If error is not model-related (e.g. 401 unauthorized), break early
                if ($response->status() === 401) {
                    Log::warning('Groq API authentication failed (401). Please check GROQ_API_KEY in .env.');
                    break;
                }
            }

            Log::warning('Groq API response error: ' . ($response->status() ?? 'none') . ' - ' . ($response->body() ?? 'no body'));
            return $this->getInstitutionalFallback($userMessage, $user, 'Groq API service temporarily unavailable');

        } catch (\Throwable $e) {
            Log::error('Groq API Exception: ' . $e->getMessage());
            return $this->getInstitutionalFallback($userMessage, $user, $e->getMessage());
        }
    }

    /**
     * Build the institutional system prompt with comprehensive KDP Patan & GTU Academic knowledge.
     */
    protected function buildSystemPrompt(?User $user = null): string
    {
        $userInfo = 'Visitor (Guest)';
        if ($user) {
            $userInfo = "Name: {$user->name}, Role: {$user->role}, Branch/Department: " . ($user->department ?? 'General') . ", Grad Year: " . ($user->graduation_year ?? 'N/A');
        }

        return <<<PROMPT
You are "KDP Mitra" (કિલાચંદ એઆઈ મિત્ર), the official AI Academic Counselor and Campus Assistant for K. D. Polytechnic, Patan (Kilachand Devchand Polytechnic, Patan).

INSTITUTE IDENTITY:
- Established: 1961 in Patan, North Gujarat, India.
- Governance: Premier State Government Polytechnic under Commissionerate of Technical Education (CTE), Government of Gujarat.
- Affiliation & Approvals: Affiliated with Gujarat Technological University (GTU, Institute Code: 622), Approved by AICTE, New Delhi.
- Campus Address: K. D. Polytechnic, Opp. T.B. Hospital, Near Railway Station, Patan - 384265, Gujarat.
- Academic Departments: Computer Engineering, Information Technology, Mechanical Engineering, Electrical Engineering, Civil Engineering, Applied Mechanics, General Science & Humanities.

CURRENT USER CONTEXT:
{$userInfo}

CORE PURPOSE & UNRESTRICTED INTELLIGENCE:
1. ANSWER ANY PROMPT OR QUESTION: You are a friendly, highly intelligent, and capable AI assistant. Answer ANY prompt, query, or request helpfully, creatively, and accurately — including coding, writing, mathematics, science, logic, general knowledge, explanations, casual conversation, jokes, culture, entertainment, or complex edge cases. Never decline or refuse user queries with artificial restrictions or gatekeeping.
2. INSTITUTIONAL & CAMPUS EXPERTISE: While answering any question across any domain, you possess deep, specialized knowledge of K. D. Polytechnic, Patan (Estd. 1961), GTU (Gujarat Technological University), diploma engineering schemes, D2D admissions, ACPC, scholarships, placements, and campus portal services. When users ask about the college or engineering studies, provide comprehensive institutional context and active links.
3. TONE & MANNER: Friendly, articulate, encouraging, polite, and clear. Match the language and tone of the user's prompt (English, Gujarati, or Hindi).

OFFICIAL EDUCATIONAL & GTU PORTAL LINKS (Always provide active markdown links):
- GTU Previous Year Question Papers (PYQ): [GTU Old Question Papers](https://oldquestionpapers.gtu.ac.in/) & [GTU Paper Downloads](https://www.gtu.ac.in/Download1.aspx)
- GTU Exam Results & Grade Cards: [GTU Results Portal](https://www.gtu.ac.in/result.aspx)
- GTU Student Portal (Hall Ticket, Exam Form, Mid-Sem Marks): [GTU Student Portal](https://student.gtu.ac.in/)
- GTU Official Circulars & Schedules: [GTU Circulars](https://www.gtu.ac.in/Circular.aspx)
- GTU Diploma Syllabus & Teaching Schemes: [GTU Syllabus Repository](https://www.gtu.ac.in/Syllabus.aspx)
- ACPC Diploma to Degree (D2D B.E./B.Tech Admissions): [ACPC Gujarat Portal](https://acpc.gujarat.gov.in/)
- Digital Gujarat Scholarships (MYSY / Post-Matric): [Digital Gujarat Portal](https://www.digitalgujarat.gov.in/)
- NPTEL / Swayam Free Engineering Courses: [NPTEL Portal](https://nptel.ac.in/)

KDP PATAN PORTAL URLS:
- Official Document Request Desk: [Document Request Desk](/documents) (Transcripts, Bonafide, Migration, LOR)
- Training & Placement (TPO): [TPO Placement Wall](/placement) (Adani, L&T, Tata, Torrent, AM/NS recruiters)
- Alumni Directory: [Alumni Directory](/alumni)
- 1-on-1 Mentorship: [Mentorship Hub](/mentorship)
- Free Tool & Book Bank: [Pay-It-Forward Tool Bank](/tools) (Mini drafters, calculators, textbooks)
- Campus Events & Reunions: [Campus Events & Reunions](/events)
- Student Pitch Deck & Incubation: [Student Pitch Deck](/incubation)
- Alumni Smart ID Card: [My Smart ID Card](/card)
- Faculty & HOD Directory: [Faculty Directory](/faculty)
- GTU Academic Calendar: [Academic Calendar](/academic-calendar)
- Engineering Job Board: [Job Board](/jobs)
- Giving Back Hub: [Giving Back Hub](/donations)

FORMATTING & UI READABILITY DIRECTIVES:
- Maintain clear visual hierarchy: Use bold headers (`### Section`), concise paragraphs, and bullet points.
- Do NOT output unstructured pipe tables or cramped text. When using Markdown tables, include proper headers and separators.
- Ensure all portal and external URLs are formatted with clear markdown link text (e.g. `[GTU Student Portal](https://student.gtu.ac.in/)`).
- Support English, Gujarati (ગુજરાતી), and Hindi (हिन्दी) warmly and professionally based on the user's language.
PROMPT;
    }

    /**
     * Intelligent Rule-Based Fallback Engine when Groq API key is not supplied or fails.
     */
    public function getInstitutionalFallback(string $userMessage, ?User $user = null, ?string $reason = null): array
    {
        $q = mb_strtolower(trim($userMessage));
        $userName = $user ? $user->name : 'Friend';

        // 1. Jokes / Humor / Fun prompts
        if (preg_match('/\b(joke|jokes|funny|comedy|meme|memes|laugh|humor)\b/i', $q)) {
            $reply = "### 😄 Here's an engineering smile for you!\n\n"
                   . "**Why do programmers prefer dark mode?**\n"
                   . "Because light attracts bugs! 🐛💡\n\n"
                   . "**And another classic:**\n"
                   . "There are 10 types of people in the world: those who understand binary, and those who don't!\n\n"
                   . "Have another question, code puzzle, or creative prompt? Ask away!";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 1.1 Entertainment / Movies / General culture
        if (preg_match('/\b(movie|movies|song|songs|game|gaming|music|entertainment)\b/i', $q)) {
            $reply = "### 🎬 Entertainment & Tech Culture\n\n"
                   . "Taking time to unwind and explore creative hobbies keeps your mind sharp and creative!\n\n"
                   . "• **Inspiring Tech & Engineering Movies:** *The Social Network*, *The Imitation Game*, *Interstellar*, *3 Idiots*, and *October Sky*.\n"
                   . "• **Study & Coding Focus:** Lo-fi instrumental beats or ambient focus audio are great companions while solving code problems.\n\n"
                   . "Feel free to ask about any topic — from programming logic, creative writing, to campus and GTU resources!";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 2. GTU Old Question Papers / PYQ / Previous Year Papers
        if (preg_match('/\b(pyq|pyqs|old paper|old papers|question paper|question papers|previous year|exam paper|question bank|paper solution)\b/i', $q)) {
            $reply = "### 📖 GTU Diploma Previous Year Question Papers (PYQ)\n\n"
                   . "You can download past GTU Diploma Engineering theory examination papers directly from official repositories:\n\n"
                   . "• **Official GTU Old Papers Archive:** [Download GTU Old Question Papers](https://oldquestionpapers.gtu.ac.in/)\n"
                   . "• **GTU Question Paper Search:** [GTU Paper Repository](https://www.gtu.ac.in/Download1.aspx)\n"
                   . "• **GTU Diploma Syllabus & Teaching Schemes:** [GTU Syllabus & Scheme](https://www.gtu.ac.in/Syllabus.aspx)\n"
                   . "• **KDP Internal Notes & Material:** [Study Resources & Notes](/resources)\n\n"
                   . "💡 *Tip:* Filter by your GTU Subject Code (e.g. 4300001, 4320002) and semester for semester-wise Winter & Summer papers.";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 3. GTU Results / Grade Card / SPI / CPI / CGPA / Rechecking
        if (preg_match('/\b(result|results|grade card|marksheet|spi|cpi|cgpa|recheck|rechecking|reassessment|re-check|re-assessment)\b/i', $q)) {
            $reply = "### 📊 GTU Diploma Exam Results & Re-assessment\n\n"
                   . "Official GTU Diploma results and electronic grade cards are published on Gujarat Technological University's verification portals:\n\n"
                   . "• **GTU Live Results Server:** [GTU Exam Results Portal](https://www.gtu.ac.in/result.aspx)\n"
                   . "• **Student Portal Grade History:** [GTU Student Portal](https://student.gtu.ac.in/)\n"
                   . "• **Re-assessment / Re-checking Dates:** Check announcements at [GTU Circulars](https://www.gtu.ac.in/Circular.aspx)\n\n"
                   . "📌 **How to check:** Enter your 12-digit GTU Enrollment Number, choose your examination session, and enter captcha code.";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 4. GTU Student Portal / Hall Ticket / Exam Forms
        if (preg_match('/\b(student portal|hall ticket|admit card|exam form|student\.gtu|enrollment no)\b/i', $q)) {
            $reply = "### 🏛️ Official GTU Student Portal\n\n"
                   . "For university exam form filling, downloading Hall Tickets, and verifying mid-semester marks, visit:\n\n"
                   . "👉 **[GTU Student Portal (student.gtu.ac.in)](https://student.gtu.ac.in/)**\n\n"
                   . "Services available on GTU Student Portal:\n"
                   . "• Download Hall Ticket (Admit Card) for Winter / Summer examinations.\n"
                   . "• Online Examination Form Submission & Fee Payment.\n"
                   . "• View internal, continuous evaluation, and mid-sem marks submitted by KDP Patan.\n"
                   . "• Apply for grade card name corrections or degree certificates.";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 5. D2D (Diploma to Degree Admissions) / ACPC Gujarat
        if (preg_match('/\b(d2d|degree admission|acpc|after diploma|b\.e|b\.tech|degree engineering)\b/i', $q)) {
            $reply = "### 🎓 ACPC Diploma to Degree (D2D) Admissions\n\n"
                   . "After completing your 3-year Diploma Engineering at K. D. Polytechnic, Patan, you can enter directly into the 2nd Year (3rd Semester) of Bachelor of Engineering (B.E. / B.Tech):\n\n"
                   . "• **Official Admission Committee:** [ACPC Gujarat Portal](https://acpc.gujarat.gov.in/)\n"
                   . "• **Merit Calculation:** Based on STPI / Sem 5 & 6 marks (or DDCET examination criteria as notified by Government of Gujarat).\n"
                   . "• **Alumni Mentorship:** Need guidance on choosing colleges (LDCE, VGEC, BVM, Nirma, PDPU)?\n"
                   . "  👉 Connect directly with KDP seniors who cleared D2D: [1-on-1 Mentorship Hub](/mentorship)";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 6. Scholarships / MYSY / Digital Gujarat
        if (preg_match('/\b(scholarship|scholarships|mysy|digital gujarat|freeship|financial aid)\b/i', $q)) {
            $reply = "### 💰 Gujarat Government Scholarships for Diploma Students\n\n"
                   . "Students at K. D. Polytechnic, Patan are eligible for premier state government scholarship schemes:\n\n"
                   . "• **Mukhyamantri Yuva Swavalamban Yojana (MYSY):** Tuition assistance & hostel grant for meritorious students.\n"
                   . "• **Digital Gujarat Portal:** Post-Matric Scholarships for SC, ST, SEBC, EBC, and NTDNT diploma students.\n"
                   . "👉 **Apply online:** [Digital Gujarat Portal](https://www.digitalgujarat.gov.in/)\n"
                   . "• **Need document endorsements?** Apply for Bonafide certificate via [Document Request Desk](/documents).";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 7. Gujarati Greeting / General Gujarati
        if (preg_match('/(કેમ છો|નમસ્તે|નમસ્કાર|હેલો|સુપ્રભાત|પાટણ|કિલાચંદ|પોલિટેકનિક)/u', $userMessage)) {
            $reply = "### 🙏 નમસ્તે, {$userName}!\n\n"
                   . "હું **કિલાચંદ એઆઈ મિત્ર (KDP Mitra)** છું — કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ (સ્થાપના: ૧૯૬૧) નો સત્તાવાર ડિજિટલ કાઉન્સેલર.\n\n"
                   . "હું આપને નીચેની બાબતોમાં સંપૂર્ણ માર્ગદર્શન આપી શકું છું:\n\n"
                   . "• **[સત્તાવાર દસ્તાવેજો](/documents):** ટ્રાન્સક્રિપ્ટ, બોનાફાઈડ, ડિગ્રી પ્રમાણપત્ર અરજી\n"
                   . "• **[કેમ્પસ પ્લેસમેન્ટ (TPO)](/placement):** અદાણી, એલ એન્ડ ટી, ટાટા જેવી કંપનીઓની ભરતી વિગતો\n"
                   . "• **[જીટીયુ પેપર્સ (PYQ)](https://oldquestionpapers.gtu.ac.in/):** જુના પ્રશ્નપત્રો ડાઉનલોડ\n"
                   . "• **[જીટીયુ પરિણામ](https://www.gtu.ac.in/result.aspx):** ડીપ્લોમા સેમેસ્ટર રીઝલ્ટ પોર્ટલ\n"
                   . "• **[ટૂલ અને બુક બેંક](/tools):** મિની ડ્રાફ્ટર અને જીટીયુ પુસ્તકોની ફ્રી સહાય\n\n"
                   . "આપને કયા શૈક્ષણિક વિષયમાં મદદ જોઈએ છે? કૃપા કરીને જણાવો!";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 8. Greetings in English
        if (preg_match('/\b(hi|hello|hey|namaste|greetings|good morning|good evening|kdp)\b/i', $q)) {
            $reply = "### 🙏 Welcome to KDP Mitra, {$userName}!\n\n"
                   . "I am your official AI Academic Counselor for **K. D. Polytechnic, Patan** (Estd. 1961). How can I assist your engineering studies or campus journey today?\n\n"
                   . "• 📜 **[Document Request Desk](/documents)** — Apply for Official Transcripts & Bonafide\n"
                   . "• 🏢 **[TPO Placement Wall](/placement)** — Campus drives, recruiter lists & salary trends\n"
                   . "• 📖 **[GTU Old Question Papers](https://oldquestionpapers.gtu.ac.in/)** — Download semester exam PYQs\n"
                   . "• 📊 **[GTU Results Portal](https://www.gtu.ac.in/result.aspx)** — Check GTU grade cards\n"
                   . "• 🛠️ **[Pay-It-Forward Tool Bank](/tools)** — Free mini drafters, textbooks & calculators\n"
                   . "• 🤝 **[Mentorship Hub](/mentorship)** — 1-on-1 guidance with senior alumni\n\n"
                   . "Type your engineering or campus query below!";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 9. Document / Transcripts / Certificates / Bonafide
        if (preg_match('/\b(transcript|transcripts|document|documents|certificate|bonafide|lor|recommendation|mark sheet|grade card|migration)\b/i', $q)) {
            $reply = "### 📜 Official Document & Transcript Services\n\n"
                   . "At K. D. Polytechnic, Patan, students and alumni can apply for official records with end-to-end digital tracking:\n\n"
                   . "1. **Official Transcripts / Attested Mark Sheets:** For WES / IQAS evaluations, overseas studies, or degree D2D admissions.\n"
                   . "2. **Bonafide & Character Certificates:** Required for scholarships, passports, or job onboarding.\n"
                   . "3. **Letter of Recommendation (LOR):** Issued directly by Department HODs.\n\n"
                   . "👉 **Submit your online application here:** [Request Official Documents](/documents)\n\n"
                   . "⚡ Standard processing timeline is **3 to 5 working days** by the college student section.";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 10. Placement / Jobs / TPO / Salary / Recruiters
        if (preg_match('/\b(placement|placements|tpo|job|jobs|salary|package|recruiter|recruiters|interview|adani|l&t|tata|torrent|am\/ns|hiring)\b/i', $q)) {
            $reply = "### 🏢 Training & Placement (TPO) Wall\n\n"
                   . "K. D. Polytechnic Patan maintains an active Training & Placement Cell empowering diploma engineers:\n\n"
                   . "• **Top Recruiters:** Adani Solar & Power, Larsen & Toubro (L&T), Tata Motors, Torrent Power, AM/NS Steel, Essar, and Suzuki Motor Gujarat.\n"
                   . "• **Annual Placements:** Over 180+ junior technicians hired each year across core branches.\n"
                   . "• **Salary Trends:** Average ₹2.40 LPA to ₹4.20 LPA for fresh diploma passouts.\n\n"
                   . "👉 **View active campus drives:** [Training & Placement Wall](/placement)\n"
                   . "👉 **Explore alumni job openings:** [Engineering Job Board](/jobs)";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 11. Tool Bank / Mini Drafter / Calculator / Textbooks
        if (preg_match('/\b(tool|tools|book|books|bank|drafter|mini drafter|calculator|scientific calculator|textbook|casio|arduino|kit)\b/i', $q)) {
            $reply = "### 🛠️ Pay-It-Forward Student Equipment Bank\n\n"
                   . "Senior alumni donate used engineering instruments to juniors for **100% Free**:\n\n"
                   . "• **Available Tools:** Rotring & Omega Mini Drafters, Casio FX-991EX scientific calculators, GTU reference textbooks, and Arduino kits.\n"
                   . "• **Value Saved:** Saves ₹1,200 to ₹1,800 per student during first-year studies.\n"
                   . "• **Honor System:** Pass the equipment forward upon graduation!\n\n"
                   . "👉 **Claim or Donate tools:** [Pay-It-Forward Tool Bank](/tools)";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 12. Mentorship / Guidance / Career
        if (preg_match('/\b(mentor|mentors|mentorship|guidance|career|advice|guidelines|counseling)\b/i', $q)) {
            $reply = "### 🤝 1-on-1 Alumni Mentorship Hub\n\n"
                   . "Connect 1-on-1 with verified KDP alumni working at multinational companies and state PSUs:\n\n"
                   . "• Guidance on D2D Degree Engineering admissions, PSU exams, and industrial career paths.\n"
                   . "• Tracks available in Software Development, EPC Project Management, Power Distribution, and Civil Infrastructure.\n\n"
                   . "👉 **Connect with a mentor:** [1-on-1 Alumni Mentorship Hub](/mentorship)";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 13. General Default Guidance
        $reply = "### 💡 KDP Mitra Universal Assistant\n\n"
               . "I am here to assist you with any inquiry, coding challenge, explanation, or campus service!\n\n"
               . "• 📜 **[Request Official Documents](/documents)** — Transcripts, Bonafide, Migration & LOR\n"
               . "• 📖 **[GTU Old Question Papers (PYQ)](https://oldquestionpapers.gtu.ac.in/)** — Download past GTU exam papers\n"
               . "• 📊 **[GTU Results Portal](https://www.gtu.ac.in/result.aspx)** — Semester results and grade cards\n"
               . "• 🏢 **[TPO Placement Wall](/placement)** — Campus drives, recruiter lists & salary trends\n"
               . "• 🛠️ **[Tool & Book Bank](/tools)** — Free mini drafters, textbooks & calculators\n"
               . "• 🎓 **[ACPC D2D Degree Admissions](https://acpc.gujarat.gov.in/)** — Degree engineering admission guide\n"
               . "• 🤝 **[Mentorship Hub](/mentorship)** — Career counseling with alumni engineers\n\n"
               . "💡 *You can ask me any question — from general knowledge, coding problems, mathematics, and science to GTU diploma subjects, transcripts, or campus guidance.*";

        return [
            'success' => true,
            'reply' => $reply,
            'model' => 'KDP Institutional Counselor (Local Fallback)',
            'source' => 'fallback',
            'note' => $reason
        ];
    }
}
