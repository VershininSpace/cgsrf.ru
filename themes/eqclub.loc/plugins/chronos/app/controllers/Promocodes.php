<?php namespace Chronos\App\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Chronos\App\Models\Product;
use Chronos\App\Models\ProductUser;
use Illuminate\Support\Facades\Input;
use Chronos\App\Components\CommerceComponent;
use Carbon\Carbon;

class Promocodes extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Chronos.App', 'main-menu-item2');
    }

    public function money()
    {
        $products = Product::all();

        return $this->makePartial('money', ['products' => $products]);
    }

    public function save_money()
    {

        $cc = new CommerceComponent();

        $user_id = Input::get('user_id');
        $product_id = Input::get('product_id');
        $summ = Input::get('summ');
        $key = Input::get('key');
        $cc->babloToNetwork($user_id, $product_id, 'product', $summ);
        $product_user = new ProductUser();
        $product_user->product_id = $product_id;
        $product_user->user_id = $user_id;
        $product_user->status = 'PAID';
        $product_user->payment_at = Carbon::now();
        $product_user->paid = 1;
        $product_user->price = $summ;
        $product_user->accepted = 1;
        $product_user->save();
        return $this->makePartial('success');
    }
}
