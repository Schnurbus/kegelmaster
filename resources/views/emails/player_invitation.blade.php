<!DOCTYPE html>
<html>

<head>
    <title>Einladung zur Verknüpfung eines Spielers</title>
</head>

<body>
    <h1>Einladung zur Verknüpfung eines Spielers</h1>
    <p>Hallo,</p>
    <p>Sie wurden eingeladen, den Spieler <strong>{{ $player->name }}</strong> mit Ihrem Konto zu verknüpfen.</p>
    <p>Klicken Sie auf den folgenden Link, um die Einladung zu akzeptieren:</p>
    <a href="{{ route('players.invitation.accept', $token) }}">Einladung akzeptieren</a>
    <p>Vielen Dank!</p>
</body>

</html>
