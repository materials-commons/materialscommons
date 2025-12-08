<div class="mb-3">
    <label for="dataset-papers">Papers</label>
    <div x-data="initPapers()">
        <a href="#" @click.prevent="addPaper()">
            <i class="fa fas fa-fw fa-plus"></i>Add Paper
        </a>

        <ul class="list-unstyled" id="existing-papers">
            <template x-for="paper in existing" :key="paper.id">
                <li class="mb-4 mt-2">
                    <div class="row align-items-center">
                        <div class="col-5">
                            <textarea class="form-control mb-2" type="text"
                                      x-model="paper.name"
                                      x-bind:name="`existing_papers[${paper.id}][name]`"
                                      placeholder="Title... (Required)" required>
                            </textarea>
                        </div>
                        <div class="col-5">
                            <textarea class="form-control mb-2" type="text"
                                      x-model="paper.reference"
                                      x-bind:name="`existing_papers[${paper.id}][reference]`"
                                      placeholder="Reference... (Required)" required>
                            </textarea>
                        </div>
                    </div>
                    <div class="row mt-1 align-items-center">
                        <div class="col-10">
                            <input class="form-control mb-2" type="text"
                                   x-model="paper.url"
                                   x-bind:name="`existing_papers[${paper.id}][url]`"
                                   placeholder="URL...">
                        </div>
                        <a href="#" @click.prevent="removeExistingPaper(paper)">
                            <i class="fa fas fa-fw fa-trash"></i>
                        </a>
                    </div>
                </li>
            </template>
        </ul>
        <ul class="list-unstyled" id="dataset-papers">
            <template x-for="paper in papers" :key="paper.id">
                <li class="mb-4 mt-2">
                    <div class="row align-items-center">
                        <div class="col-5">
                            <textarea class="form-control mb-2" type="text"
                                      x-model="paper.name"
                                      x-bind:name="`papers[${paper.id}][name]`"
                                      placeholder="Title... (Required)" required>
                            </textarea>
                        </div>
                        <div class="col-5">
                            <textarea class="form-control mb-2" type="text"
                                      x-model="paper.reference"
                                      x-bind:name="`papers[${paper.id}][reference]`"
                                      placeholder="Reference... (Required)" required>
                            </textarea>
                        </div>
                    </div>
                    <div class="row mt-1 align-items-center">
                        <div class="col-10">
                            <input class="form-control mb-2" type="text"
                                   x-model="paper.url"
                                   x-bind:name="`papers[${paper.id}][url]`"
                                   placeholder="URL...">
                        </div>
                        <a href="#" @click.prevent="removePaper(paper)">
                            <i class="fa fas fa-fw fa-trash"></i>
                        </a>
                    </div>
                </li>
            </template>
        </ul>
    </div>
</div>

@push('scripts')
    <script>
        function initPapers() {
            return {
                existing: [
                        @if(!is_null($existing))
                        @foreach($existing as $p)
                    {
                        id: {{$p->id}},
                        name: "{{$p->name}}",
                        reference: "{{$p->reference}}",
                        url: "{{$p->url}}"
                    },
                    @endforeach
                    @endif
                ],
                papers: [
                        @if(old('papers'))
                        @foreach(old('papers') as $key => $value)
                    {
                        id: {{$key}},
                        name: "{{$value['name']}}",
                        reference: "{{$value['reference']}}",
                        url: "{{$value['url']}}"
                    },
                    @endforeach
                    @endif
                ],
                addPaper() {
                    this.papers.push({
                        id: this.papers.length + 1,
                        name: '',
                        reference: '',
                        url: ''
                    });
                },
                removePaper(paper) {
                    let index = this.papers.indexOf(p => p.id === paper.id);
                    this.papers.splice(index, 1);
                },
                removeExistingPaper(paper) {
                    let index = this.existing.indexOf(p => p.id === paper.id);
                    this.existing.splice(index, 1);
                }
            };
        }
    </script>
@endpush
