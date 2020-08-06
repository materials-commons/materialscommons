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

function toggleShow(count, attrClass, msg) {
    $(`.${attrClass}`).attr('hidden', (_, attr) => !attr);
    let text = $(`#${attrClass}-text`).text().trim();
    console.log(`text = '${text}`);
    if (text.startsWith("See")) {
        $(`#${attrClass}-text`).text(`Hide ${count} ${msg}`);
    } else {
        $(`#${attrClass}-text`).text(`See ${count} more ${msg}`);
    }
}

module.exports = {
    setupDatatable,
    setupDatatableOnDocumentReady,
    autosizeTextareas,
    toggleShow,
};