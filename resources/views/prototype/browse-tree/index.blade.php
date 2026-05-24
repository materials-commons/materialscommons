@extends('layouts.app')

@section('pageTitle', 'Prototype - Browse Tree')

@section('content')
    <div class="container-fluid py-3 mc-browse-tree-prototype">
        <div class="d-flex align-items-start justify-content-between mb-3">
            <div>
                <div class="text-muted small text-uppercase fw-semibold">Prototype</div>
                <h1 class="h3 mb-1">Browse Data Tree</h1>
                <div class="text-muted">
                    Hard-coded prototype for browsing project data and searching across projects.
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="expandAllBtn">
                    <i class="fas fa-plus-square me-1"></i> Expand all
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="collapseAllBtn">
                    <i class="fas fa-minus-square me-1"></i> Collapse all
                </button>
            </div>
        </div>

        <div class="mc-browser-shell">
            <div class="mc-browser-toolbar">
                <div class="row g-2 align-items-center">
                    <div class="col-xl-5 col-lg-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="search"
                                   class="form-control"
                                   id="treeSearchInput"
                                   placeholder="Search samples, computations, files, experiments, tags, metadata...">
                            <button type="button" class="btn btn-outline-secondary" id="clearSearchBtn">
                                Clear
                            </button>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4">
                        <select class="form-select" id="scopeSelect">
                            <option value="all" selected>All projects</option>
                            <option value="project">Current project</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4">
                        <select class="form-select" id="groupBySelect">
                            <option value="project" selected>Group by project</option>
                            <option value="type">Group by type</option>
                        </select>
                    </div>

                    <div class="col-xl-3 col-lg-12">
                        <div class="d-flex gap-2 justify-content-xl-end">
                            <button type="button" class="btn btn-outline-primary" id="matchingPathsBtn">
                                Matching paths
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="fullContextBtn">
                                Full context
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mc-browser-body">
                <aside class="mc-browser-sidebar">
                    <div class="mc-sidebar-section">
                        <div class="mc-sidebar-title">Quick access</div>

                        <button type="button" class="mc-quick-link active" data-quick-filter="all">
                            <span><i class="fas fa-layer-group me-2"></i>All data</span>
                            <span class="badge text-bg-light">86</span>
                        </button>

                        <button type="button" class="mc-quick-link" data-quick-filter="recent">
                            <span><i class="fas fa-clock me-2"></i>Recently viewed</span>
                            <span class="badge text-bg-light">5</span>
                        </button>

                        <button type="button" class="mc-quick-link" data-quick-filter="pinned">
                            <span><i class="fas fa-thumbtack me-2"></i>Pinned</span>
                            <span class="badge text-bg-light">3</span>
                        </button>
                    </div>

                    <div class="mc-sidebar-section">
                        <div class="mc-sidebar-title">Filter by type</div>

                        <label class="mc-filter-row">
                            <input type="checkbox" class="form-check-input type-filter" value="sample" checked>
                            <span>Samples</span>
                            <span class="text-muted ms-auto">28</span>
                        </label>

                        <label class="mc-filter-row">
                            <input type="checkbox" class="form-check-input type-filter" value="computation" checked>
                            <span>Computations</span>
                            <span class="text-muted ms-auto">14</span>
                        </label>

                        <label class="mc-filter-row">
                            <input type="checkbox" class="form-check-input type-filter" value="file" checked>
                            <span>Files</span>
                            <span class="text-muted ms-auto">39</span>
                        </label>

                        <label class="mc-filter-row">
                            <input type="checkbox" class="form-check-input type-filter" value="experiment" checked>
                            <span>Experiments</span>
                            <span class="text-muted ms-auto">5</span>
                        </label>
                    </div>

                    <div class="mc-sidebar-section">
                        <div class="mc-sidebar-title">Suggested searches</div>

                        <button type="button" class="mc-search-chip" data-search="liver">liver</button>
                        <button type="button" class="mc-search-chip" data-search="heat treatment">heat treatment</button>
                        <button type="button" class="mc-search-chip" data-search="failed">failed</button>
                        <button type="button" class="mc-search-chip" data-search="tensile">tensile</button>
                        <button type="button" class="mc-search-chip" data-search="raw data">raw data</button>
                    </div>
                </aside>

                <main class="mc-tree-panel">
                    <div class="mc-panel-header">
                        <div>
                            <h2 class="h5 mb-1">Data tree</h2>
                            <div class="text-muted small" id="treeSummary">
                                Browse all projects. Select a leaf to preview details.
                            </div>
                        </div>

                        <div class="text-muted small">
                            <span id="matchCount">86 visible items</span>
                        </div>
                    </div>

                    <div class="mc-tree-scroll">
                        <ul class="mc-tree" id="projectTree">
                            <li class="mc-tree-node" data-label="recently viewed mouse liver sample alignment raw reads tensile report" data-type="folder">
                                <button type="button" class="mc-tree-toggle" aria-expanded="true">
                                    <i class="fas fa-chevron-down"></i>
                                    <i class="fas fa-clock text-secondary"></i>
                                    <span class="mc-node-label">Recently Viewed</span>
                                    <span class="mc-node-count">5</span>
                                </button>

                                <ul class="mc-tree-children">
                                    <li class="mc-tree-node mc-leaf"
                                        data-type="sample"
                                        data-label="mouse liver 04 liver rna sample project alloy discovery recent"
                                        data-title="Mouse Liver 04"
                                        data-kind="Sample"
                                        data-project="Alloy Discovery"
                                        data-location="Alloy Discovery > RNA-seq Batch A > Samples"
                                        data-description="Mouse tissue sample with liver metadata. Recently opened from RNA-seq Batch A."
                                        data-tags="liver, mouse, RNA-seq, treated">
                                        <button type="button" class="mc-tree-item">
                                            <i class="fas fa-vial text-success"></i>
                                            <span class="mc-node-label">Mouse Liver 04</span>
                                            <span class="badge text-bg-success">Sample</span>
                                        </button>
                                    </li>

                                    <li class="mc-tree-node mc-leaf"
                                        data-type="computation"
                                        data-label="alignment run 103 rna seq complete computation recent"
                                        data-title="Alignment Run 103"
                                        data-kind="Computation"
                                        data-project="Alloy Discovery"
                                        data-location="Alloy Discovery > RNA-seq Batch A > Computations"
                                        data-description="Completed alignment computation for RNA-seq Batch A."
                                        data-tags="RNA-seq, alignment, complete">
                                        <button type="button" class="mc-tree-item">
                                            <i class="fas fa-microchip text-primary"></i>
                                            <span class="mc-node-label">Alignment Run 103</span>
                                            <span class="badge text-bg-primary">Computation</span>
                                        </button>
                                    </li>
                                </ul>
                            </li>

                            <li class="mc-tree-node" data-label="pinned favorites heat treatment experiment tensile comparison liver samples" data-type="folder">
                                <button type="button" class="mc-tree-toggle" aria-expanded="true">
                                    <i class="fas fa-chevron-down"></i>
                                    <i class="fas fa-thumbtack text-warning"></i>
                                    <span class="mc-node-label">Pinned</span>
                                    <span class="mc-node-count">3</span>
                                </button>

                                <ul class="mc-tree-children">
                                    <li class="mc-tree-node mc-leaf"
                                        data-type="experiment"
                                        data-label="heat treatment experiment study pinned alloy"
                                        data-title="Heat Treatment Study"
                                        data-kind="Experiment"
                                        data-project="Alloy Discovery"
                                        data-location="Alloy Discovery > Experiments"
                                        data-description="Pinned experiment containing samples, microscopy files, tensile tests, and hardness measurements."
                                        data-tags="heat treatment, alloy, tensile, hardness">
                                        <button type="button" class="mc-tree-item">
                                            <i class="fas fa-flask text-purple"></i>
                                            <span class="mc-node-label">Heat Treatment Study</span>
                                            <span class="badge text-bg-warning">Experiment</span>
                                        </button>
                                    </li>
                                </ul>
                            </li>

                            <li class="mc-tree-node mc-project" data-project-key="alloy-discovery" data-label="project alloy discovery heat treatment microscopy tensile rna seq liver" data-type="folder">
                                <button type="button" class="mc-tree-toggle" aria-expanded="true">
                                    <i class="fas fa-chevron-down"></i>
                                    <i class="fas fa-folder text-warning"></i>
                                    <span class="mc-node-label">Alloy Discovery</span>
                                    <span class="mc-node-count">42</span>
                                </button>

                                <ul class="mc-tree-children">
                                    <li class="mc-tree-node" data-label="samples alloy coupon liver mouse treated control" data-type="folder">
                                        <button type="button" class="mc-tree-toggle" aria-expanded="true">
                                            <i class="fas fa-chevron-down"></i>
                                            <i class="fas fa-vials text-success"></i>
                                            <span class="mc-node-label">Samples</span>
                                            <span class="mc-node-count">12</span>
                                        </button>

                                        <ul class="mc-tree-children">
                                            <li class="mc-tree-node mc-leaf"
                                                data-type="sample"
                                                data-label="coupon a12 heat treated sample tensile alloy"
                                                data-title="Coupon A12"
                                                data-kind="Sample"
                                                data-project="Alloy Discovery"
                                                data-location="Alloy Discovery > Samples"
                                                data-description="Heat-treated alloy coupon prepared for tensile testing and microscopy."
                                                data-tags="coupon, heat treatment, tensile">
                                                <button type="button" class="mc-tree-item">
                                                    <i class="fas fa-vial text-success"></i>
                                                    <span class="mc-node-label">Coupon A12</span>
                                                    <span class="badge text-bg-success">Sample</span>
                                                </button>
                                            </li>

                                            <li class="mc-tree-node mc-leaf"
                                                data-type="sample"
                                                data-label="coupon b07 annealed sample hardness alloy"
                                                data-title="Coupon B07"
                                                data-kind="Sample"
                                                data-project="Alloy Discovery"
                                                data-location="Alloy Discovery > Samples"
                                                data-description="Annealed coupon with hardness measurements and microscopy images."
                                                data-tags="coupon, annealed, hardness">
                                                <button type="button" class="mc-tree-item">
                                                    <i class="fas fa-vial text-success"></i>
                                                    <span class="mc-node-label">Coupon B07</span>
                                                    <span class="badge text-bg-success">Sample</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="mc-tree-node" data-label="computations alignment segmentation failed complete finite element" data-type="folder">
                                        <button type="button" class="mc-tree-toggle" aria-expanded="false">
                                            <i class="fas fa-chevron-right"></i>
                                            <i class="fas fa-cogs text-primary"></i>
                                            <span class="mc-node-label">Computations</span>
                                            <span class="mc-node-count">8</span>
                                        </button>

                                        <ul class="mc-tree-children d-none">
                                            <li class="mc-tree-node mc-leaf"
                                                data-type="computation"
                                                data-label="grain segmentation run 22 microscopy complete computation"
                                                data-title="Grain Segmentation Run 22"
                                                data-kind="Computation"
                                                data-project="Alloy Discovery"
                                                data-location="Alloy Discovery > Computations"
                                                data-description="Completed segmentation of microscopy images for grain size analysis."
                                                data-tags="segmentation, microscopy, complete">
                                                <button type="button" class="mc-tree-item">
                                                    <i class="fas fa-microchip text-primary"></i>
                                                    <span class="mc-node-label">Grain Segmentation Run 22</span>
                                                    <span class="badge text-bg-primary">Computation</span>
                                                </button>
                                            </li>

                                            <li class="mc-tree-node mc-leaf"
                                                data-type="computation"
                                                data-label="fea tensile model failed computation error stress strain"
                                                data-title="FEA Tensile Model 7"
                                                data-kind="Computation"
                                                data-project="Alloy Discovery"
                                                data-location="Alloy Discovery > Computations"
                                                data-description="Failed finite element tensile simulation. Error occurred during mesh validation."
                                                data-tags="failed, tensile, FEA, stress-strain">
                                                <button type="button" class="mc-tree-item">
                                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                                    <span class="mc-node-label">FEA Tensile Model 7</span>
                                                    <span class="badge text-bg-danger">Failed</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="mc-tree-node" data-label="files raw data microscopy csv xlsx report images" data-type="folder">
                                        <button type="button" class="mc-tree-toggle" aria-expanded="false">
                                            <i class="fas fa-chevron-right"></i>
                                            <i class="fas fa-file-alt text-secondary"></i>
                                            <span class="mc-node-label">Files</span>
                                            <span class="mc-node-count">17</span>
                                        </button>

                                        <ul class="mc-tree-children d-none">
                                            <li class="mc-tree-node mc-leaf"
                                                data-type="file"
                                                data-label="raw tensile data csv stress strain file"
                                                data-title="raw-tensile-data.csv"
                                                data-kind="File"
                                                data-project="Alloy Discovery"
                                                data-location="Alloy Discovery > Files"
                                                data-description="Raw tensile stress-strain measurements for Coupon A12 and Coupon B07."
                                                data-tags="raw data, tensile, CSV">
                                                <button type="button" class="mc-tree-item">
                                                    <i class="fas fa-file-csv text-secondary"></i>
                                                    <span class="mc-node-label">raw-tensile-data.csv</span>
                                                    <span class="badge text-bg-secondary">File</span>
                                                </button>
                                            </li>

                                            <li class="mc-tree-node mc-leaf"
                                                data-type="file"
                                                data-label="microscopy montage image grain report file"
                                                data-title="microscopy-montage.tif"
                                                data-kind="File"
                                                data-project="Alloy Discovery"
                                                data-location="Alloy Discovery > Files"
                                                data-description="High-resolution microscopy montage used for grain segmentation."
                                                data-tags="microscopy, image, grain">
                                                <button type="button" class="mc-tree-item">
                                                    <i class="fas fa-file-image text-secondary"></i>
                                                    <span class="mc-node-label">microscopy-montage.tif</span>
                                                    <span class="badge text-bg-secondary">File</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="mc-tree-node" data-label="experiments heat treatment rna seq batch microscopy tensile" data-type="folder">
                                        <button type="button" class="mc-tree-toggle" aria-expanded="true">
                                            <i class="fas fa-chevron-down"></i>
                                            <i class="fas fa-flask text-purple"></i>
                                            <span class="mc-node-label">Experiments</span>
                                            <span class="mc-node-count">5</span>
                                        </button>

                                        <ul class="mc-tree-children">
                                            <li class="mc-tree-node" data-label="experiment heat treatment study alloy samples computations files tensile hardness" data-type="folder">
                                                <button type="button" class="mc-tree-toggle" aria-expanded="true">
                                                    <i class="fas fa-chevron-down"></i>
                                                    <i class="fas fa-flask text-purple"></i>
                                                    <span class="mc-node-label">Heat Treatment Study</span>
                                                    <span class="mc-node-count">9</span>
                                                </button>

                                                <ul class="mc-tree-children">
                                                    <li class="mc-tree-node" data-label="samples coupon a12 b07 heat treatment" data-type="folder">
                                                        <button type="button" class="mc-tree-toggle" aria-expanded="false">
                                                            <i class="fas fa-chevron-right"></i>
                                                            <i class="fas fa-vials text-success"></i>
                                                            <span class="mc-node-label">Samples</span>
                                                            <span class="mc-node-count">2</span>
                                                        </button>

                                                        <ul class="mc-tree-children d-none">
                                                            <li class="mc-tree-node mc-leaf"
                                                                data-type="sample"
                                                                data-label="coupon a12 heat treatment study sample"
                                                                data-title="Coupon A12"
                                                                data-kind="Sample"
                                                                data-project="Alloy Discovery"
                                                                data-location="Alloy Discovery > Experiments > Heat Treatment Study > Samples"
                                                                data-description="Same sample shown in experiment context."
                                                                data-tags="coupon, heat treatment, tensile">
                                                                <button type="button" class="mc-tree-item">
                                                                    <i class="fas fa-vial text-success"></i>
                                                                    <span class="mc-node-label">Coupon A12</span>
                                                                    <span class="badge text-bg-success">Sample</span>
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </li>

                                                    <li class="mc-tree-node" data-label="computations tensile fea segmentation failed" data-type="folder">
                                                        <button type="button" class="mc-tree-toggle" aria-expanded="false">
                                                            <i class="fas fa-chevron-right"></i>
                                                            <i class="fas fa-cogs text-primary"></i>
                                                            <span class="mc-node-label">Computations</span>
                                                            <span class="mc-node-count">2</span>
                                                        </button>

                                                        <ul class="mc-tree-children d-none">
                                                            <li class="mc-tree-node mc-leaf"
                                                                data-type="computation"
                                                                data-label="fea tensile model 7 failed"
                                                                data-title="FEA Tensile Model 7"
                                                                data-kind="Computation"
                                                                data-project="Alloy Discovery"
                                                                data-location="Alloy Discovery > Experiments > Heat Treatment Study > Computations"
                                                                data-description="Experiment-linked failed tensile simulation."
                                                                data-tags="failed, tensile, FEA">
                                                                <button type="button" class="mc-tree-item">
                                                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                                                    <span class="mc-node-label">FEA Tensile Model 7</span>
                                                                    <span class="badge text-bg-danger">Failed</span>
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>

                                            <li class="mc-tree-node" data-label="experiment rna seq batch a liver mouse samples computations files" data-type="folder">
                                                <button type="button" class="mc-tree-toggle" aria-expanded="false">
                                                    <i class="fas fa-chevron-right"></i>
                                                    <i class="fas fa-flask text-purple"></i>
                                                    <span class="mc-node-label">RNA-seq Batch A</span>
                                                    <span class="mc-node-count">11</span>
                                                </button>

                                                <ul class="mc-tree-children d-none">
                                                    <li class="mc-tree-node" data-label="samples mouse liver control treated" data-type="folder">
                                                        <button type="button" class="mc-tree-toggle" aria-expanded="false">
                                                            <i class="fas fa-chevron-right"></i>
                                                            <i class="fas fa-vials text-success"></i>
                                                            <span class="mc-node-label">Samples</span>
                                                            <span class="mc-node-count">4</span>
                                                        </button>

                                                        <ul class="mc-tree-children d-none">
                                                            <li class="mc-tree-node mc-leaf"
                                                                data-type="sample"
                                                                data-label="mouse liver 04 sample rna seq treated"
                                                                data-title="Mouse Liver 04"
                                                                data-kind="Sample"
                                                                data-project="Alloy Discovery"
                                                                data-location="Alloy Discovery > Experiments > RNA-seq Batch A > Samples"
                                                                data-description="Mouse liver sample used in RNA-seq Batch A. Treatment group."
                                                                data-tags="liver, mouse, RNA-seq, treated">
                                                                <button type="button" class="mc-tree-item">
                                                                    <i class="fas fa-vial text-success"></i>
                                                                    <span class="mc-node-label">Mouse Liver 04</span>
                                                                    <span class="badge text-bg-success">Sample</span>
                                                                </button>
                                                            </li>

                                                            <li class="mc-tree-node mc-leaf"
                                                                data-type="sample"
                                                                data-label="mouse liver 05 sample rna seq control"
                                                                data-title="Mouse Liver 05"
                                                                data-kind="Sample"
                                                                data-project="Alloy Discovery"
                                                                data-location="Alloy Discovery > Experiments > RNA-seq Batch A > Samples"
                                                                data-description="Mouse liver sample used in RNA-seq Batch A. Control group."
                                                                data-tags="liver, mouse, RNA-seq, control">
                                                                <button type="button" class="mc-tree-item">
                                                                    <i class="fas fa-vial text-success"></i>
                                                                    <span class="mc-node-label">Mouse Liver 05</span>
                                                                    <span class="badge text-bg-success">Sample</span>
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </li>

                                                    <li class="mc-tree-node" data-label="computations alignment expression rna seq" data-type="folder">
                                                        <button type="button" class="mc-tree-toggle" aria-expanded="false">
                                                            <i class="fas fa-chevron-right"></i>
                                                            <i class="fas fa-cogs text-primary"></i>
                                                            <span class="mc-node-label">Computations</span>
                                                            <span class="mc-node-count">3</span>
                                                        </button>

                                                        <ul class="mc-tree-children d-none">
                                                            <li class="mc-tree-node mc-leaf"
                                                                data-type="computation"
                                                                data-label="alignment run 103 rna seq liver complete"
                                                                data-title="Alignment Run 103"
                                                                data-kind="Computation"
                                                                data-project="Alloy Discovery"
                                                                data-location="Alloy Discovery > Experiments > RNA-seq Batch A > Computations"
                                                                data-description="Read alignment for mouse liver RNA-seq samples."
                                                                data-tags="RNA-seq, liver, alignment, complete">
                                                                <button type="button" class="mc-tree-item">
                                                                    <i class="fas fa-microchip text-primary"></i>
                                                                    <span class="mc-node-label">Alignment Run 103</span>
                                                                    <span class="badge text-bg-primary">Computation</span>
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="mc-tree-node mc-project" data-project-key="battery-archive" data-label="project battery archive cycling impedance cathode raw files experiments" data-type="folder">
                                <button type="button" class="mc-tree-toggle" aria-expanded="false">
                                    <i class="fas fa-chevron-right"></i>
                                    <i class="fas fa-folder text-warning"></i>
                                    <span class="mc-node-label">Battery Archive</span>
                                    <span class="mc-node-count">31</span>
                                </button>

                                <ul class="mc-tree-children d-none">
                                    <li class="mc-tree-node" data-label="samples cathode anode electrolyte cells" data-type="folder">
                                        <button type="button" class="mc-tree-toggle" aria-expanded="false">
                                            <i class="fas fa-chevron-right"></i>
                                            <i class="fas fa-vials text-success"></i>
                                            <span class="mc-node-label">Samples</span>
                                            <span class="mc-node-count">9</span>
                                        </button>

                                        <ul class="mc-tree-children d-none">
                                            <li class="mc-tree-node mc-leaf"
                                                data-type="sample"
                                                data-label="cathode mix c03 sample battery nmc"
                                                data-title="Cathode Mix C03"
                                                data-kind="Sample"
                                                data-project="Battery Archive"
                                                data-location="Battery Archive > Samples"
                                                data-description="NMC cathode slurry mix used for coin cell build."
                                                data-tags="cathode, NMC, slurry">
                                                <button type="button" class="mc-tree-item">
                                                    <i class="fas fa-vial text-success"></i>
                                                    <span class="mc-node-label">Cathode Mix C03</span>
                                                    <span class="badge text-bg-success">Sample</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="mc-tree-node" data-label="files cycling raw data impedance xlsx csv" data-type="folder">
                                        <button type="button" class="mc-tree-toggle" aria-expanded="false">
                                            <i class="fas fa-chevron-right"></i>
                                            <i class="fas fa-file-alt text-secondary"></i>
                                            <span class="mc-node-label">Files</span>
                                            <span class="mc-node-count">18</span>
                                        </button>

                                        <ul class="mc-tree-children d-none">
                                            <li class="mc-tree-node mc-leaf"
                                                data-type="file"
                                                data-label="cycling raw data cell 17 csv battery"
                                                data-title="cell-17-cycling-raw.csv"
                                                data-kind="File"
                                                data-project="Battery Archive"
                                                data-location="Battery Archive > Files"
                                                data-description="Raw cycling data for cell 17."
                                                data-tags="raw data, cycling, battery">
                                                <button type="button" class="mc-tree-item">
                                                    <i class="fas fa-file-csv text-secondary"></i>
                                                    <span class="mc-node-label">cell-17-cycling-raw.csv</span>
                                                    <span class="badge text-bg-secondary">File</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="mc-tree-node mc-project" data-project-key="polymer-library" data-label="project polymer library tensile thermal dsc files samples" data-type="folder">
                                <button type="button" class="mc-tree-toggle" aria-expanded="false">
                                    <i class="fas fa-chevron-right"></i>
                                    <i class="fas fa-folder text-warning"></i>
                                    <span class="mc-node-label">Polymer Library</span>
                                    <span class="mc-node-count">13</span>
                                </button>

                                <ul class="mc-tree-children d-none">
                                    <li class="mc-tree-node" data-label="experiments thermal dsc tensile polymer" data-type="folder">
                                        <button type="button" class="mc-tree-toggle" aria-expanded="false">
                                            <i class="fas fa-chevron-right"></i>
                                            <i class="fas fa-flask text-purple"></i>
                                            <span class="mc-node-label">Experiments</span>
                                            <span class="mc-node-count">2</span>
                                        </button>

                                        <ul class="mc-tree-children d-none">
                                            <li class="mc-tree-node mc-leaf"
                                                data-type="experiment"
                                                data-label="dsc thermal sweep polymer experiment"
                                                data-title="DSC Thermal Sweep"
                                                data-kind="Experiment"
                                                data-project="Polymer Library"
                                                data-location="Polymer Library > Experiments"
                                                data-description="Thermal sweep experiment for polymer blend candidates."
                                                data-tags="DSC, thermal, polymer">
                                                <button type="button" class="mc-tree-item">
                                                    <i class="fas fa-flask text-purple"></i>
                                                    <span class="mc-node-label">DSC Thermal Sweep</span>
                                                    <span class="badge text-bg-warning">Experiment</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <div class="mc-no-results d-none" id="noResults">
                            <div class="fs-2 text-muted mb-2">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="fw-semibold">No matching data found</div>
                            <div class="text-muted small">
                                Try a broader term, a different project scope, or clearing type filters.
                            </div>
                        </div>
                    </div>
                </main>

                <aside class="mc-detail-panel">
                    <div class="mc-panel-header">
                        <div>
                            <h2 class="h5 mb-1">Details</h2>
                            <div class="text-muted small">Selected item preview</div>
                        </div>
                    </div>

                    <div class="mc-detail-empty" id="detailEmpty">
                        <div class="fs-2 text-muted mb-2">
                            <i class="fas fa-mouse-pointer"></i>
                        </div>
                        <div class="fw-semibold">Select a leaf item</div>
                        <div class="text-muted small">
                            Click a sample, computation, file, or experiment to see details and actions.
                        </div>
                    </div>

                    <div class="mc-detail-content d-none" id="detailContent">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                            <div>
                                <div class="text-muted small text-uppercase fw-semibold" id="detailKind">Sample</div>
                                <h3 class="h4 mb-1" id="detailTitle">Mouse Liver 04</h3>
                                <div class="text-muted" id="detailProject">Alloy Discovery</div>
                            </div>

                            <span class="badge text-bg-primary" id="detailBadge">Sample</span>
                        </div>

                        <div class="mc-breadcrumb-box mb-3" id="detailLocation">
                            Alloy Discovery > Experiments > RNA-seq Batch A > Samples
                        </div>

                        <div class="mb-3">
                            <div class="fw-semibold mb-1">Description</div>
                            <div class="text-muted" id="detailDescription">
                                Mouse liver sample used in RNA-seq Batch A.
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="fw-semibold mb-2">Tags and matched terms</div>
                            <div class="d-flex flex-wrap gap-2" id="detailTags"></div>
                        </div>

                        <div class="mc-related-box mb-3">
                            <div class="fw-semibold mb-2">Related data</div>

                            <div class="mc-related-row">
                                <i class="fas fa-vial text-success"></i>
                                <span>2 related samples</span>
                            </div>

                            <div class="mc-related-row">
                                <i class="fas fa-file-alt text-secondary"></i>
                                <span>4 related files</span>
                            </div>

                            <div class="mc-related-row">
                                <i class="fas fa-microchip text-primary"></i>
                                <span>1 related computation</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary">
                                <i class="fas fa-external-link-alt me-1"></i>
                                Open selected item
                            </button>

                            <button type="button" class="btn btn-outline-secondary">
                                View containing project
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .mc-browse-tree-prototype {
            --mc-border: #dee2e6;
            --mc-soft-border: #edf0f2;
            --mc-soft-bg: #f8f9fa;
            --mc-card-shadow: 0 1px 2px rgba(16, 24, 40, .04);
        }

        .mc-browser-shell {
            border: 1px solid var(--mc-border);
            border-radius: .85rem;
            background: #fff;
            box-shadow: var(--mc-card-shadow);
            overflow: hidden;
        }

        .mc-browser-toolbar {
            padding: 1rem;
            background: #fbfcfd;
            border-bottom: 1px solid var(--mc-soft-border);
        }

        .mc-browser-body {
            display: grid;
            grid-template-columns: 260px minmax(420px, 1fr) 360px;
            min-height: 720px;
        }

        .mc-browser-sidebar {
            border-right: 1px solid var(--mc-soft-border);
            background: #fbfcfd;
            padding: 1rem;
        }

        .mc-tree-panel {
            min-width: 0;
            border-right: 1px solid var(--mc-soft-border);
            display: flex;
            flex-direction: column;
        }

        .mc-detail-panel {
            min-width: 0;
            background: #fff;
        }

        .mc-panel-header {
            padding: 1rem;
            border-bottom: 1px solid var(--mc-soft-border);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .mc-tree-scroll {
            padding: .75rem 1rem 1rem;
            overflow: auto;
            max-height: 720px;
        }

        .mc-sidebar-section {
            margin-bottom: 1.35rem;
        }

        .mc-sidebar-title {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6c757d;
            font-weight: 700;
            margin-bottom: .55rem;
        }

        .mc-quick-link {
            width: 100%;
            border: 1px solid transparent;
            background: transparent;
            border-radius: .55rem;
            padding: .55rem .65rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #344054;
            margin-bottom: .25rem;
            text-align: left;
        }

        .mc-quick-link:hover,
        .mc-quick-link.active {
            background: #eef5ff;
            border-color: #cfe2ff;
            color: #0d6efd;
        }

        .mc-filter-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .35rem 0;
            font-size: .92rem;
            cursor: pointer;
        }

        .mc-search-chip {
            border: 1px solid #d0d7de;
            background: #fff;
            border-radius: 999px;
            padding: .25rem .55rem;
            font-size: .82rem;
            margin: 0 .25rem .35rem 0;
        }

        .mc-search-chip:hover {
            border-color: #0d6efd;
            color: #0d6efd;
        }

        .mc-tree,
        .mc-tree-children {
            list-style: none;
            margin: 0;
            padding-left: 0;
        }

        .mc-tree-children {
            margin-left: 1.35rem;
            border-left: 1px dashed #d0d7de;
            padding-left: .55rem;
        }

        .mc-tree-node {
            margin: .18rem 0;
        }

        .mc-tree-toggle,
        .mc-tree-item {
            width: 100%;
            border: 1px solid transparent;
            background: transparent;
            display: flex;
            align-items: center;
            gap: .45rem;
            text-align: left;
            border-radius: .45rem;
            padding: .38rem .45rem;
            color: #344054;
            min-height: 34px;
        }

        .mc-tree-toggle:hover,
        .mc-tree-item:hover {
            background: #f3f6f9;
            border-color: #e9ecef;
        }

        .mc-tree-item.active {
            background: #eef5ff;
            border-color: #b6d4fe;
            color: #084298;
        }

        .mc-node-label {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .mc-node-count {
            margin-left: auto;
            color: #6c757d;
            font-size: .8rem;
            background: #f1f3f5;
            border-radius: 999px;
            padding: .05rem .45rem;
        }

        .mc-match-highlight {
            background: #fff3cd;
            color: #664d03;
            border-radius: .2rem;
            padding: 0 .1rem;
        }

        .mc-detail-empty {
            min-height: 420px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
        }

        .mc-detail-content {
            padding: 1rem;
        }

        .mc-breadcrumb-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: .55rem;
            padding: .65rem;
            color: #475467;
            font-size: .88rem;
        }

        .mc-related-box {
            border: 1px solid #e9ecef;
            border-radius: .65rem;
            padding: .8rem;
            background: #fbfcfd;
        }

        .mc-related-row {
            display: flex;
            align-items: center;
            gap: .55rem;
            padding: .35rem 0;
            color: #475467;
        }

        .mc-no-results {
            text-align: center;
            padding: 4rem 1rem;
        }

        .text-purple {
            color: #7952b3;
        }

        @media (max-width: 1199.98px) {
            .mc-browser-body {
                grid-template-columns: 230px minmax(360px, 1fr);
            }

            .mc-detail-panel {
                grid-column: 1 / -1;
                border-top: 1px solid var(--mc-soft-border);
            }
        }

        @media (max-width: 767.98px) {
            .mc-browser-body {
                grid-template-columns: 1fr;
            }

            .mc-browser-sidebar,
            .mc-tree-panel {
                border-right: 0;
                border-bottom: 1px solid var(--mc-soft-border);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tree = document.getElementById('projectTree');
            const searchInput = document.getElementById('treeSearchInput');
            const clearSearchBtn = document.getElementById('clearSearchBtn');
            const expandAllBtn = document.getElementById('expandAllBtn');
            const collapseAllBtn = document.getElementById('collapseAllBtn');
            const matchCount = document.getElementById('matchCount');
            const treeSummary = document.getElementById('treeSummary');
            const noResults = document.getElementById('noResults');
            const typeFilters = Array.from(document.querySelectorAll('.type-filter'));
            const scopeSelect = document.getElementById('scopeSelect');
            const groupBySelect = document.getElementById('groupBySelect');

            const detailEmpty = document.getElementById('detailEmpty');
            const detailContent = document.getElementById('detailContent');
            const detailKind = document.getElementById('detailKind');
            const detailTitle = document.getElementById('detailTitle');
            const detailProject = document.getElementById('detailProject');
            const detailBadge = document.getElementById('detailBadge');
            const detailLocation = document.getElementById('detailLocation');
            const detailDescription = document.getElementById('detailDescription');
            const detailTags = document.getElementById('detailTags');

            function setExpanded(toggle, expanded) {
                const children = toggle.parentElement.querySelector(':scope > .mc-tree-children');
                const chevron = toggle.querySelector('.fa-chevron-right, .fa-chevron-down');

                toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');

                if (children) {
                    children.classList.toggle('d-none', !expanded);
                }

                if (chevron) {
                    chevron.classList.toggle('fa-chevron-right', !expanded);
                    chevron.classList.toggle('fa-chevron-down', expanded);
                }
            }

            function getActiveTypes() {
                return typeFilters
                    .filter((filter) => filter.checked)
                    .map((filter) => filter.value);
            }

            function textMatches(node, query) {
                if (!query) {
                    return true;
                }

                const label = (node.dataset.label || '').toLowerCase();
                return query
                    .split(/\s+/)
                    .filter(Boolean)
                    .every((term) => label.includes(term));
            }

            function typeMatches(node, activeTypes) {
                if (!node.classList.contains('mc-leaf')) {
                    return true;
                }

                return activeTypes.includes(node.dataset.type);
            }

            function scopeMatches(node) {
                if (scopeSelect.value === 'all') {
                    return true;
                }

                const project = node.closest('.mc-project');
                return !project || project.dataset.projectKey === 'alloy-discovery';
            }

            function clearHighlights() {
                tree.querySelectorAll('.mc-node-label').forEach((label) => {
                    label.innerHTML = label.textContent;
                });
            }

            function highlightMatches(query) {
                if (!query) {
                    return;
                }

                const terms = query.split(/\s+/).filter(Boolean);

                tree.querySelectorAll('.mc-tree-node:not(.d-none) > button .mc-node-label').forEach((label) => {
                    let html = label.textContent;

                    terms.forEach((term) => {
                        const escaped = term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                        html = html.replace(new RegExp('(' + escaped + ')', 'ig'), '<span class="mc-match-highlight">$1</span>');
                    });

                    label.innerHTML = html;
                });
            }

            function applyFilters() {
                const query = searchInput.value.trim().toLowerCase();
                const activeTypes = getActiveTypes();
                let visibleLeafCount = 0;

                clearHighlights();

                const nodes = Array.from(tree.querySelectorAll('.mc-tree-node'));

                nodes.forEach((node) => {
                    node.classList.add('d-none');
                });

                nodes.reverse().forEach((node) => {
                    const isLeaf = node.classList.contains('mc-leaf');
                    const ownMatch = textMatches(node, query) && typeMatches(node, activeTypes) && scopeMatches(node);
                    const hasVisibleChild = Array.from(node.querySelectorAll(':scope > .mc-tree-children > .mc-tree-node'))
                        .some((child) => !child.classList.contains('d-none'));

                    if ((isLeaf && ownMatch) || (!isLeaf && (ownMatch || hasVisibleChild))) {
                        node.classList.remove('d-none');

                        if (isLeaf && ownMatch) {
                            visibleLeafCount++;
                        }

                        if (query && hasVisibleChild) {
                            const toggle = node.querySelector(':scope > .mc-tree-toggle');
                            if (toggle) {
                                setExpanded(toggle, true);
                            }
                        }
                    }
                });

                highlightMatches(query);

                noResults.classList.toggle('d-none', visibleLeafCount > 0 || !query);
                matchCount.textContent = visibleLeafCount + (visibleLeafCount === 1 ? ' visible item' : ' visible items');

                if (query) {
                    treeSummary.textContent = 'Showing matching paths for "' + searchInput.value.trim() + '". Matching branches have been opened.';
                } else if (scopeSelect.value === 'project') {
                    treeSummary.textContent = 'Browsing current project only: Alloy Discovery.';
                } else {
                    treeSummary.textContent = 'Browse all projects. Select a leaf to preview details.';
                }
            }

            function showDetails(node) {
                const tags = (node.dataset.tags || '')
                    .split(',')
                    .map((tag) => tag.trim())
                    .filter(Boolean);

                detailKind.textContent = node.dataset.kind || 'Item';
                detailTitle.textContent = node.dataset.title || 'Selected item';
                detailProject.textContent = node.dataset.project || '';
                detailBadge.textContent = node.dataset.kind || 'Item';
                detailLocation.textContent = node.dataset.location || '';
                detailDescription.textContent = node.dataset.description || '';

                detailTags.innerHTML = '';
                tags.forEach((tag) => {
                    const tagElement = document.createElement('span');
                    tagElement.className = 'badge text-bg-light border';
                    tagElement.textContent = tag;
                    detailTags.appendChild(tagElement);
                });

                detailEmpty.classList.add('d-none');
                detailContent.classList.remove('d-none');

                tree.querySelectorAll('.mc-tree-item.active').forEach((item) => item.classList.remove('active'));
                node.querySelector('.mc-tree-item').classList.add('active');
            }

            tree.addEventListener('click', function (event) {
                const toggle = event.target.closest('.mc-tree-toggle');
                const item = event.target.closest('.mc-tree-item');

                if (toggle) {
                    const expanded = toggle.getAttribute('aria-expanded') === 'true';
                    setExpanded(toggle, !expanded);
                }

                if (item) {
                    const node = item.closest('.mc-leaf');
                    if (node) {
                        showDetails(node);
                    }
                }
            });

            searchInput.addEventListener('input', applyFilters);

            clearSearchBtn.addEventListener('click', function () {
                searchInput.value = '';
                applyFilters();
                searchInput.focus();
            });

            expandAllBtn.addEventListener('click', function () {
                tree.querySelectorAll('.mc-tree-toggle').forEach((toggle) => setExpanded(toggle, true));
            });

            collapseAllBtn.addEventListener('click', function () {
                tree.querySelectorAll('.mc-tree-toggle').forEach((toggle) => setExpanded(toggle, false));
            });

            typeFilters.forEach((filter) => filter.addEventListener('change', applyFilters));
            scopeSelect.addEventListener('change', applyFilters);

            groupBySelect.addEventListener('change', function () {
                if (groupBySelect.value === 'type') {
                    treeSummary.textContent = 'Group by type is a placeholder for discussion. This prototype keeps the same hard-coded tree.';
                } else {
                    applyFilters();
                }
            });

            document.querySelectorAll('.mc-search-chip').forEach((chip) => {
                chip.addEventListener('click', function () {
                    searchInput.value = chip.dataset.search;
                    applyFilters();
                });
            });

            document.querySelectorAll('.mc-quick-link').forEach((link) => {
                link.addEventListener('click', function () {
                    document.querySelectorAll('.mc-quick-link').forEach((item) => item.classList.remove('active'));
                    link.classList.add('active');

                    if (link.dataset.quickFilter === 'recent') {
                        searchInput.value = 'recent';
                    } else if (link.dataset.quickFilter === 'pinned') {
                        searchInput.value = 'pinned';
                    } else {
                        searchInput.value = '';
                    }

                    applyFilters();
                });
            });

            applyFilters();
        });
    </script>
@endpush
