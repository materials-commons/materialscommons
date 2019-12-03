<?php

class GlobusUrl
{
    const GlobusBaseUrl = "https://app.globus.org/file-manager";

    public function globusUploadUrl($endpointId, $path)
    {
        return self::GlobusBaseUrl."?destination_id={$endpointId}&destination_path={$path}";
    }

    public function globusDownloadUrl($endpointId, $path)
    {
        return self::GlobusBaseUrl."?origin_id={$endpointId}&origin_path={$path}";
    }

    public function uploadEndpointPath($id)
    {
        return "/__globus_uploads/{$id}/";
    }

    public function downloadEndpointPath($id)
    {
        return "/__download_staging/";
    }
}

