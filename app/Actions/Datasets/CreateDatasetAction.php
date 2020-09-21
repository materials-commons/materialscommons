<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\ExternalUser;
use App\Models\User;
use App\Traits\HasTagsInRequest;
use Illuminate\Support\Facades\DB;

class CreateDatasetAction
{
    use HasTagsInRequest;

    public function execute($data, $projectId, $userId)
    {
        $communities = null;
        if (array_key_exists('communities', $data)) {
            $communities = $data['communities'];
            unset($data['communities']);
        }

        $experiments = null;
        if (array_key_exists('experiments', $data)) {
            $experiments = $data['experiments'];
            unset($data['experiments']);
        }

        $mcAuthors = null;
        if (array_key_exists('mc_authors', $data)) {
            $mcAuthors = $data['mc_authors'];
            unset($data['mc_authors']);
        }

        $additionalAuthors = null;
        $mcAuthorsFromAdditionalAuthors = null;
        if (array_key_exists('additional_authors', $data)) {
            $mcAuthorsFromAdditionalAuthors = $this->getMCAuthorIdsFromAdditionalAuthors($data['additional_authors']);
            $additionalAuthors = $this->createAdditionalAuthors($data['additional_authors'],
                $mcAuthorsFromAdditionalAuthors);
            unset($data['additional_authors']);
        }

        $this->loadTagsFromData($data);
        unset($data['tags']);

        $dataset = new Dataset($data);
        $dataset->owner_id = $userId;
        $dataset->project_id = $projectId;
        $dataset->file_selection = [
            'include_files' => [],
            'exclude_files' => [],
            'include_dirs'  => [],
            'exclude_dirs'  => [],
        ];

        DB::transaction(function () use (
            $dataset, $communities, $experiments, $mcAuthors, $additionalAuthors, $mcAuthorsFromAdditionalAuthors
        ) {
            $dataset->save();
            if ($communities !== null) {
                $dataset->communities()->attach($communities);
            }

            if ($experiments !== null) {
                $dataset->experiments()->attach($experiments);
                foreach ($experiments as $experimentId) {
                    $experiment = Experiment::with(['activities', 'entities'])->find($experimentId);
                    $dataset->activities()->syncWithoutDetaching($experiment->activities);
                    $dataset->entities()->syncWithoutDetaching($experiment->entities);
                }
            }

            $dataset->authors()->syncWithoutDetaching($mcAuthors);
            $additionalMCUserIds = $mcAuthorsFromAdditionalAuthors->map(function (User $user) {
                return $user->id;
            })->all();
            $dataset->authors()->syncWithoutDetaching($additionalMCUserIds);
            $additionalAuthors->each(function (ExternalUser $user) use ($dataset) {
                $user->save();
                $dataset->externalAuthors()->attach($user);
            });

            $dataset->attachTags($this->tags);
        });

        return $dataset->fresh();
    }

    private function createAdditionalAuthors($additionalAuthors, $mcAuthorsFromAdditionalAuthors)
    {
        return collect($additionalAuthors)
            ->filter(function ($userData) use ($mcAuthorsFromAdditionalAuthors) {
                $email = $userData['email'];
                // Filter out existing mc users by email from the additionalAuthors list
                return !$mcAuthorsFromAdditionalAuthors->contains(function (User $user) use ($email) {
                    return $user->email == $email;
                });
            })
            ->map(function ($userData) {
                return new ExternalUser($userData);
            })
            ->values();
    }

    private function getMCAuthorIdsFromAdditionalAuthors($additionalAuthors)
    {
        return collect($additionalAuthors)->map(function ($userData) {
            return User::firstWhere('email', $userData['email']);
        })->filter();
    }
}