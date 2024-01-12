<?php

namespace App\View\Components;

use Closure;
use CommonMark\Extension\Metadata\MetadataExtension;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use function ray;

class Markdown extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    public function toHtml(string $markdown): string
    {
        $options = [
            'html_input'         => 'allow',
            'allow_unsafe_links' => true,
        ];

        $environment = new Environment($options);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new DefaultAttributesExtension());
        $environment->addExtension(new MetadataExtension());
        $converter = new MarkdownConverter($environment);
        return $converter->convert($markdown);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.markdown');
    }
}
