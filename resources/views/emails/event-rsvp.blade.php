<div style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
    <h2 style="color: #1C3661;">RSVP Confirmed!</h2>
    <p>Hello {{ $user->name }},</p>
    <p>Your RSVP for the upcoming event <strong>{{ $event->title }}</strong> has been successfully registered.</p>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</p>
    <p style="margin-top: 20px;">We look forward to seeing you there!</p>
</div>