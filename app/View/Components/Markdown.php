<?php

namespace App\View\Components;

use App\Markdown\Extensions\MCFileDisplay\MCFileDisplayExtension;
use App\Markdown\Extensions\Renderers\Block\MCHtmlBlockRenderer;
use App\Markdown\Extensions\Renderers\Inline\MCHtmlInlineRenderer;
use Closure;
use CommonMark\Extension\Metadata\MetadataExtension;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\HtmlBlock;
use League\CommonMark\Extension\CommonMark\Node\Inline\HtmlInline;
use League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

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
        $environment->addExtension(new MCFileDisplayExtension());
        $environment->addRenderer(HtmlInline::class, new MCHtmlInlineRenderer(), 100);
        $environment->addRenderer(HtmlBlock::class, new MCHtmlBlockRenderer(), 100);
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
