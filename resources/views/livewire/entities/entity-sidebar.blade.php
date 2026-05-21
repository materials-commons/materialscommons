<div>
    @if($show)
        <div style="position:fixed; right:0; top:0; bottom:0; width:420px;
             background:white; border-left:1px solid #dee2e6; z-index:1055; overflow-y:auto;
             box-shadow:-4px 0 16px rgba(0,0,0,.12);">
            <div
                style="padding:14px 16px; border-bottom:1px solid #dee2e6; display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <div style="font-size:.65rem; text-transform:uppercase; letter-spacing:.06em; color:#6c757d;">
                        {{ $entityLabel }}
                    </div>
                    <div style="font-weight:700; font-size:.95rem; margin-top:2px;">
                        @if($experimentId !== 0)
                            @if($category == 'experimental')
                                <a class="no-underline" href="{{route('projects.experiments.entities.by-name.spread', [$entity->project_id, $experimentId, "name" => urlencode($entity->name), 'fromExperiment' => true])}}">
                                    {{ $entity->name }}
                                </a>
                            @else
                                <a class="no-underline" href="{{route('projects.experiments.computations.entities.by-name.spread', [$entity->project_id, $experimentId, "name" => urlencode($entity->name), 'fromExperiment' => true])}}">
                                    {{ $entity->name }}
                                </a>
                            @endif
                        @else
                            @if($category == 'experimental')
                                <a class="no-underline" href="{{route('projects.experiments.entities.by-name.spread', [$entity->project_id, $entity->experiments[0], "name" => urlencode($entity->name), 'fromExperiment' => false])}}">
                                    {{ $entity->name }}
                                </a>
                            @else
                                <a class="no-underline" href="{{route('projects.experiments.computations.entities.by-name.spread', [$entity->project_id, $entity->experiments[0], "name" => urlencode($entity->name), 'fromExperiment' => false])}}">
                                    {{ $entity->name }}
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
                <button type="button"
                        wire:click="close"
                        style="background:none; border:none; cursor:pointer; font-size:1.4rem; line-height:1; color:#6c757d; padding:0 0 0 8px;"
                        title="Close">&times;
                </button>
            </div>

            <div style="padding:12px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:14px;">
                    <div style="text-align:center;padding:8px 4px;border:1px solid #dee2e6;border-radius:6px;">
                        <div style="font-weight:700;font-size:1.05rem;color:#198754;">{{ $filesCount }}</div>
                        <div style="font-size:.62rem;color:#6c757d;text-transform:uppercase;letter-spacing:.04em;">
                            Files
                        </div>
                    </div>
                    <div style="text-align:center;padding:8px 4px;border:1px solid #dee2e6;border-radius:6px;">
                        <div
                            style="font-weight:700;font-size:1.05rem;color:#6c757d;">{{ $entity->activities->count() }}</div>
                        <div style="font-size:.62rem;color:#6c757d;text-transform:uppercase;letter-spacing:.04em;">
                            States
                        </div>
                    </div>
                </div>

                @if(!blank($entity->description))
                    <div
                        style="font-size:.8rem;color:#495057;margin-bottom:12px;padding:8px;background:#f8f9fa;border-radius:4px;border-left:3px solid #dee2e6;">
                        {{ $entity->description }}
                    </div>
                @endif

                <div
                    style="font-size:.68rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;margin-bottom:8px;font-weight:600;border-bottom:1px solid #dee2e6;padding-bottom:4px;">
                    Unique Processes ({{ $uniqueActivities->count() }})
                </div>

                @if($uniqueActivities->count() === 0)
                    <p style="color:#6c757d;font-style:italic;font-size:.85rem;">No processes recorded.</p>
                @else
                    @foreach($uniqueActivities as $name => $counts)
                        <div style="margin-bottom:8px;padding:8px 10px;border:1px solid #dee2e6;border-radius:6px;">
                            <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                                <span
                                    style="background:#6f42c1;color:white;border-radius:50px;min-width:20px;text-align:center;font-size:.65rem;padding:1px 4px;flex-shrink:0;">
                                    {{ $loop->index + 1 }}
                                </span>
                                <span style="font-weight:600;font-size:.85rem;">{{ $name }}</span>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:4px;padding-left:26px;">
                                <div style="font-size:.72rem;color:#6c757d;text-align:center;">
                                    <div
                                        style="font-weight:700;font-size:.9rem;color:#0d6efd;">{{ $counts['attributes_count'] }}</div>
                                    <div>Attributes</div>
                                </div>
                                <div style="font-size:.72rem;color:#6c757d;text-align:center;">
                                    <div
                                        style="font-weight:700;font-size:.9rem;color:#fd7e14;">{{ $counts['measurements_count'] }}</div>
                                    <div>Measurements</div>
                                </div>
                                <div style="font-size:.72rem;color:#6c757d;text-align:center;">
                                    <div
                                        style="font-weight:700;font-size:.9rem;color:#198754;">{{ $counts['files_count'] }}</div>
                                    <div>Files</div>
                                </div>
                                <div style="font-size:.72rem;color:#6c757d;text-align:center;">
                                    <div
                                        style="font-weight:700;font-size:.9rem;color:#198754;">{{ $counts['count'] }}</div>
                                    <div>Processes</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endif
</div>
