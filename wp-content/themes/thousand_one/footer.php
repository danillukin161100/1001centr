</main>
<footer class="footer">
	<div class="footer__container">
		<div class="footer-inner">
			<a href="/" class="footer-logoBx">
				<img src="<?= get_template_directory_uri() ?>/images/footer/logo.svg" alt="">
			</a>
			<!-- /.footer-logoBx -->

			<?php wp_nav_menu([
				'menu'            => 'footer_menu',
				'container'       => 'nav',
				'container_class' => 'footer-navBx',
				'menu_class'      => 'footer-nav',
				'echo'            => true,
				'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'depth'           => 0,
				'walker'          => new FooterWalkerNavMenu(),
			]); ?>

			<?php if ($phone = get_field('phone', 'option')) { ?>
				<div class="footer-phoneBx">
					<a href="tel: <?= phoneLink($phone) ?>" class="footer__phone"><?= $phone ?></a>
					<div class="footer__text">Подберем сервис для вас</div>
				</div>
			<?php } ?>
			<!-- /.footer-phoneBx -->
		</div>
		<!-- /.footer-inner -->
		<nav class="mobile footer-navBx">
			<ul class="footer-nav">
				<li class="footer-nav__item"><a href="" class="footer-nav__link">Главная</a></li>
				<li class="footer-nav__item"><a href="" class="footer-nav__link">Статьи</a></li>
				<li class="footer-nav__item"><a href="" class="footer-nav__link">Задать вопрос мастеру</a></li>
				<li class="footer-nav__item"><a href="" class="footer-nav__link">Добавить сервис</a></li>
			</ul>
		</nav>
		<div class="footer-bottom">
			<div class="footer-data">
				<p class="footer-data__item">2023 © 1001centr.ru</p>
				<p class="footer-data__item">ИНН: 37373734343</p>
				<p class="footer-data__item">ОГРН: 37373734343</p>
			</div>
			<!-- /.footer-data -->
			<a href="<?= get_privacy_policy_url() ?>" class="footer__docs">Политика конфиденциальности</a>
			<!-- /.footer__docs -->
			<p class="mobile footer-data__item"><?= date('Y') ?> © <?= $_SERVER["SERVER_NAME"] ?></p>
		</div>
		<!-- /.footer-bottom -->
	</div>
</footer>

<?php get_template_part('parts/popup', 'catalog') ?>
<?php get_template_part('parts/popup', 'repair') ?>

<div class="_overlay-bg modal-white region">
	<div class="region-wrapper">
		<div class="region__container">
			<div class="region-top">
				<div class="region__pretitle">Укажите, пожалуйста, ваш регион.</div>
				<div class="button-close region__cross">
					<i class="fa-solid fa-xmark"></i>
				</div>
				<!-- /.region__cross -->
			</div>
			<!-- /.region-top -->
			<div class="region-cityBx">
				<p class="region__city">Москва</p>
				<button class="button-close region__button">Подтвердить</button>
			</div>
			<!-- /.region-cityBx -->
			<div class="region-cities">
				<a href="#" class="region-cities__item">Волгоград</a>
				<a href="#" class="region-cities__item">Воронеж</a>
				<a href="#" class="region-cities__item">Екатеринбург</a>
				<a href="#" class="region-cities__item">Йошкар-Ола</a>
				<a href="#" class="region-cities__item">Казань</a>
				<a href="#" class="region-cities__item">Краснодар</a>
				<a href="#" class="region-cities__item">Красноярск</a>
				<a href="#" class="region-cities__item">Великий Новгород</a>
				<a href="#" class="region-cities__item">Новосибирск</a>
				<a href="#" class="region-cities__item">Омск</a>
				<a href="#" class="region-cities__item">Пермь</a>
				<a href="#" class="region-cities__item">Ростов-на-Дону</a>
				<a href="#" class="region-cities__item">Самара</a>
				<a href="#" class="region-cities__item">Санкт-Петербург</a>
				<a href="#" class="region-cities__item">Тюмень</a>
				<a href="#" class="region-cities__item">Уфа</a>
				<a href="#" class="region-cities__item">Чеоябинск</a>
			</div>
			<!-- /.region-cities -->
			<p class="region__notice">Выбором или отказом от выбора региона вы подтверждаете свое совершеннолетие, даете согласие на обработку персональных данных, а также передачу информации от них третьим лицам, соглашаетесь с условиями пользования сайта</p>
		</div>
		<!-- /.region__container -->
	</div>
	<!-- /.region-wrapper -->
</div>
<!-- /.modal-white region -->
<?php wp_footer() ?>
</body>

</html>