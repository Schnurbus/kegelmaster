<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function __invoke($page): Response
    {
        return Inertia::render(ucfirst($page), [
            'name' => config('owner-data.'.$page.'.name'),
            'address1' => config('owner-data.'.$page.'.address1'),
            'address2' => config('owner-data.'.$page.'.address2'),
            'country' => config('owner-data.'.$page.'.country'),
            'email' => config('owner-data.'.$page.'.email'),
            'phone' => config('owner-data.'.$page.'.phone'),
        ]);
    }
}
