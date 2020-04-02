@extends('layouts.email')

@section('content')
    <p>
        Hello {{$vm->user()->name}},
    </p>
    <p>
        We are pleased to announce that a completely new version of
        <a href="https://materialscommons.org">Materials Commons</a> will be available
        on Wednesday, April 8th. The new site offers a number of improvements that are based on user feedback.
        We also have more new features planned this year.
    </p>

    <p>Major enhancements include:</p>
    <ul>
        <li>A completely new and redesigned website</li>
        <li>Enhanced search</li>
        <li>Faster and easier data publication</li>
        <li>Improved visualization of your scientific workflow</li>
        <li>Streamlined uploads/downloads using Globus for large datasets</li>
    </ul>

    <p>
        If you would like to see screenshots and a tour of the site you can find it at the
        <a href="https://materialscommons.org/docs/docs/tour/">Materials Commons 2.0 Tour</a>. Documentation can be
        found at
        <a href="https://materialscommons.org/docs/docs/getting-started/">Materials Commons 2.0 Documentation.</a><b>
            These and other links
            will be available starting on April 8th.</b>
    </p>
    <p>
        Your data will be automatically uploaded from Materials Commons to Materials Commons 2.0 on April 6th
        and will be available for your viewing on April 8th. To login you will need to
        reset
        your password. You can easily reset your password by going <a
                href="https://materialscommons.org/password/reset">here</a>. During the transition on April 6 and 7,
        Materials Commons will not be available to upload new data.
    </p>
    <p>
        After the transition on April 6, the old Materials Commons site will be available in readonly mode at
        <a href="https://materialscommons.eecs.umich.edu">materialscommons.eecs.umich.edu</a> for a period of time
        for you to make sure that the movement of your information was successful.
    </p>
    <p>
        Thank you for your use of Materials Commons! We are committed to meeting the
        information infrastructure needs of the materials community and appreciate your support
        and feedback. If you have questions or comments you can send email to Glenn Tarcea (gtarcea@umich.edu).
    </p>
    <p>
        The Materials Commons Team
    </p>
    <p>
        <small>
            The Materials Commons is provided by the <a href="http://prisms-center.org">PRISMS Center</a> which
            is supported by the U.S. Department of Energy (DOE).
        </small>
    </p>
@endsection