<?php

namespace App\Console\Commands;

use App\Models\Dataset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSiteMapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:generate-site-map';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the site map for materialscommons';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sitemap = SitemapGenerator::create(config('app.url'))->getSitemap();
        Dataset::whereNotNull('published_at')->chunk(100, function ($datasets) use ($sitemap) {
            foreach ($datasets as $dataset) {
                $sitemap->add("/public/datasets/{$dataset->id}");
            }
        });
        $sitemap->writeToFile(Storage::disk('etc')->path('sitemap.xml'));
    }
}