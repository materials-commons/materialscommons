<div>
    <li class="nav-item">
        <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.globus.uploads.index')}}"
           data-bs-toggle="tooltip" title="Upload data to your project using Globus."
           href="{{route('projects.globus.uploads.index', [$project])}}">
            <i class="fa-fw fas fa-cloud-upload-alt me-2 mb-1 fs-11"></i>
            Globus Upload
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.globus.downloads.index')}}"
           data-bs-toggle="tooltip" title="Download your projects files using Globus."
           href="{{route('projects.globus.downloads.index', [$project])}}">
            <i class="fa-fw fas fa-cloud-download-alt me-2 mb-1 fs-11"></i>
            Globus Download
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link fs-11 ms-3 {{setActiveNavByName('projects.globus.drops.index')}}"
           data-bs-toggle="tooltip" title="List/Create/Manage drop points for outside users to upload files to your project."
           href="{{route('projects.globus.drops.index', [$project])}}">
            <i class="fa-fw fas fa-box-open me-2 mb-1 fs-11"></i>
            Globus Drops
        </a>
    </li>

</div>
