@extends('layouts.app')

@section('pageTitle', 'Dashboard')

@section('nav')
    @include('layouts.navs.dashboard')
@stop

@if (Request::routeIs('dashboard'))
    @section('breadcrumbs', Breadcrumbs::render('dashboard'))
@elseif(Request::routeIs('dashboard.projects.show'))
    @section('breadcrumbs', Breadcrumbs::render('dashboard.projects.show'))
@elseif(Request::routeIs('dashboard.published-datasets.show'))
    @section('breadcrumbs', Breadcrumbs::render('dashboard.published-datasets.show'))
@endif

@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="mb-4">Site Statistics</h4>

            <div style="width:75%;" class="mb-4">
                {!! $usersChart->render() !!}
            </div>
            <br/>
            <br/>

            <div style="width:75%" class="mb-4">
                {!! $projectsChart->render() !!}
            </div>
            <br/>
            <br/>

            <div style="width:75%" class="mb-4">
                {!! $datasetsChart->render() !!}
            </div>
            <br/>
            <br/>

            <div style="width:75%" class="mb-4">
                {!! $entitiesChart->render() !!}
            </div>
            <br/>
            <br/>

            <div style="width:75%" class="mb-4">
                {!! $activitiesChart->render() !!}
            </div>
            <br/>
            <br/>

            <div style="width:75%" class="mb-4">
                {!! $attributesChart->render() !!}
            </div>
            <br/>
            <br/>

            <div style="width:75%" class="mb-4">
                {!! $filesUploadedChart->render() !!}
            </div>
        </div>
    </div>
@endsection
