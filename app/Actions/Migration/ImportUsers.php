<?php

namespace App\Actions\Migration;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportUsers extends AbstractImporter
{

    public function __construct($pathToDumpfiles)
    {
        parent::__construct($pathToDumpfiles);
    }

    protected function setup()
    {
    }

    public function loadData($data)
    {
        $modelData = [];
        $modelData["uuid"] = $data["id"];
        $modelData["email"] = $data["email"];
        $modelData["name"] = $data["name"];

        if (isset($data["description"])) {
            $modelData["description"] = $data["description"];
        }

        if (isset($data["affiliation"])) {
            $modelData["affiliations"] = $data["affiliation"];
        }

        $modelData["api_token"] = Str::random(60);
        $modelData["password"] = Hash::make(Str::random(24));
        echo "Adding user {$modelData['email']}/{$modelData['name']}\n";

        return User::create($modelData);
    }

    protected function cleanup()
    {
    }
}