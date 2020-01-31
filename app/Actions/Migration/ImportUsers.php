<?php

namespace App\Actions\Migration;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportUsers extends AbstractImporter
{

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "users", $ignoreExisting);
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

        if (isset($data['globus_user'])) {
            $modelData['globus_user'] = $data['globus_user'];
        }

        $modelData["api_token"] = Str::random(60);
//        $modelData["password"] = Hash::make(Str::random(24));
        $modelData["password"] = Hash::make("abc123456");

        return User::create($modelData);
    }

    protected function cleanup()
    {
    }

    protected function getModelClass()
    {
        return User::class;
    }

    protected function shouldLoadRelationshipsOnSkip()
    {
        return false;
    }

    protected function loadRelationships($item)
    {
    }
}