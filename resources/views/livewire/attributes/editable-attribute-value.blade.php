<div class="attribute-row row col-11 ml-1 editable-item">
    <div class="col-7">{{$attribute->name}}:</div>
    @if($isEditing)
        <div class="col-5">
            <form class="d-flex justify-content-between align-items-center" wire:submit.prevent="save">
                <input class="form-control"
                       wire:model="value"
                       wire:keydown.escape="$set('isEditing', false)"/>
                <i class="fas fa-download edit-icon text-muted ml-2" style="cursor: pointer;" wire:click="save"></i>
            </form>
        </div>
    @else
        <div class="col-5 d-flex justify-content-between align-items-center">
                    <span class="attribute-value">
                        @if(is_array($attribute->values[0]->val["value"]))
                            @json($attribute->values[0]->val["value"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                        @else
                            @if(blank($attribute->values[0]->val["value"]))
                                No value
                            @else
                                {{$attribute->values[0]->val["value"]}}
                            @endif
                        @endif
                        @if($attribute->values[0]->unit != "")
                            {{$attribute->values[0]->unit}}
                        @endif
                    </span>
            @if($user->hasGoogleToken() && !blank($googleSheetId))
                <i class="fas fa-edit edit-icon text-muted ml-2" style="cursor: pointer; opacity: 0.6;"
                   wire:click="edit"></i>
            @endif
        </div>
        @if($updateFailure)
            <div class="col-12 mt-2">
                <div class="alert alert-warning alert-dismissible d-flex justify-content-between align-items-center"
                     role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>Failed to update Google Sheets. The value was not saved.</span>
                    </div>
                    <button type="button"
                            class="btn-close"
                            aria-label="Close"
                            wire:click="dismissFailureBanner"
                            style="cursor: pointer; background: none; border: none; font-size: 1.2em; opacity: 0.7;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif
        @if ($updatingFormula)
            <div class="col-12 mt-2">
                <div class="alert alert-info alert-dismissible d-flex justify-content-between align-items-center"
                     role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>Cannot change value of cells that contain a formula.</span>
                    </div>
                    <button type="button"
                            class="btn-close"
                            aria-label="Close"
                            wire:click="dismissFormulaBanner"
                            style="cursor: pointer; background: none; border: none; font-size: 1.2em; opacity: 0.7;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif
    @endif
</div>
