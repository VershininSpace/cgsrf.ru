<?php namespace Albus\Reviews\Components;

use Cms\Classes\ComponentBase;
use Albus\Reviews\Models\Review;
/**
 * ReviewsList Component
 */
class ReviewsList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Список отзывов',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'items' => [
                'title'       => 'Количество',
                'description' => 'Определяет количество предметов на одной странице',
                'default'     => '10',
            ],
            'pageNumber' => [
                'title'       => 'Page Number',
                'description' => 'Description',
                'type'        => 'string',
                'default'     => '{{ :page }}',
            ],
            'SortOrder' => [
                'title'         => 'Сортировка',
                'description'   => 'Настройка сортировки',
                'type'          => 'dropdown',
                'default'       => 'date',
            ],
        ];
    }

    public function getSortOrderOptions(){
        return [
            'date'   => 'Сначала новые',
            'custom' => 'Свой порядок',
        ];
    }

    public function scopeListFrontEnd($query, $options = []) 
    {
        extract(array_merge([
            'page'    => 1,
            'perPage' => 10,
        ], $options));

        return $query->paginate($perPage, $page);
    }


    public function onRun()
    {
        if ($this->property('SortOrder') == 'date') {
            $this->page['reviews'] = Review::where('published', true)->orderBy('published_at', 'DESC')->paginate($this->property('items'));
        } else {
            $this->page['reviews'] = Review::where('published', true)->paginate($this->property('items'));
        } 
    }

    public function loadItems()
    {
        $items = MyModel::listFrontEnd([
            'page'    => $this->property('pageNumber'),
            'perPage' => 10,
        ]);

        return $items;
    }

    public function onLoadMore()
    {
        // получаем номер страницы
        $pageNumber = Input::get('page') + 1;

        // выставляем номер страницы и готовим данные
        $this->setProperty('pageNumber', $pageNumber);
        $this->onRun();

        if ($pageNumber < $this->items->lastPage()) {
            $more_link = $this->renderPartial('@_morelink.htm', ['pageNumber' => $pageNumber]);
        } else {
            $more_link = ''; // если мы достигли последней страницы, кнопка больше не нужна
        }

        return [
            // если перед селектором стоит @, новое содержимое будет добавляться
            // в конец, а не заменять старое
            '@#list'            => $this->renderPartial('@_list.htm'),
            '#load-more-button' => $more_link,
        ];
    }

}
