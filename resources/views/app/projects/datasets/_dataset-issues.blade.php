@php
    $issueErrors   = [];
    $issueWarnings = [];

    if (!$dataset->hasSelectedFiles())
        $issueErrors[] = 'Dataset has no files. Files are required before publishing.';

    if (blank($dataset->description))
        $issueErrors[] = 'No description. A description is required.';

    if (blank($dataset->doi))
        $issueWarnings[] = 'No DOI assigned. A DOI gives your dataset a permanent, citable identifier.';

    if (!blank($dataset->description) && strlen($dataset->description) < 50)
        $issueWarnings[] = 'Description is under 50 characters. A longer description improves search discoverability.';

    if (blank($dataset->summary))
        $issueWarnings[] = 'No summary. A summary appears in dataset listings and helps researchers find your work.';

    if (blank($dataset->license) || $dataset->license === 'No License')
        $issueWarnings[] = 'No license selected. A license clarifies how your dataset may be used by others.';

    if (empty($dataset->ds_authors))
        $issueWarnings[] = 'No authors listed. An author list helps others evaluate and cite your dataset.';

    if (blank($dataset->funding))
        $issueWarnings[] = 'Funding field is blank. Acknowledge public or private funding sources if applicable.';

    $totalIssues = count($issueErrors) + count($issueWarnings);
@endphp

@if($totalIssues > 0)
    <div x-data="{ open: false }">

        {{-- Toggle row with count badges --}}
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button"
                    @click="open = !open"
                    class="btn btn-sm btn-link text-secondary text-decoration-none p-0 d-flex align-items-center gap-1">
                <i class="fas fa-fw text-muted"
                   :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                   style="font-size:.65rem;"></i>
                <span x-text="open ? 'Hide checklist' : 'Show checklist'"
                      class="fw-semibold" style="font-size:.8rem;"></span>
            </button>

            @if(count($issueErrors) > 0)
                <span class="badge rounded-pill text-bg-danger" style="font-size:.65rem;">
                    <i class="fas fa-times-circle me-1"></i>
                    {{ count($issueErrors) }} error{{ count($issueErrors) > 1 ? 's' : '' }}
                </span>
            @endif

            @if(count($issueWarnings) > 0)
                <span class="badge rounded-pill text-bg-warning text-dark" style="font-size:.65rem;">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    {{ count($issueWarnings) }} recommendation{{ count($issueWarnings) > 1 ? 's' : '' }}
                </span>
            @endif
        </div>

        {{-- Collapsible list --}}
        <div x-show="open" x-collapse style="display:none;" class="mt-2">
            <div class="d-flex flex-column gap-1">

                @foreach($issueErrors as $msg)
                    <div class="d-flex align-items-start gap-2 px-3 py-2 rounded"
                         style="background:#fff5f5; border-left:3px solid #dc3545;">
                        <i class="fas fa-times-circle text-danger mt-1 flex-shrink-0" style="font-size:.8rem;"></i>
                        <span style="font-size:.82rem; color:#842029;">{{ $msg }}</span>
                    </div>
                @endforeach

                @foreach($issueWarnings as $msg)
                    <div class="d-flex align-items-start gap-2 px-3 py-2 rounded"
                         style="background:#fffbf0; border-left:3px solid #ffc107;">
                        <i class="fas fa-exclamation-triangle text-warning mt-1 flex-shrink-0" style="font-size:.8rem;"></i>
                        <span style="font-size:.82rem; color:#664d03;">{{ $msg }}</span>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endif
