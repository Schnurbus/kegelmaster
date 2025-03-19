Hallo,

Sie wurden eingeladen, den Spieler <strong>{{ $player->name }}</strong> mit Ihrem Konto zu verknüpfen.

Klicken Sie auf den folgenden Link, um die Einladung zu akzeptieren:

{{ route('players.invitation.accept', $token) }}

Vielen Dank!
