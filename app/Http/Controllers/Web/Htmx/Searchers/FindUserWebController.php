<?php

namespace App\Http\Controllers\Web\Htmx\Searchers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use function blank;
use function view;

class FindUserWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // For now only allow admin users to access
        if (!auth()->user()->is_admin) {
            return '';
        }
        $userName = $request->get('user_name');
        if (blank($userName)) {
            return '';
        }

        $users = User::query()
                     ->where('name', 'like', "%{$userName}%")
                     ->get();
        return view('partials.htmx.searchers._find-user', [
            'users' => $users,
        ]);
    }
}
