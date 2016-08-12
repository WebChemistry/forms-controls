WebChemistry.FormControlsHelpers = (function ($) {
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
