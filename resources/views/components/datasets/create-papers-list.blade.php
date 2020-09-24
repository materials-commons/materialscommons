<div class="form-group">
    <label for="dataset-papers">Papers</label>
    <div x-data="initPapers()">
        <a href="#" @click.prevent="addPaper()">
            <i class="fa fas fa-fw fa-plus"></i>Add Paper
        </a>
        <ul class="list-unstyled" id="dataset-papers">
            <template x-for="paper in papers" :key="paper.id">
                <li>
                    <div class="form-row mt-2 align-items-center">
                        <div class="col-auto col-lg-auto">
                            <input class="form-control mb-2" type="text"
                                   x-bind:name="`papers[${paper.id}][name]`"
                                   placeholder="Title... (Required)" required>
                        </div>
                        <div class="col-auto col-lg-auto">
                            <input class="form-control mb-2" type="text"
                                   x-bind:name="`papers[${paper.id}][authors]`"
                                   placeholder="Authors... (Required)" required>
                        </div>
                        <div class="col-auto col-lg-auto">
                            <input class="form-control mb-2" type="text"
                                   x-bind:name="`papers[${paper.id}][journal]`"
                                   placeholder="Journal... (Required)" required>
                        </div>
                        <div class="col-auto col-lg-auto">
                            <input class="form-control mb-2" type="text"
                                   x-bind:name="`papers[${paper.id}][doi]`"
                                   placeholder="DOI...">
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
                newPaperName: 'hello',
                papers: [],
                addPaper() {
                    this.papers.push({
                        id: this.papers.length + 1,
                        name: `${this.newPaperName}_${this.papers.length}`,
                    });
                    // this.newPaperName = '';
                },
                removePaper(paper) {
                    let index = this.papers.indexOf(p => p.id === paper.id);
                    this.papers.splice(index, 1);
                }
            };
        }
    </script>
@endpush