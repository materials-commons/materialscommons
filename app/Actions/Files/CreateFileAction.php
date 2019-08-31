<?php

namespace App\Actions\Files;

class CreateFileAction
{
    public function __invoke($data)
    {
        dd($data->validated());
    }
}