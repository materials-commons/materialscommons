<?php

namespace App\View\Components\Md;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use League\CommonMark\ConverterInterface;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use function collect;
use function explode;
use function preg_match_all;
use function str_replace;
use function trim;
use function view;

class Markdown extends Component
{
    protected bool $anchors;

    public function __construct(bool $anchors = false)
    {
        $this->anchors = $anchors;
    }

    public function render(): View
    {
        return view('components.md.markdown');
    }

    public function toHtml(string $markdown): string
    {
        if ($this->anchors) {
            $markdown = $this->generateAnchors($markdown);
        }

        return (string) $this->converter()->convert($markdown);
    }

    protected function converter(): ConverterInterface
    {
        $config = ["html_input" => "allow"];
        $env = new Environment($config);
        $env->addExtension(new CommonMarkCoreExtension());
        $env->addExtension(new GithubFlavoredMarkdownExtension());
        return new MarkdownConverter($env);
    }

    protected function generateAnchors(string $markdown): string
    {
        preg_match_all('(```[a-z]*\n[\s\S]*?\n```)', $markdown, $matches);

        collect($matches[0] ?? [])->each(function (string $match, int $index) use (&$markdown) {
            $markdown = str_replace($match, "<!--code-block-$index-->", $markdown);
        });

        $markdown = collect(explode(PHP_EOL, $markdown))
            ->map(function (string $line) {
                // For levels 2 to 6.
                $anchors = [
                    '## ',
                    '### ',
                    '#### ',
                    '##### ',
                    '###### ',
                ];

                if (!Str::startsWith($line, $anchors)) {
                    return $line;
                }

                $title = trim(Str::after($line, '# '));
                $anchor = '<a class="anchor" name="'.Str::slug($title).'"></a>';

                return $anchor.PHP_EOL.$line;
            })
            ->implode(PHP_EOL);

        collect($matches[0] ?? [])->each(function (string $match, int $index) use (&$markdown) {
            $markdown = str_replace("<!--code-block-$index-->", $match, $markdown);
        });

        return $markdown;
    }
}
