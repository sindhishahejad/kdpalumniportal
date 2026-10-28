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
        $model = config('services.groq.model', 'llama-3.3-70b-versatile');
        $endpoint = config('services.groq.endpoint', 'https://api.groq.com/openai/v1/chat/completions');
        $temperature = config('services.groq.temperature', 0.6);
        $maxTokens = config('services.groq.max_tokens', 1024);

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

            $response = Http::withToken($apiKey)
                ->timeout(25)
                ->post($endpoint, [
                    'model' => $model,
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
                        'model' => $model,
                        'source' => 'groq',
                    ];
                }
            }

            Log::warning('Groq API response error: ' . $response->status() . ' - ' . $response->body());
            return $this->getInstitutionalFallback($userMessage, $user, 'Groq API service temporarily unavailable');

        } catch (\Throwable $e) {
            Log::error('Groq API Exception: ' . $e->getMessage());
            return $this->getInstitutionalFallback($userMessage, $user, $e->getMessage());
        }
    }

    /**
     * Build the institutional system prompt with comprehensive KDP Patan knowledge.
     */
    protected function buildSystemPrompt(?User $user = null): string
    {
        $userInfo = 'Visitor (Guest)';
        if ($user) {
            $userInfo = "Name: {$user->name}, Role: {$user->role}, Branch/Department: " . ($user->department ?? 'General') . ", Grad Year: " . ($user->graduation_year ?? 'N/A');
        }

        return <<<PROMPT
You are "KDP Mitra" (કિલાચંદ એઆઈ મિત્ર), the official, intelligent AI Counselor and Campus Assistant for K. D. Polytechnic, Patan (Kilachand Devchand Polytechnic, Patan).

INSTITUTE BACKGROUND:
- Established: 1961 in Patan, North Gujarat, India.
- Governance: Premier Government Polytechnic under Commissionerate of Technical Education (CTE), Government of Gujarat.
- Affiliation & Approvals: Affiliated with Gujarat Technological University (GTU, Code: 622), Approved by AICTE, New Delhi.
- Campus Address: K. D. Polytechnic, Opp. T.B. Hospital, Near Railway Station, Patan - 384265, Gujarat.
- Departments: Computer Engineering, Information Technology, Mechanical Engineering, Electrical Engineering, Civil Engineering, Applied Mechanics, General Science & Humanities.

CURRENT USER CONTEXT:
{$userInfo}

PORTAL URLS & CAPABILITIES (Always provide clickable Markdown links when relevant):
- Alumni Directory: [Alumni Directory](/alumni) - Search and network with KDP diploma engineers worldwide.
- 1-on-1 Mentorship: [Mentorship Hub](/mentorship) - Connect students with senior alumni for career guidance.
- Training & Placement: [TPO Placement Wall](/placement) - Check placement statistics, recruited companies (Adani, L&T, Tata, Torrent, AM/NS), and drives.
- Official Document Request Desk: [Document Request Desk](/documents) - Apply for official Transcripts, Bonafide, Migration, and Recommendation letters.
- Student Book & Tool Bank: [Pay-It-Forward Tool Bank](/tools) - Free mini drafters, GTU textbooks, scientific calculators, and Arduino kits.
- Alumni Giving Back: [Giving Back Hub](/donations) - Contribute to student scholarships, campus lab upgrades, and incubation funds.
- Campus Events & Reunions: [Campus Events & Reunions](/events) - Technical symposiums, annual batch reunions, and instant entry passes.
- Project Pitch Deck: [Student Pitch Deck](/incubation) - Student startup and innovation pitches seeking alumni seed grants.
- Alumni Awards & Hall of Fame: [Alumni Awards](/awards) - Outstanding alumni achievements and voting ballots.
- Smart ID Card: [Alumni Smart ID Card](/card) - Official digital membership card with verified QR code.
- Faculty Directory: [Faculty & HOD Directory](/faculty) - Department heads, professors, and contact emails.
- GTU Academic Calendar: [GTU Academic Calendar](/academic-calendar) - Examination schedules, GTU circulars, and semester timetables.
- Engineering Job Board: [Job Board](/jobs) - Alumni referrals and diploma engineering vacancies.

RESPONSE GUIDELINES:
1. Tone: Respectful, welcoming, inspiring, warm, and academically authoritative. Reflect the dignity of an established government technical institute.
2. Languages: Fluidly support English, Gujarati (ગુજરાતી), and Hindi (हिन्दी). If the user asks in Gujarati, respond in clear, grammatically sound Gujarati (using English for URLs and technical names). If the user asks in Hindi, reply in Hindi. If in English, reply in English.
3. Brevity & Clarity: Keep answers concise, helpful, and formatted with bullet points and bold headers. Always include relevant portal links so the user can take action immediately.
4. Accuracy: Never hallucinate phone numbers or fake dates. Direct administrative questions to the Principal's Office or `/contact`.
PROMPT;
    }

    /**
     * Intelligent Rule-Based Fallback Engine when Groq API key is not supplied or fails.
     */
    public function getInstitutionalFallback(string $userMessage, ?User $user = null, ?string $reason = null): array
    {
        $q = mb_strtolower(trim($userMessage));
        $userName = $user ? $user->name : 'Friend';

        // 1. Gujarati Greeting / General Gujarati
        if (preg_match('/(કેમ છો|નમસ્તે|નમસ્કાર|હેલો|સુપ્રભાત|પાટણ|કિલાચંદ|પોલિટેકનિક)/u', $userMessage)) {
            $reply = "નમસ્તે **{$userName}**! 🙏\n\n"
                   . "હું **કિલાચંદ એઆઈ મિત્ર (KDP Mitra)** છું — કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ (સ્થાપના: ૧૯૬૧) નો સત્તાવાર ડિજિટલ સહાયક.\n\n"
                   . "હું આપને નીચેની બાબતોમાં સહાય કરી શકું છું:\n"
                   . "• **[સત્તાવાર દસ્તાવેજો](/documents)**: ટ્રાન્સક્રિપ્ટ, બોનાફાઈડ, ડિગ્રી પ્રમાણપત્ર અરજી\n"
                   . "• **[કેમ્પસ પ્લેસમેન્ટ (TPO)](/placement)**: અદાણી, એલ એન્ડ ટી, ટાટા જેવી કંપનીઓની ભરતી માહિતી\n"
                   . "• **[અલુમ્નાઈ ડિરેક્ટરી](/alumni)**: વિશ્વભરમાં વસેલા કેડીપી ઇજનેરો સાથે સંપર્ક\n"
                   . "• **[મેન્ટોરશીપ હબ](/mentorship)**: સિનિયર ઇજનેરો પાસેથી માર્ગદર્શન\n"
                   . "• **[ટૂલ અને બુક બેંક](/tools)**: મિની ડ્રાફ્ટર અને જીટીયુ પુસ્તકોની ફ્રી સહાય\n\n"
                   . "આપને કઈ માહિતીની જરૂર છે? કૃપા કરીને જણાવો!";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 2. Greetings in English / Hindi
        if (preg_match('/\b(hi|hello|hey|namaste|greetings|good morning|good evening|kem cho|kdp)\b/i', $q)) {
            $reply = "Namaste and warm greetings **{$userName}**! 🙏 Welcome to **KDP Mitra**, the official AI counselor for **K. D. Polytechnic, Patan** (Estd. 1961).\n\n"
                   . "How can I assist your polytechnic or alumni journey today? Here are quick shortcuts:\n\n"
                   . "• 📜 **Apply for Transcripts & Records:** [Official Document Request Desk](/documents)\n"
                   . "• 🏢 **Placement & Career:** [TPO Placement Wall](/placement) & [Job Board](/jobs)\n"
                   . "• 🤝 **Connect with Alumni:** [Alumni Directory](/alumni) & [1-on-1 Mentorship](/mentorship)\n"
                   . "• 🛠️ **Free Tools for Juniors:** [Pay-It-Forward Tool & Book Bank](/tools)\n"
                   . "• 🗓️ **GTU Circulars & Schedules:** [GTU Academic Calendar](/academic-calendar)\n\n"
                   . "Feel free to type your question below!";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 3. Document / Transcripts / Certificates / Bonafide
        if (preg_match('/\b(transcript|transcripts|document|documents|certificate|bonafide|lor|recommendation|mark sheet|grade card|migration)\b/i', $q)) {
            $reply = "### 📜 Official Document & Transcript Services\n\n"
                   . "At K. D. Polytechnic, Patan, alumni and students can officially request documents with digital tracking:\n\n"
                   . "1. **Transcripts / Attested Mark Sheets:** For foreign university admissions (WES / IQAS) or degree engineering D2D admissions.\n"
                   . "2. **Bonafide & Character Certificates:** Required for scholarships, passports, or employment verification.\n"
                   . "3. **Letter of Recommendation (LOR):** Endorsed by respective Department HODs.\n\n"
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

        // 4. Placement / Jobs / TPO / Salary / Recruiters
        if (preg_match('/\b(placement|placements|tpo|job|jobs|salary|package|recruiter|recruiters|interview|adani|l&t|tata|torrent|am\/ns|hiring)\b/i', $q)) {
            $reply = "### 🏢 Training & Placement (TPO) Wall\n\n"
                   . "K. D. Polytechnic Patan maintains an active Training & Placement Cell empowering diploma engineers:\n\n"
                   . "• **Top Campus Recruiters:** Adani Solar & Power, Larsen & Toubro (L&T), Tata Motors, Torrent Power, AM/NS Steel, Essar, and Suzuki Motor Gujarat.\n"
                   . "• **Annual Offers:** Over 180+ junior diploma technicians recruited annually across Mechanical, Electrical, Civil, and Computer departments.\n"
                   . "• **Average Package:** ₹2.40 LPA to ₹4.20 LPA for fresh diploma passouts.\n\n"
                   . "👉 **Explore active drives:** [Training & Placement Wall](/placement)\n"
                   . "👉 **Browse alumni job referrals:** [Engineering Job Board](/jobs)";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 5. Tool Bank / Mini Drafter / Calculator / Textbooks
        if (preg_match('/\b(tool|tools|book|books|bank|drafter|mini drafter|calculator|scientific calculator|textbook|casio|arduino|kit)\b/i', $q)) {
            $reply = "### 🛠️ Pay-It-Forward Student Tool & Book Bank\n\n"
                   . "The **Pay-It-Forward Equipment Bank** enables senior alumni to pass down expensive engineering equipment to junior technicians for **100% Free**:\n\n"
                   . "• **Available Items:** Rotring & Omega Mini Drafters, Casio FX-991EX scientific calculators, GTU diploma reference textbooks, Arduino microcontroller kits, and workshop safety gear.\n"
                   . "• **Estimated Student Savings:** More than ₹1,200 to ₹1,800 saved per student.\n"
                   . "• **Honor Pledge:** Juniors agree to pass the equipment forward to incoming juniors upon graduation!\n\n"
                   . "👉 **Claim or Donate tools here:** [Pay-It-Forward Tool Bank](/tools)";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 6. Mentorship / Guidance / Career
        if (preg_match('/\b(mentor|mentors|mentorship|guidance|career|advice|guidelines|counseling)\b/i', $q)) {
            $reply = "### 🤝 1-on-1 Alumni Mentorship Hub\n\n"
                   . "Need guidance on D2D Degree Engineering admissions, cracking PSU exams, or industrial career transitions?\n\n"
                   . "• Connect 1-on-1 with verified KDP alumni working at top multinational corporations and public sector units.\n"
                   . "• Book mentoring slots in disciplines including Software Development, EPC Project Management, Power Distribution, and Civil Infrastructure.\n\n"
                   . "👉 **Find your mentor today:** [1-on-1 Alumni Mentorship Hub](/mentorship)";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 7. GTU / Academic Calendar / Exam / Circulars
        if (preg_match('/\b(gtu|academic|calendar|exam|exams|circular|circulars|schedule|syllabus|semester|mid sem|remedial)\b/i', $q)) {
            $reply = "### 🗓️ GTU Academic Calendar & Circulars\n\n"
                   . "Stay updated with official academic schedules for Gujarat Technological University (GTU) Diploma Engineering:\n\n"
                   . "• Semester commencement and term-end dates.\n"
                   . "• GTU Winter & Summer theory & practical examination timetables.\n"
                   . "• Official notifications issued by Principal Office, KDP Patan.\n\n"
                   . "👉 **View latest schedules:** [GTU Academic Calendar & Circulars](/academic-calendar)";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 8. Faculty / Departments / HOD / Principal
        if (preg_match('/\b(faculty|hod|principal|professor|lecturer|department|departments|computer|mechanical|civil|electrical|it)\b/i', $q)) {
            $reply = "### 👥 Academic Departments & Faculty Directory\n\n"
                   . "K. D. Polytechnic Patan houses 7 dedicated engineering departments:\n\n"
                   . "1. **Computer Engineering**\n"
                   . "2. **Information Technology**\n"
                   . "3. **Mechanical Engineering**\n"
                   . "4. **Electrical Engineering**\n"
                   . "5. **Civil Engineering**\n"
                   . "6. **Applied Mechanics**\n"
                   . "7. **General Science & Humanities**\n\n"
                   . "👉 **View faculty profiles and HOD contact emails:** [Faculty & HOD Directory](/faculty)";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 9. Events & Reunions
        if (preg_match('/\b(event|events|reunion|reunions|symposium|techfest|workshop|gathering|pass|ticket)\b/i', $q)) {
            $reply = "### 📅 Campus Activities & Alumni Reunions\n\n"
                   . "K. D. Polytechnic regularly hosts state-level workshops, technical symposiums, and annual alumni reunions:\n\n"
                   . "• Browse upcoming campus workshops and technical events.\n"
                   . "• One-click RSVP to receive your official **Digital Event Entry Pass** with secure QR verification for campus gate access.\n\n"
                   . "👉 **Explore scheduled events:** [Campus Events & Reunions](/events)";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 10. Smart ID Card
        if (preg_match('/\b(card|id card|smart card|membership|alumni card)\b/i', $q)) {
            $reply = "### 🪪 Official Alumni Smart ID Card\n\n"
                   . "Every verified alumnus of KDP Patan is eligible for the official digital Smart ID Card:\n\n"
                   . "• Features your enrollment number, branch, graduation year, and unique verification barcode/QR.\n"
                   . "• Grants campus library access, alumni guest house privileges, and entry to institutional reunions.\n\n"
                   . "👉 **View and download your pass:** [My Smart ID Card](/card)";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 11. Giving Back / Donations
        if (preg_match('/\b(donate|donations|donation|giving back|fund|crowdfunding|scholarship|scholarships)\b/i', $q)) {
            $reply = "### 💖 Alumni Giving Back & Crowdfunding Hub\n\n"
                   . "Empower needy diploma students and modernize laboratory infrastructure at K. D. Polytechnic:\n\n"
                   . "• Contribute towards student tuition assistance, modern CNC machinery, or IoT laboratory hardware.\n"
                   . "• Track project transparency, donor walls, and fund utilization reports.\n\n"
                   . "👉 **Support your alma mater:** [Giving Back Hub](/donations)";

            return [
                'success' => true,
                'reply' => $reply,
                'model' => 'KDP Institutional Counselor (Local Fallback)',
                'source' => 'fallback',
                'note' => $reason
            ];
        }

        // 12. General Default Knowledge Guidance
        $reply = "Thank you for asking! As **KDP Mitra**, here is what I can help you with on the K. D. Polytechnic Patan portal:\n\n"
               . "• 📜 **[Request Official Documents](/documents)** — Transcripts, Bonafide, Migration & LOR\n"
               . "• 🏢 **[TPO Placement Wall](/placement)** — Campus drives, recruiter lists & salary trends\n"
               . "• 🎓 **[Alumni Directory](/alumni)** — Connect with diploma alumni worldwide\n"
               . "• 🤝 **[Mentorship Hub](/mentorship)** — 1-on-1 career guidance with senior engineers\n"
               . "• 🛠️ **[Tool & Book Bank](/tools)** — Free mini drafters, textbooks & calculators\n"
               . "• 🗓️ **[GTU Academic Calendar](/academic-calendar)** — Exam dates & GTU circulars\n"
               . "• 👥 **[Faculty & HOD Directory](/faculty)** — Department professors & contact info\n\n"
               . "💡 *Tip:* You can ask specific questions like *'How do I apply for transcripts?'*, *'Which companies hire Mechanical students?'*, or *'Can I borrow a mini drafter?'*";

        return [
            'success' => true,
            'reply' => $reply,
            'model' => 'KDP Institutional Counselor (Local Fallback)',
            'source' => 'fallback',
            'note' => $reason
        ];
    }
}
