<?php

namespace App\Http\Middleware;

use App\Models\Club;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $userClubs = session('userClubs');
        $currentClub = session('currentClub');
        $currentClubId = null;
        if ($currentClub) {
            $currentClubId = $currentClub->id;
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'flash' => fn () => [
                'toasts' => $request->session()->get('toasts'),
            ],
            'auth' => [
                'user' => $request->user(),
                'permissions' => [
                    'club' => [
                        'create' => $request->user() ? $request->user()->can('create', Club::class) : false,
                    ],
                ],
            ],
            'locale' => function () {
                return app()->getLocale();
            },
            'language' => function () {
                return translations(
                    resource_path('lang/'.app()->getLocale().'.json')
                );
            },
            'userClubs' => $userClubs ? $userClubs : [],
            'currentClubId' => $currentClubId,
            'currentClubName' => $currentClub ? $currentClub->name : '',
        ];
    }
}
