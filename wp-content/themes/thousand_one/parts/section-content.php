<section class="content">
	<div class="content__container">
		<div class="content-inner">
			<div class="content-main">
				<h1 class="content__title"><?= getMetaTag('h1') ?></h1>
				<?php if ($subtitle = get_field('subtitle')) { ?>
					<p class="content__subtitle"><?= $subtitle ?></p>
				<?php } ?>
				<!-- /.content__subtitle -->
			</div>
			<!-- /.content-main -->
		</div>
		<!-- /.content-inner -->
	</div>
	<!-- /.content__container -->
</section>
<!-- /.content -->