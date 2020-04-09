<?php

use Illuminate\Support\Facades\Storage;

function errorBannersExist()
{
    return bannerFileExists("errors.txt");
}

function errorBannerMessages()
{
    return bannerFileToCollection("errors.txt");
}

function warningBannersExist()
{
    return bannerFileExists("warnings.txt");
}

function warnBannerMessages()
{
    return bannerFileToCollection("warnings.txt");
}

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



