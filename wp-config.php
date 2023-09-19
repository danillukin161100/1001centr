<?php

/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки базы данных
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры базы данных: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', '1001center'); // l92235wp_1001cen

/** Имя пользователя базы данных */
define('DB_USER', 'root');

/** Пароль к базе данных */
define('DB_PASSWORD', ''); // 21kx6K&N

/** Имя сервера базы данных */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'T{/(Vcbo!I{r4A6R[;zB#8ME6Ntz2<,.zkw)> >HHAWlbzKP:*FK>(|/j|LHv%Nk');
define('SECURE_AUTH_KEY',  '1!x1/% m$BsnMwcB_@R+6*N;U3iJE+}(&Fi r}KpY^`~T.Y|Tn$~~/]1XE0NjF|H');
define('LOGGED_IN_KEY',    'fy$7w$W)&%8hFU=v:a2 )^->;Vo=pQ~|!Zl`I![6kxSF+_sp_Q7-c}_1j=P8biID');
define('NONCE_KEY',        '){gr<%u%a6[NlD{R7Ck[X`1m|W?uo91&Xp1*lM59^I4/iVIgRe;|)#<PQV*Szdu/');
define('AUTH_SALT',        '>P6+jxgV4]p,flxDZaBqnA~-U$Bro3AD2kDn^D?l,&%>@$SKJ)i[%;.caVVkr{z(');
define('SECURE_AUTH_SALT', 'PXp]l&$c$Gm<k%<*~|3r5S}# Bhc.zn56)Wmk0Xx`B!+!b1e^?5frgYR:6g ~VPd');
define('LOGGED_IN_SALT',   'Xqsl#wAN<^CLz,4Uy4rH4X=|{zRoYd-eXQsqhb&hQPH[{yGj;B7:FMFz~diB@Kp>');
define('NONCE_SALT',       'mm9 ePdWYXF!H;P+m7Z0AKcrQ8ay(ceX#4z_UCg,O<6l&4L`r.r-#bAKA`Bq+9-w');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);

/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
