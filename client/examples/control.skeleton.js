(function ($) {
	var helpers = WebChemistry.FormControlsHelpers;

	var ctrl = {
		options: {
			enable: true
		},
		isEnabled: function () {
			return this.options.enable;
		},
		init: function (s) {
			this.options = $.extend(this.options, s);
		},
		before: function () {

		},
		load: function () {

		}
 	};

	WebChemistry.FormControls.addControl('', ctrl);
})(jQuery);
