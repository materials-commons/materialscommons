@extends('layouts.app')

@section('pageTitle', $title ?? 'Spreadsheet Import Prototype')

@section('content')
    <div class="container-fluid py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <div class="text-muted small text-uppercase fw-semibold">Prototype</div>
                <h1 class="h3 mb-1">{{ $heading ?? 'Study Spreadsheet Import' }}</h1>
                <div class="text-muted">{{ $subheading ?? 'Hard-coded UI prototype for spreadsheet import workflows.' }}</div>
            </div>

            <div class="btn-group">
                <a href="{{ route('prototype.experiment-import.create') }}"
                   class="btn btn-outline-primary {{ request()->routeIs('prototype.experiment-import.create') ? 'active' : '' }}">
                    Create
                </a>
                <a href="{{ route('prototype.experiment-import.update') }}"
                   class="btn btn-outline-primary {{ request()->routeIs('prototype.experiment-import.update') ? 'active' : '' }}">
                    Update
                </a>
                <a href="{{ route('prototype.experiment-import.status') }}"
                   class="btn btn-outline-primary {{ request()->routeIs('prototype.experiment-import.status') ? 'active' : '' }}">
                    Status
                </a>
            </div>
        </div>

        @yield('prototype-content')
    </div>
@endsection

@push('styles')
    <style>
        .mc-import-card {
            border: 1px solid #dee2e6;
            border-radius: .75rem;
            background: #fff;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .04);
        }

        .mc-import-card-header {
            padding: 1rem 1.1rem;
            border-bottom: 1px solid #edf0f2;
            background: #fbfcfd;
            border-radius: .75rem .75rem 0 0;
        }

        .mc-import-card-body {
            padding: 1.1rem;
        }

        .mc-soft-panel {
            border: 1px solid #e9ecef;
            background: #f8f9fa;
            border-radius: .65rem;
            padding: 1rem;
        }

        .mc-status-dot {
            width: .75rem;
            height: .75rem;
            display: inline-block;
            border-radius: 50%;
            margin-right: .45rem;
        }

        .mc-status-dot-success {
            background: #198754;
        }

        .mc-status-dot-running {
            background: #0d6efd;
            box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .15);
        }

        .mc-status-dot-waiting {
            background: #adb5bd;
        }

        .mc-status-dot-warning {
            background: #ffc107;
        }

        .mc-step {
            display: flex;
            gap: .75rem;
            padding: .75rem 0;
            border-bottom: 1px solid #edf0f2;
        }

        .mc-step:last-child {
            border-bottom: 0;
        }

        .mc-step-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .8rem;
        }

        .mc-step-icon-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .mc-step-icon-running {
            background: #cfe2ff;
            color: #084298;
        }

        .mc-step-icon-waiting {
            background: #e9ecef;
            color: #6c757d;
        }

        .mc-metric {
            border: 1px solid #e9ecef;
            border-radius: .65rem;
            padding: .85rem;
            background: #fff;
        }

        .mc-metric-value {
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1;
        }

        .mc-log {
            background: #101828;
            color: #d0d5dd;
            border-radius: .65rem;
            padding: 1rem;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            font-size: .82rem;
            max-height: 420px;
            overflow: auto;
        }

        .mc-log-line {
            margin-bottom: .25rem;
            white-space: pre-wrap;
        }

        .mc-log-time {
            color: #98a2b3;
        }

        .mc-log-info {
            color: #84caff;
        }

        .mc-log-success {
            color: #86efac;
        }

        .mc-log-warning {
            color: #fde68a;
        }

        .mc-log-error {
            color: #fca5a5;
        }

        .mc-validation-badge {
            min-width: 4.75rem;
        }

        .mc-sticky-status {
            position: sticky;
            top: 64px;
        }
    </style>
@endpush
