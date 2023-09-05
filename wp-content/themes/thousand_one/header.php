<!doctype html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<!--==========   FAVICON   ==========-->
	<link rel="apple-touch-icon" sizes="76x76" href="<?= get_template_directory_uri() ?>/static/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= get_template_directory_uri() ?>/static/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= get_template_directory_uri() ?>/static/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?= get_template_directory_uri() ?>/static/favicon/site.webmanifest">
	<link rel="mask-icon" href="<?= get_template_directory_uri() ?>/static/favicon/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<?php wp_head() ?>
</head>

<body>
	<header class="header">
		<div class="header__container">
			<div class="header-inner">
				<div class="header-logoBx">
					<a href="" class="header__logo">
						<img src="<?= get_template_directory_uri() ?>/images/header/logo.svg" alt="">
					</a>
					<div class="header-burger">
						<span></span>
					</div>
					<!-- /.header-burger -->
					<div class="header-catalog">
						<button class="header-catalog__button">
							<span class="header-catalog-svgBx">
								<img src="<?= get_template_directory_uri() ?>/images/elements/list.svg" alt="">
								<img src="<?= get_template_directory_uri() ?>/images/elements/cross.svg" alt="">
							</span>
							<!-- /.header-catalog-svgBx -->
							<span>Каталог техники</span>
						</button>
					</div>
					<!-- /.header-catalogBx -->
				</div>
				<!-- /.header-logoBx -->
				<?php if ($phone = get_field('phone', 'options')) : ?>
					<a href="tel: +7 (969) 999-42-80" class="mobile header__phone">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M15.0504 5C16.0272 5.19057 16.9248 5.66826 17.6285 6.37194C18.3322 7.07561 18.8099 7.97327 19.0004 8.95M15.0504 1C17.0797 1.22544 18.972 2.13417 20.4167 3.57701C21.8613 5.01984 22.7724 6.91101 23.0004 8.94M22.0004 16.92V19.92C22.0016 20.1985 21.9445 20.4742 21.8329 20.7293C21.7214 20.9845 21.5577 21.2136 21.3525 21.4019C21.1473 21.5901 20.905 21.7335 20.6412 21.8227C20.3773 21.9119 20.0978 21.9451 19.8204 21.92C16.7433 21.5856 13.7874 20.5341 11.1904 18.85C8.77425 17.3147 6.72576 15.2662 5.19042 12.85C3.5004 10.2412 2.44866 7.271 2.12042 4.18C2.09543 3.90347 2.1283 3.62476 2.21692 3.36162C2.30555 3.09849 2.44799 2.85669 2.63519 2.65162C2.82238 2.44655 3.05023 2.28271 3.30421 2.17052C3.5582 2.05833 3.83276 2.00026 4.11042 2H7.11042C7.59573 1.99522 8.06621 2.16708 8.43418 2.48353C8.80215 2.79999 9.0425 3.23945 9.11042 3.72C9.23705 4.68007 9.47187 5.62273 9.81042 6.53C9.94497 6.88792 9.97408 7.27692 9.89433 7.65088C9.81457 8.02485 9.62929 8.36811 9.36042 8.64L8.09042 9.91C9.51398 12.4135 11.5869 14.4864 14.0904 15.91L15.3604 14.64C15.6323 14.3711 15.9756 14.1859 16.3495 14.1061C16.7235 14.0263 17.1125 14.0555 17.4704 14.19C18.3777 14.5286 19.3204 14.7634 20.2804 14.89C20.7662 14.9585 21.2098 15.2032 21.527 15.5775C21.8441 15.9518 22.0126 16.4296 22.0004 16.92Z" stroke="#181D22" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</a>
				<?php endif ?>
				<div class="header-box">
					<div class="header-phoneBx">
						<a href="tel: +7 (969) 999-42-80" class="header__phone">+7 (969) 999-42-80</a>
						<!-- /.header__phone -->
						<span class="header__text">Бесплатная консультация</span>
					</div>
					<!-- /.header-phoneBx -->
					<button class="pb header__button" data-type="repair">Заявка на ремонт</button>
				</div>
				<!-- /.header-box -->
			</div>
			<!-- /.header-inner -->

		</div>
	</header>
	<div class="_overlay-bg modal header-navBx" data-popup="">
		<div class="header-navBx-helper">
			<nav class="header-nav">
				<ul>
					<li class="header-nav__item"><a href="#" class="header-nav__link">Статьи</a></li>
					<li class="header-nav__item">
						<p data-type="repair" class="header-nav__link">Задать вопрос мастеру</p>
					</li>
					<li class="header-nav__item">
						<p data-type="repair" class="header-nav__link">Добавить сервис</p>
					</li>
				</ul>
			</nav>
			<!-- /.header-nav -->
			<div class="mobile header-catalog">
				<button class="header-catalog__button">
					<span class="header-catalog-svgBx">
						<img src="<?= get_template_directory_uri() ?>/images/elements/list.svg" alt="">
						<img src="<?= get_template_directory_uri() ?>/images/elements/cross.svg" alt="">
					</span>
					<!-- /.header-catalog-svgBx -->
					<span>Каталог техники</span>
				</button>
			</div>
			<!-- /.header-catalogBx -->
		</div>
	</div>
	<!-- /.header-navBx -->
	<main class="main">