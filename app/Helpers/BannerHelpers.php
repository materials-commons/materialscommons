<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists("errorsBannersExist")) {
    function errorBannersExist()
    {
        return bannerFileExists("errors.txt");
    }
}

if (!function_exists("errorsBannerMessages")) {
    function errorBannerMessages()
    {
        return bannerFileToCollection("errors.txt");
    }
}

if (!function_exists("warningBannersExist")) {
    function warningBannersExist()
    {
        return bannerFileExists("warnings.txt");
    }
}

if (!function_exists("warnBannerMessages")) {
    function warnBannerMessages()
    {
        return bannerFileToCollection("warnings.txt");
    }
}

if (!function_exists("bannerFileExists")) {
    function bannerFileExists($bannerFile)
    {
        $partialPath = "banners/{$bannerFile}";

        if (!Storage::disk('mcfs')->exists($partialPath)) {
            return false;
        }

        if (Storage::disk('mcfs')->size($partialPath) == 0) {
            return false;
        }

        return true;
    }
}

if (!function_exists("bannerFileToCollection")) {
    function bannerFileToCollection($bannerFile)
    {
        $partialPath = "banners/{$bannerFile}";
        $lines = collect();

        if (bannerFileExists($bannerFile)) {
            $handle = fopen(Storage::disk('mcfs')->path($partialPath), "r");
            while (!feof($handle)) {
                $line = trim(fgets($handle));
                if ($line != "") {
                    $lines->push($line);
                }
            }

            fclose($handle);
        }

        return $lines;
    }
}


