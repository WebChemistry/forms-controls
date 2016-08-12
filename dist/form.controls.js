if (typeof WebChemistry !== 'object') {
	var WebChemistry = {};
}

var _tmp = (function ($) {
	return {
		hasMethod: function (obj, method) {
			return typeof obj[method] === 'function';
		},
		isObject: function (obj) {
			return typeof obj === 'object';
		},
		isCallable: function (callback) {
			return typeof callback === 'function';
		},
		isUndefined: function (v) {
			return typeof v === 'undefined';
		},
		printError: function (msg) {
			console.error('Form controls: ' + msg);
		},
		extensionMissing: function (name, isJquery) {
			if (this.isUndefined(isJquery)) {
				isJquery = true;
			}
			if (isJquery) {
				this.printError('jQuery extension "' + name + '" is missing.');
				return;
			}
			this.printError('Extension "' + name + '" is missing.');
		},
		hasProperty: function (obj, name) {
			if (!this.isObject(obj)) {
				return false;
			}

			return name in obj;
		},
		parseJSON: function (str) {
			return $.parseJSON(str);
		},
		mark: function (target) {
			target.attr('data-form-controls-mark-loaded', 'true');
		},
		isMarked: function (target) {
			return target.attr('data-form-controls-mark-loaded') === 'true';
		},
		removeMark: function (target) {
			target.attr('data-form-controls-mark-loaded', 'false');
		},
		/**
		 * @param {...object}
		 * @return object
		 */
		merge: function () {
			var args = arguments;
			Array.prototype.unshift.call(args, true);

			return $.extend.apply(this, args);
		}
	};
})(jQuery);

WebChemistry.FormControlsHelpers = _tmp;

var _tmp = (function ($, undefined) {
	if ($ == undefined) {
		console.error('jQuery missing.');
		return;
	}

	var helpers = WebChemistry.FormControlsHelpers;

	return {
		isInitialized: false,
		settings: {},
		controls: {},
		addSettings: function (settings) {
			if (this.isInitialized) {
				helpers.printError('Controls is initialized. This settings has no effect.');
				return;
			}
			this.settings = helpers.merge(this.settings, settings);
		},
		init: function () {
			if (this.isInitialized) {
				return;
			}
			var i, obj, settings, state;
			this.isInitialized = true;

			for (i in this.controls) {
				obj = this.controls[i];
                settings = this.settings[i] !== undefined ? this.settings[i] : {};

                obj.init(settings);
                if (!obj.isEnabled()) {
                    continue;
                }
                if (helpers.hasMethod(obj, 'before')) {
                    state = obj.before(settings);
                }
                if (!obj.isEnabled()) {
                	continue;
				}
                obj.load();
                if (helpers.hasMethod(obj, 'after')) {
                    obj.after();
                }
			}
		},
        update: function () {
		    var i, obj;
            for (i in this.controls) {
                obj = this.controls[i];
				if (!obj.isEnabled()) {
					continue;
				}
                obj.load();
            }
        },
		registerNetteAjaxEvent: function () {
        	if (helpers.isUndefined($.nette.ajax)) {
        		helpers.printError('$.nette.ajax missing.');
				return;
			}

			var self = this;
			$.nette.ext('formControlsAutoEvent', {
				success: function () {
					self.update();
				}
			});
		},
		addControl: function (name, object) {
		    if (!helpers.isObject(object)) {
		        this.printError(name + ' must be object.');
				return;
            }
            if (!helpers.hasMethod(object, 'init')) {
                this.printError(name + ' must have init function.');
				return;
            }
            if (!helpers.hasMethod(object, 'load')) {
                this.printError(name + ' must have load function.');
				return;
            }
            if (!helpers.hasMethod(object, 'isEnabled')) {
                this.printError(name + ' must have isEnabled function.');
				return;
            }
			this.controls[name] = object;
		}
	};
})(jQuery);

WebChemistry.FormControls = _tmp;

(function ($) {
	var helpers = WebChemistry.FormControlsHelpers;

	var ctrl = {
		options: {
			enable: true,
			selector: 'input.date-input',
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
			if (helpers.isUndefined($.fn.datetimepicker)) {
				this.options.enable = false;

				helpers.extensionMissing('datetimepicker');
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
					format: target.attr('data-format')
				}, helpers.parseJSON(target.attr('data-settings')), self.options.plugin.options);

				if (self.callCallback(options, target) !== false) {
					target.datetimepicker(options);

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

	WebChemistry.FormControls.addControl('date', ctrl);
})(jQuery);

(function ($) {
	var helpers = WebChemistry.FormControlsHelpers;

	var ctrl = {
		options: {
			enable: true,
			selector: 'input[data-mask-input]',
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
			if (helpers.isUndefined($.fn.inputmask)) {
				this.options.enable = false;

				helpers.extensionMissing('inputmask');
			}
		},
		load: function () {
			var self = this;

			$(this.options.selector).each(function () {
				var target = $(this);
				if (helpers.isMarked(target)) {
					return;
				}

				var options = helpers.merge(helpers.parseJSON(target.attr('data-mask-input')), self.options.plugin.options);

				if (self.callCallback(options, target) !== false) {
					if (helpers.isUndefined(options.regex)) {
						target.inputmask(options);
					} else {
						target.inputmask('Regex', options);
					}

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

	WebChemistry.FormControls.addControl('mask', ctrl);
})(jQuery);

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
