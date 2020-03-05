@if (request()->query('show-overview', false))
    <p>
        Experiments allow you to break your research into individual chunks. Experiments are a convenient way to
        organize your research. Experiments are composed of files, samples, and processes from your project. You can
        share data across experiments. For example a process, sample and/or file may exist in more than one experiment
        in your project.
    </p>
@endif