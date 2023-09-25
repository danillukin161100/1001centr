<div class="_overlay-bg modal repair" data-popup="repair">
	<div class="repair-formBx">
		<div class="title repair__title">Заявка на ремонт техники</div>
		<div class="repair__subtitle">Оставьте данные и мы передадим заявку в сервисный центр, который окажет вам услугу в ближайшее время.
		</div>
		<!-- /.repair__subtitle -->
		<form action="#" class="repair-form">
			<div class="repair-grid">
				<div class="repair-inputBx"><input type="text" name="fio" placeholder="Ваше имя" required></div>
				<div class="repair-inputBx"><input type="text" name="tel" placeholder="Номер телефона" required></div>
			</div>
			<!-- /.repair-grid -->
			<div class="repair-inputBx select">
				<select name="" id="">
					<option value="Выберите технику" disabled="disabled" selected="selected">Выберите технику</option>
					<option value="Монитор">Монитор</option>
					<option value="Моноблок">Моноблок</option>
					<option value="Телевизор">Телевизор</option>
				</select>
			</div>
			<!-- /.repair-inputBx -->
			<button class="pb repair__button">Отправить</button>
		</form>
		<p class="repair__notice">
			Нажимая на кнопку «Отправить», вы соглашаетесь на обработку персональных данных и <a href="<?= get_privacy_policy_url() ?>">политикой конфиденциальности</a>
		</p>
		<!-- /.repair__notice -->
		<div class="button-close repair__cross">
			<i class="fa-solid fa-xmark"></i>
		</div>
	</div>
	<!-- /.repair-formBx -->
</div>
<!-- /.modal repair -->