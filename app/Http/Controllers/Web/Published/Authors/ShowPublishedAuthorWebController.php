<?php

namespace App\Http\Controllers\Web\Published\Authors;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthorProfileService;
use Illuminate\Http\Request;

class ShowPublishedAuthorWebController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        $profile = new AuthorProfileService($user, publishedOnly: true);

        return view('public.authors.show', [
            'user'    => $user,
            'profile' => $profile,
        ]);
    }
}
