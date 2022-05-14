<?php return [
    'plugin' => [
        'name' => 'Reviews',
        'description' => 'Плагин для ведения отзывов',
    ],
    'Модерация отзывов' => 'full',


    'leave_review_component' => [
        'info' => [
        	'name' 			=> 'Оставить отзыв',
        	'description' 	=> 'Форма для отправки отзыва'
        ],

        'success' => [
        	'title'			=> 'Сообщение об отправке',
        	'description'	=> 'Текст cообщения при успешной отправке отзыва',
        	'default'		=> 'Спасибо! Отзыв успешно отправлен'
        ],

        'error'	=> [
        	'title'			=> 'Сообщение об ошибке',
        	'description'	=> 'Текст cообщения с ошибкой отправки отзыва',
        	'default'		=> 'Отзыв не был отправлен'
        ],
        'addstyles'	=> [
        	'title'			=> 'Подключить стили',
        	'description'	=> 'Для подключения необходимо в шаблон доавить тег {% styles %}'
        ],

		'label_group'		=> 'Насройки надписей',
		'hidelabel'			 => [
            'title'     	 => 'Спрятать надпись',
            'description'    => 'Убрать Label и вывести placeholder'
        ],
		'name_label'		=> [
        	'title'			=> 'Поле "Имя"',
        	'description'	=> 'Текст названия поля ввода Имени',
        	'default'		=> 'Имя'
        ],
		'theme_label'		=> [
        	'title'			=> 'Поле "Тема"',
        	'description'	=> 'Текст названия поля ввода темы отзыва',
        	'default'		=> 'Тема'
        ],
		'text_label'		=> [
        	'title'			=> 'Поле "Текст"',
        	'description'	=> 'Текст названия поля ввода текста отзыва',
        	'default'		=> 'Текст отзыва'
        ],

		'button_group'		=> 'Насройки кнопки',
		'button_classes'	=> [
        	'title'			=> 'Классы кнопки',
        	'description'	=> 'Вставьте сюда свои классы кнопки отправить',
        	'default'		=> 'submit'
        ],
		'button_text'		=> [
        	'title'			=> 'Текст кнопки отправить',
        	'description'	=> 'Текст кнопки. Например "Отпрввить" или "Поделиться" ',
        	'default'		=> 'Отправить'
        ]
    ],

];