<?php
$cities = get_terms([
	'taxonomy' => 'city',
]);

$city_names = [];

foreach ($cities as $city) {
	$first_letter = strtoupper(mb_substr($city->name, 0, 1));
	$city_names[$first_letter][$city->term_id] = $city->name;
}

if (empty($cities)) return false;
?>
<section class="guide">
	<div class="guide__container">
		<h2 class="title guide__title">Справочник лучших сервисных центров</h2>
		<!-- /.guide__title title -->
        <div class="guide-gerbs">
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/1.png" alt=""></div>
                <p class="guide-gerbs__item-city">Москва</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/7.png" alt=""></div>
                <p class="guide-gerbs__item-city">Санкт-Петербург</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/12.png" alt=""></div>
                <p class="guide-gerbs__item-city">Новосибирск</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/2.png" alt=""></div>
                <p class="guide-gerbs__item-city">Екатеринбург</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/6.png" alt=""></div>
                <p class="guide-gerbs__item-city">Казань</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/8.png" alt=""></div>
                <p class="guide-gerbs__item-city">Нижний Новгород</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/3.png" alt=""></div>
                <p class="guide-gerbs__item-city">Челябинск</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/9.png" alt=""></div>
                <p class="guide-gerbs__item-city">Самара</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/13.png" alt=""></div>
                <p class="guide-gerbs__item-city">Уфа</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/4.png" alt=""></div>
                <p class="guide-gerbs__item-city">Ростов-на-Дону</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/10.png" alt=""></div>
                <p class="guide-gerbs__item-city">Омск</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/14.png" alt=""></div>
                <p class="guide-gerbs__item-city">Красноярск</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/5.png" alt=""></div>
                <p class="guide-gerbs__item-city">Воронеж</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/11.png" alt=""></div>
                <p class="guide-gerbs__item-city">Пермь</p>
            </a>
            <a href="#" class="guide-gerbs__item">
                <div class="guide-gerbs__item-imgBx"><img src="<?= get_template_directory_uri() ?>/images/gerbs/15.png" alt=""></div>
                <p class="guide-gerbs__item-city">Волгоград</p>
            </a>
        </div>
        <!-- /.guide-gerbs -->
        <button class="brands__button js-button-city">Показать список городов</button>
        <!-- /.brands__button -->
        <div class="guide-wrapper">
            <div class="guide-inner">
                <?php foreach ($city_names as $frst_l => $c_names) { ?>
                    <div class="guide__item">
                        <p class="guide__item-symbol"><?= $frst_l ?></p>
                        <ul class="guide__item-list">
                            <?php foreach ($c_names as $term_id => $c_name) { ?>
                                <li><a href="<?= getCitiesPermalink($term_id) ?>"><?= $c_name ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
                <!-- /.guide-wrapper -->
                <img src="<?= get_template_directory_uri() ?>/images/guide/map.svg" alt="" class="guide__bg">
            </div>
		</div>
		<!-- /.guide-inner -->
	</div>
	<!-- /.guide__container -->
</section>
<!-- /.guide -->