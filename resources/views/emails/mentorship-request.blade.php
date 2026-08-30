<div style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
    <h2 style="color: #1C3661;">New Mentorship Request</h2>
    <p>Hello,</p>
    <p><strong>{{ $student->name }}</strong> has requested mentorship guidance from you:</p>
    <blockquote style="border-left: 4px solid #1C3661; padding-left: 10px; margin: 15px 0; color: #555;">
        "{{ $message }}"
    </blockquote>
    <p style="margin-top: 20px;">
        <a href="{{ route('mentorship.index') }}" style="background: #1C3661; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 3px; font-weight: bold;">View Request</a>
    </p>
</div>