<div class="mt-2 ml-4 col-12">
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
            <div class="form-group">
                <label>Select X Attribute Type:</label>
                <select wire:model.live="xAttrType" class="custom-select col-4 font-weight-bold"
                        title="select X attribute type">
                    <option value="sample">Sample Attributes</option>
                    <option value="process">Process Attributes</option>
                </select>
            </div>
            @if($xAttrType == "sample")
                <div>
                    <div class="form-group">
                        <label class="ml-4">X:</label>
                        <select wire:model.live="xAttr" class="custom-select col-4 font-weight-bold"
                                title="x attribute">
                            @foreach($sampleAttributes as $attr)
                                <option value="{{$attr->name}}">{{$attr->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if($xAttrType == "process")
                <div id="show-x-process-attrs" style="display: none">
                    <div class="form-group">
                        <label class="ml-4">X:</label>
                        <select wire:model.live="xAttr" class="custom-select col-4 font-weight-bold"
                                title="x attribute">
                            @foreach($processAttributes as $attr)
                                <option value="{{$attr->name}}">{{$attr->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        </div>

        <div class="row" id="y-attr-select">
            <div class="form-group">
                <label>Select Y Attribute Type: </label>
                <select wire:model.live="yAttrType" class="custom-select col-4 font-weight-bold"
                        title="select Y attribute type">
                    <option value="sample">Sample Attributes</option>
                    <option value="process">Process Attributes</option>
                </select>
            </div>

            @if($yAttrType == "sample")
                <div id="show-y-sample-attrs">
                    <div class="form-group">
                        <label class="ml-4">Y:</label>
                        <select wire:model.live="yAttr" class="custom-select col-4 font-weight-bold"
                                title="y attribute">
                            @foreach($sampleAttributes as $attr)
                                <option value="{{$attr->name}}">{{$attr->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if($yAttrType == "process")
                <div id="show-y-process-attrs" class="col-8">
                    <div class="form-group">
                        <label class="ml-4">Y:</label>
                        <select wire:model.live="yAttr" class="custom-select col-4 font-weight-bold"
                                title="y attribute">
                            @foreach($processAttributes as $attr)
                                <option value="{{$attr->name}}">{{$attr->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="form-group">
                <a class="btn btn-success ml-4 cursor-pointer disabled"
                   wire:click.prevent="addToChart()"
                   roll="button" aria-disabled="true">Add To Chart</a>
            </div>
        </div>
    </form>
</div>
