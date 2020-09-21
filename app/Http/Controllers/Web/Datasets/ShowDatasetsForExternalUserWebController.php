<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\ExternalUser;
use Illuminate\Http\Request;

class ShowDatasetsForExternalUserWebController extends Controller
{
    public function __invoke(Request $request, ExternalUser $user)
    {
        return view('app.users.show', [
            'user'     => $user,
            'datasets' => $user->datasets()->whereNotNull('published_at')->get(),
        ]);
    }
}
