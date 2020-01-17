<?php

namespace App\Actions\Globus;

class GlobusUrl
{
    const GlobusBaseUrl = "https://app.globus.org/file-manager";

    public static function globusUploadUrl($endpointId, $path)
    {
        return self::GlobusBaseUrl."?destination_id={$endpointId}&destination_path={$path}";
    }

    public static function globusDownloadUrl($endpointId, $path)
    {
        return self::GlobusBaseUrl."?origin_id={$endpointId}&origin_path={$path}";
    }

    public static function uploadEndpointPath($id)
    {
        return "/__globus_uploads/{$id}/";
    }

    public static function downloadEndpointPath($id)
    {
        return "/__download_staging/";
    }
}

