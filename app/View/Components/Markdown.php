<?php

namespace App\View\Components;

use App\Markdown\Extensions\Renderers\Block\MCHtmlBlockRenderer;
use App\Markdown\Extensions\Renderers\Inline\MCHtmlInlineRenderer;
use App\Markdown\Extensions\Renderers\Inline\MCImageRenderer;
use App\Markdown\Processor\MCDocumentProcessor;
use Closure;
use CommonMark\Extension\Metadata\MetadataExtension;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\HtmlBlock;
use League\CommonMark\Extension\CommonMark\Node\Inline\HtmlInline;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
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
        $environment->addRenderer(Image::class, new MCImageRenderer(), 100);
        $environment->addRenderer(HtmlInline::class, new MCHtmlInlineRenderer(), 100);
        $environment->addRenderer(HtmlBlock::class, new MCHtmlBlockRenderer(), 100);
        $environment->addEventListener(DocumentParsedEvent::class, [new MCDocumentProcessor(), 'onDocumentParsed'],
            -100);
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
