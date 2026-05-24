@php
    if (old('ds_authors')) {
        $initialAuthors = collect(old('ds_authors'))->map(fn($a) => [
            'name'         => $a['name'] ?? '',
            'email'        => $a['email'] ?? '',
            'affiliations' => $a['affiliations'] ?? '',
        ])->values()->toArray();
    } elseif (!is_null($dataset) && is_array($dataset->ds_authors) && count($dataset->ds_authors) > 0) {
        $initialAuthors = collect($dataset->ds_authors)->map(fn($a) => [
            'name'         => $a['name'],
            'email'        => $a['email'],
            'affiliations' => $a['affiliations'],
        ])->values()->toArray();
    } else {
        $initialAuthors = $project->team->members->merge($project->team->admins)->map(fn($a) => [
            'name'         => $a->name,
            'email'        => $a->email,
            'affiliations' => $a->affiliations ?? '',
        ])->values()->toArray();
    }
@endphp

<div class="mb-3" x-data="initAuthors()">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <label class="mb-0 fw-semibold">Authors</label>
        <button type="button" class="btn btn-sm btn-outline-primary" @click="addAuthor()">
            <i class="fas fa-plus me-1"></i> Add Author
        </button>
    </div>

    <div x-show="authors.length === 0"
         class="text-muted fst-italic small py-2 px-1 border rounded bg-light text-center">
        No authors added yet.
    </div>

    <template x-for="(author, index) in authors" :key="author._key">
        <div class="card mb-2 border">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center gap-2">

                    {{-- Order badge --}}
                    <span class="badge rounded-pill text-bg-secondary flex-shrink-0"
                          style="min-width:1.6rem; font-size:.7rem;"
                          x-text="index + 1"></span>

                    {{-- Fields --}}
                    <div class="row g-2 flex-grow-1">
                        <div class="col-md-4">
                            <input class="form-control form-control-sm"
                                   type="text"
                                   x-model="author.name"
                                   x-bind:name="`ds_authors[${index}][name]`"
                                   placeholder="Name (Required)">
                        </div>
                        <div class="col-md-4">
                            <input class="form-control form-control-sm"
                                   type="email"
                                   x-model="author.email"
                                   x-bind:name="`ds_authors[${index}][email]`"
                                   placeholder="Email">
                        </div>
                        <div class="col-md-4">
                            <input class="form-control form-control-sm"
                                   type="text"
                                   x-model="author.affiliations"
                                   x-bind:name="`ds_authors[${index}][affiliations]`"
                                   placeholder="Affiliations">
                        </div>
                    </div>

                    {{-- Move up/down --}}
                    <div class="d-flex flex-column gap-1 flex-shrink-0">
                        <button type="button"
                                class="btn btn-sm btn-outline-secondary py-0 px-1"
                                :disabled="index === 0"
                                @click="moveUp(index)"
                                title="Move up">
                            <i class="fas fa-chevron-up" style="font-size:.6rem;"></i>
                        </button>
                        <button type="button"
                                class="btn btn-sm btn-outline-secondary py-0 px-1"
                                :disabled="index === authors.length - 1"
                                @click="moveDown(index)"
                                title="Move down">
                            <i class="fas fa-chevron-down" style="font-size:.6rem;"></i>
                        </button>
                    </div>

                    {{-- Remove --}}
                    <button type="button"
                            class="btn btn-sm btn-outline-danger py-0 px-2 flex-shrink-0"
                            @click="removeAuthor(index)"
                            title="Remove author">
                        <i class="fas fa-trash" style="font-size:.7rem;"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
    <script>
        function initAuthors() {
            let _nextKey = 0;
            const initialAuthors = @json($initialAuthors);

            return {
                authors: initialAuthors.map(a => ({...a, _key: _nextKey++})),

                addAuthor() {
                    this.authors.push({name: '', email: '', affiliations: '', _key: _nextKey++});
                },

                removeAuthor(index) {
                    this.authors.splice(index, 1);
                },

                moveUp(index) {
                    if (index > 0) {
                        const tmp = this.authors[index - 1];
                        this.authors[index - 1] = this.authors[index];
                        this.authors[index] = tmp;
                        this.authors = [...this.authors];
                    }
                },

                moveDown(index) {
                    if (index < this.authors.length - 1) {
                        const tmp = this.authors[index + 1];
                        this.authors[index + 1] = this.authors[index];
                        this.authors[index] = tmp;
                        this.authors = [...this.authors];
                    }
                },
            };
        }
    </script>
@endpush
