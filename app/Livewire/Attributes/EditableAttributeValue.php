<?php

namespace App\Livewire\Attributes;

use App\Services\GoogleSheetsService;
use App\Traits\GoogleSheets;
use Livewire\Component;

class EditableAttributeValue extends Component
{
    use GoogleSheets;

    public $attribute;
    public $isEditing = false;
    public $value;
    public $activity;
    public $experiment;
    public $user;
    public $googleSheetId = null;
    public $updateFailure = false;
    public $updatingFormula = false;

    public function mount($attribute, $activity, $experiment, $user)
    {
        $this->attribute = $attribute;
        $this->activity = $activity;
        $this->experiment = $experiment;
        $this->user = $user;
        $this->value = $attribute->values[0]->val["value"];
        $this->googleSheetId = $this->getGoogleSheetId();
    }

    public function edit()
    {
        $this->isEditing = true;
    }

    public function dismissFailureBanner()
    {
        $this->updateFailure = false;
    }

    public function dismissFormulaBanner()
    {
        $this->updatingFormula = false;
    }

    public function save()
    {
        // Validate if needed
        $this->validate([
            'value' => 'required'
        ]);

        // Update the attribute in google sheets
        $googleSheetsService = app(GoogleSheetsService::class);

        if ($this->isFormula()) {
            $this->updatingFormula = true;
            $this->isEditing = false;
            return;
        }

        $range = "{$this->activity->name}!{$this->attribute->cell_coordinates}";

        $success = $googleSheetsService->updateValues(auth()->user(), $this->googleSheetId, $range, [[$this->value]]);

        if (!$success) {
            // Failed to update the sheet, so don't update the database entry
            $this->updateFailure = true;
            $this->isEditing = false;
            return;
        }

        // Update the attribute value in the database
        $this->attribute->values[0]->update(['val' => ['value' => $this->value]]);

        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.attributes.editable-attribute-value');
    }

    private function getGoogleSheetId()
    {
        if (is_null($this->experiment->sheet)) {
            return null;
        }

        return $this->getGoogleSheetsId($this->experiment->sheet->url);
    }

    private function isFormula(): bool
    {
        if ($this->googleSheetId) {
            $googleSheetsService = app(GoogleSheetsService::class);
            $range = "{$this->activity->name}!{$this->attribute->cell_coordinates}";

            $cellInfo = $googleSheetsService->getCellInfo(auth()->user(), $this->googleSheetId, $range);

            if ($cellInfo) {
                return $cellInfo['isFormula'];
            }
        }

        return false;
    }
}

