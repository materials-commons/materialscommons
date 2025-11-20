@props(['project'])
<!-- Include vis.js from CDN if not already included in your layout -->

<div class="d-flex" style="height: 92vh;">
    <!-- Network Visualization Area (Left - 70%) -->
    <div class="bg-light border-end p-2 rounded" style="width: 70%;">
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
                       placeholder="e.g., temperature" readonly>
            </div>

            <div id="node-color-min-max-info" class="mb-3">
                <!-- Dynamic display of min/max range will be added here -->
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

@push('scripts')
    <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>

    <script>

        let nodeColorRangeCount = 0;
        let edgeColorRangeCount = 0;
        let nodeSizeRangeCount = 0;
        let network = null;
        let nodesDataset = null;
        let edgesDataset = null;
        let networkData = {};
        let nodeColorValuesMinMax = null;
        let nodeSizeValuesMinMax = null;
        let edgeColorValuesMinMax = null;

        document.addEventListener('livewire:initialized', () => {
            console.log('livewire:initialized');
            Livewire.on('network-data-loaded', (event) => {
                if (!event.data) {
                    return;
                }

                const nodeColorInput = document.getElementById('node-color-attribute');
                nodeColorInput.value = event.data.nodeColorAttributeName;
                const ncAttrName = event.data.nodeColorAttributeName;

                const edgeColorInput = document.getElementById('edge-color-attribute');
                edgeColorInput.value = event.data.edgeColorAttributeName;
                const ecAttrName = event.data.edgeColorAttributeName;

                const nodeSizeInput = document.getElementById('node-size-attribute');
                nodeSizeInput.value = event.data.nodeSizeAttributeName;
                const nsAttrName = event.data.nodeSizeAttributeName;

                let nodes = [];
                let edges = [];
                const nodeIdValues = event.data.nodeIdValues;
                const nodePositions = event.data.nodePositions;
                const nodeColorAttributeValues = event.data.nodeColorAttributeValues;
                const nodeSizeAttributeValues = event.data.nodeSizeAttributeValues;
                const positionScale = 3;
                nodeColorValuesMinMax = findMinMax(nodeColorAttributeValues);
                nodeSizeValuesMinMax = findMinMaxWithPercentiles(nodeSizeAttributeValues);
                for (let i = 0; i < nodeIdValues.length; i++) {
                    nodes.push({
                        id: nodeIdValues[i],
                        x: nodePositions[i][0] * positionScale,
                        y: nodePositions[i][1] * positionScale,
                        nc_value: nodeColorAttributeValues[i],
                        size_value: nodeSizeAttributeValues[i],
                        // color: '#97c2fc',
                        color: valueToHeatmapColor(nodeColorAttributeValues[i], nodeColorValuesMinMax.min, nodeColorValuesMinMax.max),
                        // size: 25,
                        size: mapValueToRange(nodeSizeAttributeValues[i], nodeSizeValuesMinMax.min, nodeSizeValuesMinMax.max, 10, 50),
                        font: {size: 14},
                        title: `Node ID: ${nodeIdValues[i]}, ${ncAttrName}: ${nodeColorAttributeValues[i]}, ${nsAttrName}: ${nodeSizeAttributeValues[i]}`,
                    });
                }

                const edgeColorAttributeValues = event.data.edgeColorAttributeValues;
                edgeColorValuesMinMax = findMinMax(edgeColorAttributeValues);
                for (let i = 0; i < event.data.edges.length; i++) {
                    const nodeId1 = event.data.edges[i][0];
                    const nodeId2 = event.data.edges[i][1];
                    edges.push({
                        id: `${nodeId1}-${nodeId2}`,
                        from: nodeId1,
                        to: nodeId2,
                        ec_value: edgeColorAttributeValues[i],
                        // color: '#ededed',
                        color: valueToHeatmapColor(edgeColorAttributeValues[i], edgeColorValuesMinMax.min, edgeColorValuesMinMax.max),
                        width: 20,
                        title: `Edge ID: ${nodeId1}-${nodeId2}, ${ecAttrName}: ${edgeColorAttributeValues[i]}`,
                    });
                }

                networkData.nodes = nodes;
                networkData.edges = edges;
                nodesDataset = new vis.DataSet(nodes);
                edgesDataset = new vis.DataSet(edges);
                const data = {nodes: nodesDataset, edges: edgesDataset};
                const options = {
                    physics: {
                        enabled: false,
                        // stabilization: {
                        //     enabled: true,
                        //     iterations: 200
                        // },
                        // barnesHut: {
                        //     gravitationalConstant: -8000,
                        //     centralGravity: 0.3,
                        //     springLength: 95,
                        //     springConstant: 0.04,
                        //     damping: 0.09,
                        //     avoidOverlap: 1  // This makes nodes push away from each other
                        // }
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
                            type: "continuous",
                            forceDirection: "none",
                            roundness: 1
                        }
                    }
                };

                const container = document.getElementById('network-container');
                network = new vis.Network(container, data, options);
                console.log('network-data-loaded', event.data);
            });
        });

        // Display settings state
        let displaySettings = {
            showEdges: true,
            showEdgeColor: true,
            showNodeSize: true,
            showNodeColor: true
        };

        // Finds min and max values in array
        function findMinMax(array) {
            if (array.length === 0) {
                return {min: 0, max: 0};
            }

            let min = array[0];
            let max = array[0];

            for (let i = 1; i < array.length; i++) {
                if (array[i] < min) min = array[i];
                if (array[i] > max) max = array[i];
            }

            return {min, max};
        }

        // Finds min and max using percentiles to exclude outliers
        function findMinMaxWithPercentiles(array, lowerPercentile = 0.05, upperPercentile = 0.95) {
            if (array.length === 0) {
                return {min: 0, max: 0};
            }

            const sorted = [...array].sort((a, b) => a - b);
            const lowerIndex = Math.floor(sorted.length * lowerPercentile);
            const upperIndex = Math.floor(sorted.length * upperPercentile);

            return {
                min: sorted[lowerIndex],
                max: sorted[upperIndex]
            };
        }

        /**
         * Maps a value to a color using a multi-stop gradient
         */
        function valueToHeatmapColor(value, minValue, maxValue) {
            const normalized = (value - minValue) / (maxValue - minValue);

            // Define color stops: [position (0-1), color]
            const stops = [
                [0.0, '#0000FF'],  // Blue
                [0.33, '#00FFFF'], // Cyan
                [0.66, '#FFFF00'], // Yellow
                [1.0, '#FF0000']   // Red
            ];

            // Find the two stops to interpolate between
            let startStop = stops[0];
            let endStop = stops[stops.length - 1];

            for (let i = 0; i < stops.length - 1; i++) {
                if (normalized >= stops[i][0] && normalized <= stops[i + 1][0]) {
                    startStop = stops[i];
                    endStop = stops[i + 1];
                    break;
                }
            }

            // Calculate position between the two stops
            const localNormalized = (normalized - startStop[0]) / (endStop[0] - startStop[0]);

            // Interpolate
            const start = {
                r: parseInt(startStop[1].slice(1, 3), 16),
                g: parseInt(startStop[1].slice(3, 5), 16),
                b: parseInt(startStop[1].slice(5, 7), 16)
            };

            const end = {
                r: parseInt(endStop[1].slice(1, 3), 16),
                g: parseInt(endStop[1].slice(3, 5), 16),
                b: parseInt(endStop[1].slice(5, 7), 16)
            };

            const r = Math.round(start.r + (end.r - start.r) * localNormalized);
            const g = Math.round(start.g + (end.g - start.g) * localNormalized);
            const b = Math.round(start.b + (end.b - start.b) * localNormalized);

            return `#${r.toString(16).padStart(2, '0')}${g.toString(16).padStart(2, '0')}${b.toString(16).padStart(2, '0')}`;
        }

        /**
         * Maps a value within a range to an integer within a different range using linear interpolation
         */
        function mapValueToRange(value, min, max, starting, ending) {
            // Clamp value to input range
            const clampedValue = Math.max(min, Math.min(max, value));

            // Normalize value to 0-1 range
            const normalized = (clampedValue - min) / (max - min);

            // Map to output range and round to integer
            return Math.round(starting + (ending - starting) * normalized);
        }

        // Example usage:
        // mapValueToRange(50, 0, 100, 10, 50);  // Returns 30 (halfway between 10 and 50)
        // mapValueToRange(25, 0, 100, 0, 200);  // Returns 50 (25% of the way from 0 to 200)

        function applyDisplayChanges() {
            displaySettings.showEdges = document.getElementById('toggle-edges').value === 'true';
            displaySettings.showEdgeColor = document.getElementById('toggle-edge-color').value === 'true';
            displaySettings.showNodeSize = document.getElementById('toggle-node-size').value === 'true';
            displaySettings.showNodeColor = document.getElementById('toggle-node-color').value === 'true';

            // Update edges visibility
            if (displaySettings.showEdges) {
                edgesDataset.update(networkData.edges.map(edge => ({
                    id: `${edge.from}-${edge.to}`,
                    hidden: false
                })));
            } else {
                edgesDataset.update(networkData.edges.map(edge => ({
                    id: `${edge.from}-${edge.to}`,
                    hidden: true
                })));
            }

            // Handle node color toggle
            if (!displaySettings.showNodeColor) {
                // Reset to default color
                nodesDataset.update(networkData.nodes.map(node => ({
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
                nodesDataset.update(networkData.nodes.map(node => ({
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
                edgesDataset.update(networkData.edges.map(edge => ({
                    id: `${edge.from}-${edge.to}`,
                    color: '#ededed'
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
                <div class="d-flex flex-column gap-1 mt-2 pt-2 border-top">
                    <div class="form-check">
                        <input class="form-check-input range-filter-show" type="checkbox" name="node-color-filter" data-type="show" data-range-id="${rangeId}" onchange="handleNodeColorFilterChange('${rangeId}', 'show')">
                        <label class="form-check-label small">Show only nodes in this range</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input range-filter-hide" type="checkbox" name="node-color-filter" data-type="hide" data-range-id="${rangeId}" onchange="handleNodeColorFilterChange('${rangeId}', 'hide')">
                        <label class="form-check-label small">Hide nodes in this range</label>
                   </div>
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
                <div class="d-flex flex-column gap-1 mt-2 pt-2 border-top">
                    <div class="form-check">
                        <input class="form-check-input range-filter-show" type="checkbox" name="node-size-filter" data-type="show" data-range-id="${rangeId}" onchange="handleNodeSizeFilterChange('${rangeId}', 'show')">
                        <label class="form-check-label small">Show only nodes in this range</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input range-filter-hide" type="checkbox" name="node-size-filter" data-type="hide" data-range-id="${rangeId}" onchange="handleNodeSizeFilterChange('${rangeId}', 'hide')">
                        <label class="form-check-label small">Hide nodes in this range</label>
                    </div>
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

        function handleNodeColorFilterChange(rangeId, filterType) {
            const currentCheckbox = document.querySelector(`#${rangeId} .range-filter-${filterType}`);

            // If this checkbox was just checked
            if (currentCheckbox.checked) {
                // Uncheck all other filter checkboxes in node-color-ranges
                document.querySelectorAll('#node-color-ranges .range-filter-show, #node-color-ranges .range-filter-hide').forEach(cb => {
                    if (cb !== currentCheckbox) {
                        cb.checked = false;
                    }
                });

                // Apply the filter
                applyNodeColorFilter(rangeId, filterType);
            } else {
                // If unchecked, clear the filter (show all nodes)
                clearNodeColorFilter();
            }
        }

        function handleNodeSizeFilterChange(rangeId, filterType) {
            const currentCheckbox = document.querySelector(`#${rangeId} .range-filter-${filterType}`);

            // If this checkbox was just checked
            if (currentCheckbox.checked) {
                // Uncheck all other filter checkboxes in node-size-ranges
                document.querySelectorAll('#node-size-ranges .range-filter-show, #node-size-ranges .range-filter-hide').forEach(cb => {
                    if (cb !== currentCheckbox) {
                        cb.checked = false;
                    }
                });

                // Apply the filter
                applyNodeSizeFilter(rangeId, filterType);
            } else {
                // If unchecked, clear the filter (show all nodes)
                clearNodeSizeFilter();
            }
        }

        function applyNodeColorFilter(rangeId, filterType) {
            const rangeDiv = document.getElementById(rangeId);
            const min = parseFloat(rangeDiv.querySelector('.range-min').value);
            const max = parseFloat(rangeDiv.querySelector('.range-max').value);

            const updates = networkData.nodes.map(node => {
                const val = node.nc_value;
                const inRange = val >= min && val <= max;

                // If 'show' is checked: hide nodes NOT in range
                // If 'hide' is checked: hide nodes IN range
                const shouldHide = filterType === 'show' ? !inRange : inRange;

                return { id: node.id, hidden: shouldHide };
            });

            nodesDataset.update(updates);
            console.log(`Node color filter applied (${filterType}):`, { min, max });
        }

        function applyNodeSizeFilter(rangeId, filterType) {
            const rangeDiv = document.getElementById(rangeId);
            const min = parseFloat(rangeDiv.querySelector('.range-min').value);
            const max = parseFloat(rangeDiv.querySelector('.range-max').value);

            const updates = networkData.nodes.map(node => {
                const val = node.size_value;
                const inRange = val >= min && val <= max;

                // If 'show' is checked: hide nodes NOT in range
                // If 'hide' is checked: hide nodes IN range
                const shouldHide = filterType === 'show' ? !inRange : inRange;

                return { id: node.id, hidden: shouldHide };
            });

            nodesDataset.update(updates);
            console.log(`Node size filter applied (${filterType}):`, { min, max });
        }

        function clearNodeColorFilter() {
            // Show all nodes
            const updates = networkData.nodes.map(node => ({
                id: node.id,
                hidden: false
            }));
            nodesDataset.update(updates);
            console.log('Node color filter cleared - all nodes visible');
        }

        function clearNodeSizeFilter() {
            // Show all nodes
            const updates = networkData.nodes.map(node => ({
                id: node.id,
                hidden: false
            }));
            nodesDataset.update(updates);
            console.log('Node size filter cleared - all nodes visible');
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

            // Apply colors to nodes based on nc_value
            const updates = networkData.nodes.map(node => {
                const val = node.nc_value;
                let color = '#97C2FC'; // default

                for (const range of colorRanges) {
                    if (val >= range.min && val <= range.max) {
                        color = range.color;
                        break;
                    }
                }

                return {id: node.id, color: color};
            });

            nodesDataset.update(updates);

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

            // Apply colors to edges based on ec_value
            const updates = networkData.edges.map(edge => {
                const ec_value = edge.ec_value;
                let color = '#ededed'; // default

                for (const range of colorRanges) {
                    if (ec_value >= range.min && ec_value <= range.max) {
                        color = range.color;
                        break;
                    }
                }

                return {id: `${edge.from}-${edge.to}`, color: color};
            });

            edgesDataset.update(updates);
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
            const updates = networkData.nodes.map(node => {
                const value = node.size_value;
                let size = 25; // default

                for (const range of sizeRanges) {
                    if (value >= range.min && value <= range.max) {
                        size = range.size;
                        // console.log(`setting node ${node.id} to size ${size}`);
                        break;
                    }
                }

                return {id: node.id, size: size};
            });

            nodesDataset.update(updates);

            console.log('Node sizes applied:', sizeRanges);
        }
    </script>
@endpush
