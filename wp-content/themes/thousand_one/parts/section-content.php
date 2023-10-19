<section class="docs">
	<div class="content__container">
		<div class="content-inner">
			<div class="">
				<h1 class="content__title"><?= getMetaTag('h1') ?></h1>
				<?php if ($subtitle = get_field('subtitle')) { ?>
					<p class="content__subtitle"><?= $subtitle ?></p>
				<?php } ?>
				<!-- /.content__subtitle -->

				<?php if ($content = get_the_content()) { ?>
					<div class="content__text">
						<?= apply_filters('the_content', $content); ?>
					</div>
				<?php } ?>
			</div>
			<!-- /.content-main -->
		</div>
		<!-- /.content-inner -->
	</div>
	<!-- /.content__container -->
</section>
<!-- /.content -->