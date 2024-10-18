<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvenuto!</title>
</head>

<body>
    <h1>Ciao {{ $userName }},</h1>
    <p>Grazie per esserti registrato! Per favore conferma il tuo indirizzo email cliccando sul link qui sotto:</p>
    <a href="{{ $verificationUrl }}">Conferma Email</a>
    <p>Grazie!</p>
</body>

</html>
