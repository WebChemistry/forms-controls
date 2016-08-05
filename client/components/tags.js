(function ($) {
	var helpers = WebChemistry.FormControlsHelpers;

	var ctrl = {
		options: {
			enable: true,
			plugin: {
				options: {},
				callback: function () {}
			},
			selector: 'input.tag-input'
		},
		isEnabled: function () {
			return this.options.enable;
		},
		init: function (s) {
			this.options = helpers.merge(this.options, s);
		},
		before: function () {
			if (helpers.isUndefined($.fn.tagsInput)) {
				this.options.enable = false;

				helpers.extensionMissing('tagsInput');
			}
		},
		load: function () {
			var self = this;

			$(this.options.selector).each(function () {
				var target = $(this);
				if (helpers.isMarked(target)) {
					return;
				}
				var options = self.options.plugin.options;

				if (self.callCallback(options, target) !== false) {
					target.tagsInput(options);

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

	WebChemistry.FormControls.addControl('tags', ctrl);
})(jQuery);
