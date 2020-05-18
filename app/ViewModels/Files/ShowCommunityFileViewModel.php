<?php

namespace App\ViewModels\Files;

use App\Models\Community;
use App\Models\File;
use Spatie\ViewModels\ViewModel;

class ShowCommunityFileViewModel extends ViewModel
{
    use FileView;

    /**
     * @var \App\Models\File
     */
    private $file;

    /**
     * @var \App\Models\Community
     */
    private $community;

    public function __construct(File $file, Community $community)
    {
        $this->file = $file;
        $this->community = $community;
    }

    public function file(): File
    {
        return $this->file;
    }

    public function community(): Community
    {
        return $this->community;
    }
}