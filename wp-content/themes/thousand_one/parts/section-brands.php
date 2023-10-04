<?php
$firms = get_terms([
	'taxonomy' => 'firms',
	// 'hide_empty' => false,
	'exclude' => [588],
]);
$iteration = (isset($_GET['firm_iteration'])) ? $_GET['firm_iteration'] : 1;
$iteration--;
$step = 16;
$start = $step * $iteration;
$end = $start + $step - 1;

if (empty($firms)) return false;
?>

<section class="brands" data-more="firm">
	<div class="brands__container">
		<h2 class="title brands__title">Бренд вашей техники</h2>
		<div class="brands-box">
			<?php foreach ($firms as $key => $firm) { ?>
				<?php if ($key < $start) continue; ?>
				<a href="<?= getFirmsPermalink($firm) ?>" class="brands__item">
					<img data-src="<?= getFirmImageName($firm->term_id, 1) ?>" src="<?= kama_thumb_src('w=200&h=120&crop=0', getFirmImageName($firm->term_id)) ?>" alt="">
				</a>
				<?php if ($key >= $end) break; ?>
			<?php } ?>
		</div>
		<!-- /.brands-box -->
		<button class="brands__button" data-page="1"><span>Показать еще</span><span><img src="<?= get_template_directory_uri() ?>/images/elements/loader.svg" alt=""></span></button>
		<!-- /.brands__button -->
	</div>
	<!-- /.brands__container -->
</section>
<!-- /.brands -->