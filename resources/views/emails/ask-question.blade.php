<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nieuwe vraag ontvangen</title>
</head>
<body>
    <h2>Hallo,</h2>

    <p>Je hebt een nieuwe vraag ontvangen over je advertentie:</p>

    <blockquote style="margin: 20px 0; padding: 10px; background-color: #f9f9f9;">
        {{ $question }}
    </blockquote>

    <p><strong>Van:</strong> {{ $sender->first_name }} {{ $sender->last_name }}</p>
    <p><strong>E-mailadres:</strong> {{ $sender->email }}</p>

    <p>Beantwoord de vraag rechtstreeks door op dit e-mailadres te reageren.</p>

    <p>Met vriendelijke groet,<br>
    Het Buyz-team</p>
</body>
</html>
