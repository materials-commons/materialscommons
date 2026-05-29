@props([
    'persistenceKey',
])

@script
<script>
    const browseTreeFilterStorageKey = @js($persistenceKey . ':loaded-filter');

    function normalizeBrowseTreeFilterText(value) {
        return (value || '').toString().trim().toLowerCase();
    }

    function browseTreeNodeMatches(node, terms) {
        if (terms.length === 0) {
            return true;
        }

        const searchable = normalizeBrowseTreeFilterText(node.dataset.search || '');

        return terms.every(term => searchable.includes(term));
    }

    function applyBrowseTreeLoadedFilter() {
        const filterInput = document.querySelector('.js-browse-tree-filter');
        const tree = document.querySelector('.mc-tree');

        if (!filterInput || !tree) {
            return;
        }

        const query = normalizeBrowseTreeFilterText(filterInput.value);
        const terms = query.split(/\s+/).filter(Boolean);

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

            const ownMatch = browseTreeNodeMatches(node, terms);
            const visible = terms.length === 0 || ownMatch || hasVisibleChild;

            node.classList.toggle('d-none', !visible);
            node.classList.toggle('mc-tree-filter-own-match', terms.length > 0 && ownMatch);

            return visible;
        }

        tree.querySelectorAll(':scope > .mc-tree-node').forEach(node => {
            applyToNode(node);
        });

        try {
            if (query === '') {
                window.sessionStorage.removeItem(browseTreeFilterStorageKey);
            } else {
                window.sessionStorage.setItem(browseTreeFilterStorageKey, filterInput.value);
            }
        } catch (error) {
            // Filtering remains usable if sessionStorage is unavailable.
        }
    }

    function restoreBrowseTreeLoadedFilter() {
        const filterInput = document.querySelector('.js-browse-tree-filter');

        if (!filterInput) {
            return;
        }

        try {
            const savedFilter = window.sessionStorage.getItem(browseTreeFilterStorageKey);

            if (savedFilter !== null) {
                filterInput.value = savedFilter;
            }
        } catch (error) {
            // Ignore storage failures.
        }

        applyBrowseTreeLoadedFilter();
    }

    function clearBrowseTreeLoadedFilter() {
        const filterInput = document.querySelector('.js-browse-tree-filter');

        if (!filterInput) {
            return;
        }

        filterInput.value = '';

        try {
            window.sessionStorage.removeItem(browseTreeFilterStorageKey);
        } catch (error) {
            // Ignore storage failures.
        }

        applyBrowseTreeLoadedFilter();
        filterInput.focus();
    }

    document.addEventListener('input', function (event) {
        if (!event.target.matches('.js-browse-tree-filter')) {
            return;
        }

        applyBrowseTreeLoadedFilter();
    });

    document.addEventListener('click', function (event) {
        const clearButton = event.target.closest('.js-browse-tree-filter-clear');

        if (!clearButton) {
            return;
        }

        clearBrowseTreeLoadedFilter();
    });

    restoreBrowseTreeLoadedFilter();

    Livewire.hook('morph.updated', function () {
        applyBrowseTreeLoadedFilter();
    });
</script>
@endscript
