<?php namespace Albus\Reviews;

use Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Albus\Reviews\Components\ReviewsList' => 'ReviewsList',
            'Albus\Reviews\Components\LeaveRreview' => 'LeaveRreview',
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'Albus\Reviews\FormWidgets\StarRating' => 'starrating',
        ];
    }

    public function registerNavigation()
    {
        return [
            'files' => [
                'label'       => 'Отзывы',
                'url'         => Backend::url('albus/reviews/reviews'),
                'icon'        => 'icon-star',
                'iconSvg'     => 'plugins/albus/reviews/assets/images/reviews.svg',
                'permissions' => ['albus.reviews.*'],
                'order'       => 500,
            ],
        ];
    }

    public function registerReportWidgets()
    {
        return [
            'Albus\Reviews\ReportWidgets\Reviews' => [
                'label'   => 'Статистика отзывов',
                'context' => 'dashboard'
            ],
        ];
    }

}
