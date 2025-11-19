<div class="mb-4">
    <h2 class="fs-4 fw-semibold mb-4 text-dark">Data Source Configuration</h2>

    <div wire:loading class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>

    <!-- File Selection -->
    <div class="mb-3">
        <label class="form-label fw-medium">Select Data File/Google Sheet</label>
        <select wire:model.live="selectedSheet" id="data-file" class="form-select">
            <option value="">-- Choose a file/Google Sheet --</option>
            @foreach($sheets as $sheet)
                @if (isset($sheet->title))
                    <option value="{{$sheet->id}}:g">{{$sheet->title}}</option>
                @else
                    <option value="{{$sheet->id}}:f">{{$sheet->name}}</option>
                @endif
            @endforeach
            <!-- File options will be populated dynamically -->
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label fw-medium">Select Sheet In File</label>
        <select wire:model.live="selectedSubsheet" id="data-file-sheet" class="form-select">
            <option value="">-- Choose a sheet --</option>
            @foreach($subsheets as $subsheet)
                <option value="{{$subsheet}}">{{$subsheet}}</option>
            @endforeach
        </select>
    </div>

    <!-- Column Mappings -->
    @if($selectedSubsheet !== "")
        <div id="column-mappings">
            <h3 class="fs-6 fw-medium mb-3 text-secondary">Column Mappings</h3>

            <!-- Node ID Column -->
            <div class="mb-3">
                <label class="form-label">Node ID Column</label>
                <select wire:model="nodeIdColumn" id="col-node-id" class="form-select form-select-sm">
                    <option value="">-- Select column --</option>
                    @foreach($columns as $column)
                        <option value="{{$column[0]}}">{{$column[1]}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Node X Position Column -->
            <div class="mb-3">
                <label class="form-label">Node X Position Column</label>
                <select wire:model="nodeXColumn" id="col-node-x" class="form-select form-select-sm">
                    <option value="">-- Select column --</option>
                    @foreach($columns as $column)
                        <option value="{{$column[0]}}">{{$column[1]}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Node Y Position Column -->
            <div class="mb-3">
                <label class="form-label">Node Y Position Column</label>
                <select wire:model="nodeYColumn" id="col-node-y" class="form-select form-select-sm">
                    <option value="">-- Select column --</option>
                    @foreach($columns as $column)
                        <option value="{{$column[0]}}">{{$column[1]}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Node 1 Column (Edge Start) -->
            <div class="mb-3">
                <label class="form-label">Node 1 Column (Edge Start)</label>
                <select wire:model="edgeStartColumn" id="col-node-1" class="form-select form-select-sm">
                    <option value="">-- Select column --</option>
                    @foreach($columns as $column)
                        <option value="{{$column[0]}}">{{$column[1]}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Node 2 Column (Edge End) -->
            <div class="mb-3">
                <label class="form-label">Node 2 Column (Edge End)</label>
                <select wire:model="edgeEndColumn" id="col-node-2" class="form-select form-select-sm">
                    <option value="">-- Select column --</option>
                    @foreach($columns as $column)
                        <option value="{{$column[0]}}">{{$column[1]}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Attribute for Node Size -->
            <div class="mb-3">
                <label class="form-label">Attribute for Node Size</label>
                <select wire:model="nodeSizeColumn" id="col-node-size-attr" class="form-select form-select-sm">
                    <option value="">-- Select column --</option>
                    @foreach($columns as $column)
                        <option value="{{$column[0]}}">{{$column[1]}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Attribute for Node Color -->
            <div class="mb-3">
                <label class="form-label">Attribute for Node Color</label>
                <select wire:model="nodeColorColumn" id="col-node-color-attr" class="form-select form-select-sm">
                    <option value="">-- Select column --</option>
                    @foreach($columns as $column)
                        <option value="{{$column[0]}}">{{$column[1]}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Attribute for Edge Color -->
            <div class="mb-3">
                <label class="form-label">Attribute for Edge Color</label>
                <select wire:model="edgeColorColumn" id="col-edge-color-attr" class="form-select form-select-sm">
                    <option value="">-- Select column --</option>
                    @foreach($columns as $column)
                        <option value="{{$column[0]}}">{{$column[1]}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Load Data Button -->
            <button wire:click.prevent="loadNetworkData()" class="btn btn-success w-100 mt-3">
                Load Network Data
            </button>
        </div>
    @endif
</div>
