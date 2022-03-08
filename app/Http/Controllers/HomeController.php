<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function __invoke()
    {
        $topUsersData = Collection::make();

        User::query()
            ->with('posts')
            ->whereHas('posts', fn (Builder $query) => $query->where('created_at', '>=', now()->subWeek()), '>=', 10)
            ->chunk(1000, fn (Collection $users) => $users->each(
                fn (User $user) => $topUsersData->push([
                    'username' => $user->username,
                    'total_posts_count' => $user->posts->count(),
                    'last_post_title' => $user->posts->last()->title,
                ])
            ));

        return response()->json($topUsersData);
    }
}
