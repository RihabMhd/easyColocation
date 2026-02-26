{{-- resources/views/emails/invite.blade.php --}}
<h1>Hello!</h1>
<p>You have been invited to join a colocation on **EasyColoc**.</p>
<p>Click the link below to accept:</p>

<a href="{{ route('invitations.accept', $invitation->token) }}" 
   style="background: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
    Accept Invitation
</a>