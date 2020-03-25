<?php

namespace App\ViewModels\Emails;

use Spatie\ViewModels\ViewModel;

class AnnouncementMailViewModel extends ViewModel
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function user()
    {
        return $this->user;
    }

    public function embedPngFile($file)
    {
        $file64Encoded = base64_encode(file_get_contents(storage_path($file)));
        return "data:image/png;base64,{$file64Encoded}";
    }
}
