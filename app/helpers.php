<?php

use App\Enums\ToastType;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

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
        $toasts = session('toasts', []);
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

if (! function_exists('setClubContext')) {
    /**
     * Set the current team context and check if user is club owner
     */
    function setClubContext(User $user, int $clubId): bool
    {
        setPermissionsTeamId($clubId);

        $isClubOwner = $user->clubs()->where('id', $clubId)->exists();

        if ($isClubOwner) {
            Log::debug('Club owner check passed', [
                'user_id' => $user->id,
                'club_id' => $clubId,
            ]);
        }

        return $isClubOwner;
    }
}
