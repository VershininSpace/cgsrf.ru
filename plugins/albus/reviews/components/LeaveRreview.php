<?php namespace Albus\Reviews\Components;

use Lang;
use Input;
use Flash;
use Redirect;
Use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Albus\Reviews\Models\Review;

/**
 * LeaveRreview Component
 */
class LeaveRreview extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Форма отправки отзыва',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        {
            return [
                'SuccessSend' => [
                    'title'         => 'albus.reviews::lang.leave_review_component.success.title',
                    'description'   => 'albus.reviews::lang.leave_review_component.success.description',
                    'type'          => 'string',
                    'default'       => Lang::get('albus.reviews::lang.leave_review_component.success.default')
                ],
                'ErrorSend' => [
                    'title'         => 'albus.reviews::lang.leave_review_component.error.title',
                    'description'   => 'albus.reviews::lang.leave_review_component.error.description',
                    'type'          => 'string',
                    'default'       => Lang::get('albus.reviews::lang.leave_review_component.error.default')
                ],
                'addstyles' => [
                    'title'         => 'albus.reviews::lang.leave_review_component.addstyles.title',
                    'description'   => 'albus.reviews::lang.leave_review_component.addstyles.description',
                    'type'          => 'checkbox',
                    'default'       => 1
                ],
                'FormClasses' => [
                    'title'         => 'Классы формы',
                    'description'   => 'Дополнительные классы для оформления формы',
                    'type'          => 'string',
                ],
                // Настройка надписей
                'HideLabel' => [
                    'title'         => 'albus.reviews::lang.leave_review_component.hidelabel.title',
                    'description'   => 'albus.reviews::lang.leave_review_component.hidelabel.description',
                    'type'          => 'checkbox',
                    'default'      => 0,
                    'group'         => 'albus.reviews::lang.leave_review_component.label_group',
                ],
                'NameLabel' => [
                    'title'         => 'albus.reviews::lang.leave_review_component.name_label.title',
                    'description'   => 'albus.reviews::lang.leave_review_component.name_label.description',
                    'type'          => 'string',
                    'default'       => Lang::get('albus.reviews::lang.leave_review_component.name_label.default'),
                    'group'         => 'albus.reviews::lang.leave_review_component.label_group',
                ],
                'PhoneLabel' => [
                    'title'         => 'Телефон',
                    'type'          => 'string',
                    'default'       => 'Телефон',
                    'group'         => 'albus.reviews::lang.leave_review_component.label_group',
                ],
                'EmailLabel' => [
                    'title'         => 'Email',
                    'type'          => 'string',
                    'default'       => 'Email   ',
                    'group'         => 'albus.reviews::lang.leave_review_component.label_group',
                ],
                'ThemeLabel' => [
                    'title'         => 'albus.reviews::lang.leave_review_component.theme_label.title',
                    'description'   => 'albus.reviews::lang.leave_review_component.theme_label.description',
                    'type'          => 'string',
                    'default'       => Lang::get('albus.reviews::lang.leave_review_component.theme_label.default'),
                    'group'         => 'albus.reviews::lang.leave_review_component.label_group',
                ],
                'TextLabel' => [
                    'title'         => 'albus.reviews::lang.leave_review_component.text_label.title',
                    'description'   => 'albus.reviews::lang.leave_review_component.text_label.description',
                    'type'          => 'string',
                    'default'       => Lang::get('albus.reviews::lang.leave_review_component.text_label.default'),
                    'group'         => 'albus.reviews::lang.leave_review_component.label_group',
                ],
                'RatingLabel' => [
                    'title'         => 'Рейтинг',
                    'type'          => 'string',
                    'default'       => 'Поставьте вашу оценку',
                    'group'         => 'albus.reviews::lang.leave_review_component.label_group',
                ],
                // Настройка кнопки отправить
                'ButtonClasses' => [
                    'title'         => 'albus.reviews::lang.leave_review_component.button_classes.title',
                    'description'   => 'albus.reviews::lang.leave_review_component.button_classes.description',
                    'type'          => 'string',
                    'default'       => Lang::get('albus.reviews::lang.leave_review_component.button_classes.default'),
                    'group'         => 'albus.reviews::lang.leave_review_component.button_group',
                ],
                'ButtonText' => [
                    'title'         => 'albus.reviews::lang.leave_review_component.button_text.title',
                    'description'   => 'albus.reviews::lang.leave_review_component.button_text.description',
                    'type'          => 'string',
                    'default'       => Lang::get('albus.reviews::lang.leave_review_component.button_text.default'),
                    'group'         => 'albus.reviews::lang.leave_review_component.button_group',
                ],
            ];
        }
    }

    public function onSaveReview()
    {
        $date = Carbon::now();
        $Review = new Review();
        $Review->name = Input::get('name');
        // $Review->email = Input::get('email');
        $Review->phone = Input::get('phone');
        $Review->content = Input::get('content');
        $Review->rating = Input::get('rating');
        $Review->published = false;

        $Review->unread = true;

        $Review->save();
    }

}
