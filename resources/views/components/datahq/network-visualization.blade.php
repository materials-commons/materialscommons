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
        <div class="mb-4">
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
</script>
