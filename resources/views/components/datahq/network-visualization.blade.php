@props(['project'])
<!-- Include vis.js from CDN if not already included in your layout -->
<script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>

<div class="d-flex" style="height: 100vh;">
    <!-- Network Visualization Area (Left - 70%) -->
    <div class="bg-light border-end p-4 rounded" style="width: 70%;">
        <div id="network-container" class="w-100 h-100 bg-white rounded shadow-sm border"></div>
    </div>

    <!-- Controls Area (Right - 30%) -->
    <div class="bg-white p-4 overflow-auto" style="width: 30%;">
        <h2 class="fs-4 fw-semibold mb-4 text-dark">Network Controls</h2>

        <!-- Display Feature Toggles -->
        <div class="mb-5 pb-4 border-bottom">
            <h3 class="fs-5 fw-medium mb-3 text-secondary">Display Features</h3>

            <div class="row g-2">
                <!-- Show Edges Toggle -->
                <div class="col-6">
                    <label class="form-label small mb-1">Show Edges</label>
                    <select id="toggle-edges" class="form-select form-select-sm" onchange="applyDisplayChanges()">
                        <option value="true">Yes</option>
                        <option value="false">No</option>
                    </select>
                </div>

                <!-- Show Edge Color Toggle -->
                <div class="col-6">
                    <label class="form-label small mb-1">Show Edge Color</label>
                    <select id="toggle-edge-color" class="form-select form-select-sm" onchange="applyDisplayChanges()">
                        <option value="true">Yes</option>
                        <option value="false">No</option>
                    </select>
                </div>

                <!-- Show Node Size Toggle -->
                <div class="col-6">
                    <label class="form-label small mb-1">Show Node Size</label>
                    <select id="toggle-node-size" class="form-select form-select-sm" onchange="applyDisplayChanges()">
                        <option value="true">Yes</option>
                        <option value="false">No</option>
                    </select>
                </div>

                <!-- Show Node Color Toggle -->
                <div class="col-6">
                    <label class="form-label small mb-1">Show Node Color</label>
                    <select id="toggle-node-color" class="form-select form-select-sm" onchange="applyDisplayChanges()">
                        <option value="true">Yes</option>
                        <option value="false">No</option>
                    </select>
                </div>
            </div>
        </div>

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
                    class="btn btn-primary btn-sm w-100">
                + Add Range
            </button>
            <button onclick="applyNodeColorRanges()"
                    class="btn btn-success btn-sm w-100 mt-2">
                Apply Node Colors
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
                    class="btn btn-primary btn-sm w-100">
                + Add Range
            </button>
            <button onclick="applyEdgeColorRanges()"
                    class="btn btn-success btn-sm w-100 mt-2">
                Apply Edge Colors
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
                    class="btn btn-primary btn-sm w-100">
                + Add Range
            </button>
            <button onclick="applyNodeSizeRanges()"
                    class="btn btn-success btn-sm w-100 mt-2">
                Apply Node Sizes
            </button>
        </div>

        <!-- Data Source Configuration -->
        <livewire:datahq.networkhq.data-source :project="$project"/>
    </div>
</div>

