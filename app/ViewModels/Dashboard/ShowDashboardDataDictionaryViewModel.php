<?php

namespace App\ViewModels\Dashboard;

use App\ViewModels\DataDictionary\AbstractShowDataDictionaryViewModel;
use App\ViewModels\DataDictionary\AttributeStatistics;

class ShowDashboardDataDictionaryViewModel extends AbstractShowDataDictionaryViewModel
{
    use AttributeStatistics;

    public function __construct()
    {
        //
    }

    public function activityAttributeRoute($attrName)
    {
        return "";
    }

    public function entityAttributeRoute($attrName)
    {
        return "";
    }
}
