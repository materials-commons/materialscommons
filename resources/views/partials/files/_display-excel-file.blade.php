<div id="gridctr"></div>
@push('scripts')
    <script>
        $(document).ready(() => {


                    {{--let oReq = new XMLHttpRequest();--}}
                    {{--oReq.open("GET", "{{$downloadRoute}}", true);--}}
                    {{--oReq.responseType = "arraybuffer";--}}

                    {{--oReq.onload = function (e) {--}}
                    {{--    let arraybuffer = oReq.response;--}}
                    {{--    /* convert data to binary string */--}}
                    {{--    let data = new Uint8Array(arraybuffer);--}}
                    {{--    let arr = new Array();--}}
                    {{--    for (let i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);--}}
                    {{--    let bstr = arr.join("");--}}
                    {{--    /* Call XLSX */--}}
                    {{--    let workbook = XLSX.read(bstr, {type: "binary"});--}}
                    {{--    let first_sheet_name = workbook.SheetNames[0];--}}
                    {{--    let sheet = workbook.Sheets[first_sheet_name];--}}
                    {{--    grid.data = xlsx.utils.sheet_to_json(sheet);--}}
                    {{--};--}}

                    {{--oReq.send();--}}
            let fileContents = "{!!$fileContentsBase64!!}";
            // console.log('past base64ToArrayBuffer', fileContents);
            let workbook = xlsx.read(fileContents, {type: "base64"});
            console.log('past xlsx.read');
            let first_sheet_name = workbook.SheetNames[2];
            console.log('first_sheet_name', first_sheet_name);
            let sheet = workbook.Sheets[first_sheet_name];
            console.log(sheet);
            // xlsx.sheet_to_json(sheet);
            let j = xlsx.utils.sheet_to_json(sheet, {raw: false, header: 1});
            // let j = XLSX.utils.sheet_to_json(sheet);
            console.log(j);
            // grid.data = j;
            // grid.data = xlsx.utils.sheet_to_json(sheet);
            let gridctr = document.getElementById('gridctr');
            gridctr.style.display = "block";
            // let grid = canvasDatagrid({
            let grid = datagrid({
                parentNode: document.getElementById('gridctr'),
                editable: false,
            });
            grid.style.height = '100%';
            grid.style.width = '100%';
            // grid.data = [
            //     {"col1": "row 1 column 1", "col2": "row 1 column 2", "col3": "row 1 column 3"},
            //     {"col1": "row 2 column 1", "col2": "row 2 column 2", "col3": "row 2 column 3"}
            // ];
            grid.data = j;
            console.log('assigned data to grid');
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
    </script>
@endpush