<?php

namespace App\Actions\BrowseTree\Support;

class BrowseTreeNode
{
    public static function folder(
        string $key,
        string $title,
        string $icon,
        ?int $count = null,
        bool $lazy = false,
        array $children = [],
        array $searchTerms = [],
        ?string $projectKey = null,
    ): array {
        return [
            'key'         => $key,
            'projectKey'  => $projectKey,
            'kind'        => 'folder',
            'type'        => 'folder',
            'title'       => $title,
            'icon'        => $icon,
            'count'       => $count,
            'lazy'        => $lazy,
            'searchTerms' => $searchTerms,
            'children'    => $children,
        ];
    }

    public static function leaf(
        string $key,
        string $type,
        string $title,
        string $icon,
        string $badge,
        string $project,
        string $location,
        string $description = '',
        array $tags = [],
        ?string $experiment = null,
        ?string $dateBucket = null,
        ?string $dateLabel = null,
        ?string $url = null,
        array $searchTerms = [],
    ): array {
        return [
            'key'         => $key,
            'kind'        => 'leaf',
            'type'        => $type,
            'title'       => $title,
            'icon'        => $icon,
            'badge'       => $badge,
            'project'     => $project,
            'location'    => $location,
            'description' => $description,
            'tags'        => $tags,
            'experiment'  => $experiment,
            'dateBucket'  => $dateBucket,
            'dateLabel'   => $dateLabel,
            'url'         => $url,
            'searchTerms' => $searchTerms,
        ];
    }

    public static function action(
        string $key,
        string $title,
        string $icon,
        string $directoryKey,
    ): array {
        return [
            'key'          => $key,
            'kind'         => 'action',
            'type'         => 'action',
            'title'        => $title,
            'icon'         => $icon,
            'directoryKey' => $directoryKey,
            'children'     => [],
        ];
    }

    public static function message(
        string $key,
        string $title,
        string $icon = 'fas fa-info-circle text-muted',
    ): array {
        return [
            'key'      => $key,
            'kind'     => 'message',
            'type'     => 'message',
            'title'    => $title,
            'icon'     => $icon,
            'children' => [],
        ];
    }
}
