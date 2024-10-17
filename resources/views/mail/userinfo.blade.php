{{-- resources/views/mail/userinfo.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <title>Benvenuto</title>
</head>

<body>
    <h1>Benvenuto, {{ $userName }}!</h1>
    <p>Grazie per esserti registrato. Il tuo ID utente è: {{ $userId }}.</p>
</body>

</html>
