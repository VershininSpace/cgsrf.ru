<?php namespace Albus\Reviews\Controllers;

use App;
use BackendMenu;
use Carbon\Carbon;
use Backend\Classes\Controller;
use Albus\Reviews\Models\Review;

class Reviews extends Controller
{
    public $bodyClass = 'compact-container';

    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'full' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Albus.Reviews', 'reviews');
    }

    public function getRecordsStats( $part ){
        switch( $part ){

            case 'all_count':
              return Review::count();
            break;

            case 'read_count':
                return Review::isRead()->count();
            break;
  
            case 'new_count':
                return Review::isNew()->count();
            break;

            case 'latest_message_date':

                $data = Review::orderBy( 'created_at', 'DESC' )->first();

                if ( !empty( $data->created_at ) ) {
                    Carbon::setLocale( App::getLocale() );
                    return Carbon::createFromFormat( 'Y-m-d H:i:s', $data->created_at )->diffForHumans();
                }

              return NULL;
            break;

            case 'latest_message_name':
                $data = Review::orderBy( 'created_at', 'DESC' )->first();

                if( !empty( $data->name ) ) {
                 return $data->name;
                }

                return NULL;
            break;

            default:
                return NULL;
            break;

        }

  }



    public function listOverrideColumnValue($record, $columnName, $definition = null)
    {
        if ($columnName == 'rating') {
            $starsCount = 5;
            $rating = intval($record->rating);

            return
                '<span style="display:inline-block;min-width:62px;">' .
                str_repeat('<i class="icon-star"></i>', $rating) .
                str_repeat('<i class="icon-star-o"></i>', $starsCount - $rating) .
                '</span>';
        }
    }
    public function update($recordId)
    {
        $record = Review::find($recordId);
        $record->unread = false;
        $record->save();
        $this->vars['record'] = $record;
        return $this->asExtension('FormController')->update($recordId);
    }
}
