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

// addAppComponentMethod adds a component path method to the App variable. For example
// calling: addAppComponentMethod("datahq.explorer.show-data", "toggleShow", function(){})
// would result in app having:
// App["datahq.explorer.show-data"]["toggleShow"] = function(){}
function addAppComponentMethod(componentPath, fname, f) {
    if (App[componentPath] === undefined) {
        App[componentPath] = {};
    }

    App[componentPath][fname] = f
}

// getAppComponentMethod takes a full component path to a method
// and returns the underling function. For example, if the window.App
// looks like window.App["datahq.explorer.show-data"]["toggleShow"] = function() {...}
// then you would call this as: getAppComponentMethod("datahq.explorer.show-data.toggleShow")
// and it would return App["datahq.explorer.show-data"]["toggleShow"], which would
// be the defined function.
function getAppComponentMethod(methodPath) {
    const lastDotIndex = methodPath.lastIndexOf('.');
    const componentPath = methodPath.substring(0, lastDotIndex);
    const methodName = methodPath.substring(lastDotIndex + 1);
    return App[componentPath][methodName];
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
    toCamelCase,
    addAppComponentMethod,
    getAppComponentMethod,
};