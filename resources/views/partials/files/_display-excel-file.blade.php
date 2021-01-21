<ul class="nav nav-tabs" id="{{$file->uuid}}sheet-tabs">
</ul>
<div id="{{$file->uuid}}grid"></div>
@push('scripts')
    <script>

        $(document).ready(() => {
            let workbook, grid;

            let fileContents = "{!!$fileContentsBase64($file)!!}";
            workbook = xlsx.read(fileContents, {type: "base64"});
            let first_sheet_name = workbook.SheetNames[0];
            createSheetTabs(workbook.SheetNames);
            let sheet = workbook.Sheets[first_sheet_name];
            let gridroot = document.getElementById("{{$file->uuid}}grid");
            gridroot.style.display = "block";
            grid = datagrid({
                parentNode: gridroot,
                editable: false,
                allowFreezingColumns: true,
                allowFreezingRows: true,
                columnSelectorText: 'Show/hide columns',
            });
            grid.style.height = '100%';
            grid.style.width = '100%';
            showSheet(sheet);

            function createSheetTabs(sheets) {
                let sheetTabs = document.getElementById("{{$file->uuid}}sheet-tabs");

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

            window.changeToSheet = changeToSheet;

            function showSheet(sheet) {
                grid.data = xlsx.utils.sheet_to_json(sheet, {raw: false, header: 1});
            }
        });
    </script>
@endpush
