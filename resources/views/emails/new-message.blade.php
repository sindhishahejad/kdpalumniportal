<div style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
    <h2 style="color: #1C3661;">New Message Received</h2>
    <p>Hello,</p>
    <p>You have received a new message from <strong>{{ $sender->name }}</strong> on the K.D. Polytechnic Alumni Portal:</p>
    <blockquote style="border-left: 4px solid #8b0000; padding-left: 10px; margin: 15px 0; color: #555;">
        "{{ $messageText }}"
    </blockquote>
    <p style="margin-top: 20px;">
        <a href="{{ route('messages.inbox', $sender->id) }}" style="background: #8b0000; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 3px; font-weight: bold;">Reply Now</a>
    </p>
</div>