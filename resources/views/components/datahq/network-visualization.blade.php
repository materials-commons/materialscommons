@props(['project'])
<!-- Include vis.js from CDN if not already included in your layout -->
<div id="node-context-menu"
     style="display: none; position: absolute; z-index: 1000; background: white; border: 1px solid #ccc; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); min-width: 150px;">
    <div class="context-menu-item" onclick="networkDataController.hideNode()"
         style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #eee;">
        Hide Node
    </div>
    <div class="context-menu-item" onclick="networkDataController.unhideAllNodes()" style="padding: 8px 12px; cursor: pointer;">
        Unhide All Nodes
    </div>
</div>

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
                    <select id="toggle-edges" class="form-select form-select-sm" onchange="networkDataController.applyDisplayChanges()">
                        <option value="true">Yes</option>
                        <option value="false">No</option>
                    </select>
                </div>

                <!-- Show Edge Color Toggle -->
                <div class="col-6">
                    <label class="form-label small mb-1">Show Edge Color</label>
                    <select id="toggle-edge-color" class="form-select form-select-sm" onchange="networkDataController.applyDisplayChanges()">
                        <option value="true">Yes</option>
                        <option value="false">No</option>
                    </select>
                </div>

                <!-- Show Node Size Toggle -->
                <div class="col-6">
                    <label class="form-label small mb-1">Show Node Size</label>
                    <select id="toggle-node-size" class="form-select form-select-sm" onchange="networkDataController.applyDisplayChanges()">
                        <option value="true">Yes</option>
                        <option value="false">No</option>
                    </select>
                </div>

                <!-- Show Node Color Toggle -->
                <div class="col-6">
                    <label class="form-label small mb-1">Show Node Color</label>
                    <select id="toggle-node-color" class="form-select form-select-sm" onchange="networkDataController.applyDisplayChanges()">
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
            <button onclick="networkDataController.applyNodeColorRanges()"
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

            <div id="edge-color-min-max-info" class="mb-3">
                <!-- Dynamic display of min/max range will be added here -->
            </div>

            <div id="edge-color-ranges" class="mb-3">
                <!-- Dynamic ranges will be added here -->
            </div>

            <button onclick="addEdgeColorRange()"
                    class="btn btn-primary btn-sm w-100">
                + Add Range
            </button>
            <button onclick="networkDataController.applyEdgeColorRanges()"
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

            <div id="node-size-min-max-info" class="mb-3">
                <!-- Dynamic display of min/max range will be added here -->
            </div>

            <div id="node-size-ranges" class="mb-3">
                <!-- Dynamic ranges will be added here -->
            </div>

            <button onclick="addNodeSizeRange()"
                    class="btn btn-primary btn-sm w-100">
                + Add Range
            </button>
            <button onclick="networkDataController.applyNodeSizeRanges()"
                    class="btn btn-success btn-sm w-100 mt-2">
                Apply Node Sizes
            </button>
        </div>

        <!-- Data Source Configuration -->
        <livewire:datahq.networkhq.data-source :project="$project"/>
    </div>
</div>

@push('styles')
    <style>
        .context-menu-item:hover {
            background-color: #f0f0f0;
        }
    </style>
@endpush

@push('scripts')
{{--    <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>--}}

    <script>

        const networkDataController = new NetworkDataController();
        let nodeColorRangeCount = 0;
        let edgeColorRangeCount = 0;
        let nodeSizeRangeCount = 0;

        document.addEventListener('livewire:initialized', () => {
            console.log('livewire:initialized (class)');
            if (!window.Livewire) {
                console.warn('Livewire not found. NetworkDataController awaiting data.');
                return;
            }
            window.Livewire.on('network-data-loaded', (event) => {
                if (!event || !event.data) {
                    return;
                }
                networkDataController.onNetworkDataLoaded(event);
            });
        });

        function addNodeColorRange() {
            const container = document.getElementById('node-color-ranges');
            if (!container) return;
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
                </div>`;
            container.insertAdjacentHTML('beforeend', rangeHtml);
        }

        function addEdgeColorRange() {
            const container = document.getElementById('edge-color-ranges');
            if (!container) return;
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
                </div>`;
            container.insertAdjacentHTML('beforeend', rangeHtml);
        }

        function addNodeSizeRange() {
            const container = document.getElementById('node-size-ranges');
            if (!container) return;
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
                </div>`;
            container.insertAdjacentHTML('beforeend', rangeHtml);
        }

        function removeRange(rangeId) {
            const element = document.getElementById(rangeId);
            if (element) element.remove();
            if (rangeId.startsWith('node-size')) {
                console.log('removed node-size element');
                networkDataController.applyNodeSizeRanges()
            } else if (rangeId.startsWith('node-color')) {
                console.log('removed node-color element');
                networkDataController.applyNodeColorRanges();
            } else {
                console.log('removed edge-color element');
                networkDataController.applyEdgeColorRanges();
            }
        }

        function getRandomColor() {
            const colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E2'];
            return colors[Math.floor(Math.random() * colors.length)];
        }

    </script>
@endpush
