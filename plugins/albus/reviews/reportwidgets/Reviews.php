<?php namespace Albus\Reviews\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Albus\Reviews\Controllers\Reviews as ReviewsController;

/**
 * Contact form sent messages report widget
 */
class Messages extends ReportWidgetBase
{

    public function render()
    {
        return $this->makePartial('reviews');
    }

    public function getRecordsStats($value){

        $controller = new ReviewsController;

        return $controller->getRecordsStats($value);

    }

}