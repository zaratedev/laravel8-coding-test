<?php

namespace App\Http\Controllers;

use App\Jobs\SaveTopUsersJob;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

class HomeController extends Controller
{
    public function __invoke()
    {
        User::query()
            ->topUsers()
            ->chunk(1000, fn (Collection $users) => $users->each(
                fn (User $user) => Bus::dispatch(new SaveTopUsersJob($user))
            ));

        return response()->json('Processing top users');
    }
}
