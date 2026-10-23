<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f6f8; color: #333; padding: 20px; }
        .container { max-width: 600px; background: #ffffff; padding: 30px; border-radius: 8px; margin: auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-top: 4px solid #8b0000; }
        .header { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #8b0000; font-size: 24px; margin: 0 0 4px 0; font-family: Georgia, serif; }
        .header p { color: #64748b; font-size: 14px; margin: 0; }
        .section-title { color: #1C3661; font-size: 17px; margin-top: 25px; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; font-weight: 700; }
        .item { margin-bottom: 12px; padding: 12px; background: #f8fafc; border-radius: 6px; border-left: 3px solid #8b0000; }
        .item h3 { margin: 0 0 4px 0; font-size: 15px; color: #0f172a; }
        .item p { margin: 0; font-size: 13px; color: #64748b; line-height: 1.4; }
        .footer { text-align: center; font-size: 12px; color: #94a3b8; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>K. D. Polytechnic, Patan</h1>
            <p>Alumni Community • Weekly Digest &amp; Campus Highlights</p>
        </div>

        <!-- Success Stories Section -->
        <h2 class="section-title">🌟 Latest Alumni Spotlights</h2>
        @forelse($successStories as $story)
            <div class="item">
                <h3>{{ $story->title }}</h3>
                <p><strong>{{ $story->alumni_name }}</strong> (Batch {{ $story->batch_year }}) • {{ $story->department }}</p>
                @if($story->story)
                    <p style="margin-top: 4px; color: #475569;">{{ Str::limit($story->story, 120) }}</p>
                @endif
            </div>
        @empty
            <p style="font-size: 13px; color: #94a3b8; font-style: italic;">No new spotlights published this week.</p>
        @endforelse

        <!-- Jobs & Apprenticeships Section -->
        <h2 class="section-title">💼 New Career &amp; Apprenticeship Openings</h2>
        @forelse($jobs as $job)
            <div class="item" style="border-left-color: #1C3661;">
                <h3>{{ $job->title }} at {{ $job->company }}</h3>
                <p>Location: {{ $job->location ?? 'Gujarat' }} • Type: {{ ucfirst($job->employment_type ?? $job->type ?? 'Full-time') }}</p>
                @if($job->application_link_or_email)
                    <p style="margin-top: 4px;"><span style="color: #1C3661; font-weight: 600;">Apply:</span> {{ $job->application_link_or_email }}</p>
                @endif
            </div>
        @empty
            <p style="font-size: 13px; color: #94a3b8; font-style: italic;">No new career opportunities posted this week.</p>
        @endforelse

        <!-- Announcements / Notices Section -->
        <h2 class="section-title">📢 Campus Announcements</h2>
        @forelse($notices as $notice)
            <div class="item" style="border-left-color: #0284c7;">
                <h3>{{ $notice->title }}</h3>
                <p>{{ Str::limit($notice->body ?? $notice->content, 140) }}</p>
            </div>
        @empty
            <p style="font-size: 13px; color: #94a3b8; font-style: italic;">No active campus announcements at this time.</p>
        @endforelse

        <div class="footer">
            <p>&copy; {{ date('Y') }} K. D. Polytechnic, Patan. All rights reserved.</p>
            <p>You received this digest as an active member of the KDP Alumni Portal.</p>
        </div>
    </div>
</body>
</html>
