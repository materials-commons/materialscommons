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

function toCamelCase(str) {
    if (!str) {
        return ''; // Handle empty strings
    }

    return str
        .split('-') // Split the string by hyphens
        .map((word, index) => index === 0 ? word : word.charAt(0).toUpperCase() + word.slice(1)) // Capitalize the first letter of each subsequent word
        .join(''); // Join them back into a single string
}

function copyToClipboard(what) {
    if (what.startsWith('#')) {
        let element = document.getElementById(what.substring(1));
        navigator.clipboard.writeText(element.textContent);
    } else {
        navigator.clipboard.writeText(what);
    }
}

function onAlpineInit(name, fn) {
    document.addEventListener('alpine:init', () => {
        Alpine.data(name, fn);
    });
}

function initDataTable(id, args) {
    let e = $(id);
    if (!$.fn.dataTable.isDataTable(e)) {
        return e.DataTable(args);
    }

    return e.DataTable();
}

module.exports = {
    setupDatatable,
    setupDatatableOnDocumentReady,
    autosizeTextareas,
    toggleShow,
    copyToClipboard,
    toCamelCase,
    onAlpineInit,
    initDataTable,
};