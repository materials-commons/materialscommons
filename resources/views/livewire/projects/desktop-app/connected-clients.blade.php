<div wirexx:poll.15s>
    <li class="nav-item mt-3">
        <span class="ms-3 fs-11"><i class="fas fa-desktop me-2"></i>Desktop Client Connections</span>
    </li>

    @forelse($clients as $client)
        <li class="nav-item">
            <a class="nav-link fs-11 ms-3" href="{{route('projects.desktops.show', [$project, $client->clientId])}}">
                <i class="fas fa-circle me-2 text-success"
                   style="font-size: 0.5rem; vertical-align: middle;"></i>
                {{ $client->hostname }}
            </a>
        </li>
    @empty
        <li class="nav-item">
            <span class="nav-link fs-11 ms-3 text-muted">
                <i class="far fa-circle me-2" style="font-size: 0.5rem; vertical-align: middle;"></i>
                No Active Clients
            </span>
        </li>
    @endforelse
</div>