<script>
    let nodeColorRangeCount = 0;
    let edgeColorRangeCount = 0;
    let nodeSizeRangeCount = 0;
    let network = null;
    let nodes = null;
    let edges = null;

    // Hardcoded network data
    const networkData = {
        nodes: [
            { id: 1, label: 'Node 1', x: -100, y: -100, temperature: 25, size_value: 10 },
            { id: 2, label: 'Node 2', x: 100, y: -100, temperature: 45, size_value: 20 },
            { id: 3, label: 'Node 3', x: 100, y: 100, temperature: 65, size_value: 30 },
            { id: 4, label: 'Node 4', x: -100, y: 100, temperature: 85, size_value: 40 },
            { id: 5, label: 'Node 5', x: 0, y: 0, temperature: 55, size_value: 25 },
            { id: 6, label: 'Node 6', x: -200, y: 0, temperature: 35, size_value: 15 },
            { id: 7, label: 'Node 7', x: 200, y: 0, temperature: 75, size_value: 35 },
            { id: 8, label: 'Node 8', x: 0, y: -200, temperature: 50, size_value: 22 },
            { id: 9, label: 'Node 9', x: 0, y: 200, temperature: 70, size_value: 28 },
            { id: 10, label: 'Node 10', x: 150, y: 150, temperature: 90, size_value: 45 }
        ],
        edges: [
            { from: 1, to: 2, weight: 5 },
            { from: 2, to: 3, weight: 15 },
            { from: 3, to: 4, weight: 25 },
            { from: 4, to: 1, weight: 35 },
            { from: 5, to: 1, weight: 10 },
            { from: 5, to: 2, weight: 20 },
            { from: 5, to: 3, weight: 30 },
            { from: 5, to: 4, weight: 40 },
            { from: 6, to: 1, weight: 12 },
            { from: 6, to: 5, weight: 8 },
            { from: 7, to: 2, weight: 18 },
            { from: 7, to: 5, weight: 22 },
            { from: 8, to: 1, weight: 14 },
            { from: 8, to: 2, weight: 16 },
            { from: 9, to: 3, weight: 28 },
            { from: 9, to: 4, weight: 32 },
            { from: 10, to: 3, weight: 38 },
            { from: 10, to: 7, weight: 42 }
        ]
    };

    // Display settings state
    let displaySettings = {
        showEdges: true,
        showEdgeColor: true,
        showNodeSize: true,
        showNodeColor: true
    };

    // Initialize the network
    document.addEventListener('DOMContentLoaded', function() {
        initializeNetwork();
        // Fetch available files and populate the dropdown
        // fetchAvailableFiles().then(files => {
        //     const fileSelect = document.getElementById('data-file');
        //     files.forEach(file => {
        //         const option = document.createElement('option');
        //         option.value = file.id;
        //         option.textContent = file.name;
        //         fileSelect.appendChild(option);
        //     });
        // });
    });

    function initializeNetwork() {
        const container = document.getElementById('network-container');

        // Create nodes and edges datasets
        nodes = new vis.DataSet(networkData.nodes.map(node => ({
            id: node.id,
            label: node.label,
            x: node.x,
            y: node.y,
            temperature: node.temperature,
            size_value: node.size_value,
            color: '#97C2FC',
            size: 25,
            font: { size: 14 }
        })));

        edges = new vis.DataSet(networkData.edges.map(edge => ({
            id: `${edge.from}-${edge.to}`,
            from: edge.from,
            to: edge.to,
            weight: edge.weight,
            color: '#848484',
            width: 2
        })));

        const data = { nodes, edges };

        const options = {
            physics: {
                enabled: false
            },
            interaction: {
                dragNodes: true,
                dragView: true,
                zoomView: true
            },
            nodes: {
                shape: 'dot',
                scaling: {
                    min: 10,
                    max: 150
                }
            },
            edges: {
                smooth: {
                    type: 'continuous'
                }
            }
        };

        network = new vis.Network(container, data, options);

        console.log('Network initialized with', nodes.length, 'nodes and', edges.length, 'edges');
    }

    function applyDisplayChanges() {
        displaySettings.showEdges = document.getElementById('toggle-edges').value === 'true';
        displaySettings.showEdgeColor = document.getElementById('toggle-edge-color').value === 'true';
        displaySettings.showNodeSize = document.getElementById('toggle-node-size').value === 'true';
        displaySettings.showNodeColor = document.getElementById('toggle-node-color').value === 'true';

        // Update edges visibility
        if (displaySettings.showEdges) {
            edges.update(networkData.edges.map(edge => ({
                id: `${edge.from}-${edge.to}`,
                hidden: false
            })));
        } else {
            edges.update(networkData.edges.map(edge => ({
                id: `${edge.from}-${edge.to}`,
                hidden: true
            })));
        }

        // Handle node color toggle
        if (!displaySettings.showNodeColor) {
            // Reset to default color
            nodes.update(networkData.nodes.map(node => ({
                id: node.id,
                color: '#97C2FC'
            })));
        } else {
            // Reapply node color ranges if they exist
            const nodeColorRanges = document.getElementById('node-color-ranges');
            if (nodeColorRanges.children.length > 0) {
                applyNodeColorRanges();
            }
        }

        // Handle node size toggle
        if (!displaySettings.showNodeSize) {
            // Reset to default size
            nodes.update(networkData.nodes.map(node => ({
                id: node.id,
                size: 25
            })));
        } else {
            // Reapply node size ranges if they exist
            const nodeSizeRanges = document.getElementById('node-size-ranges');
            if (nodeSizeRanges.children.length > 0) {
                applyNodeSizeRanges();
            }
        }

        // Handle edge color toggle
        if (!displaySettings.showEdgeColor) {
            // Reset to default color
            edges.update(networkData.edges.map(edge => ({
                id: `${edge.from}-${edge.to}`,
                color: '#848484'
            })));
        } else {
            // Reapply edge color ranges if they exist
            const edgeColorRanges = document.getElementById('edge-color-ranges');
            if (edgeColorRanges.children.length > 0) {
                applyEdgeColorRanges();
            }
        }

        // Force network redraw
        network.redraw();

        console.log('Display settings applied:', displaySettings);
    }

    function addNodeColorRange() {
        const container = document.getElementById('node-color-ranges');
        const rangeId = `node-color-${nodeColorRangeCount++}`;
        const rangeHtml = `
            <div id="${rangeId}" class="p-3 bg-light rounded border mb-2" data-range-id="${nodeColorRangeCount}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-medium">Range ${nodeColorRangeCount}</span>
                    <button type="button" onclick="removeRange('${rangeId}')" class="btn-close btn-sm" aria-label="Close"></button>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <input type="number" placeholder="Min" class="form-control form-control-sm range-min" value="${20 + (nodeColorRangeCount - 1) * 20}">
                    <span class="text-muted">to</span>
                    <input type="number" placeholder="Max" class="form-control form-control-sm range-max" value="${40 + (nodeColorRangeCount - 1) * 20}">
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted mb-0">Color:</label>
                    <input type="color" class="form-control form-control-color range-color" style="width: 3rem; height: 2rem;" value="${getRandomColor()}">
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', rangeHtml);
    }

    function addEdgeColorRange() {
        const container = document.getElementById('edge-color-ranges');
        const rangeId = `edge-color-${edgeColorRangeCount++}`;
        const rangeHtml = `
            <div id="${rangeId}" class="p-3 bg-light rounded border mb-2" data-range-id="${edgeColorRangeCount}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-medium">Range ${edgeColorRangeCount}</span>
                    <button type="button" onclick="removeRange('${rangeId}')" class="btn-close btn-sm" aria-label="Close"></button>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <input type="number" placeholder="Min" class="form-control form-control-sm range-min" value="${5 + (edgeColorRangeCount - 1) * 10}">
                    <span class="text-muted">to</span>
                    <input type="number" placeholder="Max" class="form-control form-control-sm range-max" value="${15 + (edgeColorRangeCount - 1) * 10}">
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted mb-0">Color:</label>
                    <input type="color" class="form-control form-control-color range-color" style="width: 3rem; height: 2rem;" value="${getRandomColor()}">
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', rangeHtml);
    }

    function addNodeSizeRange() {
        const container = document.getElementById('node-size-ranges');
        const rangeId = `node-size-${nodeSizeRangeCount++}`;
        const rangeHtml = `
            <div id="${rangeId}" class="p-3 bg-light rounded border mb-2" data-range-id="${nodeSizeRangeCount}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-medium">Range ${nodeSizeRangeCount}</span>
                    <button type="button" onclick="removeRange('${rangeId}')" class="btn-close btn-sm" aria-label="Close"></button>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <input type="number" placeholder="Min" class="form-control form-control-sm range-min" value="${10 + (nodeSizeRangeCount - 1) * 10}">
                    <span class="text-muted">to</span>
                    <input type="number" placeholder="Max" class="form-control form-control-sm range-max" value="${20 + (nodeSizeRangeCount - 1) * 10}">
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted mb-0">Size:</label>
                    <input type="number" placeholder="px" min="1" max="100" value="${20 + nodeSizeRangeCount * 10}" class="form-control form-control-sm range-size" style="width: 5rem;">
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

    function getRandomColor() {
        const colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E2'];
        return colors[Math.floor(Math.random() * colors.length)];
    }

    function applyNodeColorRanges() {
        if (!displaySettings.showNodeColor) {
            alert('Node Color display is turned off. Please enable it in Display Features.');
            return;
        }

        const container = document.getElementById('node-color-ranges');
        const ranges = container.querySelectorAll('[id^="node-color-"]');

        if (ranges.length === 0) {
            alert('Please add at least one color range.');
            return;
        }

        const colorRanges = Array.from(ranges).map(range => ({
            min: parseFloat(range.querySelector('.range-min').value),
            max: parseFloat(range.querySelector('.range-max').value),
            color: range.querySelector('.range-color').value
        }));

        // Apply colors to nodes based on temperature
        networkData.nodes.forEach(node => {
            const temp = node.temperature;
            let color = '#97C2FC'; // default

            for (const range of colorRanges) {
                if (temp >= range.min && temp <= range.max) {
                    color = range.color;
                    break;
                }
            }

            nodes.update({ id: node.id, color: color });
        });

        console.log('Node colors applied:', colorRanges);
    }

    function applyEdgeColorRanges() {
        if (!displaySettings.showEdgeColor) {
            alert('Edge Color display is turned off. Please enable it in Display Features.');
            return;
        }

        const container = document.getElementById('edge-color-ranges');
        const ranges = container.querySelectorAll('[id^="edge-color-"]');

        if (ranges.length === 0) {
            alert('Please add at least one color range.');
            return;
        }

        const colorRanges = Array.from(ranges).map(range => ({
            min: parseFloat(range.querySelector('.range-min').value),
            max: parseFloat(range.querySelector('.range-max').value),
            color: range.querySelector('.range-color').value
        }));

        // Apply colors to edges based on weight
        networkData.edges.forEach(edge => {
            const weight = edge.weight;
            let color = '#848484'; // default

            for (const range of colorRanges) {
                if (weight >= range.min && weight <= range.max) {
                    color = range.color;
                    break;
                }
            }

            edges.update({ id: `${edge.from}-${edge.to}`, color: color });
        });

        console.log('Edge colors applied:', colorRanges);
    }

    function applyNodeSizeRanges() {
        if (!displaySettings.showNodeSize) {
            alert('Node Size display is turned off. Please enable it in Display Features.');
            return;
        }

        const container = document.getElementById('node-size-ranges');
        const ranges = container.querySelectorAll('[id^="node-size-"]');

        if (ranges.length === 0) {
            alert('Please add at least one size range.');
            return;
        }

        const sizeRanges = Array.from(ranges).map(range => ({
            min: parseFloat(range.querySelector('.range-min').value),
            max: parseFloat(range.querySelector('.range-max').value),
            size: parseFloat(range.querySelector('.range-size').value)
        }));

        // Apply sizes to nodes based on size_value
        networkData.nodes.forEach(node => {
            const value = node.size_value;
            let size = 25; // default

            for (const range of sizeRanges) {
                if (value >= range.min && value <= range.max) {
                    size = range.size;
                    console.log(`setting node ${node.id} to size ${size}`);
                    break;
                }
            }

            nodes.update({ id: node.id, size: size });
        });

        console.log('Node sizes applied:', sizeRanges);
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
        fetchFileColumns(selectedFile).then(columns => {
            populateColumnDropdowns(columns);
        });
    }

    function fetchFileColumns(fileId) {
        // Placeholder - implement your actual API call here
        // For now, return sample columns
        return Promise.resolve([
            'id', 'name', 'x_pos', 'y_pos', 'node1', 'node2',
            'size_value', 'color_value', 'edge_weight', 'temperature'
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
        console.log('Current display settings:', displaySettings);

        // Placeholder for actual network data loading
        // You'll implement the actual API call and network rendering here
        alert('Column mappings configured! This would load data from your selected file.\n\nFor now, the hardcoded network is displayed.');
    }

    // function fetchAvailableFiles() {
    //     // Placeholder - implement your actual API call here
    //     // For now, return sample files
    //     return Promise.resolve([
    //         { id: '1', name: 'network_data_1.csv' },
    //         { id: '2', name: 'network_data_2.csv' },
    //         { id: '3', name: 'graph_connections.xlsx' }
    //     ]);
    // }
</script>
