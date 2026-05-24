@extends('layouts.app')

@section('pageTitle', $user->name . ' — Author Profile')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @php
        $ownedCount = $profile->ownedDatasets->count();
        $includedCount = $profile->includedDatasets->count();
        $paperCount = $profile->papers->count();
        $coauthorCount = count($profile->coAuthors);
    @endphp

    <x-public.author.header
        :user="$user"
        :owned-count="$ownedCount"
        :included-count="$includedCount"
    />

    <x-public.author.kpi
        :owned-count="$ownedCount"
        :included-count="$includedCount"
        :total-views="$profile->totalViews"
        :total-downloads="$profile->totalDownloads"
        :paper-count="$paperCount"
        :coauthor-count="$coauthorCount"
    />

    <x-public.author.analytics
        :user="$user"
        :profile="$profile"
        :owned-count="$ownedCount"
        :included-count="$includedCount"
    />

    {{-- ══ Content tabs ════════════════════════════════════════════════════════════ --}}
    <div class="overflow-auto mb-3">
        <ul class="nav nav-pills flex-nowrap" id="author-tabs" role="tablist" style="min-width:max-content;">
            <li class="nav-item">
                <button class="nav-link active text-nowrap" data-bs-toggle="pill" data-bs-target="#tab-owned" type="button">
                    <i class="fas fa-database me-1"></i>Owned Datasets
                    <span class="badge text-bg-primary ms-1">{{ $ownedCount }}</span>
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link text-nowrap" data-bs-toggle="pill" data-bs-target="#tab-included" type="button">
                    <i class="fas fa-list me-1"></i>Included In
                    <span class="badge text-bg-info ms-1">{{ $includedCount }}</span>
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link text-nowrap" data-bs-toggle="pill" data-bs-target="#tab-papers" type="button">
                    <i class="fas fa-file-alt me-1"></i>Papers
                    <span class="badge text-bg-secondary ms-1">{{ $paperCount }}</span>
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link text-nowrap" data-bs-toggle="pill" data-bs-target="#tab-coauthors" type="button">
                    <i class="fas fa-users me-1"></i>Co-authors
                    <span class="badge text-bg-danger ms-1">{{ $coauthorCount }}</span>
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link text-nowrap" data-bs-toggle="pill" data-bs-target="#tab-tags" type="button">
                    <i class="fas fa-tags me-1"></i>Tags
                    <span class="badge text-bg-success ms-1">{{ count($profile->allTags) }}</span>
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <x-public.author.tabs.owned-datasets
            :datasets="$profile->ownedDatasets"
            :owned-count="$ownedCount"
        />

        <x-public.author.tabs.included-datasets
            :datasets="$profile->includedDatasets"
            :included-count="$includedCount"
        />

        <x-public.author.tabs.papers
            :papers="$profile->papers"
            :paper-count="$paperCount"
        />

        <x-public.author.tabs.coauthors
            :co-authors="$profile->coAuthors"
            :coauthor-count="$coauthorCount"
        />

        <x-public.author.tabs.tags
            :owned-tags="$profile->ownedTags"
            :included-tags="$profile->includedTags"
        />
    </div>

    {{-- ══ Chart drill-down modal ═════════════════════════════════════════════════ --}}
    <div class="modal fade" id="ds-drilldown-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title fw-semibold" id="ds-drilldown-title"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-3" id="ds-drilldown-body"></div>
            </div>
        </div>
    </div>

    <x-public.author.scripts
        :user="$user"
        :profile="$profile"
    />
@stop
