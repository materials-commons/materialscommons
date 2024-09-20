<div>
    <div class="row">
        <div class="col-8">
            <div class="row">
                <div class="col-4">
                    @if($details->isNumeric)
                        <x-datahq.show-numeric-attribute-details :details="$details"/>
                    @else
                        <x-datahq.show-string-attribute-details :details="$details"/>
                    @endif
                </div>
                <div class="col-8">
                    @if($details->isNumeric)
                        <x-datahq.show-numeric-attribute-filters :project="$project"
                                                                 :attr-name="$attrName"
                                                                 :attr-type="$attrType"
                                                                 :attr-details="$details"/>
                    @else
                        <x-datahq.show-string-attribute-filters :project="$project"
                                                                :attr-name="$attrName"
                                                                :attr-type="$attrType"
                                                                :attr-details="$details"/>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-4 border">
            <div style="height: 300px" id="chart-{{$attrName}}-{{$attrType}}" data-chart-values='{{$jsonData}}'></div>
        </div>
    </div>
</div>