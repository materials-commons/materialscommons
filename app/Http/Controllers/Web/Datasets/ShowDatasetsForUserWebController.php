<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ShowDatasetsForUserWebController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        return view('app.users.show', [
            'user'     => $user,
            'datasets' => $user->datasets()->whereNotNull('published_at')->get(),
        ]);
    }
}
