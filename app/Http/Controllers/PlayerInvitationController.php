<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerInvitationRequest;
use App\Mail\PlayerInvitationMail;
use App\Models\Player;
use App\Models\PlayerInvitation;
use App\Models\Role;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Silber\Bouncer\BouncerFacade;

class PlayerInvitationController extends Controller
{
    use AuthorizesRequests;

    public function store(StorePlayerInvitationRequest $request, Player $player)
    {
        $validated = $request->validated();

        // Token generieren
        $token = Str::random(60);

        try {

            // Einladung in der Datenbank speichern
            PlayerInvitation::updateOrCreate([
                'player_id' => $player->id,
                'email' => $validated['email'],
                'token' => $token,
                'expires_at' => now()->addDays(7),
            ]);

            // Einladungs-E-Mail versenden
            Mail::to($validated['email'])->send(new PlayerInvitationMail($player, $token));
            toast_success('Player invited successfully');
        } catch (Exception $exception) {
            Log::error('Error inviting player', ['error' => $exception->getMessage()]);
            toast_error('Could not invite player');

            return back();
        }

        return to_route('players.index');
    }

    public function accept($token)
    {
        $invitation = PlayerInvitation::where('token', $token)->firstOrFail();

        if (Auth::check()) {
            $user = Auth::user();

            // Player mit der User-ID verknüpfen
            $player = Player::findOrFail($invitation->player_id);
            $player->user_id = $user->id;
            $player->save();

            BouncerFacade::scope()->to($player->club_id);
            $role = Role::findOrFail($player->role_id);

            BouncerFacade::assign($role)->to($user);
            BouncerFacade::refreshFor($user);

            $invitation->delete();

            toast_success('Player attached successfully.');

            return to_route('dashboard');
        }

        return to_route('login');
    }
}
