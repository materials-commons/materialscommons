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

function autosizeTextareas() {
    $('textarea').each(function () {
        this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
    }).on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
}

module.exports = {
    setupDatatable,
    setupDatatableOnDocumentReady,
    autosizeTextareas,
};