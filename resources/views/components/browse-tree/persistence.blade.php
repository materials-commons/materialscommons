@props([
    'persistenceKey',
])

@script
<script>
    const browseTreePersistenceKey = @js($persistenceKey);

    function readBrowseTreeState() {
        try {
            const rawState = window.sessionStorage.getItem(browseTreePersistenceKey);

            if (!rawState) {
                return null;
            }

            return JSON.parse(rawState);
        } catch (error) {
            window.sessionStorage.removeItem(browseTreePersistenceKey);
            return null;
        }
    }

    function writeBrowseTreeState(state) {
        try {
            window.sessionStorage.setItem(browseTreePersistenceKey, JSON.stringify(state));
        } catch (error) {
            // Ignore storage failures. The tree remains usable without persistence.
        }
    }

    function clearBrowseTreeState() {
        window.sessionStorage.removeItem(browseTreePersistenceKey);
    }

    const restoredState = readBrowseTreeState();

    if (restoredState) {
        $wire.restoreBrowserState(restoredState);
    }

    $wire.on('browse-tree-state-changed', function (event) {
        const payload = Array.isArray(event) ? event[0] : event;

        if (!payload || payload.key !== browseTreePersistenceKey) {
            return;
        }

        writeBrowseTreeState(payload.state);
    });

    $wire.on('browse-tree-state-reset', function (event) {
        const payload = Array.isArray(event) ? event[0] : event;

        if (!payload || payload.key !== browseTreePersistenceKey) {
            return;
        }

        clearBrowseTreeState();
    });
</script>
@endscript
