<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>2FA Code</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; background-color: #f8f8f8; padding: 20px;">

    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px;">
        <h2 style="color: #d9534f;">Er is geprobeerd in te loggen met dit e-mailadres.</h2> 
        <h2>Ben jij dit niet? <a href="mailto:support@buyz.com" style="color: #0275d8;">Stuur een mail naar support@buyz.com</a></h2> 
        <hr>
        <p>Je 2FA-verificatiecode is:</p>
        <h2 style="font-size: 32px; color: #5cb85c;">{{ $code }}</h2>
        <p>Let op: Deze code vervalt binnen 10 minuten.</p>
    </div>

</body>
</html>
