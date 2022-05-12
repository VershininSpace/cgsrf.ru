<?php namespace Chronos\App\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Transactions extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController'    ];
    
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Chronos.App', 'main-menu-reqm', 'side-menu-item');
    }
}
