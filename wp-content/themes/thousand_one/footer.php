</main>

<?php if (!is_404()) { ?>
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
<?php } ?>

<?php get_template_part('parts/popup', 'catalog') ?>
<?php get_template_part('parts/popup', 'repair') ?>
<?php get_template_part('parts/popup', 'question') ?>


<?php wp_footer() ?>
</body>

</html>