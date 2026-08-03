@props([
    'persistenceKey',
])

@script
<script>
    const browseTreeFilterStorageKey = @js($persistenceKey . ':loaded-filter');
    const browseTreeTypeFilterStorageKey = @js($persistenceKey . ':loaded-type-filter');
    const browseTreeDateFilterStorageKey = @js($persistenceKey . ':loaded-date-filter');
    const browseTreeExperimentFilterStorageKey = @js($persistenceKey . ':loaded-experiment-filter');
    const browseTreeTagFilterStorageKey = @js($persistenceKey . ':loaded-tag-filter');

    function normalizeBrowseTreeFilterText(value) {
        return (value || '').toString().trim().toLowerCase();
    }

    function selectedBrowseTreeTypes() {
        return Array.from(document.querySelectorAll('.js-browse-tree-type-filter'))
            .filter(input => input.checked)
            .map(input => input.dataset.type || input.value)
            .filter(Boolean);
    }

    function selectedBrowseTreeDateFilter() {
        const input = document.querySelector('.js-browse-tree-date-filter');
        return input ? input.value : 'any';
    }

    function selectedBrowseTreeExperimentFilter() {
        const input = document.querySelector('.js-browse-tree-experiment-filter');
        return input ? input.value : 'any';
    }

    function selectedBrowseTreeTags() {
        return Array.from(document.querySelectorAll('.js-browse-tree-tag-filter.active'))
            .map(button => button.dataset.tag)
            .filter(Boolean);
    }

    function saveBrowseTreeClientFilters() {
        try {
            window.sessionStorage.setItem(
                browseTreeTypeFilterStorageKey,
                JSON.stringify(selectedBrowseTreeTypes())
            );

            window.sessionStorage.setItem(
                browseTreeDateFilterStorageKey,
                selectedBrowseTreeDateFilter()
            );

            window.sessionStorage.setItem(
                browseTreeExperimentFilterStorageKey,
                selectedBrowseTreeExperimentFilter()
            );

            window.sessionStorage.setItem(
                browseTreeTagFilterStorageKey,
                JSON.stringify(selectedBrowseTreeTags())
            );
        } catch (error) {
            // Filtering remains usable if sessionStorage is unavailable.
        }
    }

    function restoreBrowseTreeTypeFilters() {
        let savedTypes = null;

        try {
            const rawTypes = window.sessionStorage.getItem(browseTreeTypeFilterStorageKey);

            if (rawTypes) {
                savedTypes = JSON.parse(rawTypes);
            }
        } catch (error) {
            savedTypes = null;
        }

        if (!Array.isArray(savedTypes)) {
            return;
        }

        document.querySelectorAll('.js-browse-tree-type-filter').forEach(input => {
            const type = input.dataset.type || input.value;
            input.checked = savedTypes.includes(type);
        });
    }

    function restoreBrowseTreeDateFilter() {
        const input = document.querySelector('.js-browse-tree-date-filter');

        if (!input) {
            return;
        }

        try {
            const savedDateFilter = window.sessionStorage.getItem(browseTreeDateFilterStorageKey);

            if (savedDateFilter !== null) {
                input.value = savedDateFilter;
            }
        } catch (error) {
            // Ignore storage failures.
        }
    }

    function restoreBrowseTreeExperimentFilter() {
        const input = document.querySelector('.js-browse-tree-experiment-filter');

        if (!input) {
            return;
        }

        try {
            const savedExperimentFilter = window.sessionStorage.getItem(browseTreeExperimentFilterStorageKey);

            if (savedExperimentFilter !== null) {
                input.value = savedExperimentFilter;
            }
        } catch (error) {
            // Ignore storage failures.
        }
    }

    function restoreBrowseTreeTagFilters() {
        let savedTags = null;

        try {
            const rawTags = window.sessionStorage.getItem(browseTreeTagFilterStorageKey);

            if (rawTags) {
                savedTags = JSON.parse(rawTags);
            }
        } catch (error) {
            savedTags = null;
        }

        if (!Array.isArray(savedTags)) {
            return;
        }

        document.querySelectorAll('.js-browse-tree-tag-filter').forEach(button => {
            button.classList.toggle('active', savedTags.includes(button.dataset.tag));
        });
    }

    function browseTreeNodeTextMatches(node, terms) {
        if (terms.length === 0) {
            return true;
        }

        const searchable = normalizeBrowseTreeFilterText(node.dataset.search || '');

        return terms.every(term => searchable.includes(term));
    }

    function browseTreeNodeTypeMatches(node, selectedTypes) {
        const kind = node.dataset.nodeKind || '';
        const type = node.dataset.nodeType || '';

        if (kind !== 'leaf') {
            return true;
        }

        return selectedTypes.includes(type);
    }

    function browseTreeNodeDateMatches(node, dateFilter) {
        const kind = node.dataset.nodeKind || '';

        if (kind !== 'leaf') {
            return true;
        }

        if (dateFilter === 'any') {
            return true;
        }

        return (node.dataset.dateBucket || '') === dateFilter;
    }

    function browseTreeNodeExperimentMatches(node, experimentFilter) {
        const kind = node.dataset.nodeKind || '';

        if (kind !== 'leaf') {
            return true;
        }

        if (experimentFilter === 'any') {
            return true;
        }

        return (node.dataset.experiment || '') === experimentFilter;
    }

    function browseTreeNodeTagsMatch(node, selectedTags) {
        const kind = node.dataset.nodeKind || '';

        if (kind !== 'leaf') {
            return true;
        }

        if (selectedTags.length === 0) {
            return true;
        }

        const nodeTags = (node.dataset.tags || '')
            .split('|')
            .map(tag => tag.trim())
            .filter(Boolean);

        return selectedTags.every(tag => nodeTags.includes(tag));
    }

    function applyBrowseTreeLoadedFilter() {
        const filterInput = document.querySelector('.js-browse-tree-filter');
        const tree = document.querySelector('.mc-tree');

        if (!tree) {
            return;
        }

        const query = normalizeBrowseTreeFilterText(filterInput ? filterInput.value : '');
        const terms = query.split(/\s+/).filter(Boolean);
        const activeTypes = selectedBrowseTreeTypes();
        const dateFilter = selectedBrowseTreeDateFilter();
        const experimentFilter = selectedBrowseTreeExperimentFilter();
        const activeTags = selectedBrowseTreeTags();

        function applyToNode(node) {
            const childList = node.querySelector(':scope > .mc-tree-children');
            let hasVisibleChild = false;

            if (childList) {
                childList.querySelectorAll(':scope > .mc-tree-node').forEach(childNode => {
                    if (applyToNode(childNode)) {
                        hasVisibleChild = true;
                    }
                });
            }

            const textMatches = browseTreeNodeTextMatches(node, terms);
            const typeMatches = browseTreeNodeTypeMatches(node, activeTypes);
            const dateMatches = browseTreeNodeDateMatches(node, dateFilter);
            const experimentMatches = browseTreeNodeExperimentMatches(node, experimentFilter);
            const tagsMatch = browseTreeNodeTagsMatch(node, activeTags);

            const ownMatch = textMatches && typeMatches && dateMatches && experimentMatches && tagsMatch;
            const visible = ownMatch || hasVisibleChild;

            node.classList.toggle('d-none', !visible);
            node.classList.toggle('mc-tree-filter-own-match', terms.length > 0 && ownMatch);

            return visible;
        }

        tree.querySelectorAll(':scope > .mc-tree-node').forEach(node => {
            applyToNode(node);
        });

        try {
            if (filterInput) {
                if (query === '') {
                    window.sessionStorage.removeItem(browseTreeFilterStorageKey);
                } else {
                    window.sessionStorage.setItem(browseTreeFilterStorageKey, filterInput.value);
                }
            }
        } catch (error) {
            // Filtering remains usable if sessionStorage is unavailable.
        }

        saveBrowseTreeClientFilters();
    }

    function restoreBrowseTreeLoadedFilter() {
        const filterInput = document.querySelector('.js-browse-tree-filter');

        if (filterInput) {
            try {
                const savedFilter = window.sessionStorage.getItem(browseTreeFilterStorageKey);

                if (savedFilter !== null) {
                    filterInput.value = savedFilter;
                }
            } catch (error) {
                // Ignore storage failures.
            }
        }

        restoreBrowseTreeTypeFilters();
        restoreBrowseTreeDateFilter();
        restoreBrowseTreeExperimentFilter();
        restoreBrowseTreeTagFilters();
        applyBrowseTreeLoadedFilter();
    }

    function clearBrowseTreeLoadedTextFilter() {
        const filterInput = document.querySelector('.js-browse-tree-filter');

        if (filterInput) {
            filterInput.value = '';
        }

        try {
            window.sessionStorage.removeItem(browseTreeFilterStorageKey);
        } catch (error) {
            // Ignore storage failures.
        }

        applyBrowseTreeLoadedFilter();

        if (filterInput) {
            filterInput.focus();
        }
    }

    function clearBrowseTreeFacets() {
        document.querySelectorAll('.js-browse-tree-type-filter').forEach(input => {
            input.checked = true;
        });

        const dateInput = document.querySelector('.js-browse-tree-date-filter');
        if (dateInput) {
            dateInput.value = 'any';
        }

        const experimentInput = document.querySelector('.js-browse-tree-experiment-filter');
        if (experimentInput) {
            experimentInput.value = 'any';
        }

        document.querySelectorAll('.js-browse-tree-tag-filter').forEach(button => {
            button.classList.remove('active');
        });

        try {
            window.sessionStorage.removeItem(browseTreeTypeFilterStorageKey);
            window.sessionStorage.removeItem(browseTreeDateFilterStorageKey);
            window.sessionStorage.removeItem(browseTreeExperimentFilterStorageKey);
            window.sessionStorage.removeItem(browseTreeTagFilterStorageKey);
        } catch (error) {
            // Ignore storage failures.
        }

        applyBrowseTreeLoadedFilter();
    }

    function applySuggestedBrowseTreeFilter(value) {
        const filterInput = document.querySelector('.js-browse-tree-filter');

        if (!filterInput) {
            return;
        }

        filterInput.value = value || '';
        applyBrowseTreeLoadedFilter();
        filterInput.focus();
    }

    document.addEventListener('input', function (event) {
        if (!event.target.matches('.js-browse-tree-filter')) {
            return;
        }

        applyBrowseTreeLoadedFilter();
    });

    document.addEventListener('change', function (event) {
        if (
            !event.target.matches('.js-browse-tree-type-filter') &&
            !event.target.matches('.js-browse-tree-date-filter') &&
            !event.target.matches('.js-browse-tree-experiment-filter')
        ) {
            return;
        }

        applyBrowseTreeLoadedFilter();
    });

    document.addEventListener('click', function (event) {
        const clearTextButton = event.target.closest('.js-browse-tree-filter-clear');

        if (clearTextButton) {
            clearBrowseTreeLoadedTextFilter();
            return;
        }

        const clearFacetsButton = event.target.closest('.js-browse-tree-clear-facets');

        if (clearFacetsButton) {
            clearBrowseTreeFacets();
            return;
        }

        const tagButton = event.target.closest('.js-browse-tree-tag-filter');

        if (tagButton) {
            tagButton.classList.toggle('active');
            applyBrowseTreeLoadedFilter();
            return;
        }

        const suggestedFilterButton = event.target.closest('.js-browse-tree-suggested-filter');

        if (suggestedFilterButton) {
            applySuggestedBrowseTreeFilter(suggestedFilterButton.dataset.filter || '');
        }
    });

    restoreBrowseTreeLoadedFilter();

    Livewire.hook('morph.updated', function () {
        restoreBrowseTreeTypeFilters();
        restoreBrowseTreeDateFilter();
        restoreBrowseTreeExperimentFilter();
        restoreBrowseTreeTagFilters();
        applyBrowseTreeLoadedFilter();
    });
</script>
@endscript
