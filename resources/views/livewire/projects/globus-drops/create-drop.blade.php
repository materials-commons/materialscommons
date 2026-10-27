<div class="card">
    <div class="card-body">
        <form wire:submit.prevent="save">
            <div class="mb-3">
                <label for="name" class="form-label">Name
                    <span class="text-danger">*</span>
                </label>
                <input type="text"
                       id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       wire:model.blur="name"
                       required>

                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" wire:model.blur="message"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Globus Users
                    <span class="text-danger">*</span>
                </label>

                @foreach($globusUsers as $index => $globusUser)
                    <div class="mb-2">
                        <div class="input-group">
                            <input type="email"
                                   class="form-control @error("globusUsers.{$index}") is-invalid @enderror"
                                   wire:model="globusUsers.{{ $index }}"
                                   placeholder="user@example.com"
                                   required>

                            @if($index === count($globusUsers) - 1)
                                <button type="button"
                                        class="btn btn-outline-primary"
                                        wire:click="addGlobusUser"
                                        title="Add another Globus user">
                                    <i class="fas fa-plus"></i>
                                </button>
                            @endif

                            @if(count($globusUsers) > 1)
                                <button type="button"
                                        class="btn btn-outline-danger"
                                        wire:click="removeGlobusUser({{ $index }})"
                                        title="Remove Globus user">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @endif

                            @error("globusUsers.{$index}")
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endforeach

                <div wire:loading
                     wire:target="globusUsers,addGlobusUser,save"
                     class="form-text text-primary">
                    <i class="fas fa-spinner fa-spin me-1"></i>
                    Validating Globus identities...
                </div>

                <div class="form-text">
                    Enter Globus IDs as email addresses. Click the plus button to add another user.
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('projects.globus.drops.index', [$project]) }}"
                   class="btn btn-secondary me-2">
                    Cancel
                </a>

                <button type="submit"
                        class="btn btn-primary"
                        wire:loading.attr="disabled"
                        wire:target="save">
                    <span wire:loading.remove wire:target="save">Submit</span>
                    <span wire:loading wire:target="save"><i
                            class="fas fa-spinner fa-spin me-1"></i>Validating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
