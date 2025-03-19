<?php

use App\Enums\ToastType;
use Illuminate\Http\RedirectResponse;

function translations(string $json): mixed
{
    if (! file_exists($json)) {
        return [];
    }

    return json_decode(file_get_contents($json), true);
}

if (! function_exists('toast')) {
    function toast(ToastType $type, string $message, ?RedirectResponse $response = null): ?RedirectResponse
    {
        $toasts = session()->get('toasts', []);
        $toasts[] = [
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => $type->value,
            'message' => $message,
        ];
        if ($response) {
            return $response->with('toasts', $toasts);
        } else {
            session()->flash('toasts', $toasts);

            return null;
        }
    }
}

if (! function_exists('toast_success')) {
    function toast_success(string $message): ?RedirectResponse
    {
        return toast(ToastType::SUCCESS, $message);
    }
}

if (! function_exists('toast_warning')) {
    function toast_warning(string $message): ?RedirectResponse
    {
        return toast(ToastType::WARNING, $message);
    }
}

if (! function_exists('toast_error')) {
    function toast_error(string $message): ?RedirectResponse
    {
        return toast(ToastType::ERROR, $message);
    }
}

if (!function_exists('resolveClubScopedModel')) {
    /**
     * Erzeugt eine neue Instanz eines Models und injiziert die club_id.
     *
     * @param string $modelClass Der vollqualifizierte Klassenname des Models (z.B. App\Models\Matchday)
     * @param int|null $clubId Optional. Falls nicht gesetzt, wird die club_id aus der Session verwendet.
     * @param array $attributes Weitere Attribute, die beim Instanziieren gesetzt werden sollen.
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \InvalidArgumentException wenn keine club_id ermittelt werden kann.
     */
    function getClubScopedModel(string $modelClass, ?int $clubId = null, array $attributes = []): \Illuminate\Database\Eloquent\Model
    {
        // Falls keine club_id als Parameter übergeben wird, versuchen wir diese aus der Session zu ermitteln:
        if (!$clubId) {
            // Hier wird angenommen, dass in der Session ein Objekt oder Array unter 'currentClub' gespeichert ist,
            // das die club_id enthält (z.B. ['id' => 5, ...]).
            $currentClub = session('currentClub');
            if ($currentClub && isset($currentClub['id'])) {
                $clubId = $currentClub['id'];
            } elseif ($currentClub instanceof \App\Models\Club && isset($currentClub->id)) {
                $clubId = $currentClub->id;
            } else {
                throw new \InvalidArgumentException('Keine club_id übergeben und auch kein currentClub in der Session gefunden.');
            }
        }

        // Setze oder überschreibe das Attribut "club_id"
        $attributes['club_id'] = $clubId;

        // Erzeuge eine neue Instanz des gewünschten Models mit den angegebenen Attributen
        return new $modelClass($attributes);
    }
}
