<?php

namespace App\DTO;

class TriggerDTO
{
    public string $name;
    public string $description;
    public string $what;
    public string $when;
    public int $scriptFileId;

    public static function fromArray(array $data): self
    {
        $trigger = new self();
        $trigger->name = $data['name'];
        $trigger->description = $data['description'];
        $trigger->what = $data['what'];
        $trigger->when = $data['when'];
        $trigger->scriptFileId = $data['script_file_id'];
        return $trigger;
    }
}