<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

class HomeController extends Controller
{
    public function __invoke()
    {
        $topUsersData = Collection::make();

        User::query()
            ->topUsers()
            ->chunk(1000, fn (Collection $users) => $users->each(
                fn (User $user) => Bus::dispatch(new SaveTopUsersJob($user))
            ));

        return response()->json($topUsersData);
    }
}

// $topUsersData->push([
//     'username' => $user->username,
//     'total_posts_count' => $user->posts->count(),
//     'last_post_title' => $user->posts->last()->title,
// ])