(function ($) {
	var helpers = WebChemistry.FormControlsHelpers;

	var ctrl = {
		options: {
			enable: true,
			selector: 'input.suggestion-input',
			plugin: {
				options: {},
				callback: function () {}
			}
		},
		isEnabled: function () {
			return this.options.enable;
		},
		init: function (s) {
			this.options = helpers.merge(this.options, s);
		},
		before: function () {
			if (helpers.isUndefined($.fn.easyAutocomplete)) {
				this.options.enable = false;

				helpers.extensionMissing('easyAutocomplete');
			}
		},
		load: function () {
			var self = this;

			$(this.options.selector).each(function () {
				var target = $(this);
				if (helpers.isMarked(target)) {
					return;
				}

				var options = helpers.merge({
					url: function (pharse) {
						return target.attr('data-url') + '&term=' + pharse;
					}
				}, helpers.parseJSON(target.attr('data-suggestion')), self.options.plugin.options);

				if (self.callCallback(options, target) !== false) {
					target.easyAutocomplete(options);

					helpers.mark(target);
				}
			});
		},
		callCallback: function (settings, target) {
			var callback = this.options.plugin.callback;
			if (!helpers.isCallable(callback)) {
				helpers.printError('Callback must be an function.');
			}

			return callback(settings, target);
		}
	};

	WebChemistry.FormControls.addControl('suggestion', ctrl);
})(jQuery);
