<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Silber\Bouncer\Bouncer;

class ScopeBouncer
{
    /**
     * The Bouncer instance.
     *
     * @var \Silber\Bouncer\Bouncer
     */
    protected $bouncer;

    /**
     * Constructor.
     */
    public function __construct(Bouncer $bouncer)
    {
        $this->bouncer = $bouncer;
    }

    /**
     * Set the proper Bouncer scope for the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $CurrentClub = session('currentClub');

        if ($CurrentClub) {
            // Log::info('Bouncer Middleware: Setting Bouncer scope to club {club}', ['club' => $CurrentClub->id]);
            $this->bouncer->scope()->to($CurrentClub->id);
        } else {
            $this->bouncer->scope()->to(null);
            Log::info('Bouncer Middleware: Could not get current club');
        }

        return $next($request);
    }
}
