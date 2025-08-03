@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Sample")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@if(Request::routeIs('projects.entities.*') || Request::routeIs('projects.experiments.entities*'))
    @php
        $title = "Compare Samples";
        $groupRoute = 'projects.entities.show';
    @endphp
    @section('breadcrumbs', Breadcrumbs::render('projects.entities.show', $project, $entity))
@else
    @php
        $title = "Compare Computations";
        $groupRoute = 'projects.computations.entities.show';
    @endphp
    @section('breadcrumbs', Breadcrumbs::render('projects.computations.entities.show', $project, $entity))
@endif

@section('content')
    @component('components.card')
        @slot('header')
            @if($entity->category == "computational")
                Computation: {{$entity->name}}
            @else
                Sample: {{$entity->name}}
            @endif

            <div class="float-right">

                @if(isset($prevEntity))
                    @if($entity->category == "computational")
                        <a class="action-link mr-3"
                           href="{{route('projects.computations.entities.show-spread', [$project, $prevEntity])}}">
                            <i class="fas fa-chevron-left mr-1"></i>Previous
                        </a>
                    @else
                        <a class="action-link mr-3"
                           href="{{route('projects.entities.show-spread', [$project, $prevEntity])}}">
                            <i class="fas fa-chevron-left mr-1"></i>Previous
                        </a>
                    @endif
                @endif

                @if(isset($nextEntity))
                    @if($entity->category == "computational")
                        <a class="action-link mr-3"
                           href="{{route('projects.computations.entities.show-spread', [$project, $nextEntity])}}">
                            Next<i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    @else
                        <a class="action-link mr-5"
                           href="{{route('projects.entities.show-spread', [$project, $nextEntity])}}">
                            Next<i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    @endif
                @endif

                <a class="action-link" href="#"
                   onclick="window.location.replace('{{route($groupRoute, [$project, $entity])}}')">
                    <i class="fas fa-object-group mr-2"></i>Group By Process Type
                </a>
            </div>

            <div class="col col-lg-4 float-right">
                <select class="selectpicker col-lg-10 mc-select"
                        data-style="btn-light no-tt"
                        data-live-search="true" title="{{$title}}">
                    @foreach($allEntities as $entry)
                        @if ($entry->name != $entity->name)
                            <option data-tokens="{{$entry->id}}" value="{{$entry->id}}">{{$entry->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        @endslot

        @slot('body')
            <div class="row ml-1">
                <div class="col-lg-5 col-md-10 col-sm-10 ml-2 mt-2 tile background-white">
                    <h4>Option 3</h4>
                    <dl class="table-list">
                        <dt>Material Type</dt>
                        <dd>Aluminum Alloy</dd>

                        <dt>Processing Temperature</dt>
                        <dd>150 °C</dd>

                        <dt>Sample Preparation Method</dt>
                        <dd>Heat Treatment</dd>

                        <dt>Additional Notes</dt>
                        <dd>Standard atmospheric conditions</dd>
                    </dl>
                </div>
            </div>
        @endslot
    @endcomponent
@endsection

@push('styles')
    <style>
        /* Option 3 */

        .table-list {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 0.25rem 1rem;
            background: #f9fafb;
            padding: 0.5rem;
        }

        .table-list dt {
            padding: 0.25rem 0.5rem;
            background: #f3f4f6;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .table-list dd {
            margin: 0;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        /* Hover effects */
        .table-list dt:hover,
        .table-list dd:hover {
            background-color: #edf2f7;
        }

        /* Row highlight effect */
        .table-list dt:hover + dd {
            background-color: #edf2f7;
        }

        /* Hover pair highlight */
        .table-list dt,
        .table-list dd {
            position: relative;
        }

        .table-list dt:hover::before,
        .table-list dd:hover::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            pointer-events: none;
        }

        /* Optional: Add subtle animation for smoother interaction */
        .table-list dt,
        .table-list dd {
            transform-origin: center;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .table-list dt:hover,
        .table-list dd:hover {
            transform: scale(1.01);
        }



        /* Add to Option 1 or 3 */
        .table-list dd:hover {
            background-color: #f8fafc;
        }

    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(() => {
            $('select').on('change', () => {
                let selected = $('.selectpicker option:selected').val();
                window.location.href = route('projects.entities.compare', {
                    project: "{{$project->id}}",
                    entity1: "{{$entity->id}}",
                    entity2: selected
                });
            });
        });
    </script>
@endpush
