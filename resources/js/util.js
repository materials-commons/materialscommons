function setupDatatable(id) {
    if (id[0] !== '#') {
        id = `#${id}`;
    }

    $(id).DataTable({
        stateSave: true,
    });
}

function setupDatatableOnDocumentReady(id) {
    $(document).ready(() => setupDatatable(id));
}

module.exports = {
    setupDatatable,
    setupDatatableOnDocumentReady,
};