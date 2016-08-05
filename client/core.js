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
            }
            if (!helpers.hasMethod(object, 'init')) {
                this.printError(name + ' must have init function.');
            }
            if (!helpers.hasMethod(object, 'load')) {
                this.printError(name + ' must have load function.');
            }
            if (!helpers.hasMethod(object, 'isEnabled')) {
                this.printError(name + ' must have isEnabled function.');
            }
			this.controls[name] = object;
		}
	};
})(jQuery);

WebChemistry.FormControls = _tmp;
