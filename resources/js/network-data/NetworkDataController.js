/*
 Refactor of resources/js/network-data/index.js into a class-based controller.
 This module imports vis components and exports the class for usage from app.js,
 removing reliance on global variables and window-scoped symbols.
*/

import {DataSet} from 'vis-data';
import {Network} from 'vis-network';

export default class NetworkDataController {
    constructor() {
        // State formerly stored in globals
        this.contextMenuNodeId = null;
        this.hiddenNodes = new Set();
        this.nodeColorRangeCount = 0;
        this.edgeColorRangeCount = 0;
        this.nodeSizeRangeCount = 0;
        this.network = null;
        this.nodesDataset = null;
        this.edgesDataset = null;
        this.networkData = {};
        this.nodeColorValuesMinMax = null;
        this.nodeSizeValuesMinMax = null;
        this.edgeColorValuesMinMax = null;

        // Display settings
        this.displaySettings = {
            showEdges: true,
            showEdgeColor: true,
            showNodeSize: true,
            showNodeColor: true
        };

    }


    onNetworkDataLoaded(event) {
        const dataObj = event.data;
        const nodeColorInput = document.getElementById('node-color-attribute');
        if (nodeColorInput) nodeColorInput.value = dataObj.nodeColorAttributeName;
        const ncAttrName = dataObj.nodeColorAttributeName;

        const edgeColorInput = document.getElementById('edge-color-attribute');
        if (edgeColorInput) edgeColorInput.value = dataObj.edgeColorAttributeName;
        const ecAttrName = dataObj.edgeColorAttributeName;

        const nodeSizeInput = document.getElementById('node-size-attribute');
        if (nodeSizeInput) nodeSizeInput.value = dataObj.nodeSizeAttributeName;
        const nsAttrName = dataObj.nodeSizeAttributeName;

        let nodes = [];
        let edges = [];
        const nodeIdValues = dataObj.nodeIdValues;
        const nodePositions = dataObj.nodePositions;
        const nodeColorAttributeValues = dataObj.nodeColorAttributeValues;
        const nodeSizeAttributeValues = dataObj.nodeSizeAttributeValues;
        const positionScale = 3;
        this.nodeColorValuesMinMax = this.findMinMax(nodeColorAttributeValues);
        this.nodeSizeValuesMinMax = this.findMinMaxWithPercentiles(nodeSizeAttributeValues);
        const containerForNodeColorMinMax = document.getElementById('node-color-min-max-info');
        const containerForNodeSizeMinMax = document.getElementById('node-size-min-max-info');
        if (containerForNodeColorMinMax) containerForNodeColorMinMax.insertAdjacentHTML('beforeend', `<span>Min: ${this.nodeColorValuesMinMax.min}, Max: ${this.nodeColorValuesMinMax.max}</span>`);
        if (containerForNodeSizeMinMax) containerForNodeSizeMinMax.insertAdjacentHTML('beforeend', `<span>Min: ${this.nodeSizeValuesMinMax.min}, Max: ${this.nodeSizeValuesMinMax.max}</span>`);
        let differentColors = {};
        for (let i = 0; i < nodeIdValues.length; i++) {
            let ncValue = nodeColorAttributeValues[i];
            let nsValue = nodeSizeAttributeValues[i];
            let nodeColor = this.valueToHeatmapColor(ncValue, this.nodeColorValuesMinMax.min, this.nodeColorValuesMinMax.max);
            let nodeSize = this.mapValueToRange(nsValue, this.nodeSizeValuesMinMax.min, this.nodeSizeValuesMinMax.max, 10, 50);
            if (!nodeColor in differentColors) {
                differentColors[nodeColor] = 0;
            }
            differentColors[nodeColor]++;
            nodes.push({
                id: nodeIdValues[i],
                x: nodePositions[i][0] * positionScale,
                y: nodePositions[i][1] * positionScale,
                nc_value: ncValue,
                size_value: nsValue,
                color: nodeColor,
                size: nodeSize,
                font: {size: 14},
                title: `Node ID: ${nodeIdValues[i]}, ${ncAttrName}: ${nodeColorAttributeValues[i]}, ${nsAttrName}: ${nodeSizeAttributeValues[i]}`,
            });
        }

        const edgeColorAttributeValues = dataObj.edgeColorAttributeValues;
        const edgeDashedAttributeValues = dataObj.edgeDashedAttributeValues.map(value => this.valueToDashesAttribute(value));
        this.edgeColorValuesMinMax = this.findMinMax(edgeColorAttributeValues);
        const containerForEdgeColorMinMax = document.getElementById('edge-color-min-max-info');
        if (containerForEdgeColorMinMax) containerForEdgeColorMinMax.insertAdjacentHTML('beforeend', `<span>Min: ${this.edgeColorValuesMinMax.min}, Max: ${this.edgeColorValuesMinMax.max}</span>`);
        console.log('drawing edges dashes[5,5] with color, with width, with large dashes');
        for (let i = 0; i < dataObj.edges.length; i++) {
            const nodeId1 = dataObj.edges[i][0];
            const nodeId2 = dataObj.edges[i][1];
            edges.push({
                id: `${nodeId1}-${nodeId2}`,
                from: nodeId1,
                to: nodeId2,
                ec_value: edgeColorAttributeValues[i],
                color: this.valueToHeatmapColor(edgeColorAttributeValues[i], this.edgeColorValuesMinMax.min, this.edgeColorValuesMinMax.max),
                width: 20,
                // dashes: [4,50],
                dashes: edgeDashedAttributeValues[i],
                title: `Edge ID: ${nodeId1}-${nodeId2}, ${ecAttrName}: ${edgeColorAttributeValues[i]}`,
            });
        }

        this.networkData.nodes = nodes;
        this.networkData.edges = edges;
        this.nodesDataset = new DataSet(nodes);
        this.edgesDataset = new DataSet(edges);
        const data = {nodes: this.nodesDataset, edges: this.edgesDataset};
        const options = {
            physics: {enabled: false},
            interaction: {dragNodes: true, dragView: true, zoomView: true},
            nodes: {shape: 'dot', scaling: {min: 10, max: 150}},
            edges: {smooth: {type: 'continuous', forceDirection: 'none', roundness: 1}},
        };

        const container = document.getElementById('network-container');
        this.network = new Network(container, data, options);
        this.network.on('oncontext', (params) => {
            params.event.preventDefault();
            if (params.nodes.length > 0) {
                const nodeId = params.nodes[0];
                this.showNodeContextMenu(params.event, nodeId);
            }
        });
        document.addEventListener('click', () => this.closeNodeContextMenu());
        console.log('network-data-loaded (class)', dataObj);
    }

