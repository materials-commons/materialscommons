<?php
namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;
use JsonSerializable;

class View implements JsonSerializable
{
    public string $name;  // Name of the view
    public string $description; // Description of the view
    public string $mql; // Any mql that is used to filter this views attributes, samples, computations or processes
    public string $currentSubview; // The current subview
    public Collection $subviews; // List of subviews

    public function __construct(string     $name, string $description, string $mql, string $currentSubview,
                                Collection $subviews)
    {
        $this->name = $name;
        $this->description = $description;
        $this->mql = $mql;
        $this->currentSubview = $currentSubview;
        $this->subviews = $subviews;
    }

    public function jsonSerialize(): array
    {
        return [
            'name'           => $this->name,
            'description'    => $this->description,
            'mql'            => $this->mql,
            'currentSubview' => $this->currentSubview,
            'subviews'       => $this->subviews->map(function ($subview, $key) {
                return $subview->jsonSerialize();
            })->toArray(),
        ];
    }
}
