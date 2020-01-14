<?php

namespace App\Actions\Migration;

trait LineHandler
{
    public function ignoreLine($line)
    {
        if ($line == '') {
            return true;
        }

        if ($line[0] == '[') {
            return true;
        }

        if ($line[0] == ']') {
            return true;
        }

        return false;
    }

    public function decodeLine($line)
    {
        $l = trim($line);
        if ($l[strlen($l) - 1] == ',') {
            $l = substr_replace($l, "", -1);
        }

        return json_decode($l, true);
    }
}

