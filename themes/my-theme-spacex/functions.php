<?php

function wpt_register_js() {
    wp_register_script('jquery.bootstrap.min', get_template_directory_uri() . '/js/bootstrap.min.js', 'jquery');
    wp_enqueue_script('jquery.bootstrap.min');
}
add_action( 'init', 'wpt_register_js' );
function wpt_register_css() {
    wp_register_style( 'bootstrap.min', get_template_directory_uri() . '/css/bootstrap.min.css' );
    wp_enqueue_style( 'bootstrap.min' );
}
add_action( 'wp_enqueue_scripts', 'wpt_register_css' );

register_nav_menus(array(
	'top'    => 'Верхнее меню',    //Название месторасположения меню в шаблоне
	'bottom' => 'Нижнее меню'      //Название другого месторасположения меню в шаблоне
));

/*
wp_nav_menu( array(
	'menu'            => '',              // (string) Название выводимого меню (указывается в админке при создании меню, приоритетнее 
										  // чем указанное местоположение theme_location - если указано, то параметр theme_location игнорируется)
	'container'       => 'div',           // (string) Контейнер меню. Обворачиватель ul. Указывается тег контейнера (по умолчанию в тег div)
	'container_class' => '',              // (string) class контейнера (div тега)
	'container_id'    => '',              // (string) id контейнера (div тега)
	'menu_class'      => 'menu',          // (string) class самого меню (ul тега)
	'menu_id'         => '',              // (string) id самого меню (ul тега)
	'echo'            => true,            // (boolean) Выводить на экран или возвращать для обработки
	'fallback_cb'     => 'wp_page_menu',  // (string) Используемая (резервная) функция, если меню не существует (не удалось получить)
	'before'          => '',              // (string) Текст перед <a> каждой ссылки
	'after'           => '',              // (string) Текст после </a> каждой ссылки
	'link_before'     => '',              // (string) Текст перед анкором (текстом) ссылки
	'link_after'      => '',              // (string) Текст после анкора (текста) ссылки
	'depth'           => 0,               // (integer) Глубина вложенности (0 - неограничена, 2 - двухуровневое меню)
	'walker'          => '',              // (object) Класс собирающий меню. Default: new Walker_Nav_Menu
	'theme_location'  => ''               // (string) Расположение меню в шаблоне. (указывается ключ которым было зарегистрировано меню в функции register_nav_menus)
) );
*/

function create_spacex_posttype() {
    $labels = array(
        'name' => _x( 'Запуски ракет', 'Тип записей Запуски ракет' ),
        'singular_name' => _x( 'Запуск ракеты', 'Тип Запуск ракеты' ),
        'menu_name' => __( 'Запуски ракет' ),
        'all_items' => __( 'Все Запуски ракет' ),
        'view_item' => __( 'Смотреть Запуск ракеты' ),
        'add_new_item' => __( 'Добавить Запуск ракеты' ),
        'add_new' => __( 'Добавить новый' ),
        'edit_item' => __( 'Редактировать Запуск ракеты' ),
        'update_item' => __( 'Обновить Запуск ракеты' ),
        'search_items' => __( 'Искать Запуск ракеты' ),
        'not_found' => __( 'Не найдено' ),
        'not_found_in_trash' => __( 'Не найдено в корзине' ),
    );

    $args = array(
        'label' => __( 'spacex' ),
        'description' => __( 'Каталог Запусков ракет' ),
        'labels' => $labels,
        'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        'has_archive' => true,
        'taxonomies' => array( 'your_taxonomy' ),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
    );

    register_post_type( 'spacex', $args );

}
add_action( 'init', 'create_spacex_posttype', 0 );

function add_spacex_to_query( $query ) {
    if ( is_home() && $query->is_main_query() )
        $query->set( 'post_type', array( 'post', 'spacex' ) );
    return $query;
}
add_action( 'pre_get_posts', 'add_spacex_to_query' );

