<?php

/**
 * Plugin Name: Ciba Forms
 * Description: Плагин для отправки форм с классом ciba_form в CRM
 * Version: 1.0
 * Author: LD
 */

add_action('admin_menu', function () {
	add_submenu_page(
		'tools.php', // Инструменты
		'Ciba Forms', // тайтл страницы
		'Ciba Forms', // текст ссылки в меню
		'manage_options', // права пользователя, необходимые для доступа к странице
		'ciba-forms', // ярлык страницы
		'cibaForms_callback' // функция, которая выводит содержимое страницы
	);
}, 25);

add_action('wp_enqueue_scripts', function () {
	wp_enqueue_script('ciba-forms-script', plugin_dir_url(__FILE__) . 'js/script.js', array(), '1.0', true);
});

function cibaForms_callback()
{
	global $wpdb; // Глобальный объект базы данных WordPress

	// Создаем таблицу, если она не существует
	$table_name = $wpdb->prefix . 'ciba_forms_options';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		api_key varchar(255) DEFAULT '' NULL,
		source_id varchar(255) DEFAULT '' NULL,
		mango_name varchar(255) DEFAULT '' NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	// Проверяем, если форма отправлена
	if (isset($_POST['save_settings'])) {
		// Сохраняем значение поля в базу данных
		$id = sanitize_text_field($_POST['id']);
		$api_key = sanitize_text_field($_POST['api_key']);
		$source_id = sanitize_text_field($_POST['source_id']);
		$mango_name = sanitize_text_field($_POST['mango_name']);
		
		$wpdb->replace($table_name, array(
			'id' => (!empty($id)) ? $id : null,
			'api_key' => $api_key,
			'source_id' => $source_id,
			'mango_name' => $mango_name,
		));
		echo '<div class="updated"><p>Настройки сохранены!</p></div>';
	}

	// Получаем текущее значение поля из базы данных
	$result = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC LIMIT 1");

	$id         = isset($result[0]->id)    ? $result[0]->id    : '';
	$api_key    = isset($result[0]->api_key)    ? $result[0]->api_key    : '';
	$source_id  = isset($result[0]->source_id)  ? $result[0]->source_id  : '';
	$mango_name = isset($result[0]->mango_name) ? $result[0]->mango_name : '';

	// Выводим форму на странице
?>
	<div class="wrap">
		<h2>Настройки Ciba Forms</h2>
		<div class="form-wrap">
			<form method="post" action="" class="validate">
				<input type="hidden" name="id" value="<?= esc_attr($id); ?>">
				<div class="form-field">
					<label for="api_key">Ключ API:</label>
					<input type="text" id="api_key" name="api_key" value="<?= esc_attr($api_key); ?>" class="regular-text">
				</div>
				<div class="form-field">
					<label for="source_id">Источник:</label>
					<input type="text" id="source_id" name="source_id" value="<?= esc_attr($source_id); ?>" class="regular-text">
				</div>
				<div class="form-field">
					<label for="mango_name">Mango:</label>
					<input type="text" id="mango_name" name="mango_name" value="<?= esc_attr($mango_name); ?>" class="regular-text">
				</div>
				<div class="form-field">
					<input type="submit" name="save_settings" class="button button-primary" value="Сохранить">
				</div>
			</form>
		</div>
	</div>
<?php
}
