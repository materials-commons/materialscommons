@extends('layouts.email')

@section('content')
    <h4>Below is a starting point for the materials commons announcement email:</h4>
    <hr>
    <p>
        Hello {{$vm->user()->name}},
    </p>
    <p>
        We are excited to announce that a completely new version of Materials Commons is
        available. This is a ground up rewrite of the site that adds many ease of use
        enhancements. Below is a screenshot for the new published datasets page.
    </p>

    <div class="col-8">
        {{--        <img width="400" height="300"--}}
        <img class="img-fluid" src="{{$vm->embedPngFile('email/published-dataset.png')}}">
    </div>
    <br>
    <p>
        The new site features a number of improvements and we have many exciting new features planned
        this year. If you tried Materials Commons in the past and found it difficult to use we hope
        you will give it another look. We've been busy incorporating feedback and working to improve
        the site.
    </p>

    <p>A small list of improvements includes</p>
    <ul>
        <li>A completely new and redesigned website</li>
        <li>Enhanced search</li>
        <li>Replaced the old workflow editor with a simpler tool for describing your experimental steps.</li>
        <li>Completely redesigned how you publish your data to make it easier to use, including
            a wizard that guides you through the steps.
        </li>
        <li>And much, much more.</li>
    </ul>

    <p>
        If you would like to take a tour of the site you can find it at the
        <a href="{{makeHelpUrl("tour")}}">Materials Commons 2.0 Tour</a>. Documentation can be found at
        <a href="{{makeHelpUrl('getting-started')}}">Materials Commons 2.0 Documentation.</a>. And finally Materials
        Commons is located
        <a href="http://materialscommons.org">here</a>.
    </p>
    <p>
        Thank you!
    </p>
    <p>
        The Materials Commons Team
    </p>
@endsection