@extends('layouts.email')

@section('content')
    <p>
        Hello {{$vm->user()->name}},
    </p>
    <p>
        We are excited to announce that a completely new version of
        <a href="https://materialscommons.org">Materials Commons</a> will be available
        on Friday, April 2nd. The new site offers a number of improvements based on user feedback. We also have
        exciting new features planned this year.
    </p>

    <p>Major enhancements include:</p>
    <ul>
        <li>A completely new and redesigned website</li>
        <li>Enhanced search</li>
        <li>Improved workflow visualization</li>
        <li>Faster and easier data publication
        </li>
    </ul>

    <p>
        If you would like to see screenshots and a tour of the site you can find it at the
        <a href="{{makeHelpUrl("tour")}}">Materials Commons 2.0 Tour</a>. Documentation can be found at
        <a href="{{makeHelpUrl('getting-started')}}">Materials Commons 2.0 Documentation.</a>.
    </p>
    <p>
        Your data will be automatically uploaded into the new Materials Commons 2.0 site. To login you will need to
        reset
        your password. You can easily reset your password by going <a
                href="https://materialscommons.org/password/reset">here</a>.
    </p>
    <p>
        The old Materials Commons site will be available in readonly mode at
        <a href="https://materialscommons.eecs.umich.edu">materialscommons.eecs.umich.edu</a>.
    </p>
    <p>
        Thank you!
    </p>
    <p>
        The Materials Commons Team
    </p>
@endsection