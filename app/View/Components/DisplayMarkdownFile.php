<?php

namespace App\View\Components;

use App\Models\File;
use App\Traits\FileView;
use Illuminate\View\Component;
use function is_null;

class DisplayMarkdownFile extends Component
{
    use FileView;

    public ?File $file;

    public function __construct(?File $file)
    {
        $this->file = $file;
    }

    public function render()
    {
        // Laravel is having trouble referencing both fileContents and contents. I think it's because
        // its in the x-markdown body, and it's resolving to that component. The code below works.
        // We check if $this->file is not null, if it isn't we read it into the contents variable
        // and explicitly pass it into the view.
        $contents = "";
        if (!is_null($this->file)) {
            $contents = $this->fileContents($this->file);
        }
        return view('components.display-markdown-file', ["contents" => $contents]);
    }
}
