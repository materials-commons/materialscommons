<div class="d-flex align-items-center mb-2 mt-3">
    <button class="btn btn-link btn-sm p-0 text-decoration-none text-muted d-flex align-items-center gap-2"
            type="button"
            id="proj-chat-toggle"
            data-bs-toggle="collapse"
            data-bs-target="#proj-chat"
            aria-expanded="false"
            aria-controls="proj-chat">
        <i class="fas fa-chevron-right fa-fw proj-chevron" style="transition:transform .2s; font-size:.75rem;"></i>
        <span class="fw-semibold" style="font-size:.85rem; letter-spacing:.03em; text-transform:uppercase;">
            Chat
        </span>
        <span class="badge rounded-pill ms-1" style="font-size:.65rem; background:#6366f1; color:#fff;">AI</span>
    </button>
    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>

<div class="collapse mb-1" id="proj-chat"
     data-mc-collapse-key="{{$projKey}}_chat">
    <div class="card border-0 shadow-sm mb-3" style="border-radius:.75rem; overflow:hidden;">
        {{-- Chat header --}}
        <div class="card-header d-flex align-items-center gap-2 py-2 px-3"
             style="background:linear-gradient(135deg,#6366f1 0%,#4f46e5 100%); border:none;">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width:30px;height:30px;background:rgba(255,255,255,.2); flex-shrink:0;">
                <i class="fas fa-robot" style="color:#fff; font-size:.75rem;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-semibold text-white" style="font-size:.85rem; line-height:1.1;">Project Assistant
                </div>
                <div style="font-size:.7rem; color:rgba(255,255,255,.7);">Ask anything about this project</div>
            </div>
            <span class="d-flex align-items-center gap-1" style="font-size:.7rem; color:rgba(255,255,255,.8);">
                <span class="rounded-circle"
                      style="width:7px;height:7px;background:#4ade80;display:inline-block;"></span>
                Ready
            </span>
        </div>

        {{-- Message thread --}}
        <div id="proj-chat-messages"
             class="px-3 py-3 background-white"
             style="min-height:260px; max-height:380px; overflow-y:auto; display:flex; flex-direction:column; gap:.75rem;">

            {{-- Assistant greeting bubble --}}
            <div class="d-flex align-items-start gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
                     style="width:28px;height:28px;background:#ede9fe;">
                    <i class="fas fa-robot" style="color:#6366f1; font-size:.65rem;"></i>
                </div>
                <div>
                    <div class="px-3 py-2 rounded-3"
                         style="background:#f3f4f6; max-width:480px; font-size:.84rem; line-height:1.5;">
                        Hi! I'm your Project Assistant. I can help you explore your data, summarize studies,
                        find files, and answer questions about <strong>{{ $project->name }}</strong>.
                        What would you like to know?
                    </div>
                    <div class="text-muted mt-1" style="font-size:.68rem; padding-left:.25rem;">Just now</div>
                </div>
            </div>

            {{-- Example user bubble --}}
            <div class="d-flex align-items-start gap-2 flex-row-reverse">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
                     style="width:28px;height:28px;background:#dbeafe;">
                    <i class="fas fa-user" style="color:#3b82f6; font-size:.65rem;"></i>
                </div>
                <div>
                    <div class="px-3 py-2 rounded-3"
                         style="background:#6366f1; color:#fff; max-width:480px; font-size:.84rem; line-height:1.5;">
                        How many files were uploaded this month?
                    </div>
                    <div class="text-muted mt-1 text-end" style="font-size:.68rem; padding-right:.25rem;">Just now
                    </div>
                </div>
            </div>

            {{-- Assistant reply bubble --}}
            <div class="d-flex align-items-start gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
                     style="width:28px;height:28px;background:#ede9fe;">
                    <i class="fas fa-robot" style="color:#6366f1; font-size:.65rem;"></i>
                </div>
                <div>
                    <div class="px-3 py-2 rounded-3"
                         style="background:#f3f4f6; max-width:480px; font-size:.84rem; line-height:1.5;">
                        This project currently has <strong>{{ number_format($project->file_count) }} files</strong>
                        across <strong>{{ number_format($project->directory_count) }} directories</strong>.
                        For a detailed upload timeline, check the <em>Analytics</em> section below.
                    </div>
                    <div class="text-muted mt-1" style="font-size:.68rem; padding-left:.25rem;">Just now</div>
                </div>
            </div>

        </div>

        {{-- Suggested prompts --}}
        <div class="px-3 pb-2 pt-1 background-white border-top" style="border-color:#f3f4f6 !important;">
            <div class="d-flex flex-wrap gap-2 py-2" style="font-size:.75rem;">
                <span class="text-muted me-1" style="line-height:1.9;">Try:</span>
                <button type="button" class="btn btn-sm px-2 py-1 mc-chip-btn"
                        style="font-size:.74rem; border:1px solid #e5e7eb; border-radius:999px; background:#fff; color:#6366f1;"
                        disabled>
                    Summarize recent activity
                </button>
                <button type="button" class="btn btn-sm px-2 py-1 mc-chip-btn"
                        style="font-size:.74rem; border:1px solid #e5e7eb; border-radius:999px; background:#fff; color:#6366f1;"
                        disabled>
                    List my studies
                </button>
                <button type="button" class="btn btn-sm px-2 py-1 mc-chip-btn"
                        style="font-size:.74rem; border:1px solid #e5e7eb; border-radius:999px; background:#fff; color:#6366f1;"
                        disabled>
                    Find large files
                </button>
                <button type="button" class="btn btn-sm px-2 py-1 mc-chip-btn"
                        style="font-size:.74rem; border:1px solid #e5e7eb; border-radius:999px; background:#fff; color:#6366f1;"
                        disabled>
                    What datasets are published?
                </button>
            </div>
        </div>

        {{-- Input row --}}
        <div class="px-3 pb-3 background-white">
            <div class="d-flex gap-2 align-items-end">
                <div class="flex-grow-1 position-relative">
                    <textarea class="form-control"
                              id="proj-chat-input"
                              rows="2"
                              placeholder="Ask a question about your project…"
                              style="resize:none; border-radius:.6rem; border-color:#e5e7eb; font-size:.84rem; padding-right:2.5rem; line-height:1.45;"
                              disabled></textarea>
                    <span class="position-absolute text-muted"
                          style="right:.6rem; bottom:.5rem; font-size:.65rem; pointer-events:none;">
                        Preview
                    </span>
                </div>
                <button type="button"
                        class="btn d-flex align-items-center justify-content-center"
                        style="width:40px;height:40px;flex-shrink:0;border-radius:.6rem;background:#6366f1;border:none;"
                        disabled
                        title="Send (coming soon)">
                    <i class="fas fa-paper-plane" style="color:#fff; font-size:.8rem;"></i>
                </button>
            </div>
            {{--            <div class="text-muted mt-1" style="font-size:.67rem;">--}}
            {{--                <i class="fas fa-info-circle me-1"></i>--}}
            {{--                Chat functionality coming soon — this is a UI preview.--}}
            {{--            </div>--}}
        </div>
    </div>
</div>
