<div class="mt-2 ms-4 col-12">
    <form id="chart-controls-form">
        {{--        <div class="row" style="display: none">--}}
        {{--            <div class="form-group">--}}
        {{--                <label>Chart Type:</label>--}}
        {{--                <select wire:model.live="chartType" class="custom-select col-4 font-weight-bold"--}}
        {{--                        title="Select Chart Type">--}}
        {{--                    <option value="histogram">Histogram</option>--}}
        {{--                    <option value="line">Line</option>--}}
        {{--                    <option value="scatter">Scatter</option>--}}
        {{--                </select>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        <div class="row" id="x-attr-select">
            <div class="form-group col-6 col-lg-5">
                <label>Select X Attribute Type:</label>
                <select wire:model.live="xAttrType" class="custom-select col-4 font-weight-bold"
                        title="select X attribute type">
                    <option value="" disabled selected>Select X Attribute Type</option>
                    <option value="sample">Sample Attributes</option>
                    <option value="process">Process Attributes</option>
                </select>
            </div>

            @if($xAttrType == "sample")
                <div class="form-group col-6 col-lg-5">
                    <label class="ms-4">X:</label>
                    <select wire:model.live="xAttr" class="custom-select col-4 font-weight-bold"
                            title="x attribute">
                        <option value="" disabled selected>Select X Attribute</option>
                        @foreach($sampleAttributes as $attr)
                            <option value="{{$attr->name}}">{{$attr->name}}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($xAttrType == "process")
                <div class="form-group col-6 col-lg-5">
                    <label class="ms-4">X:</label>
                    <select wire:model.live="xAttr" class="custom-select col-4 font-weight-bold"
                            title="x attribute">
                        <option value="" disabled selected>Select X Attribute</option>
                        @foreach($processAttributes as $attr)
                            <option value="{{$attr->name}}">{{$attr->name}}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="row" id="y-attr-select">
            <div class="form-group col-6 col-lg-5">
                <label>Select Y Attribute Type: </label>
                <select wire:model.live="yAttrType" class="custom-select col-4 font-weight-bold"
                        title="select Y attribute type">
                    <option value="" disabled selected>Select Y Attribute Type</option>
                    <option value="sample">Sample Attributes</option>
                    <option value="process">Process Attributes</option>
                </select>
            </div>

            @if($yAttrType == "sample")
                <div class="form-group col-6 col-lg-5">
                    <label class="ms-4">Y:</label>
                    <select wire:model.live="yAttr" class="custom-select col-4 font-weight-bold"
                            title="y attribute">
                        <option value="" disabled selected>Select Y Attribute</option>
                        @foreach($sampleAttributes as $attr)
                            <option value="{{$attr->name}}">{{$attr->name}}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($yAttrType == "process")
                <div class="form-group col-6 col-lg-5">
                    <label class="ms-4">Y:</label>
                    <select wire:model.live="yAttr" class="custom-select col-4 font-weight-bold"
                            title="y attribute">
                        <option value="" disabled selected>Select Y Attribute</option>
                        @foreach($processAttributes as $attr)
                            <option value="{{$attr->name}}">{{$attr->name}}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="form-group">
                <a @class(["btn", "btn-success", "ms-4", "cursor-pointer", "disabled" => !$this->allAttrsSet()])
                   wire:click.prevent="addToChart()">Draw Chart</a>
            </div>
        </div>
    </form>
</div>
