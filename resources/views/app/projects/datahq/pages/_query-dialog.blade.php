<div class="modal fade" tabindex="-1" id="query-dialog" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title help-color">Select/Query on Attribute HV</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="help-color">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-6">
                            <h4>Select</h4>
                            <div class="row ml-5">
                                <input type="checkbox" class="mr-2"> Add to select statement
                            </div>
                            <br>
                            <h4>Where</h4>
                            <div class="row ml-5">
                                <label>HV</label>
                                <div class="row ml-1">
                                    <select id="select-1"
                                            class="selectpicker col-6">
                                        <option>Select</option>
                                        <option value="=">=</option>
                                        <option value=">">></option>
                                        <option value=">=">>=</option>
                                        <option value="<"><</option>
                                        <option value="<="><=</option>
                                        <option value="<>">not-equal</option>
                                    </select>
                                    <input type="text" placeholder="Value..." class="col-4">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4>Overview</h4>
                            <div class="row ml-1">
                                Samples
                                <div class="row">
                                    <ul>
                                        <li>
                                            <a href="#">S1</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row ml-1">
                                Numeric Attribute -
                            </div>
                            <div class="row">
                                <ul>
                                    <li>Min: 75.9</li>
                                    <li>Max: 92.5</li>
                                </ul>
                            </div>
                            <div class="row ml-1">
                                Show 3 of 3 unique values -
                            </div>
                            <div class="row">
                                <ul>
                                    <li>75.9</li>
                                    <li>76.7</li>
                                    <li>92.5</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" data-dismiss="modal">Add To Query</button>
            </div>
        </div>
    </div>
</div>