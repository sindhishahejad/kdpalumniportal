<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f8; color: #333; padding: 20px; }
        .container { max-width: 600px; background: #ffffff; padding: 30px; border-radius: 8px; margin: auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #0f172a; font-size: 24px; margin: 0; }
        .section-title { color: #1e3a8a; font-size: 18px; margin-top: 25px; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px; }
        .item { margin-bottom: 15px; padding: 10px; background: #f9fafb; border-radius: 6px; }
        .item h3 { margin: 0 0 5px 0; font-size: 16px; color: #111; }
        .item p { margin: 0; font-size: 14px; color: #6b7280; }
        .footer { text-align: center; font-size: 12px; color: #9ca3af; margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>K. D. Polytechnic Alumni Association</h1>
            <p>Weekly Campus & Community Digest</p>
        </div>

        <!-- Success Stories Section -->
        <h2 class="section-title">🌟 Latest Success Stories</h2>
        @forelse($successStories as $story)
            <div class="item">
                <h3>{{ $story->title }}</h3>
                <p>{{ $story->alumni_name }} (Batch {{ $story->batch_year }}) - {{ $story->department }}</p>
            </div>
        @empty
            <p style="font-size: 14px; color: #6b7280;">No new success stories this week.</p>
        @endforelse

        <!-- Jobs Section -->
        <h2 class="section-title">💼 New Job & Internship Openings</h2>
        @forelse($jobs as $job)
            <div class="item">
                <h3>{{ $job->title }} at {{ $job->company }}</h3>
                <p>Location: {{ $job->location }} | Type: {{ ucfirst($job->type) }}</p>
            </div>
        @empty
            <p style="font-size: 14px; color: #6b7280;">No new job postings this week.</p>
        @endforelse

        <!-- Notices Section -->
        <h2 class="section-title">📢 Campus Announcements</h2>
        @forelse($notices as $notice)
            <div class="item">
                <h3>{{ $notice->title }}</h3>
                <p>{{ Str::limit($notice->content, 100) }}</p>
            </div>
        @empty
            <p style="font-size: 14px; color: #6b7280;">No new notices this week.</p>
        @endforelse

        <div class="footer">
            <p>&copy; 2026 K. D. Polytechnic, Patan. All rights reserved.</p>
            <p>You are receiving this email because you are registered on the KDP Alumni Portal.</p>
        </div>
    </div>
</body>
</html>