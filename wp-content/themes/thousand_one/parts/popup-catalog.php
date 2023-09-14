<?php
$categories = get_terms([
	'taxonomy' => 'categories',
	// 'hide_empty' => false, /* показывать путсые категории */
]);

if (empty($categories)) {
	return false;
}
?>
<div class="_overlay-bg modal catalog" data-popup="catalog">
	<div class="catalog__container">
		<div class="catalog-wrapper svg">
			<p class="catalog__title">Каталог техники</p>
			<div class="catalog-inner">
				<div class="catalog-type">
					<?php foreach ($categories as $cat) { ?>
						<a href="<?= getCategoriesPermalink($cat) ?>" class="catalog-type__item">
							<div class="catalog-type__item-svgBx">
								<?= kama_thumb_img('w=34&h=34&crop=0', getCategoryImageName($cat->term_id)) ?>
							</div>
							<p class="catalog-type__item-title"><?= $cat->name ?></p>
						</a>
					<?php } ?>
				</div>

				<?php /* Подкатегории
				<!-- /.catalog-type -->
				<div class="catalog-helper">
					<div class="catalog-technics">
						<a href="#" class="catalog-technics__item">
							<div class="catalog-technics__item-svgBx"><img src="<?= get_template_directory_uri() ?>/images/catalog/01.svg" alt=""></div>
							<p class="catalog-technics__item-title">Компьютеры</p>
						</a>
						<a href="#" class="catalog-technics__item">
							<div class="catalog-technics__item-svgBx"><img src="<?= get_template_directory_uri() ?>/images/catalog/02.svg" alt=""></div>
							<p class="catalog-technics__item-title">Ноутбуки</p>
						</a>
						<a href="#" class="catalog-technics__item">
							<div class="catalog-technics__item-svgBx"><img src="<?= get_template_directory_uri() ?>/images/catalog/03.svg" alt=""></div>
							<p class="catalog-technics__item-title">Мониторы</p>
						</a>
						<a href="#" class="catalog-technics__item">
							<div class="catalog-technics__item-svgBx"><img src="<?= get_template_directory_uri() ?>/images/catalog/04.svg" alt=""></div>
							<p class="catalog-technics__item-title">Моноблоки</p>
						</a>
					</div>
					<!-- /.catalog-technics -->
				</div>
				<!-- /.catalog-helper -->
				*/ ?>
			</div>
			<!-- /.catalog-inner -->
		</div>
		<!-- /.catalog-wrapper -->
	</div>
</div>