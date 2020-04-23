<?php

namespace App\Actions\Files;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class ConvertFileAction
{
    public function execute(File $file)
    {
        umask(0);
        if ($file->isConvertibleImage()) {
            $this->convertImage($file);
        } elseif ($file->isConvertibleOfficeDocument()) {
            $this->convertOfficeDocument($file);
        }
    }

    private function convertImage(File $file)
    {
        if (Storage::disk('mcfs')->exists($file->convertedPathPartial())) {
            return;
        }

        $this->createConversionDir($file);

        $originalFilePath = Storage::disk('mcfs')->path($file->realPathPartial());
        $convertedFilePath = Storage::disk('mcfs')->path($file->convertedPathPartial());
        try {
            shell_exec("convert {$originalFilePath} {$convertedFilePath}");
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            echo "Conversion failed: {$msg}\n";
        }
        chmod($convertedFilePath, 0777);
    }

    private function convertOfficeDocument(File $file)
    {
        if (Storage::disk('mcfs')->exists($file->convertedPathPartial())) {
            return;
        }

        $this->createConversionDir($file);

        $tmpDir = sys_get_temp_dir();
        $tmpConvertedFilePath = "{$tmpDir}/{$file->uuid}.pdf";
        $convertedFilePath = Storage::disk('mcfs')->path($file->convertedPathPartial());
        $filePath = Storage::disk('mcfs')->path($file->realPathPartial());
        $command = "libreoffice -env:UserInstallation=file://{$tmpDir}/{$file->uuid}".
            "--headless ".
            "--convert-to pdf ".
            "--outdir {$tmpDir} ".
            "{$filePath}";
        try {
            shell_exec($command);
            shell_exec("cp {$tmpConvertedFilePath} {$convertedFilePath}");
            shell_exec("rm {$tmpConvertedFilePath}");
            chmod($convertedFilePath, 0777);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            echo "Conversion failed: {$msg}\n";
        }
    }

    private function createConversionDir(File $file)
    {
        if (!Storage::disk('mcfs')->exists($file->pathDirPartial()."/.conversion")) {
            Storage::disk('mcfs')->makeDirectory($file->pathDirPartial()."/.conversion");
            chmod(Storage::disk('mcfs')->path($file->pathDirPartial()."/.conversion"), 0777);
        }
    }
}