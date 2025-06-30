@extends('layouts.app')

@section('pageTitle', 'Creating Zip File')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('breadcrumbs', Breadcrumbs::render('projects.folders.create-zip', $project, $folder))

@section('content')
    <div class="container mx-auto">
        <div class="max-w-4xl mx-auto py-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Creating Zip File for {{ $folder->name }}
                </h1>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <p class="mb-4 text-gray-600">
                        We're creating a zip file of this folder and its contents. This may take some time depending on the size and number of files.
                    </p>
                    
                    <livewire:folders.folder-zip-progress :zipId="$zipId" />
                </div>
            </div>
            
            <div class="mt-6">
                <a href="{{ route('projects.folders.show', [$project, $folder]) }}" class="text-indigo-600 hover:text-indigo-900">
                    Return to folder
                </a>
            </div>
        </div>
    </div>
@endsection