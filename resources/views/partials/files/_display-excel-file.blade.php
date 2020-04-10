<ul class="nav nav-tabs" id="sheet-tabs">
</ul>
<div id="gridroot"></div>
@push('scripts')
    <script>
        let workbook, grid;
        $(document).ready(() => {
            let fileContents = "{!!$fileContentsBase64!!}";
            workbook = xlsx.read(fileContents, {type: "base64"});
            let first_sheet_name = workbook.SheetNames[0];
            createSheetTabs(workbook.SheetNames);
            let sheet = workbook.Sheets[first_sheet_name];
            let gridroot = document.getElementById('gridroot');
            gridroot.style.display = "block";
            grid = datagrid({
                parentNode: gridroot,
                editable: false,
                allowFreezingColumns: true,
                allowFreezingRows: true,
            });
            grid.style.height = '100%';
            grid.style.width = '100%';
            showSheet(sheet);
        });

        function base64ToArrayBuffer(base64) {
            let raw = window.atob(base64);
            let rawLength = raw.length;
            let array = new Uint8Array(new ArrayBuffer(rawLength));

            for (let i = 0; i < rawLength; i++) {
                array[i] = raw.charCodeAt(i);
            }
            return (array.buffer);
        }

        function createSheetTabs(sheets) {
            console.log(sheets);
            let sheetTabs = document.getElementById('sheet-tabs');

            for (let i = 0; i < sheets.length; i++) {
                let sheet = sheets[i];
                let active = i === 0 ? 'active' : '';
                sheetTabs.insertAdjacentHTML('beforeend', `
                    <li class="nav-item">
                        <a class="nav-link ${active} sheet outline-none text-decoration-none" href="#"
                            onclick="changeToSheet(${i})" id="${i}_sheet">${sheet}</a>
                    </li>`);
            }
        }

        function changeToSheet(index) {
            let sheetName = workbook.SheetNames[index];
            let sheet = workbook.Sheets[sheetName];
            showSheet(sheet);
            $('a.nav-link.active.sheet').removeClass("active");
            $(`#${index}_sheet`).addClass("active");
        }

        function showSheet(sheet) {
            grid.data = xlsx.utils.sheet_to_json(sheet, {raw: false, header: 1});
        }
    </script>
@endpush