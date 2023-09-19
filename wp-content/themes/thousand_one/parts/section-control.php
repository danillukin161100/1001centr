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
						<input type="text" placeholder="Все города" data-list=".control-list-city" class="search-input search-input-city">
                        <input type="hidden" name="city">
						<!-- /.header-citiesBx-inputBX -->
						<ul class="control-list control-list-city">
							<?php foreach ($cities as $city) { ?>
								<li class="control-list__item">
									<div class="control-list__link" data-code="<?= $city->term_id ?>"><?= $city->name ?></div>
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
                        <input type="hidden" name="cat">
						<!-- /.header-citiesBx-inputBX -->
						<ul class="control-list control-list-technics">
							<?php foreach ($categories as $cat) { ?>
								<li class="control-list__item">
									<div class="control-list__link" data-code="<?= $cat->term_id ?>"><?= $cat->name ?></div>
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
                        <input type="hidden" name="firm">
						<!-- /.header-citiesBx-inputBX -->
						<ul class="control-list control-list-brands">
							<?php foreach ($firms as $firm) { ?>
								<li class="control-list__item">
									<div class="control-list__link" data-code="<?= $firm->term_id ?>"><?= $firm->name ?></div>
								</li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
				<div class="control-selectBx">
					<a href="#" class="pb control__button">Показать <span></span></a>
				</div>
			</form>
			<!-- /.control-box -->
		</div>
		<!-- /.control-inner -->
	</div>
	<!-- /.control__container -->
</section>
<!-- /.control -->