    // Utility methods
    findMinMax(array) {
        if (!array || array.length === 0) {
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

    findMinMaxWithPercentiles(array, lowerPercentile = 0.05, upperPercentile = 0.95) {
        if (!array || array.length === 0) {
            return {min: 0, max: 0};
        }
        const sorted = [...array].sort((a, b) => a - b);
        const lowerIndex = Math.floor(sorted.length * lowerPercentile);
        const upperIndex = Math.floor(sorted.length * upperPercentile);
        return {min: sorted[lowerIndex], max: sorted[upperIndex]};
    }

    valueToDashesAttribute(value) {
        if (value === 'Y' || value === 'y' || value === 1) {
            return [4,50];
        } else {
            return false;
        }
    }

    valueToHeatmapColor(value, minValue, maxValue) {
        const normalized = (value - minValue) / (maxValue - minValue);
        const stops = [
            [0.0, '#0000FF'],
            [0.33, '#00FFFF'],
            [0.66, '#FFFF00'],
            [1.0, '#FF0000']
        ];
        let startStop = stops[0];
        let endStop = stops[stops.length - 1];
        for (let i = 0; i < stops.length - 1; i++) {
            if (normalized >= stops[i][0] && normalized <= stops[i + 1][0]) {
                startStop = stops[i];
                endStop = stops[i + 1];
                break;
            }
        }
        const localNormalized = (normalized - startStop[0]) / (endStop[0] - startStop[0]);
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

    mapValueToRange(value, min, max, starting, ending) {
        const clampedValue = Math.max(min, Math.min(max, value));
        const normalized = (clampedValue - min) / (max - min);
        return Math.round(starting + (ending - starting) * normalized);
    }

    // Context menu
    showNodeContextMenu(event, nodeId) {
        this.contextMenuNodeId = nodeId;
        const menu = document.getElementById('node-context-menu');
        if (!menu) return;
        menu.style.display = 'block';
        menu.style.left = event.pageX + 'px';
        menu.style.top = event.pageY + 'px';
    }

    closeNodeContextMenu() {
        const menu = document.getElementById('node-context-menu');
        if (menu) menu.style.display = 'none';
        this.contextMenuNodeId = null;
    }

    hideNode() {
        if (this.contextMenuNodeId !== null) {
            this.hiddenNodes.add(this.contextMenuNodeId);
            if (this.nodesDataset) this.nodesDataset.update({id: this.contextMenuNodeId, hidden: true});
            console.log('Node hidden:', this.contextMenuNodeId);
        }
        this.closeNodeContextMenu();
    }

    unhideAllNodes() {
        const updates = Array.from(this.hiddenNodes).map(nodeId => ({id: nodeId, hidden: false}));
        if (this.nodesDataset) this.nodesDataset.update(updates);
        this.hiddenNodes.clear();
        console.log('All nodes unhidden');
        this.closeNodeContextMenu();
    }

    // Display changes
    applyDisplayChanges() {
        this.displaySettings.showEdges = document.getElementById('toggle-edges').value === 'true';
        this.displaySettings.showEdgeColor = document.getElementById('toggle-edge-color').value === 'true';
        this.displaySettings.showNodeSize = document.getElementById('toggle-node-size').value === 'true';
        this.displaySettings.showNodeColor = document.getElementById('toggle-node-color').value === 'true';

        if (this.nodesDataset && this.edgesDataset) {
            // Edges visibility
            if (this.displaySettings.showEdges) {
                this.edgesDataset.update(this.networkData.edges.map(edge => ({
                    id: `${edge.from}-${edge.to}`,
                    hidden: false
                })));
            } else {
                this.edgesDataset.update(this.networkData.edges.map(edge => ({
                    id: `${edge.from}-${edge.to}`,
                    hidden: true
                })));
            }

            // Node color toggle
            if (!this.displaySettings.showNodeColor) {
                this.nodesDataset.update(this.networkData.nodes.map(node => ({id: node.id, color: '#97C2FC'})));
            } else {
                const nodeColorRanges = document.getElementById('node-color-ranges');
                if (nodeColorRanges && nodeColorRanges.children.length > 0) {
                    this.applyNodeColorRanges();
                } else {
                    this.resetNodeColorsToDefault();
                }
            }

            // Node size toggle
            if (!this.displaySettings.showNodeSize) {
                this.nodesDataset.update(this.networkData.nodes.map(node => ({id: node.id, size: 25})));
            } else {
                const nodeSizeRanges = document.getElementById('node-size-ranges');
                if (nodeSizeRanges && nodeSizeRanges.children.length > 0) {
                    this.applyNodeSizeRanges();
                } else {
                    this.resetNodeSizesToDefault();
                }
            }

            // Edge color toggle
            if (!this.displaySettings.showEdgeColor) {
                this.edgesDataset.update(this.networkData.edges.map(edge => ({
                    id: `${edge.from}-${edge.to}`,
                    color: '#ededed'
                })));
            } else {
                const edgeColorRanges = document.getElementById('edge-color-ranges');
                if (edgeColorRanges && edgeColorRanges.children.length > 0) {
                    this.applyEdgeColorRanges();
                } else {
                    this.resetEdgeColorsToDefault();
                }
            }

            if (this.network) this.network.redraw();
        }
        console.log('Display settings applied:', this.displaySettings);
    }

    resetNodeColorsToDefault() {
        this.nodesDataset.update(this.networkData.nodes.map(node => ({
            id: node.id,
            color: this.valueToHeatmapColor(node.nc_value, this.nodeColorValuesMinMax.min, this.nodeColorValuesMinMax.max)
        })));
    }

    resetNodeSizesToDefault() {
        this.nodesDataset.update(this.networkData.nodes.map(node => ({
            id: node.id,
            size: this.mapValueToRange(node.size_value, this.nodeSizeValuesMinMax.min, this.nodeSizeValuesMinMax.max, 10, 50)
        })));
    }

    resetEdgeColorsToDefault() {
        this.edgesDataset.update(this.networkData.edges.map(edge => ({
            id: `${edge.from}-${edge.to}`,
            color: this.valueToHeatmapColor(edge.ec_value, this.edgeColorValuesMinMax.min, this.edgeColorValuesMinMax.max)
        })));
    }

    // Filter handlers (ensure mutual exclusivity and apply filters)
    handleNodeColorFilterChange(rangeId, filterType) {
        const currentCheckbox = document.querySelector(`#${rangeId} .range-filter-${filterType}`);
        if (!currentCheckbox) return;
        if (currentCheckbox.checked) {
            document.querySelectorAll('#node-color-ranges .range-filter-show, #node-color-ranges .range-filter-hide').forEach(cb => {
                if (cb !== currentCheckbox) cb.checked = false;
            });
            this.applyNodeColorFilter(rangeId, filterType);
        } else {
            this.clearNodeColorFilter();
        }
    }

    handleNodeSizeFilterChange(rangeId, filterType) {
        const currentCheckbox = document.querySelector(`#${rangeId} .range-filter-${filterType}`);
        if (!currentCheckbox) return;
        if (currentCheckbox.checked) {
            document.querySelectorAll('#node-size-ranges .range-filter-show, #node-size-ranges .range-filter-hide').forEach(cb => {
                if (cb !== currentCheckbox) cb.checked = false;
            });
            this.applyNodeSizeFilter(rangeId, filterType);
        } else {
            this.clearNodeSizeFilter();
        }
    }

    applyNodeColorFilter(rangeId, filterType) {
        const rangeDiv = document.getElementById(rangeId);
        if (!rangeDiv) return;
        const min = parseFloat(rangeDiv.querySelector('.range-min').value);
        const max = parseFloat(rangeDiv.querySelector('.range-max').value);
        const updates = this.networkData.nodes.map(node => {
            const val = node.nc_value;
            const inRange = val >= min && val <= max;
            const shouldHide = filterType === 'show' ? !inRange : inRange;
            return {id: node.id, hidden: shouldHide};
        });
        if (this.nodesDataset) this.nodesDataset.update(updates);
        console.log(`Node color filter applied (${filterType}):`, {min, max});
    }

    applyNodeSizeFilter(rangeId, filterType) {
        const rangeDiv = document.getElementById(rangeId);
        if (!rangeDiv) return;
        const min = parseFloat(rangeDiv.querySelector('.range-min').value);
        const max = parseFloat(rangeDiv.querySelector('.range-max').value);
        const updates = this.networkData.nodes.map(node => {
            const val = node.size_value;
            const inRange = val >= min && val <= max;
            const shouldHide = filterType === 'show' ? !inRange : inRange;
            return {id: node.id, hidden: shouldHide};
        });
        if (this.nodesDataset) this.nodesDataset.update(updates);
        console.log(`Node size filter applied (${filterType}):`, {min, max});
    }

    clearNodeColorFilter() {
        const updates = this.networkData.nodes.map(node => ({id: node.id, hidden: false}));
        if (this.nodesDataset) this.nodesDataset.update(updates);
        console.log('Node color filter cleared - all nodes visible');
    }

    clearNodeSizeFilter() {
        const updates = this.networkData.nodes.map(node => ({id: node.id, hidden: false}));
        if (this.nodesDataset) this.nodesDataset.update(updates);
        console.log('Node size filter cleared - all nodes visible');
    }

    applyNodeColorRanges() {
        console.log('Applying node color ranges...');
        if (!this.displaySettings.showNodeColor) {
            alert('Node Color display is turned off. Please enable it in Display Features.');
            return;
        }
        const container = document.getElementById('node-color-ranges');
        if (!container) return;
        const ranges = container.querySelectorAll('[id^="node-color-"]');
        // if (ranges.length === 0) {
        //     alert('Please add at least one color range.');
        //     return;
        // }
        const colorRanges = Array.from(ranges).map(range => ({
            min: parseFloat(range.querySelector('.range-min').value),
            max: parseFloat(range.querySelector('.range-max').value),
            color: range.querySelector('.range-color').value
        }));
        const updates = this.networkData.nodes.map(node => {
            const val = node.nc_value;
            let color = this.valueToHeatmapColor(val, this.nodeColorValuesMinMax.min, this.nodeColorValuesMinMax.max);
            for (const range of colorRanges) {
                if (val >= range.min && val <= range.max) {
                    color = range.color;
                    break;
                }
            }
            return {id: node.id, color};
        });
        if (this.nodesDataset) this.nodesDataset.update(updates);
        console.log('Node colors applied:', colorRanges);
    }

    applyEdgeColorRanges() {
        if (!this.displaySettings.showEdgeColor) {
            alert('Edge Color display is turned off. Please enable it in Display Features.');
            return;
        }
        const container = document.getElementById('edge-color-ranges');
        if (!container) return;
        const ranges = container.querySelectorAll('[id^="edge-color-"]');
        // if (ranges.length === 0) {
        //     alert('Please add at least one color range.');
        //     return;
        // }
        const colorRanges = Array.from(ranges).map(range => ({
            min: parseFloat(range.querySelector('.range-min').value),
            max: parseFloat(range.querySelector('.range-max').value),
            color: range.querySelector('.range-color').value
        }));
        const updates = this.networkData.edges.map(edge => {
            const ec_value = edge.ec_value;
            let color = this.valueToHeatmapColor(ec_value, this.edgeColorValuesMinMax.min, this.edgeColorValuesMinMax.max);
            for (const range of colorRanges) {
                if (ec_value >= range.min && ec_value <= range.max) {
                    color = range.color;
                    break;
                }
            }
            return {id: `${edge.from}-${edge.to}`, color};
        });
        if (this.edgesDataset) this.edgesDataset.update(updates);
        console.log('Edge colors applied:', colorRanges);
    }

    applyNodeSizeRanges() {
        if (!this.displaySettings.showNodeSize) {
            alert('Node Size display is turned off. Please enable it in Display Features.');
            return;
        }
        const container = document.getElementById('node-size-ranges');
        if (!container) return;
        const ranges = container.querySelectorAll('[id^="node-size-"]');
        // if (ranges.length === 0) {
        //     alert('Please add at least one size range.');
        //     return;
        // }
        const sizeRanges = Array.from(ranges).map(range => ({
            min: parseFloat(range.querySelector('.range-min').value),
            max: parseFloat(range.querySelector('.range-max').value),
            size: parseFloat(range.querySelector('.range-size').value)
        }));
        const updates = this.networkData.nodes.map(node => {
            const value = node.size_value;
            let size = this.mapValueToRange(value, this.nodeSizeValuesMinMax.min, this.nodeSizeValuesMinMax.max, 10, 50)
            for (const range of sizeRanges) {
                if (value >= range.min && value <= range.max) {
                    size = range.size;
                    break;
                }
            }
            return {id: node.id, size};
        });
        if (this.nodesDataset) this.nodesDataset.update(updates);
        console.log('Node sizes applied:', sizeRanges);
    }
}
