<!DOCTYPE html>
<html>
<body>
    <h1>Réponse à votre message</h1>
    <p>Bonjour {{ $messageModel->name }},</p>
    <p>Vous nous avez écrit au sujet de "{{ $messageModel->subject }}".</p>
    <p>Notre réponse:</p>
    <blockquote>{!! nl2br(e($reply)) !!}</blockquote>
    <p>Si vous avez d'autres questions, répondez simplement à cet email.</p>
    <p>Cordialement,<br>L'équipe support</p>
</body>
</html>


