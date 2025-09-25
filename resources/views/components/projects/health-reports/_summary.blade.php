@props(['healthReport'])
<div class="row mb-4">
    <div class="col-md-3">
        @php
            $hasIssues = $healthReport->missingFiles->count() > 0;
            $cardClass = $hasIssues ? 'health-card-danger' : 'health-card-success';
            $textClass = $hasIssues ? 'text-danger' : 'text-black';
        @endphp
        <div class="card {{ $cardClass }} border-3">
            <div class="card-body text-center {{ $textClass }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    @if($hasIssues)
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
{{--                        <input type="checkbox" class="form-check-input" style="transform: scale(1.2);">--}}
                    @else
                        <i class="fas fa-check-circle fa-lg text-success"></i>
                        <span></span>
                    @endif
                </div>
                <h4 class="{{ $textClass }}">
                    {{ $healthReport->missingFiles->count() }}
                </h4>
                <p class="mb-0">Missing Files</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        @php
            $hasIssues = $healthReport->oldGlobusDownloads->count() > 0;
            $cardClass = $hasIssues ? 'health-card-warning' : 'health-card-success border-success';
            $textClass = $hasIssues ? 'text-warning' : 'text-green';
        @endphp
        <div class="card {{ $cardClass }} border-3">
            <div class="card-body text-center {{ $textClass }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    @if($hasIssues)
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    @else
                        <i class="fas fa-check-circle fa-lg text-green"></i>
                        <span></span>
                    @endif
                </div>
                <h4 class="{{ $textClass }}">
                    {{ $healthReport->oldGlobusDownloads->count() }}
                </h4>
                <p class="mb-0">Old Globus Downloads</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        @php
            $hasIssues = $healthReport->unpublishedDatasetsWithDOIs->count() > 0;
            $cardClass = $hasIssues ? 'health-card-warning' : 'health-card-success';
            $textClass = $hasIssues ? 'text-warning' : 'text-green';
        @endphp
        <div class="card {{ $cardClass }} border-3">
            <div class="card-body text-center {{ $textClass }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    @if($hasIssues)
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    @else
                        <i class="fas fa-check-circle fa-lg text-green"></i>
                        <span></span>
                    @endif
                </div>
                <h4 class="{{ $textClass }}">
                    {{ $healthReport->unpublishedDatasetsWithDOIs->count() }}
                </h4>
                <p class="mb-0">Unpublished Datasets with DOIs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        @php
            $hasIssues = $healthReport->unprocessedGlobusUploads->count() > 0;
            $cardClass = $hasIssues ? 'health-card-info' : 'health-card-success';
            $textClass = $hasIssues ? 'text-info' : 'text-green';
        @endphp
        <div class="card {{ $cardClass }} border-3">
            <div class="card-body text-center {{ $textClass }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    @if($hasIssues)
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    @else
                        <i class="fas fa-check-circle fa-lg text-green"></i>
                        <span></span>
                    @endif
                </div>
                <h4 class="{{ $textClass }}">
                    {{ $healthReport->unprocessedGlobusUploads->count() }}
                </h4>
                <p class="mb-0">Unprocessed Globus Uploads</p>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .health-card-success {
            background-color: #d4edda !important;
            border: 3px solid #28a745 !important;
        }

        .text-green {
            color: #28a745 !important;
        }

        .health-card-danger {
            background-color: #f8d7da !important;
            border: 3px solid #dc3545 !important;
        }
        .health-card-warning {
            background-color: #fff3cd !important;
            border: 3px solid #ffc107 !important;
        }
        .health-card-info {
            background-color: #d1ecf1 !important;
            border: 3px solid #17a2b8 !important;
        }
    </style>
@endpush
