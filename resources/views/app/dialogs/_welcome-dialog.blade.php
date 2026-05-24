<div class="modal fade" tabindex="-1" id="welcome-dialog" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Welcome Aboard!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <h4>Welcome to Materials Commons!</h4>

                <p>
                    Materials Commons will help you to organize, track and publish your research.
                <p>

                <p>
                    It can be confusing learning a new system. We have guides to help you get started. To start
                    everything in Materials Commons is organized around
                    a project. The "CREATE PROJECT" button will guide you through setting up your first project
                    and using the system.
                </p>

                <p>
                    If you are the type who prefers to start with documentation we've also included a link to our
                    Getting Started guide. Don't worry about dismissing this dialog, you can always get back to it by
                    clicking on the Help button. The help popup has a button titled "Welcome Dialog" that will bring
                    this dialog up.
                </p>

                <form>
                    <div>
                        <a class="btn btn-primary" href="{{route('projects.create', ['show-overview' => true])}}">
                            Create project
                        </a>
                        <a class="btn btn-info" href="https://materialscommons.org/mcdocs2/getting_started/overview.html" target="_blank">
                            Read the getting started guide
                        </a>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Dismiss</button>
            </div>
        </div>
    </div>
</div>
