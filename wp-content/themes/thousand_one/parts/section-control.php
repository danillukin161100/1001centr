<section class="control">
	<div class="control__container">
		<div class="control-inner">
			<form action="#" class="control-box">

				<?php
				$cities = get_terms([
					'taxonomy' => 'city',
				]);
				if (!empty($cities)) {
				?>
					<div class="control-selectBx">
						<input type="text" placeholder="Все города" data-list=".control-list-city" class="search-input">
						<!-- /.header-citiesBx-inputBX -->
						<ul class="control-list control-list-city">
							<?php foreach ($cities as $city) { ?>
								<li class="control-list__item">
									<div class="control-list__link" data-city="<?= $city->term_id ?>"><?= $city->name ?></div>
								</li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>

				<?php
				$categories = get_terms([
					'taxonomy' => 'categories',
				]);
				if (!empty($categories)) {
				?>
					<div class="control-selectBx">
						<input type="text" placeholder="Все виды техники" data-list=".control-list-technics" class="search-input">
						<!-- /.header-citiesBx-inputBX -->
						<ul class="control-list control-list-technics">
							<?php foreach ($categories as $cat) { ?>
								<li class="control-list__item">
									<div class="control-list__link" data-cat="<?= $cat->term_id ?>"><?= $cat->name ?></div>
								</li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>

				<?php
				$firms = get_terms([
					'taxonomy' => 'firms',
				]);
				if (!empty($firms)) {
				?>
					<div class="control-selectBx">
						<input type="text" placeholder="Все производители" data-list=".control-list-brands" class="search-input">
						<!-- /.header-citiesBx-inputBX -->
						<ul class="control-list control-list-brands">
							<?php foreach ($firms as $firm) { ?>
								<li class="control-list__item">
									<div class="control-list__link" data-firm="<?= $firm->term_id ?>"><?= $firm->name ?></div>
								</li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
				<div class="control-selectBx">
					<button class="pb control__button">Показать <span>92</span></button>
				</div>
			</form>
			<!-- /.control-box -->
		</div>
		<!-- /.control-inner -->
	</div>
	<!-- /.control__container -->
</section>
<!-- /.control -->