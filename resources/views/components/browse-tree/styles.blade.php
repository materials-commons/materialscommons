@once
    @push('styles')
        <style>
            .mc-browse-tree {
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

            .mc-search-chip:hover,
            .mc-search-chip.active {
                border-color: #0d6efd;
                background: #eef5ff;
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
            .mc-tree-item,
            .mc-tree-action,
            .mc-tree-message {
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
            .mc-tree-item:hover,
            .mc-tree-action:hover {
                background: transparent;
                border-color: transparent;
                color: #0d6efd;
            }

            .mc-tree-action {
                color: #0d6efd;
                font-size: .9rem;
            }

            .mc-tree-message {
                color: #6c757d;
                font-size: .86rem;
                cursor: default;
                align-items: flex-start;
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
                background: transparent;
                border-radius: 0;
                padding: 0;
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

            .mc-mini-meta {
                border: 1px solid #e9ecef;
                background: #fbfcfd;
                border-radius: .55rem;
                padding: .6rem .7rem;
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
                    grid-template-columns: 230px minmax(320px, 1fr) 340px;
                }

                .mc-detail-panel {
                    grid-column: auto;
                    border-top: 0;
                }
            }

            @media (max-width: 991.98px) {
                .mc-browser-body {
                    grid-template-columns: 220px minmax(300px, 1fr) 320px;
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

                .mc-detail-panel {
                    grid-column: 1 / -1;
                    border-top: 1px solid var(--mc-soft-border);
                }
            }
        </style>
    @endpush
@endonce