class trueMetaBox {
	function __construct($options) {
		$this->options = $options;
		$this->prefix = $this->options['id'] .'_';
		add_action( 'add_meta_boxes', array( &$this, 'create' ) );
		add_action( 'save_post', array( &$this, 'save' ), 1, 2 );
	}
	function create() {
		foreach ($this->options['post'] as $post_type) {
			if (current_user_can( $this->options['cap'])) {
				add_meta_box($this->options['id'], $this->options['name'], array(&$this, 'fill'), $post_type, $this->options['pos'], $this->options['pri']);
			}
		}
	}
	function fill(){
		global $post; $p_i_d = $post->ID;
		wp_nonce_field( $this->options['id'], $this->options['id'].'_wpnonce', false, true );
		?>
		<table class="form-table"><tbody><?php
		foreach ( $this->options['args'] as $param ) {
			if (current_user_can( $param['cap'])) {
			?><tr><?php
				if(!$value = get_post_meta($post->ID, $this->prefix .$param['id'] , true)) $value = $param['std'];
				switch ( $param['type'] ) {
					case 'text':{ ?>
						<th scope="row"><label for="<?php echo $this->prefix .$param['id'] ?>"><?php echo $param['title'] ?></label></th>
						<td>
							<input name="<?php echo $this->prefix .$param['id'] ?>" type="<?php echo $param['type'] ?>" id="<?php echo $this->prefix .$param['id'] ?>" value="<?php echo $value ?>" placeholder="<?php echo $param['placeholder'] ?>" class="regular-text" /><br />
							<span class="description"><?php echo $param['desc'] ?></span>
						</td>
						<?php
						break;							
					}
					case 'textarea':{ ?>
						<th scope="row"><label for="<?php echo $this->prefix .$param['id'] ?>"><?php echo $param['title'] ?></label></th>
						<td>
							<textarea name="<?php echo $this->prefix .$param['id'] ?>" type="<?php echo $param['type'] ?>" id="<?php echo $this->prefix .$param['id'] ?>" value="<?php echo $value ?>" placeholder="<?php echo $param['placeholder'] ?>" class="large-text" /><?php echo $value ?></textarea><br />
							<span class="description"><?php echo $param['desc'] ?></span>
						</td>
						<?php
						break;							
					}
					case 'checkbox':{ ?>
						<th scope="row"><label for="<?php echo $this->prefix .$param['id'] ?>"><?php echo $param['title'] ?></label></th>
						<td>
							<label for="<?php echo $this->prefix .$param['id'] ?>"><input name="<?php echo $this->prefix .$param['id'] ?>" type="<?php echo $param['type'] ?>" id="<?php echo $this->prefix .$param['id'] ?>"<?php echo ($value=='on') ? ' checked="checked"' : '' ?> />
							<?php echo $param['desc'] ?></label>
						</td>
						<?php
						break;							
					}
					case 'select':{ ?>
						<th scope="row"><label for="<?php echo $this->prefix .$param['id'] ?>"><?php echo $param['title'] ?></label></th>
						<td>
							<label for="<?php echo $this->prefix .$param['id'] ?>">
							<select name="<?php echo $this->prefix .$param['id'] ?>" id="<?php echo $this->prefix .$param['id'] ?>"><option>...</option><?php
								foreach($param['args'] as $val=>$name){
									?><option value="<?php echo $val ?>"<?php echo ( $value == $val ) ? ' selected="selected"' : '' ?>><?php echo $name ?></option><?php
								}
							?></select></label><br />
							<span class="description"><?php echo $param['desc'] ?></span>
						</td>
						<?php
						break;							
					}
				} 
			?></tr><?php
			}
		}
		?></tbody></table><?php
	}
	function save($post_id, $post){
		if ( !wp_verify_nonce( $_POST[ $this->options['id'].'_wpnonce' ], $this->options['id'] ) ) return;
		if ( !current_user_can( 'edit_post', $post_id ) ) return;
		if ( !in_array($post->post_type, $this->options['post'])) return;
		foreach ( $this->options['args'] as $param ) {
			if ( current_user_can( $param['cap'] ) ) {
				if ( isset( $_POST[ $this->prefix . $param['id'] ] ) && trim( $_POST[ $this->prefix . $param['id'] ] ) ) {
					update_post_meta( $post_id, $this->prefix . $param['id'], trim($_POST[ $this->prefix . $param['id'] ]) );
				} else {
					delete_post_meta( $post_id, $this->prefix . $param['id'] );
				}
			}
		}
	}
}


