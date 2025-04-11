Hallo,

Sie wurden eingeladen, den Spieler {{ $player->name }} des Vereins {{$player->club->name}} mit Ihrem Konto zu verknüpfen.

Klicken Sie auf den folgenden Link, um die Einladung zu akzeptieren:

{{ route('players.invitation.accept', $token) }}

Vielen Dank!
