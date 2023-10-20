class CibaForms {
	constructor(callbackSuccess, callbackError) {
		this.success = callbackSuccess;
		this.error = callbackError;
	}
}

window.CibaForms = new CibaForms();

jQuery(document).ready(($) => {

	/* Маска ввода телефона */
	(() => {
		let $phones = $('.ciba_form [name="phone"]');
		if (!$phones.length) return false;

		$phones.each((index, phone) => {
			let $phone = $(phone);

			$phone.mask("+7 (999) 999-99-99");
		});
	})();

	/* Отправка формы */
	$(document).on('submit', '.ciba_form', (e) => {
		e.preventDefault();

		let $form = $(e.currentTarget);
		let $fields = $form.find('[name]');
		let data = {};
		data.comment = {};

		$fields.each((index, input) => {
			let $input = $(input);

			if (input.name == 'phone') {
				data[input.name] = input.value;
				return;
			}

			if (input.name == 'source_id') {
				data[input.name] = input.value;
				return;
			}

			if (input.name == 'mango_name') {
				data[input.name] = input.value;
				return;
			}

			data['comment'][input.name] = input.value;
		});

		$.ajax({
			url: $form.attr('data-cibaForms-action'),
			type: "POST",
			data: data,
			dataType: "json",
		}).done(function (response) {
			if (response.status == 'success') {
				if (window.CibaForms.success !== undefined) {
					window.CibaForms.success($form);
				}

				// Очистка полей формы
				$fields.each(() => {
					$fields.val('');
				});

			} else {
				if (window.CibaForms.error !== undefined) {
					window.CibaForms.error($form);
				}
			}
		}.bind(this));
	});
});