$options = array(
	array( // первый метабокс
		'id'	=>	'meta1', // ID метабокса, а также префикс названия произвольного поля
		'name'	=>	'Информация о запуске', // заголовок метабокса
		'post'	=>	array('spacex'), // типы постов для которых нужно отобразить метабокс
		'pos'	=>	'normal', // расположение, параметр $context функции add_meta_box()
		'pri'	=>	'high', // приоритет, параметр $priority функции add_meta_box()
		'cap'	=>	'edit_posts', // какие права должны быть у пользователя
		'args'	=>	array(
			array(
					'id'			=>	'data', // атрибуты name и id без префикса, например с префиксом будет meta1_field_1
					'title'			=>	'Дата запуска', // лейбл поля
					'type'			=>	'text', // тип, в данном случае обычное текстовое поле
					//'placeholder'		=>	'', // атрибут placeholder
					//'desc'			=>	'пример использования текстового поля ввода в метабоксе', // что-то типа пояснения, подписи к полю
					'cap'			=>	'edit_posts'
				),
				
			array(
					'id'			=>	'picture_url', // атрибуты name и id без префикса, например с префиксом будет meta1_field_1
					'title'			=>	'Картинка', // лейбл поля
					'type'			=>	'text', // тип, в данном случае обычное текстовое поле
					//'placeholder'		=>	'Имя Фамилия', // атрибут placeholder
					//'desc'			=>	'пример использования текстового поля ввода в метабоксе', // что-то типа пояснения, подписи к полю
					'cap'			=>	'edit_posts'
				),	
			
			array(
				'id'			=>	'video_url',
				'title'			=>	'Видео',
				'type'			=>	'text', // выпадающий список
				//'desc'			=>	'тут тоже можно написать пояснение к полю, значения же задаются через ассоциативный массив',
				'cap'			=>	'edit_posts'
				//'args'			=>	array('Детектив' => 'Детектив', 'Фантастика' => 'Фантастика', 'Ужасы' => 'Ужасы' ) // элементы списка задаются через массив args, по типу value=>лейбл
			)
			
		)
	),
	array(
		'id'	=>	'meta2', // ID метабокса, а также префикс названия произвольного поля
		'name'	=>	'Информация о ракете', // заголовок метабокса
		'post'	=>	array('spacex'), // типы постов для которых нужно отобразить метабокс
		'pos'	=>	'normal', // расположение, параметр $context функции add_meta_box()
		'pri'	=>	'high', // приоритет, параметр $priority функции add_meta_box()
		'cap'	=>	'edit_posts', // какие права должны быть у пользователя
		'args'	=>	array(
			array(
					'id'			=>	'rocet', // атрибуты name и id без префикса, например с префиксом будет meta1_field_1
					'title'			=>	'Ракета', // лейбл поля
					'type'			=>	'text', // тип, в данном случае обычное текстовое поле
					//'placeholder'		=>	'', // атрибут placeholder
					//'desc'			=>	'пример использования текстового поля ввода в метабоксе', // что-то типа пояснения, подписи к полю
					'cap'			=>	'edit_posts'
				)
			
		)
	)
);
 
foreach ($options as $option) {
	$truemetabox = new trueMetaBox($option);
}

