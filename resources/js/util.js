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
        let scrollHeight = 75;
        if (this.scrollHeight && this.scrollHeight > 75) {
            scrollHeight = this.scrollHeight;
        }
        this.setAttribute('style', 'height:' + (scrollHeight) + 'px;overflow-y:hidden;');
    }).on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
}

function toggleShow(count, attrClass, msg) {
    $(`.${attrClass}`).attr('hidden', (_, attr) => !attr);
    let text = $(`#${attrClass}-text`).text().trim();
    if (text.startsWith("See")) {
        $(`#${attrClass}-text`).text(`Hide ${count} ${msg}`);
    } else {
        $(`#${attrClass}-text`).text(`See ${count} more ${msg}`);
    }
}

function copyToClipboard(what) {
    if (what.startsWith('#')) {
        let element = document.getElementById(what.substring(1));
        navigator.clipboard.writeText(element.textContent);
    } else {
        navigator.clipboard.writeText(what);
    }
}

module.exports = {
    setupDatatable,
    setupDatatableOnDocumentReady,
    autosizeTextareas,
    toggleShow,
    copyToClipboard,
};