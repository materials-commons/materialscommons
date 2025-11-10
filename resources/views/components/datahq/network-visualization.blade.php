<div class="d-flex" style="height: 100vh;">
    <!-- Network Visualization Area (Left - 70%) -->
    <div class="bg-light border-end p-4" style="width: 70%;">
        <div id="network-container" class="w-100 h-100 bg-white rounded shadow-sm border d-flex align-items-center justify-content-center">
            <span class="text-muted fs-5">Network Graph Area</span>
        </div>
    </div>

    <!-- Controls Area (Right - 30%) -->
    <div class="bg-white p-4 overflow-auto" style="width: 30%;">
        <h2 class="fs-4 fw-semibold mb-4 text-dark">Network Controls</h2>

        <!-- Node Color Controls -->
        <div class="mb-5 pb-4 border-bottom">
            <h3 class="fs-5 fw-medium mb-3 text-secondary">Node Color by Value Range</h3>

            <div class="mb-3">
                <label class="form-label fw-medium">Attribute Name</label>
                <input type="text" id="node-color-attribute"
                       class="form-control"
                       placeholder="e.g., temperature">
            </div>

            <div id="node-color-ranges" class="mb-3">
                <!-- Dynamic ranges will be added here -->
            </div>

            <button onclick="addNodeColorRange()"
                    class="btn btn-primary w-100">
                + Add Range
            </button>
        </div>

        <!-- Edge Color Controls -->
        <div class="mb-5 pb-4 border-bottom">
            <h3 class="fs-5 fw-medium mb-3 text-secondary">Edge Color by Value Range</h3>

            <div class="mb-3">
                <label class="form-label fw-medium">Attribute Name</label>
                <input type="text" id="edge-color-attribute"
                       class="form-control"
                       placeholder="e.g., strength">
            </div>

            <div id="edge-color-ranges" class="mb-3">
                <!-- Dynamic ranges will be added here -->
            </div>

            <button onclick="addEdgeColorRange()"
                    class="btn btn-primary w-100">
                + Add Range
            </button>
        </div>

        <!-- Node Size Controls -->
        <div class="mb-5 pb-4 border-bottom">
            <h3 class="fs-5 fw-medium mb-3 text-secondary">Node Size by Value Range</h3>

            <div class="mb-3">
                <label class="form-label fw-medium">Attribute Name</label>
                <input type="text" id="node-size-attribute"
                       class="form-control"
                       placeholder="e.g., importance">
            </div>

            <div id="node-size-ranges" class="mb-3">
                <!-- Dynamic ranges will be added here -->
            </div>

            <button onclick="addNodeSizeRange()"
                    class="btn btn-primary w-100">
                + Add Range
            </button>
        </div>

        <!-- Data Source Configuration -->
        <div class="mb-4">
            <h2 class="fs-4 fw-semibold mb-4 text-dark">Data Source Configuration</h2>

            <!-- File Selection -->
            <div class="mb-3">
                <label class="form-label fw-medium">Select Data File</label>
                <select id="data-file" class="form-select" onchange="loadFileColumns()">
                    <option value="">-- Choose a file --</option>
                    <!-- File options will be populated dynamically -->
                </select>
            </div>

            <!-- Column Mappings -->
            <div id="column-mappings" style="display: none;">
                <h3 class="fs-6 fw-medium mb-3 text-secondary">Column Mappings</h3>

                <!-- Node ID Column -->
                <div class="mb-3">
                    <label class="form-label">Node ID Column</label>
                    <select id="col-node-id" class="form-select form-select-sm">
                        <option value="">-- Select column --</option>
                    </select>
                </div>

                <!-- Node X Position Column -->
                <div class="mb-3">
                    <label class="form-label">Node X Position Column</label>
                    <select id="col-node-x" class="form-select form-select-sm">
                        <option value="">-- Select column --</option>
                    </select>
                </div>

                <!-- Node Y Position Column -->
                <div class="mb-3">
                    <label class="form-label">Node Y Position Column</label>
                    <select id="col-node-y" class="form-select form-select-sm">
                        <option value="">-- Select column --</option>
                    </select>
                </div>

                <!-- Node 1 Column (Edge Start) -->
                <div class="mb-3">
                    <label class="form-label">Node 1 Column (Edge Start)</label>
                    <select id="col-node-1" class="form-select form-select-sm">
                        <option value="">-- Select column --</option>
                    </select>
                </div>

                <!-- Node 2 Column (Edge End) -->
                <div class="mb-3">
                    <label class="form-label">Node 2 Column (Edge End)</label>
                    <select id="col-node-2" class="form-select form-select-sm">
                        <option value="">-- Select column --</option>
                    </select>
                </div>

                <!-- Attribute for Node Size -->
                <div class="mb-3">
                    <label class="form-label">Attribute for Node Size</label>
                    <select id="col-node-size-attr" class="form-select form-select-sm">
                        <option value="">-- Select column --</option>
                    </select>
                </div>

                <!-- Attribute for Node Color -->
                <div class="mb-3">
                    <label class="form-label">Attribute for Node Color</label>
                    <select id="col-node-color-attr" class="form-select form-select-sm">
                        <option value="">-- Select column --</option>
                    </select>
                </div>

                <!-- Attribute for Edge Color -->
                <div class="mb-3">
                    <label class="form-label">Attribute for Edge Color</label>
                    <select id="col-edge-color-attr" class="form-select form-select-sm">
                        <option value="">-- Select column --</option>
                    </select>
                </div>

                <!-- Load Data Button -->
                <button onclick="loadNetworkData()" class="btn btn-success w-100 mt-3">
                    Load Network Data
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let nodeColorRangeCount = 0;
    let edgeColorRangeCount = 0;
    let nodeSizeRangeCount = 0;

    function addNodeColorRange() {
        const container = document.getElementById('node-color-ranges');
        const rangeId = `node-color-${nodeColorRangeCount++}`;
        const rangeHtml = `
            <div id="${rangeId}" class="p-3 bg-light rounded border mb-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-medium">Range ${nodeColorRangeCount}</span>
                    <button type="button" onclick="removeRange('${rangeId}')" class="btn-close btn-sm text-danger" aria-label="Close"></button>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <input type="number" placeholder="Min" class="form-control form-control-sm">
                    <span class="text-muted">to</span>
                    <input type="number" placeholder="Max" class="form-control form-control-sm">
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted mb-0">Color:</label>
                    <input type="color" class="form-control form-control-color" style="width: 3rem; height: 2rem;">
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', rangeHtml);
    }

    function addEdgeColorRange() {
        const container = document.getElementById('edge-color-ranges');
        const rangeId = `edge-color-${edgeColorRangeCount++}`;
        const rangeHtml = `
            <div id="${rangeId}" class="p-3 bg-light rounded border mb-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-medium">Range ${edgeColorRangeCount}</span>
                    <button type="button" onclick="removeRange('${rangeId}')" class="btn-close btn-sm text-danger" aria-label="Close"></button>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <input type="number" placeholder="Min" class="form-control form-control-sm">
                    <span class="text-muted">to</span>
                    <input type="number" placeholder="Max" class="form-control form-control-sm">
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted mb-0">Color:</label>
                    <input type="color" class="form-control form-control-color" style="width: 3rem; height: 2rem;">
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', rangeHtml);
    }

    function addNodeSizeRange() {
        const container = document.getElementById('node-size-ranges');
        const rangeId = `node-size-${nodeSizeRangeCount++}`;
        const rangeHtml = `
            <div id="${rangeId}" class="p-3 bg-light rounded border mb-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-medium">Range ${nodeSizeRangeCount}</span>
                    <button type="button" onclick="removeRange('${rangeId}')" class="btn-close btn-sm text-danger" aria-label="Close"></button>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <input type="number" placeholder="Min" class="form-control form-control-sm">
                    <span class="text-muted">to</span>
                    <input type="number" placeholder="Max" class="form-control form-control-sm">
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted mb-0">Size:</label>
                    <input type="number" placeholder="px" min="1" max="100" value="10" class="form-control form-control-sm" style="width: 5rem;">
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', rangeHtml);
    }

    function removeRange(rangeId) {
        const element = document.getElementById(rangeId);
        if (element) {
            element.remove();
        }
    }

    // Data Source Configuration Functions
    function loadFileColumns() {
        const fileSelect = document.getElementById('data-file');
        const selectedFile = fileSelect.value;

        if (!selectedFile) {
            document.getElementById('column-mappings').style.display = 'none';
            return;
        }

        // Show the column mappings section
        document.getElementById('column-mappings').style.display = 'block';

        // Fetch columns from the selected file
        // This is a placeholder - you'll need to implement the actual API call
        fetchFileColumns(selectedFile).then(columns => {
            populateColumnDropdowns(columns);
        });
    }

    function fetchFileColumns(fileId) {
        // Placeholder - implement your actual API call here
        // Example API call:
        // return fetch(`/api/files/${fileId}/columns`).then(response => response.json());

        // For now, return sample columns
        return Promise.resolve([
            'id', 'name', 'x_pos', 'y_pos', 'node1', 'node2',
            'size_value', 'color_value', 'edge_weight'
        ]);
    }

    function populateColumnDropdowns(columns) {
        const dropdownIds = [
            'col-node-id',
            'col-node-x',
            'col-node-y',
            'col-node-1',
            'col-node-2',
            'col-node-size-attr',
            'col-node-color-attr',
            'col-edge-color-attr'
        ];

        dropdownIds.forEach(dropdownId => {
            const select = document.getElementById(dropdownId);
            // Clear existing options except the first one
            select.innerHTML = '<option value="">-- Select column --</option>';

            // Add column options
            columns.forEach(column => {
                const option = document.createElement('option');
                option.value = column;
                option.textContent = column;
                select.appendChild(option);
            });
        });
    }

    function loadNetworkData() {
        const fileId = document.getElementById('data-file').value;
        const columnMappings = {
            nodeId: document.getElementById('col-node-id').value,
            nodeX: document.getElementById('col-node-x').value,
            nodeY: document.getElementById('col-node-y').value,
            node1: document.getElementById('col-node-1').value,
            node2: document.getElementById('col-node-2').value,
            nodeSizeAttr: document.getElementById('col-node-size-attr').value,
            nodeColorAttr: document.getElementById('col-node-color-attr').value,
            edgeColorAttr: document.getElementById('col-edge-color-attr').value
        };

        // Validate that required fields are selected
        const requiredFields = ['nodeId', 'node1', 'node2'];
        const missingFields = requiredFields.filter(field => !columnMappings[field]);

        if (missingFields.length > 0) {
            alert('Please select all required columns: Node ID, Node 1, and Node 2');
            return;
        }

        console.log('Loading network data with mappings:', columnMappings);

        // Placeholder for actual network data loading
        // You'll implement the actual vis.js network rendering here
        // Example:
        // fetchNetworkData(fileId, columnMappings).then(data => {
        //     renderNetwork(data);
        // });
    }

    // Initialize file list on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch available files and populate the dropdown
        // This is a placeholder - implement your actual API call
        fetchAvailableFiles().then(files => {
            const fileSelect = document.getElementById('data-file');
            files.forEach(file => {
                const option = document.createElement('option');
                option.value = file.id;
                option.textContent = file.name;
                fileSelect.appendChild(option);
            });
        });
    });

    function fetchAvailableFiles() {
        // Placeholder - implement your actual API call here
        // Example: return fetch('/api/files').then(response => response.json());

        // For now, return sample files
        return Promise.resolve([
            { id: '1', name: 'network_data_1.csv' },
            { id: '2', name: 'network_data_2.csv' },
            { id: '3', name: 'graph_connections.xlsx' }
        ]);
    }
</script>
