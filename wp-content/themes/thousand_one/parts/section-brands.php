<?php
$firms = get_terms([
	'taxonomy' => 'firms',
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
				<? /*
				<a href="<?= get_term_link($firm) ?>" class="brands__item">
				*/ ?>
				<a href="<?= getFirmsPermalink($firm) ?>" class="brands__item">
					<img data-src="<?= getFirmImageName($firm->term_id) ?>" src="<?= kama_thumb_src('w=200&h=120&crop=0', getFirmImageName($firm->term_id)) ?>" alt="">
				</a>
				<?php if ($key >= $end) break; ?>
			<?php } ?>
		</div>
		<!-- /.brands-box -->
		<button class="brands__button" data-page="1">Показать ещё</button>
		<!-- /.brands__button -->
	</div>
	<!-- /.brands__container -->
</section>
<!-- /.brands -->