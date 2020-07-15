<?php

namespace App\Console\Commands;

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
        ini_set("memory_limit", "4096M");
        umask(0);
        $siteMapPath = Storage::disk('etc')->path('sitemap.xml');
        SitemapGenerator::create(config('app.url'))->writeToFile($siteMapPath);
        chmod($siteMapPath, 0777);
    }
}