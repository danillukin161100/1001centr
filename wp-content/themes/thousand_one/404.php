<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package thousand_one
 */

get_header();
?>

<section class="not_found">
	<div class="not_found__container">
		<div class="not_found-inner">
			<!-- /.not_found-inner -->
			<img class="not_found__img" src="<?= get_template_directory_uri() ?>/images/content/main.svg" alt="">
			<h1 class="not_found__title">Ошибка 404</h1>
			<p class="content__subtitle not_found__subtitle">Страница, которую вы запрашиваете, не существует.</p>
			<a href="/" class="not_found__back pb">Вернуться на главную</a>
			<!-- /.not_found__back -->
		</div>
	</div>
</section>

<?php get_footer() ?>