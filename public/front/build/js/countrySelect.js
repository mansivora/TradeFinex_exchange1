// wrap in UMD - see https://github.com/umdjs/umd/blob/master/jqueryPlugin.js
(function(factory) {
	if (typeof define === "function" && define.amd) {
		define([ "jquery" ], function($) {
			factory($, window, document);
		});
	} else {
		factory(jQuery, window, document);
	}
})(function($, window, document, undefined) {
	"use strict";
	var pluginName = "countrySelect", id = 1, // give each instance its own ID for namespaced event handling
	defaults = {
		// Default country
		defaultCountry: "",
		// Position the selected flag inside or outside of the input
		defaultStyling: "inside",
		// Display only these countries
		onlyCountries: [],
		// The countries at the top of the list. Defaults to United States and United Kingdom
		preferredCountries: [ "us", "gb" ]
	}, keys = {
		UP: 38,
		DOWN: 40,
		ENTER: 13,
		ESC: 27,
		PLUS: 43,
		A: 65,
		Z: 90
	}, windowLoaded = false;
	// keep track of if the window.load event has fired as impossible to check after the fact
	$(window).on('load', function() {
		windowLoaded = true;
	});
	function Plugin(element, options) {
		this.element = element;
		this.options = $.extend({}, defaults, options);
		this._defaults = defaults;
		// event namespace
		this.ns = "." + pluginName + id++;
		this._name = pluginName;
		this.init();
	}
	Plugin.prototype = {
		init: function() {
			// Process all the data: onlyCountries, preferredCountries, defaultCountry etc
			this._processCountryData();
			// Generate the markup
			this._generateMarkup();
			// Set the initial state of the input value and the selected flag
			this._setInitialState();
			// Start all of the event listeners: input keyup, selectedFlag click
			this._initListeners();
			// Return this when the auto country is resolved.
			this.autoCountryDeferred = new $.Deferred();
			// Get auto country.
			this._initAutoCountry();

			return this.autoCountryDeferred;
		},
		/********************
		 *  PRIVATE METHODS
		 ********************/
		// prepare all of the country data, including onlyCountries, preferredCountries and
		// defaultCountry options
		_processCountryData: function() {
			// set the instances country data objects
			this._setInstanceCountryData();
			// set the preferredCountries property
			this._setPreferredCountries();
		},
		// process onlyCountries array if present
		_setInstanceCountryData: function() {
			var that = this;
			if (this.options.onlyCountries.length) {
				var newCountries = [];
				$.each(this.options.onlyCountries, function(i, countryCode) {
					var countryData = that._getCountryData(countryCode, true);
					if (countryData) {
						newCountries.push(countryData);
					}
				});
				this.countries = newCountries;
			} else {
				this.countries = allCountries;
			}
		},
		// Process preferred countries - iterate through the preferences,
		// fetching the country data for each one
		_setPreferredCountries: function() {
			var that = this;
			this.preferredCountries = [];
			$.each(this.options.preferredCountries, function(i, countryCode) {
				var countryData = that._getCountryData(countryCode, false);
				if (countryData) {
					that.preferredCountries.push(countryData);
				}
			});
		},
		// generate all of the markup for the plugin: the selected flag overlay, and the dropdown
		_generateMarkup: function() {
			// Country input
			this.countryInput = $(this.element);
			// containers (mostly for positioning)
			var mainClass = "country-select";
			if (this.options.defaultStyling) {
				mainClass += " " + this.options.defaultStyling;
			}
			this.countryInput.wrap($("<div>", {
				"class": mainClass
			}));
			var flagsContainer = $("<div>", {
				"class": "flag-dropdown"
			}).insertAfter(this.countryInput);
			// currently selected flag (displayed to left of input)
			var selectedFlag = $("<div>", {
				"class": "selected-flag"
			}).appendTo(flagsContainer);
			this.selectedFlagInner = $("<div>", {
				"class": "flag"
			}).appendTo(selectedFlag);
			// CSS triangle
			$("<div>", {
				"class": "arrow"
			}).appendTo(this.selectedFlagInner);
			// country list contains: preferred countries, then divider, then all countries
			this.countryList = $("<ul>", {
				"class": "country-list v-hide"
			}).appendTo(flagsContainer);
			if (this.preferredCountries.length) {
				this._appendListItems(this.preferredCountries, "preferred");
				$("<li>", {
					"class": "divider"
				}).appendTo(this.countryList);
			}
			this._appendListItems(this.countries, "");
			// Add the hidden input for the country code
			this.countryCodeInput = $("#"+this.countryInput.attr("id")+"_code");
			if (!this.countryCodeInput) {
				this.countryCodeInput = $('<input type="hidden" id="'+this.countryInput.attr("id")+'_code" name="'+this.countryInput.attr("name")+'_code" value="" />');
				this.countryCodeInput.insertAfter(this.countryInput);
			}
			// now we can grab the dropdown height, and hide it properly
			this.dropdownHeight = this.countryList.outerHeight();
			this.countryList.removeClass("v-hide").addClass("hide");
			// this is useful in lots of places
			this.countryListItems = this.countryList.children(".country");
		},
		// add a country <li> to the countryList <ul> container
		_appendListItems: function(countries, className) {
			// Generate DOM elements as a large temp string, so that there is only
			// one DOM insert event
			var tmp = "";
			// for each country
			$.each(countries, function(i, c) {
				// open the list item
				tmp += '<li class="country ' + className + '" data-country-code="' + c.iso2 + '" data-isd-code="' + c.isd + '">';
				// add the flag
				tmp += '<div class="flag ' + c.iso2 + '"></div>';
				// and the country name
				tmp += '<span class="country-name">' + c.name + '</span>';
				// close the list item
				tmp += '</li>';
			});
			this.countryList.append(tmp);
		},
		// set the initial state of the input value and the selected flag
		_setInitialState: function() {
			var flagIsSet = false;
			// If the input is pre-populated, then just update the selected flag
			if (this.countryInput.val()) {
				flagIsSet = this._updateFlagFromInputVal();
			}
			// If the country code input is pre-populated, update the name and the selected flag
			var selectedCode = this.countryCodeInput.val();
			if (selectedCode) {
				this.selectCountry(selectedCode);
			}
			if (!flagIsSet) {
				// flag is not set, so set to the default country
				var defaultCountry;
				// check the defaultCountry option, else fall back to the first in the list
				if (this.options.defaultCountry) {
					defaultCountry = this._getCountryData(this.options.defaultCountry, false);
					// Did we not find the requested default country?
					if (!defaultCountry) {
						defaultCountry = this.preferredCountries.length ? this.preferredCountries[0] : this.countries[0];
					}
				} else {
					defaultCountry = this.preferredCountries.length ? this.preferredCountries[0] : this.countries[0];
				}
				this.defaultCountry = defaultCountry.iso2;
			}
		},
		// initialise the main event listeners: input keyup, and click selected flag
		_initListeners: function() {
			var that = this;
			// Update flag on keyup.
			// Use keyup instead of keypress because we want to update on backspace
			// and instead of keydown because the value hasn't updated when that
			// event is fired.
			// NOTE: better to have this one listener all the time instead of
			// starting it on focus and stopping it on blur, because then you've
			// got two listeners (focus and blur)
			this.countryInput.on("keyup" + this.ns, function() {
				that._updateFlagFromInputVal();
			});
			// toggle country dropdown on click
			var selectedFlag = this.selectedFlagInner.parent();
			selectedFlag.on("click" + this.ns, function(e) {
				// only intercept this event if we're opening the dropdown
				// else let it bubble up to the top ("click-off-to-close" listener)
				// we cannot just stopPropagation as it may be needed to close another instance
				if (that.countryList.hasClass("hide") && !that.countryInput.prop("disabled")) {
					that._showDropdown();
				}
			});
			// Despite above note, added blur to ensure partially spelled country
			// with correctly chosen flag is spelled out on blur. Also, correctly
			// selects flag when field is autofilled
			this.countryInput.on("blur" + this.ns, function() {
				if (that.countryInput.val() != that.getSelectedCountryData().name) {
					that.setCountry(that.countryInput.val());
				}
				that.countryInput.val(that.getSelectedCountryData().name);
			});
		},
		_initAutoCountry: function() {
			if (this.options.initialCountry === "auto") {
				this._loadAutoCountry();
			} else {
				this.selectCountry(this.defaultCountry);
				this.autoCountryDeferred.resolve();
			}
		},
		// perform the geo ip lookup
		_loadAutoCountry: function() {
			var that = this;

			// 3 options:
			// 1) already loaded (we're done)
			// 2) not already started loading (start)
			// 3) already started loading (do nothing - just wait for loading callback to fire)
			if ($.fn[pluginName].autoCountry) {
				this.handleAutoCountry();
			} else if (!$.fn[pluginName].startedLoadingAutoCountry) {
				// don't do this twice!
				$.fn[pluginName].startedLoadingAutoCountry = true;

				if (typeof this.options.geoIpLookup === 'function') {
					this.options.geoIpLookup(function(countryCode) {
						$.fn[pluginName].autoCountry = countryCode.toLowerCase();
						// tell all instances the auto country is ready
						// TODO: this should just be the current instances
						// UPDATE: use setTimeout in case their geoIpLookup function calls this callback straight away (e.g. if they have already done the geo ip lookup somewhere else). Using setTimeout means that the current thread of execution will finish before executing this, which allows the plugin to finish initialising.
						setTimeout(function() {
							$(".country-select input").countrySelect("handleAutoCountry");
						});
					});
				}
			}
		},
		// Focus input and put the cursor at the end
		_focus: function() {
			this.countryInput.focus();
			var input = this.countryInput[0];
			// works for Chrome, FF, Safari, IE9+
			if (input.setSelectionRange) {
				var len = this.countryInput.val().length;
				input.setSelectionRange(len, len);
			}
		},
		// Show the dropdown
		_showDropdown: function() {
			this._setDropdownPosition();
			// update highlighting and scroll to active list item
			var activeListItem = this.countryList.children(".active");
			this._highlightListItem(activeListItem);
			// show it
			this.countryList.removeClass("hide");
			this._scrollTo(activeListItem);
			// bind all the dropdown-related listeners: mouseover, click, click-off, keydown
			this._bindDropdownListeners();
			// update the arrow
			this.selectedFlagInner.children(".arrow").addClass("up");
		},
		// decide where to position dropdown (depends on position within viewport, and scroll)
		_setDropdownPosition: function() {
			var inputTop = this.countryInput.offset().top, windowTop = $(window).scrollTop(),
			dropdownFitsBelow = inputTop + this.countryInput.outerHeight() + this.dropdownHeight < windowTop + $(window).height(), dropdownFitsAbove = inputTop - this.dropdownHeight > windowTop;
			// dropdownHeight - 1 for border
			var cssTop = !dropdownFitsBelow && dropdownFitsAbove ? "-" + (this.dropdownHeight - 1) + "px" : "";
			this.countryList.css("top", cssTop);
		},
		// we only bind dropdown listeners when the dropdown is open
		_bindDropdownListeners: function() {
			var that = this;
			// when mouse over a list item, just highlight that one
			// we add the class "highlight", so if they hit "enter" we know which one to select
			this.countryList.on("mouseover" + this.ns, ".country", function(e) {
				that._highlightListItem($(this));
			});
			// listen for country selection
			this.countryList.on("click" + this.ns, ".country", function(e) {
				that._selectListItem($(this));
			});
			// click off to close
			// (except when this initial opening click is bubbling up)
			// we cannot just stopPropagation as it may be needed to close another instance
			var isOpening = true;
			$("html").on("click" + this.ns, function(e) {
				if (!isOpening) {
					that._closeDropdown();
				}
				isOpening = false;
			});
			// Listen for up/down scrolling, enter to select, or letters to jump to country name.
			// Use keydown as keypress doesn't fire for non-char keys and we want to catch if they
			// just hit down and hold it to scroll down (no keyup event).
			// Listen on the document because that's where key events are triggered if no input has focus
			$(document).on("keydown" + this.ns, function(e) {
				// prevent down key from scrolling the whole page,
				// and enter key from submitting a form etc
				e.preventDefault();
				if (e.which == keys.UP || e.which == keys.DOWN) {
					// up and down to navigate
					that._handleUpDownKey(e.which);
				} else if (e.which == keys.ENTER) {
					// enter to select
					that._handleEnterKey();
				} else if (e.which == keys.ESC) {
					// esc to close
					that._closeDropdown();
				} else if (e.which >= keys.A && e.which <= keys.Z) {
					// upper case letters (note: keyup/keydown only return upper case letters)
					// cycle through countries beginning with that letter
					that._handleLetterKey(e.which);
				}
			});
		},
		// Highlight the next/prev item in the list (and ensure it is visible)
		_handleUpDownKey: function(key) {
			var current = this.countryList.children(".highlight").first();
			var next = key == keys.UP ? current.prev() : current.next();
			if (next.length) {
				// skip the divider
				if (next.hasClass("divider")) {
					next = key == keys.UP ? next.prev() : next.next();
				}
				this._highlightListItem(next);
				this._scrollTo(next);
			}
		},
		// select the currently highlighted item
		_handleEnterKey: function() {
			var currentCountry = this.countryList.children(".highlight").first();
			if (currentCountry.length) {
				this._selectListItem(currentCountry);
			}
		},
		// Iterate through the countries starting with the given letter
		_handleLetterKey: function(key) {
			var letter = String.fromCharCode(key);
			// filter out the countries beginning with that letter
			var countries = this.countryListItems.filter(function() {
				return $(this).text().charAt(0) == letter && !$(this).hasClass("preferred");
			});
			if (countries.length) {
				// if one is already highlighted, then we want the next one
				var highlightedCountry = countries.filter(".highlight").first(), listItem;
				// if the next country in the list also starts with that letter
				if (highlightedCountry && highlightedCountry.next() && highlightedCountry.next().text().charAt(0) == letter) {
					listItem = highlightedCountry.next();
				} else {
					listItem = countries.first();
				}
				// update highlighting and scroll
				this._highlightListItem(listItem);
				this._scrollTo(listItem);
			}
		},
		// Update the selected flag using the input's current value
		_updateFlagFromInputVal: function() {
			var that = this;
			// try and extract valid country from input
			var value = this.countryInput.val().replace(/(?=[() ])/g, '\\');
			if (value) {
				var countryCodes = [];
				var matcher = new RegExp("^"+value, "i");
				for (var i = 0; i < this.countries.length; i++) {
					if (this.countries[i].name.match(matcher)) {
						countryCodes.push(this.countries[i].iso2);
					}
				}
				// Check if one of the matching countries is already selected
				var alreadySelected = false;
				$.each(countryCodes, function(i, c) {
					if (that.selectedFlagInner.hasClass(c)) {
						alreadySelected = true;
					}
				});
				if (!alreadySelected) {
					this._selectFlag(countryCodes[0]);
					this.countryCodeInput.val(countryCodes[0]).trigger("change");
				}
				// Matching country found
				return true;
			}
			// No match found
			return false;
		},
		// remove highlighting from other list items and highlight the given item
		_highlightListItem: function(listItem) {
			this.countryListItems.removeClass("highlight");
			listItem.addClass("highlight");
		},
		// find the country data for the given country code
		// the ignoreOnlyCountriesOption is only used during init() while parsing the onlyCountries array
		_getCountryData: function(countryCode, ignoreOnlyCountriesOption) {
			var countryList = ignoreOnlyCountriesOption ? allCountries : this.countries;
			for (var i = 0; i < countryList.length; i++) {
				if (countryList[i].iso2 == countryCode) {
					return countryList[i];
				}
			}
			return null;
		},
		// update the selected flag and the active list item
		_selectFlag: function(countryCode) {
			if (! countryCode) {
				return false;
			}
			this.selectedFlagInner.attr("class", "flag " + countryCode);
			// update the title attribute
			var countryData = this._getCountryData(countryCode);
			this.selectedFlagInner.parent().attr("title", countryData.name);
			// update the active list item
			var listItem = this.countryListItems.children(".flag." + countryCode).first().parent();
			this.countryListItems.removeClass("active");
			listItem.addClass("active");
		},
		// called when the user selects a list item from the dropdown
		_selectListItem: function(listItem) {
			// update selected flag and active list item
			var countryCode = listItem.attr("data-country-code");
			this._selectFlag(countryCode);
			this._closeDropdown();
			// update input value
			this._updateName(countryCode);
			this.countryInput.trigger("change");
			this.countryCodeInput.trigger("change");
			// focus the input
			this._focus();
		},
		// close the dropdown and unbind any listeners
		_closeDropdown: function() {
			this.countryList.addClass("hide");
			// update the arrow
			this.selectedFlagInner.children(".arrow").removeClass("up");
			// unbind event listeners
			$(document).off("keydown" + this.ns);
			$("html").off("click" + this.ns);
			// unbind both hover and click listeners
			this.countryList.off(this.ns);
		},
		// check if an element is visible within its container, else scroll until it is
		_scrollTo: function(element) {
			if (!element || !element.offset()) {
				return;
			}
			var container = this.countryList, containerHeight = container.height(), containerTop = container.offset().top, containerBottom = containerTop + containerHeight, elementHeight = element.outerHeight(), elementTop = element.offset().top, elementBottom = elementTop + elementHeight, newScrollTop = elementTop - containerTop + container.scrollTop();
			if (elementTop < containerTop) {
				// scroll up
				container.scrollTop(newScrollTop);
			} else if (elementBottom > containerBottom) {
				// scroll down
				var heightDifference = containerHeight - elementHeight;
				container.scrollTop(newScrollTop - heightDifference);
			}
		},
		// Replace any existing country name with the new one
		_updateName: function(countryCode) {
			this.countryCodeInput.val(countryCode).trigger("change");
			this.countryInput.val(this._getCountryData(countryCode).name);
		},
		/********************
		 *  PUBLIC METHODS
		 ********************/
		// this is called when the geoip call returns
		handleAutoCountry: function() {
			if (this.options.initialCountry === "auto") {
				// we must set this even if there is an initial val in the input: in case the initial val is invalid and they delete it - they should see their auto country
				this.defaultCountry = $.fn[pluginName].autoCountry;
				// if there's no initial value in the input, then update the flag
				if (!this.countryInput.val()) {
					this.selectCountry(this.defaultCountry);
				}
				this.autoCountryDeferred.resolve();
			}
		},
		// get the country data for the currently selected flag
		getSelectedCountryData: function() {
			// rely on the fact that we only set 2 classes on the selected flag element:
			// the first is "flag" and the second is the 2-char country code
			var countryCode = this.selectedFlagInner.attr("class").split(" ")[1];
			return this._getCountryData(countryCode);
		},
		// update the selected flag
		selectCountry: function(countryCode) {
			countryCode = countryCode.toLowerCase();
			// check if already selected
			if (!this.selectedFlagInner.hasClass(countryCode)) {
				this._selectFlag(countryCode);
				this._updateName(countryCode);
			}
		},
		// set the input value and update the flag
		setCountry: function(country) {
			this.countryInput.val(country);
			this._updateFlagFromInputVal();
		},
		// remove plugin
		destroy: function() {
			// stop listeners
			this.countryInput.off(this.ns);
			this.selectedFlagInner.parent().off(this.ns);
			// remove markup
			var container = this.countryInput.parent();
			container.before(this.countryInput).remove();
		}
	};
	// adapted to allow public functions
	// using https://github.com/jquery-boilerplate/jquery-boilerplate/wiki/Extending-jQuery-Boilerplate
	$.fn[pluginName] = function(options) {
		var args = arguments;
		// Is the first parameter an object (options), or was omitted,
		// instantiate a new instance of the plugin.
		if (options === undefined || typeof options === "object") {
			return this.each(function() {
				if (!$.data(this, "plugin_" + pluginName)) {
					$.data(this, "plugin_" + pluginName, new Plugin(this, options));
				}
			});
		} else if (typeof options === "string" && options[0] !== "_" && options !== "init") {
			// If the first parameter is a string and it doesn't start
			// with an underscore or "contains" the `init`-function,
			// treat this as a call to a public method.
			// Cache the method call to make it possible to return a value
			var returns;
			this.each(function() {
				var instance = $.data(this, "plugin_" + pluginName);
				// Tests that there's already a plugin-instance
				// and checks that the requested public method exists
				if (instance instanceof Plugin && typeof instance[options] === "function") {
					// Call the method of our plugin instance,
					// and pass it the supplied arguments.
					returns = instance[options].apply(instance, Array.prototype.slice.call(args, 1));
				}
				// Allow instances to be destroyed via the 'destroy' method
				if (options === "destroy") {
					$.data(this, "plugin_" + pluginName, null);
				}
			});
			// If the earlier cached method gives a value back return the value,
			// otherwise return this to preserve chainability.
			return returns !== undefined ? returns : this;
		}
	};
	/********************
   *  STATIC METHODS
   ********************/
	// get the country data object
	$.fn[pluginName].getCountryData = function() {
		return allCountries;
	};
	// set the country data object
	$.fn[pluginName].setCountryData = function(obj) {
		allCountries = obj;
	};
	// Tell JSHint to ignore this warning: "character may get silently deleted by one or more browsers"
	// jshint -W100
	// Array of country objects for the flag dropdown.
	// Each contains a name and country code (ISO 3166-1 alpha-2).
	//
	// Note: using single char property names to keep filesize down
	// n = name
	// i = iso2 (2-char country code)

	var allCountries = $.each([
	{
		n: "Afghanistan (+ 93 )",
		i: "af",
		p: 93
	},
	{
		n: "Albania (+ 355 )",
		i: "al",
		p: 355
	},
	{
		n: "Algeria (+ 213 )",
		i: "dz",
		p: 213
	},
	{
		n: "American Samoa (+ 1684 )",
		i: "as",
		p: 1684
	},
	{
		n: "Andorra (+ 376 )",
		i: "ad",
		p: 376
	},
	{
		n: "Angola (+ 244 )",
		i: "ao",
		p: 244
	},
	{
		n: "Anguilla (+ 1264 )",
		i: "ai",
		p: 1264
	},
	{
		n: "Antarctica (+ 672 )",
		i: "aq",
		p: 672
	},
	{
		n: "Antigua and Barbuda (+ 1268 )",
		i: "ag",
		p: 1268
	},
	{
		n: "Argentina (+ 54 )",
		i: "ar",
		p: 54
	},
	{
		n: "Armenia (+ 374 )",
		i: "am",
		p: 374
	},
	{
		n: "Aruba (+ 297 )",
		i: "aw",
		p: 297
	},
	{
		n: "Australia (+ 61 )",
		i: "au",
		p: 61
	},
	{
		n: "Austria (+ 43 )",
		i: "at",
		p: 43
	},
	{
		n: "Azerbaijan (+ 994 )",
		i: "az",
		p: 994
	},
	{
		n: "Bahamas (+ 1242 )",
		i: "bs",
		p: 1242
	},
	{
		n: "Bahrain (+ 973 )",
		i: "bh",
		p: 973
	},
	{
		n: "Bangladesh (+ 880 )",
		i: "bd",
		p: 880
	},
	{
		n: "Barbados (+ 1246 )",
		i: "bb",
		p: 1246
	},
	{
		n: "Belarus (+ 375 )",
		i: "by",
		p: 375
	},
	{
		n: "Belgium (+ 32 )",
		i: "be",
		p: 32
	},
	{
		n: "Belize (+ 501 )",
		i: "bz",
		p: 501
	},
	{
		n: "Benin (+ 229 )",
		i: "bj",
		p: 229
	},
	{
		n: "Bermuda (+ 1441 )",
		i: "bm",
		p: 1441
	},
	{
		n: "Bhutan (+ 975 )",
		i: "bt",
		p: 975
	},
	{
		n: "Bolivia (+ 591 )",
		i: "bo",
		p: 591
	},
	{
		n: "Bosnia and Herzegovina (+ 387 )",
		i: "ba",
		p: 387
	},
	{
		n: "Botswana (+ 267 )",
		i: "bw",
		p: 267
	},
	{
		n: "Bouvet Island (+ 47 )",
		i: "bv",
		p: 47
	},
	{
		n: "Brazil (+ 55 )",
		i: "br",
		p: 55
	},
	{
		n: "British Indian Ocean Territory (+ 246 )",
		i: "io",
		p: 246
	},
	{
		n: "Brunei Darussalam (+ 673 )",
		i: "bn",
		p: 673
	},
	{
		n: "Bulgaria (+ 359 )",
		i: "bg",
		p: 359
	},
	{
		n: "Burkina Faso (+ 226 )",
		i: "bf",
		p: 226
	},
	{
		n: "Burundi (+ 257 )",
		i: "bi",
		p: 257
	},
	{
		n: "Cambodia (+ 855 )",
		i: "kh",
		p: 855
	},
	{
		n: "Cameroon (+ 237 )",
		i: "cm",
		p: 237
	},
	{
		n: "Canada (+ 1 )",
		i: "ca",
		p: 1
	},
	{
		n: "Cape Verde (+ 238 )",
		i: "cv",
		p: 238
	},
	{
		n: "Cayman Islands (+ 1345 )",
		i: "ky",
		p: 1345
	},
	{
		n: "Central African Republic (+ 236 )",
		i: "cf",
		p: 236
	},
	{
		n: "Chad (+ 235 )",
		i: "td",
		p: 235
	},
	{
		n: "Chile (+ 56 )",
		i: "cl",
		p: 56
	},
	{
		n: "China (+ 86 )",
		i: "cn",
		p: 86
	},
	{
		n: "Christmas Island (+ 61 )",
		i: "cx",
		p: 61
	},
	{
		n: "Cocos (Keeling) Islands (+ 672 )",
		i: "cc",
		p: 672
	},
	{
		n: "Colombia (+ 57 )",
		i: "co",
		p: 57
	},
	{
		n: "Comoros (+ 269 )",
		i: "km",
		p: 269
	},
	{
		n: "Congo (+ 242 )",
		i: "cg",
		p: 242
	},
	{
		n: "Congo, the Democratic Republic of the (+ 243 )",
		i: "cd",
		p: 243
	},
	{
		n: "Cook Islands (+ 682 )",
		i: "ck",
		p: 682
	},
	{
		n: "Costa Rica (+ 506 )",
		i: "cr",
		p: 506
	},
	{
		n: "Cote D'Ivoire (+ 225 )",
		i: "ci",
		p: 225
	},
	{
		n: "Croatia (+ 385 )",
		i: "hr",
		p: 385
	},
	{
		n: "Cuba (+ 53 )",
		i: "cu",
		p: 53
	},
	{
		n: "Cyprus (+ 357 )",
		i: "cy",
		p: 357
	},
	{
		n: "Czech Republic (+ 420 )",
		i: "cz",
		p: 420
	},
	{
		n: "Denmark (+ 45 )",
		i: "dk",
		p: 45
	},
	{
		n: "Djibouti (+ 253 )",
		i: "dj",
		p: 253
	},
	{
		n: "Dominica (+ 1767 )",
		i: "dm",
		p: 1767
	},
	{
		n: "Dominican Republic (+ 1809 )",
		i: "do",
		p: 1809
	},
	{
		n: "Ecuador (+ 593 )",
		i: "ec",
		p: 593
	},
	{
		n: "Egypt (+ 20 )",
		i: "eg",
		p: 20
	},
	{
		n: "El Salvador (+ 503 )",
		i: "sv",
		p: 503
	},
	{
		n: "Equatorial Guinea (+ 240 )",
		i: "gq",
		p: 240
	},
	{
		n: "Eritrea (+ 291 )",
		i: "er",
		p: 291
	},
	{
		n: "Estonia (+ 372 )",
		i: "ee",
		p: 372
	},
	{
		n: "Ethiopia (+ 251 )",
		i: "et",
		p: 251
	},
	{
		n: "Falkland Islands (Malvinas) (+ 500 )",
		i: "fk",
		p: 500
	},
	{
		n: "Faroe Islands (+ 298 )",
		i: "fo",
		p: 298
	},
	{
		n: "Fiji (+ 679 )",
		i: "fj",
		p: 679
	},
	{
		n: "Finland (+ 358 )",
		i: "fi",
		p: 358
	},
	{
		n: "France (+ 33 )",
		i: "fr",
		p: 33
	},
	{
		n: "French Guiana (+ 594 )",
		i: "gf",
		p: 594
	},
	{
		n: "French Polynesia (+ 689 )",
		i: "pf",
		p: 689
	},
	{
		n: "French Southern Territories (+ 262 )",
		i: "tf",
		p: 262
	},
	{
		n: "Gabon (+ 241 )",
		i: "ga",
		p: 241
	},
	{
		n: "Gambia (+ 220 )",
		i: "gm",
		p: 220
	},
	{
		n: "Georgia (+ 995 )",
		i: "ge",
		p: 995
	},
	{
		n: "Germany (+ 49 )",
		i: "de",
		p: 49
	},
	{
		n: "Ghana (+ 233 )",
		i: "gh",
		p: 233
	},
	{
		n: "Gibraltar (+ 350 )",
		i: "gi",
		p: 350
	},
	{
		n: "Greece (+ 30 )",
		i: "gr",
		p: 30
	},
	{
		n: "Greenland (+ 299 )",
		i: "gl",
		p: 299
	},
	{
		n: "Grenada (+ 1473 )",
		i: "gd",
		p: 1473
	},
	{
		n: "Guadeloupe (+ 590 )",
		i: "gp",
		p: 590
	},
	{
		n: "Guam (+ 1671 )",
		i: "gu",
		p: 1671
	},
	{
		n: "Guatemala (+ 502 )",
		i: "gt",
		p: 502
	},
	{
		n: "Guinea (+ 224 )",
		i: "gn",
		p: 224
	},
	{
		n: "Guinea-Bissau (+ 245 )",
		i: "gw",
		p: 245
	},
	{
		n: "Guyana (+ 592 )",
		i: "gy",
		p: 592
	},
	{
		n: "Haiti (+ 509 )",
		i: "ht",
		p: 509
	},
	{
		n: "Heard Island and Mcdonald Islands (+ 0 )",
		i: "hm",
		p: 0
	},
	{
		n: "Holy See (Vatican City State) (+ 379 )",
		i: "va",
		p: 379
	},
	{
		n: "Honduras (+ 504 )",
		i: "hn",
		p: 504
	},
	{
		n: "Hong Kong (+ 852 )",
		i: "hk",
		p: 852
	},
	{
		n: "Hungary (+ 36 )",
		i: "hu",
		p: 36
	},
	{
		n: "Iceland (+ 354 )",
		i: "is",
		p: 354
	},
	{
		n: "India (+ 91 )",
		i: "in",
		p: 91
	},
	{
		n: "Indonesia (+ 62 )",
		i: "id",
		p: 62
	},
	{
		n: "Iran, Islamic Republic of (+ 98 )",
		i: "ir",
		p: 98
	},
	{
		n: "Iraq (+ 964 )",
		i: "iq",
		p: 964
	},
	{
		n: "Ireland (+ 353 )",
		i: "ie",
		p: 353
	},
	{
		n: "Israel (+ 972 )",
		i: "il",
		p: 972
	},
	{
		n: "Italy (+ 39 )",
		i: "it",
		p: 39
	},
	{
		n: "Jamaica (+ 1876 )",
		i: "jm",
		p: 1876
	},
	{
		n: "Japan (+ 81 )",
		i: "jp",
		p: 81
	},
	{
		n: "Jordan (+ 962 )",
		i: "jo",
		p: 962
	},
	{
		n: "Kazakhstan (+ 7 )",
		i: "kz",
		p: 7
	},
	{
		n: "Kenya (+ 254 )",
		i: "ke",
		p: 254
	},
	{
		n: "Kiribati (+ 686 )",
		i: "ki",
		p: 686
	},
	{
		n: "Korea, Democratic People's Republic of (+ 850 )",
		i: "kp",
		p: 850
	},
	{
		n: "Korea, Republic of (+ 82 )",
		i: "kr",
		p: 82
	},
	{
		n: "Kuwait (+ 965 )",
		i: "kw",
		p: 965
	},
	{
		n: "Kyrgyzstan (+ 996 )",
		i: "kg",
		p: 996
	},
	{
		n: "Lao People's Democratic Republic (+ 856 )",
		i: "la",
		p: 856
	},
	{
		n: "Latvia (+ 371 )",
		i: "lv",
		p: 371
	},
	{
		n: "Lebanon (+ 961 )",
		i: "lb",
		p: 961
	},
	{
		n: "Lesotho (+ 266 )",
		i: "ls",
		p: 266
	},
	{
		n: "Liberia (+ 231 )",
		i: "lr",
		p: 231
	},
	{
		n: "Libyan Arab Jamahiriya (+ 218 )",
		i: "ly",
		p: 218
	},
	{
		n: "Liechtenstein (+ 423 )",
		i: "li",
		p: 423
	},
	{
		n: "Lithuania (+ 370 )",
		i: "lt",
		p: 370
	},
	{
		n: "Luxembourg (+ 352 )",
		i: "lu",
		p: 352
	},
	{
		n: "Macao (+ 853 )",
		i: "mo",
		p: 853
	},
	{
		n: "Macedonia, the Former Yugoslav Republic of (+ 389 )",
		i: "mk",
		p: 389
	},
	{
		n: "Madagascar (+ 261 )",
		i: "mg",
		p: 261
	},
	{
		n: "Malawi (+ 265 )",
		i: "mw",
		p: 265
	},
	{
		n: "Malaysia (+ 60 )",
		i: "my",
		p: 60
	},
	{
		n: "Maldives (+ 960 )",
		i: "mv",
		p: 960
	},
	{
		n: "Mali (+ 223 )",
		i: "ml",
		p: 223
	},
	{
		n: "Malta (+ 356 )",
		i: "mt",
		p: 356
	},
	{
		n: "Marshall Islands (+ 692 )",
		i: "mh",
		p: 692
	},
	{
		n: "Martinique (+ 596 )",
		i: "mq",
		p: 596
	},
	{
		n: "Mauritania (+ 222 )",
		i: "mr",
		p: 222
	},
	{
		n: "Mauritius (+ 230 )",
		i: "mu",
		p: 230
	},
	{
		n: "Mayotte (+ 262 )",
		i: "yt",
		p: 262
	},
	{
		n: "Mexico (+ 52 )",
		i: "mx",
		p: 52
	},
	{
		n: "Micronesia, Federated States of (+ 691 )",
		i: "fm",
		p: 691
	},
	{
		n: "Moldova, Republic of (+ 373 )",
		i: "md",
		p: 373
	},
	{
		n: "Monaco (+ 377 )",
		i: "mc",
		p: 377
	},
	{
		n: "Mongolia (+ 976 )",
		i: "mn",
		p: 976
	},
	{
		n: "Montserrat (+ 1664 )",
		i: "ms",
		p: 1664
	},
	{
		n: "Morocco (+ 212 )",
		i: "ma",
		p: 212
	},
	{
		n: "Mozambique (+ 258 )",
		i: "mz",
		p: 258
	},
	{
		n: "Myanmar (+ 95 )",
		i: "mm",
		p: 95
	},
	{
		n: "Namibia (+ 264 )",
		i: "na",
		p: 264
	},
	{
		n: "Nauru (+ 674 )",
		i: "nr",
		p: 674
	},
	{
		n: "Nepal (+ 977 )",
		i: "np",
		p: 977
	},
	{
		n: "Netherlands (+ 31 )",
		i: "nl",
		p: 31
	},
	{
		n: "Netherlands Antilles (+ 599 )",
		i: "an",
		p: 599
	},
	{
		n: "New Caledonia (+ 687 )",
		i: "nc",
		p: 687
	},
	{
		n: "New Zealand (+ 64 )",
		i: "nz",
		p: 64
	},
	{
		n: "Nicaragua (+ 505 )",
		i: "ni",
		p: 505
	},
	{
		n: "Niger (+ 227 )",
		i: "ne",
		p: 227
	},
	{
		n: "Nigeria (+ 234 )",
		i: "ng",
		p: 234
	},
	{
		n: "Niue (+ 683 )",
		i: "nu",
		p: 683
	},
	{
		n: "Norfolk Island (+ 672 )",
		i: "nf",
		p: 672
	},
	{
		n: "Northern Mariana Islands (+ 1670 )",
		i: "mp",
		p: 1670
	},
	{
		n: "Norway (+ 47 )",
		i: "no",
		p: 47
	},
	{
		n: "Oman (+ 968 )",
		i: "om",
		p: 968
	},
	{
		n: "Pakistan (+ 92 )",
		i: "pk",
		p: 92
	},
	{
		n: "Palau (+ 680 )",
		i: "pw",
		p: 680
	},
	{
		n: "Palestinian Territory, Occupied (+ 970 )",
		i: "ps",
		p: 970
	},
	{
		n: "Panama (+ 507 )",
		i: "pa",
		p: 507
	},
	{
		n: "Papua New Guinea (+ 675 )",
		i: "pg",
		p: 675
	},
	{
		n: "Paraguay (+ 595 )",
		i: "py",
		p: 595
	},
	{
		n: "Peru (+ 51 )",
		i: "pe",
		p: 51
	},
	{
		n: "Philippines (+ 63 )",
		i: "ph",
		p: 63
	},
	{
		n: "Pitcairn (+ 64 )",
		i: "pn",
		p: 64
	},
	{
		n: "Poland (+ 48 )",
		i: "pl",
		p: 48
	},
	{
		n: "Portugal (+ 351 )",
		i: "pt",
		p: 351
	},
	{
		n: "Puerto Rico (+ 1787 )",
		i: "pr",
		p: 1787
	},
	{
		n: "Qatar (+ 974 )",
		i: "qa",
		p: 974
	},
	{
		n: "Reunion (+ 262 )",
		i: "re",
		p: 262
	},
	{
		n: "Romania (+ 40 )",
		i: "ro",
		p: 40
	},
	{
		n: "Russian Federation (+ 7 )",
		i: "ru",
		p: 7
	},
	{
		n: "Rwanda (+ 250 )",
		i: "rw",
		p: 250
	},
	{
		n: "Saint Helena (+ 290 )",
		i: "sh",
		p: 290
	},
	{
		n: "Saint Kitts and Nevis (+ 1869 )",
		i: "kn",
		p: 1869
	},
	{
		n: "Saint Lucia (+ 1758 )",
		i: "lc",
		p: 1758
	},
	{
		n: "Saint Pierre and Miquelon (+ 508 )",
		i: "pm",
		p: 508
	},
	{
		n: "Saint Vincent and the Grenadines (+ 1784 )",
		i: "vc",
		p: 1784
	},
	{
		n: "Samoa (+ 685 )",
		i: "ws",
		p: 685
	},
	{
		n: "San Marino (+ 378 )",
		i: "sm",
		p: 378
	},
	{
		n: "Sao Tome and Principe (+ 239 )",
		i: "st",
		p: 239
	},
	{
		n: "Saudi Arabia (+ 966 )",
		i: "sa",
		p: 966
	},
	{
		n: "Senegal (+ 221 )",
		i: "sn",
		p: 221
	},
	{
		n: "Serbia and Montenegro (+ 381 )",
		i: "cs",
		p: 381
	},
	{
		n: "Seychelles (+ 248 )",
		i: "sc",
		p: 248
	},
	{
		n: "Sierra Leone (+ 232 )",
		i: "sl",
		p: 232
	},
	{
		n: "Singapore (+ 65 )",
		i: "sg",
		p: 65
	},
	{
		n: "Slovakia (+ 421 )",
		i: "sk",
		p: 421
	},
	{
		n: "Slovenia (+ 386 )",
		i: "si",
		p: 386
	},
	{
		n: "Solomon Islands (+ 677 )",
		i: "sb",
		p: 677
	},
	{
		n: "Somalia (+ 252 )",
		i: "so",
		p: 252
	},
	{
		n: "South Africa (+ 27 )",
		i: "za",
		p: 27
	},
	{
		n: "South Georgia and the South Sandwich Islands (+ 500 )",
		i: "gs",
		p: 500
	},
	{
		n: "Spain (+ 34 )",
		i: "es",
		p: 34
	},
	{
		n: "Sri Lanka (+ 94 )",
		i: "lk",
		p: 94
	},
	{
		n: "Sudan (+ 249 )",
		i: "sd",
		p: 249
	},
	{
		n: "Suriname (+ 597 )",
		i: "sr",
		p: 597
	},
	{
		n: "Svalbard and Jan Mayen (+ 47 )",
		i: "sj",
		p: 47
	},
	{
		n: "Swaziland (+ 268 )",
		i: "sz",
		p: 268
	},
	{
		n: "Sweden (+ 46 )",
		i: "se",
		p: 46
	},
	{
		n: "Switzerland (+ 41 )",
		i: "ch",
		p: 41
	},
	{
		n: "Syrian Arab Republic (+ 963 )",
		i: "sy",
		p: 963
	},
	{
		n: "Taiwan, Province of China (+ 886 )",
		i: "tw",
		p: 886
	},
	{
		n: "Tajikistan (+ 992 )",
		i: "tj",
		p: 992
	},
	{
		n: "Tanzania, United Republic of (+ 255 )",
		i: "tz",
		p: 255
	},
	{
		n: "Thailand (+ 66 )",
		i: "th",
		p: 66
	},
	{
		n: "Timor-Leste (+ 670 )",
		i: "tl",
		p: 670
	},
	{
		n: "Togo (+ 228 )",
		i: "tg",
		p: 228
	},
	{
		n: "Tokelau (+ 690 )",
		i: "tk",
		p: 690
	},
	{
		n: "Tonga (+ 676 )",
		i: "to",
		p: 676
	},
	{
		n: "Trinidad and Tobago (+ 1868 )",
		i: "tt",
		p: 1868
	},
	{
		n: "Tunisia (+ 216 )",
		i: "tn",
		p: 216
	},
	{
		n: "Turkey (+ 90 )",
		i: "tr",
		p: 90
	},
	{
		n: "Turkmenistan (+ 993 )",
		i: "tm",
		p: 993
	},
	{
		n: "Turks and Caicos Islands (+ 1649 )",
		i: "tc",
		p: 1649
	},
	{
		n: "Tuvalu (+ 688 )",
		i: "tv",
		p: 688
	},
	{
		n: "Uganda (+ 256 )",
		i: "ug",
		p: 256
	},
	{
		n: "Ukraine (+ 380 )",
		i: "ua",
		p: 380
	},
	{
		n: "United Arab Emirates (+ 971 )",
		i: "ae",
		p: 971
	},
	{
		n: "United Kingdom (+ 44 )",
		i: "gb",
		p: 44
	},
	{
		n: "United States (+ 1 )",
		i: "us",
		p: 1
	},
	{
		n: "United States Minor Outlying Islands (+ 1 )",
		i: "um",
		p: 1
	},
	{
		n: "Uruguay (+ 598 )",
		i: "uy",
		p: 598
	},
	{
		n: "Uzbekistan (+ 998 )",
		i: "uz",
		p: 998
	},
	{
		n: "Vanuatu (+ 678 )",
		i: "vu",
		p: 678
	},
	{
		n: "Venezuela (+ 58 )",
		i: "ve",
		p: 58
	},
	{
		n: "Viet Nam (+ 84 )",
		i: "vn",
		p: 84
	},
	{
		n: "Virgin Islands, British (+ 1284 )",
		i: "vg",
		p: 1284
	},
	{
		n: "Virgin Islands, U.s. (+ 1340 )",
		i: "vi",
		p: 1340
	},
	{
		n: "Wallis and Futuna (+ 681 )",
		i: "wf",
		p: 681
	},
	{
		n: "Western Sahara (+ 212 )",
		i: "eh",
		p: 212
	},
	{
		n: "Yemen (+ 967 )",
		i: "ye",
		p: 967
	},
	{
		n: "Zambia (+ 260 )",
		i: "zm",
		p: 260
	},
	{
		n: "Zimbabwe (+ 263 )",
		i: "zw",
		p: 263
	}
], function(i, c) {
		c.name = c.n;
		c.iso2 = c.i;
		c.isd = c.p;
		delete c.n;
		delete c.i;
		delete c.p;
	});
});
