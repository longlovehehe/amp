/*
International Telephone Input v1.1.12
https://github.com/Bluefieldscom/intl-tel-input.git
*/
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
    var pluginName = "currencyCode", id = 1, // give each instance it's own id for namespaced event handling
    defaults = {
        // don't insert international dial codes
        nationalMode: false,
        // if there is just a dial code in the input: remove it on blur, and re-add it on focus
        autoHideDialCode: true,
        // default country
        defaultCountry: "sg",
        // character to appear between dial code and phone number
        dialCodeDelimiter: "",
        // position the selected flag inside or outside of the input
        defaultStyling: "outside",
        // display only these countries
        onlyCountries: [],
        // the countries at the top of the list. defaults to united states and united kingdom
        preferredCountries: [ "sg", "my","hk","id" ],
        // specify the path to the libphonenumber script to enable validation
        validationScript: ""
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
    $(window).load(function() {
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
            // process all the data: onlyCounties, preferredCountries, defaultCountry etc
            this._processCountryData();
            // generate the markup
            this._generateMarkup();
            // set the initial state of the input value and the selected flag
            this._setInitialState();
            // start all of the event listeners: autoHideDialCode, input keyup, selectedFlag click
            this._initListeners();
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
                var newCountries = [], newCountryCodes = {};
                $.each(this.options.onlyCountries, function(i, countryCode) {
                    var countryData = that._getCountryData(countryCode, true);
                    if (countryData) {
                        newCountries.push(countryData);
                        // add this country's dial code to the countryCodes
                        var dialCode = countryData.dialCode;
                        if (newCountryCodes[dialCode]) {
                            newCountryCodes[dialCode].push(countryCode);
                        } else {
                            newCountryCodes[dialCode] = [ countryCode ];
                        }
                    }
                });
                // maintain country priority
                for (var dialCode in newCountryCodes) {
                    if (newCountryCodes[dialCode].length > 1) {
                        var sortedCountries = [];
                        // go through all of the allCountryCodes countries for this dialCode and create a new (ordered) array of values (if they're in the newCountryCodes array)
                        for (var i = 0; i < allCountryCodes[dialCode].length; i++) {
                            var country = allCountryCodes[dialCode][i];
                            if ($.inArray(newCountryCodes[dialCode], country)) {
                                sortedCountries.push(country);
                            }
                        }
                        newCountryCodes[dialCode] = sortedCountries;
                    }
                }
                this.countries = newCountries;
                this.countryCodes = newCountryCodes;
            } else {
                this.countries = allCountries;
                this.countryCodes = allCountryCodes;
            }
        },
        // process preferred countries - iterate through the preferences,
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
            // telephone input
            this.telInput = $(this.element);
            // containers (mostly for positioning)
            var mainClass = "intl-tel-input";
            if (this.options.defaultStyling) {
                mainClass += " " + this.options.defaultStyling;
            }
            this.telInput.wrap($("<div>", {
                "class": mainClass
            }));
            var flagsContainer = $("<div>", {
                "class": "flag-dropdown"
            }).insertAfter(this.telInput);
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
            // now we can grab the dropdown height, and hide it properly
            this.dropdownHeight = this.countryList.outerHeight();
            this.countryList.removeClass("v-hide").addClass("hide");
            // this is useful in lots of places
            this.countryListItems = this.countryList.children(".country");
        },
        // add a country <li> to the countryList <ul> container
        _appendListItems: function(countries, className) {
            // we create so many DOM elements, I decided it was faster to build a temp string
            // and then add everything to the DOM in one go at the end
            var tmp = "";
            // for each country
            $.each(countries, function(i, c) {
                if(c.dialCode==undefined){
                    
                }else{
                     // open the list item
                tmp += "<li class='country " + className + "' data-dial-code='" + c.dialCode + "' data-country-code='" + c.iso2 + "'>";
                // add the flag
                tmp += "<div class='flag " + c.iso2 + "'></div>";
                // and the country name and dial code
                tmp += "<span class='country-name'>" + c.name + "</span>";
                tmp += "<span class='dial-code'>" + c.dialCode + "</span>";
                // close the list item
                tmp += "</li>";
                }
               
            });
            this.countryList.append(tmp);
        },
        // set the initial state of the input value and the selected flag
        _setInitialState: function() {
            var flagIsSet = false;
            // if the input is pre-populated, then just update the selected flag accordingly
            // however, if no valid international dial code was found, flag will not have been set
            if (this.telInput.val()) {
                flagIsSet = this._updateFlagFromInputVal();
            }
            if (!flagIsSet) {
                // flag is not set, so set to the default country
                var defaultCountry;
                // check the defaultCountry option, else fall back to the first in the list
                if (this.options.defaultCountry) {
                    defaultCountry = this._getCountryData(this.options.defaultCountry, false);
                } else {
                    defaultCountry = this.preferredCountries.length ? this.preferredCountries[0] : this.countries[0];
                }
                this._selectFlag(defaultCountry.iso2);
                var countryData = this._getCountryData(defaultCountry.iso2);
                this._updateNumber(countryData.dialCode);
                // if autoHideDialCode is disabled, insert the default dial code
                if (!this.options.autoHideDialCode) {
                    this._resetToDialCode(defaultCountry.dialCode);
                }
            }
        },
        // initialise the main event listeners: input keyup, and click selected flag
        _initListeners: function() {
            var that = this;
            // auto hide dial code option (ignore if in national mode)
            if (this.options.autoHideDialCode && !this.options.nationalMode) {
                this._initAutoHideDialCode();
            }
            // update flag on keyup (by extracting the dial code from the input value).
            // use keyup instead of keypress because we want to update on backspace
            // and instead of keydown because the value hasn't updated when that event is fired
            // NOTE: better to have this one listener all the time instead of starting it on focus
            // and stopping it on blur, because then you've got two listeners (focus and blur)
            this.telInput.on("keyup" + this.ns, function() {
                that._updateFlagFromInputVal();
            });
            // toggle country dropdown on click
            var selectedFlag = this.selectedFlagInner.parent();
            selectedFlag.on("click" + this.ns, function(e) {
                // only intercept this event if we're opening the dropdown
                // else let it bubble up to the top ("click-off-to-close" listener)
                // we cannot just stopPropagation as it may be needed to close another instance
                if (that.countryList.hasClass("hide") && !that.telInput.prop("disabled")) {
                    that._showDropdown();
                }
            });
            // if the user has specified the path to the validation script
            // inject a new script element for it at the end of the body
            if (this.options.validationScript) {
                var injectValidationScript = function() {
                    var script = document.createElement("script");
                    script.type = "text/javascript";
                    script.src = that.options.validationScript;
                    document.body.appendChild(script);
                };
                // if the plugin is being initialised after the window.load event has already been fired
                if (windowLoaded) {
                    injectValidationScript();
                } else {
                    // wait until the load event so we don't block any other requests e.g. the flags image
                    $(window).load(injectValidationScript);
                }
            }
        },
        // on focus: if empty add dial code. on blur: if just dial code, then empty it
        _initAutoHideDialCode: function() {
            var that = this;
            // mousedown decides where the cursor goes, so if we're focusing
            // we must prevent this from happening
            this.telInput.on("mousedown" + this.ns, function(e) {
                if (!that.telInput.is(":focus") && !that.telInput.val()) {
                    e.preventDefault();
                    // but this also cancels the focus, so we must trigger that manually
                    that._focus();
                }
            });
            // on focus: if empty, insert the dial code for the currently selected flag
            this.telInput.on("focus" + this.ns, function() {
                if (!$.trim(that.telInput.val())) {
                    var countryData = that.getSelectedCountryData();
                    //alert(countryData.dialCode);
                    that._resetToDialCode(countryData.dialCode);
                    // after auto-inserting a dial code, if the first key they hit is '+' then assume
                    // they are entering a new number, so remove the dial code.
                    // use keypress instead of keydown because keydown gets triggered for the shift key
                    // (required to hit the + key), and instead of keyup because that shows the new '+'
                    // before removing the old one
                    that.telInput.one("keypress" + that.ns, function(e) {
                        if (e.which == keys.PLUS) {
                            that.telInput.val("");
                        }
                    });
                }
            });
            // on blur: if just a dial code then remove it
            this.telInput.on("blur" + this.ns, function() {
                var value = $.trim(that.telInput.val());
                if (value) {
                    if ($.trim(that._getDialCode(value) + that.options.dialCodeDelimiter) == value) {
                       // that.telInput.val("");
                    }
                }
                that.telInput.off("keypress" + that.ns);
            });
        },
        // focus input and put the cursor at the end
        _focus: function() {
            this.telInput.focus();
            var input = this.telInput[0];
            // works for Chrome, FF, Safari, IE9+
            if (input.setSelectionRange) {
                var len = this.telInput.val().length;
                input.setSelectionRange(len, len);
            }
        },
        // show the dropdown
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
            var inputTop = this.telInput.offset().top, windowTop = $(window).scrollTop(), // dropdownFitsBelow = (dropdownBottom < windowBottom)
            dropdownFitsBelow = inputTop + this.telInput.outerHeight() + this.dropdownHeight < windowTop + $(window).height(), dropdownFitsAbove = inputTop - this.dropdownHeight > windowTop;
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
            // listen for up/down scrolling, enter to select, or letters to jump to country name.
            // use keydown as keypress doesn't fire for non-char keys and we want to catch if they
            // just hit down and hold it to scroll down (no keyup event).
            // listen on the document because that's where key events are triggered if no input has focus
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
        // highlight the next/prev item in the list (and ensure it is visible)
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
        // iterate through the countries starting with the given letter
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
        // update the selected flag using the input's current value
        _updateFlagFromInputVal: function() {
            var that = this;
            // try and extract valid dial code from input
            var dialCode = this._getDialCode(this.telInput.val());
            if (dialCode) {
                // check if one of the matching countries is already selected
                var countryCodes = this.countryCodes[dialCode.replace(/\d/g, "")], alreadySelected = false;

                $.each(countryCodes, function(i, c) {
                    if (that.selectedFlagInner.hasClass(c)) {
                        alreadySelected = true;
                    }
                });
                if (!alreadySelected) {
                    this._selectFlag(countryCodes[0]);
                }
                // valid international dial code found
                return true;
            }
            // valid international dial code not found
            return false;
        },
        // reset the input value to just a dial code
        _resetToDialCode: function(dialCode) {
            // if nationalMode is enabled then don't insert the dial code
            var value = this.options.nationalMode ? "" : dialCode + this.options.dialCodeDelimiter;
            this.telInput.val(value);
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
            this.selectedFlagInner.attr("class", "flag " + countryCode);
            // update the title attribute
            var countryData = this._getCountryData(countryCode);
            this.selectedFlagInner.parent().attr("title", countryData.name + ": +" + countryData.dialCode);
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
            if (!this.options.nationalMode) {
                this._updateNumber("" + listItem.attr("data-dial-code"));
                this.telInput.trigger("change");
            }
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
        // check if an element is visible within it's container, else scroll until it is
        _scrollTo: function(element) {
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
        // replace any existing dial code with the new one
        _updateNumber: function(newDialCode) {
            var inputVal = this.telInput.val(), prevDialCode = this._getDialCode(inputVal), newNumber;
            // if the previous number contained a valid dial code, replace it
            // (if more than just a plus character)
            if (prevDialCode.length > 1) { 
                newNumber = inputVal.replace(prevDialCode, newDialCode);
                newNumber=newNumber.substr(0,3);
                // if the old number was just the dial code,
                // then we will need to add the space again
                if (inputVal == prevDialCode) {
                    newNumber = this.options.dialCodeDelimiter;
                }
            } else {
                // if the previous number didn't contain a dial code, we should persist it
                var existingNumber = inputVal && inputVal.substr(0, 1) != "+" ? $.trim(inputVal) : "";
                newNumber = newDialCode + this.options.dialCodeDelimiter + existingNumber;
            }
            var arr=newNumber.split(" ");
            this.telInput.val(arr[0]+"");
        },
        // try and extract a valid international dial code from a full telephone number
        // Note: returns the raw string inc plus character and any whitespace/dots etc
        _getDialCode: function(inputVal) {
            var dialCode = "";
            inputVal = $.trim(inputVal);
            // only interested in international numbers (starting with a plus)
            //if (inputVal.charAt(0) == "+") {
                var numericChars = "";
                // iterate over chars
                for (var i = 0; i < inputVal.length; i++) {
                    var c = inputVal.charAt(i);
                    // if char is number
                    if (!$.isNumeric(c)) {
                        numericChars += c;
                        // if current numericChars make a valid dial code
                        if (this.countryCodes[numericChars]) {
                            // store the actual raw string (useful for matching later)
                            dialCode = inputVal.substring(0, i + 1);
                        }
                        // longest dial code is 4 chars
                        if (numericChars.length == 4) {
                            break;
                        }
                    }
                }
            //}
            return dialCode;
        },
        /********************
     *  PUBLIC METHODS
     ********************/
        // get the country data for the currently selected flag
        getSelectedCountryData: function() {
            // rely on the fact that we only set 2 classes on the selected flag element:
            // the first is "flag" and the second is the 2-char country code
            var countryCode = this.selectedFlagInner.attr("class").split(" ")[1];
            return this._getCountryData(countryCode);
        },
        // validate the input val - assumes the global function isValidNumber
        // pass in true if you want to allow national numbers (no country dial code)
        isValidNumber: function(allowNational) {
            var val = $.trim(this.telInput.val()), countryData = this.getSelectedCountryData(), countryCode = allowNational ? countryData.iso2 : "";
            return window.isValidNumber(val, countryCode);
        },
        // update the selected flag, and insert the dial code
        selectCountry: function(countryCode) {
            // check if already selected
            if (!this.selectedFlagInner.hasClass(countryCode)) {
                this._selectFlag(countryCode);
                if (!this.options.autoHideDialCode) {
                    var countryData = this._getCountryData(countryCode, false);
                    this._resetToDialCode(countryData.dialCode);
                }
            }
        },
        // set the input value and update the flag
        setNumber: function(number) {
            this.telInput.val(number);
            this._updateFlagFromInputVal();
        },
        // remove plugin
        destroy: function() {
            // stop listeners
            this.telInput.off(this.ns);
            this.selectedFlagInner.parent().off(this.ns);
            // remove markup
            var container = this.telInput.parent();
            container.before(this.telInput).remove();
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
    // Each contains a name, country code (ISO 3166-1 alpha-2) and dial code.
    // Originally from https://github.com/mledoze/countries
    // then modified using the following JavaScript:
    /*
var result = [];
_.each(countries, function(c) {
  // ignore countries without a dial code
  if (c.callingCode[0].length) {
    result.push({
      // var locals contains country names with localised versions in brackets
      n: _.findWhere(locals, {
        countryCode: c.cca2
      }).name,
      i: c.cca2.toLowerCase(),
      d: c.callingCode[0]
    });
  }
});
JSON.stringify(result);
*/
    // then with a couple of manual re-arrangements to be alphabetical
    // then changed Kazakhstan from +76 to +7
    // then manually removed quotes from property names as not required
    // Note: using single char property names to keep filesize down
    // n = name
    // i = iso2 (2-char country code)
    // d = dial code
    var allCountries = $.each([ {
        n: "Afghanistan (‫افغانستان‬‎)",
        i: "af",
        d: "93",
        c:"AFA"
    }, {
        n: "Åland Islands (Åland)",
        i: "ax",
        d: "358"
    }, {
        n: "Albania (Shqipëri)",
        i: "al",
        d: "355",
        c:"ALL"
    }, {
        n: "Algeria (‫الجزائر‬‎)",
        i: "dz",
        d: "213",
        c:"DZD"
    }, {
        n: "American Samoa",
        i: "as",
        d: "1684"
    }, {
        n: "Andorra",
        i: "ad",
        d: "376",
        c:"FRF"
    }, {
        n: "Angola",
        i: "ao",
        d: "244",
        c:"AON"
    }, {
        n: "Anguilla",
        i: "ai",
        d: "1264",
        c:"XCD"
    }, {
        n: "Antigua and Barbuda",
        i: "ag",
        d: "1268",
        c:"XCD"
    }, {
        n: "Argentina",
        i: "ar",
        d: "54",
        c:"ARP"
    }, {
        n: "Armenia (Հայաստան)",
        i: "am",
        d: "374",
        c:"AMD"
    }, {
        n: "Aruba",
        i: "aw",
        d: "297"
    }, {
        n: "Australia",
        i: "au",
        d: "61",
        c:"AUD"
    }, {
        n: "Austria (Österreich)",
        i: "at",
        d: "43",
        c:"ATS"
    }, {
        n: "Azerbaijan (Azərbaycan)",
        i: "az",
        d: "994",
        c:"AZM"
    }, {
        n: "Bahamas",
        i: "bs",
        d: "1242"
    }, {
        n: "Bahrain (‫البحرين‬‎)",
        i: "bh",
        d: "973"
    }, {
        n: "Bangladesh (বাংলাদেশ)",
        i: "bd",
        d: "880"
    }, {
        n: "Barbados",
        i: "bb",
        d: "1246"
    }, {
        n: "Belarus (Беларусь)",
        i: "by",
        d: "375",
        c:"RUR"
    }, {
        n: "Belgium (België)",
        i: "be",
        d: "32",
        c:"BEF"
    }, {
        n: "Belize",
        i: "bz",
        d: "501",
        c:"BZD"
    }, {
        n: "Benin (Bénin)",
        i: "bj",
        d: "229"
    }, {
        n: "Bermuda",
        i: "bm",
        d: "1441"
    }, {
        n: "Bhutan (འབྲུག)",
        i: "bt",
        d: "975"
    }, {
        n: "Bolivia",
        i: "bo",
        d: "591"
    }, {
        n: "Bosnia and Herzegovina (Босна и Херцеговина)",
        i: "ba",
        d: "387"
    }, {
        n: "Botswana",
        i: "bw",
        d: "267"
    }, {
        n: "Brazil (Brasil)",
        i: "br",
        d: "55",
        c:"BRL"
    }, {
        n: "British Indian Ocean Territory",
        i: "io",
        d: "246"
    }, {
        n: "British Virgin Islands",
        i: "vg",
        d: "1284"
    }, {
        n: "Brunei",
        i: "bn",
        d: "673",
        c:"BND"
    }, {
        n: "Bulgaria (България)",
        i: "bg",
        d: "359",
        c:"BGL"
    }, {
        n: "Burkina Faso",
        i: "bf",
        d: "226"
    }, {
        n: "Burundi (Uburundi)",
        i: "bi",
        d: "257"
    }, {
        n: "Cambodia (កម្ពុជា)",
        i: "kh",
        d: "855"
    }, {
        n: "Cameroon (Cameroun)",
        i: "cm",
        d: "237",
        c:"XAF"
    }, {
        n: "Canada",
        i: "ca",
        d: "1",
        c:"CAD"
    }, {
        n: "Cape Verde (Kabu Verdi)",
        i: "cv",
        d: "238"
    }, {
        n: "Caribbean Netherlands",
        i: "bq",
        d: "5997"
    }, {
        n: "Cayman Islands",
        i: "ky",
        d: "1345"
    }, {
        n: "Central African Republic (République centrafricaine)",
        i: "cf",
        d: "236",
        c:"XAF"
    }, {
        n: "Chad (Tchad)",
        i: "td",
        d: "235",
        c:"XAF"
    }, {
        n: "Chile",
        i: "cl",
        d: "56",
        c:"CLP"
    }, {
        n: "China (中国)",
        i: "cn",
        d: "86",
        c:"CNY"
    }, {
        n: "Christmas Island",
        i: "cx",
        d: "61"
    }, {
        n: "Cocos (Keeling) Islands (Kepulauan Cocos (Keeling))",
        i: "cc",
        d: "61"
    }, {
        n: "Colombia",
        i: "co",
        d: "57",
        c:"COP"
    }, {
        n: "Comoros (‫جزر القمر‬‎)",
        i: "km",
        d: "269"
    }, {
        n: "Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)",
        i: "cd",
        d: "243"
    }, {
        n: "Congo (Republic) (Congo-Brazzaville)",
        i: "cg",
        d: "242",
        c:"CDF"
    }, {
        n: "Cook Islands",
        i: "ck",
        d: "682"
    }, {
        n: "Costa Rica",
        i: "cr",
        d: "506",
        c:"CRC"
    }, {
        n: "Côte d’Ivoire",
        i: "ci",
        d: "225"
    }, {
        n: "Croatia (Hrvatska)",
        i: "hr",
        d: "385"
    }, {
        n: "Cuba",
        i: "cu",
        d: "53",
        c:"CUP"
    }, {
        n: "Curaçao",
        i: "cw",
        d: "5999"
    }, {
        n: "Cyprus (Κύπρος)",
        i: "cy",
        d: "357",
        c:"CYP"
    }, {
        n: "Czech Republic (Česká republika)",
        i: "cz",
        d: "420",
        c:"CZK"
    }, {
        n: "Denmark (Danmark)",
        i: "dk",
        d: "45",
        c:"DKK"
    }, {
        n: "Djibouti",
        i: "dj",
        d: "253"
    }, {
        n: "Dominica",
        i: "dm",
        d: "1767"
    }, {
        n: "Dominican Republic (República Dominicana)",
        i: "do",
        d: "1809"
    }, {
        n: "Ecuador",
        i: "ec",
        d: "593",
        c:"ECS"
    }, {
        n: "Egypt (‫مصر‬‎)",
        i: "eg",
        d: "20",
        c:"EGP"
    }, {
        n: "El Salvador",
        i: "sv",
        d: "503",
        c:"SVC"
    }, {
        n: "Equatorial Guinea (Guinea Ecuatorial)",
        i: "gq",
        d: "240"
    }, {
        n: "Eritrea",
        i: "er",
        d: "291"
    }, {
        n: "Estonia (Eesti)",
        i: "ee",
        d: "372",
        c:"EEK"
    }, {
        n: "Ethiopia",
        i: "et",
        d: "251",
        c:"ETB"
    }, {
        n: "Falkland Islands (Islas Malvinas)",
        i: "fk",
        d: "500"
    }, {
        n: "Faroe Islands (Føroyar)",
        i: "fo",
        d: "298"
    }, {
        n: "Fiji",
        i: "fj",
        d: "679",
        c:"FJD"
    }, {
        n: "Finland (Suomi)",
        i: "fi",
        d: "358",
        c:"FIM"
    }, {
        n: "France",
        i: "fr",
        d: "33",
        c:"FRF"
    }, {
        n: "French Guiana (Guyane française)",
        i: "gf",
        d: "594",
        c:"GYD"
    }, {
        n: "French Polynesia (Polynésie française)",
        i: "pf",
        d: "689"
    }, {
        n: "Gabon",
        i: "ga",
        d: "241",
        c:"XAF"
    }, {
        n: "Gambia",
        i: "gm",
        d: "220",
        c:"GMD"
    }, {
        n: "Georgia (საქართველო)",
        i: "ge",
        d: "995"
    }, {
        n: "Germany (Deutschland)",
        i: "de",
        d: "49",
        c:"DEM"
    }, {
        n: "Ghana (Gaana)",
        i: "gh",
        d: "233",
        c:"GHC"
    }, {
        n: "Gibraltar",
        i: "gi",
        d: "350",
        c:"GIP"
    }, {
        n: "Greece (Ελλάδα)",
        i: "gr",
        d: "30",
        c:"GRD"
    }, {
        n: "Greenland (Kalaallit Nunaat)",
        i: "gl",
        d: "299"
    }, {
        n: "Grenada",
        i: "gd",
        d: "1473"
    }, {
        n: "Guadeloupe",
        i: "gp",
        d: "590"
    }, {
        n: "Guam",
        i: "gu",
        d: "1671"
    }, {
        n: "Guatemala",
        i: "gt",
        d: "502",
        c:"GTQ"
    }, {
        n: "Guernsey",
        i: "gg",
        d: "44"
    }, {
        n: "Guinea (Guinée)",
        i: "gn",
        d: "224",
        c:"GNF"
    }, {
        n: "Guinea-Bissau (Guiné Bissau)",
        i: "gw",
        d: "245"
    }, {
        n: "Guyana",
        i: "gy",
        d: "592"
    }, {
        n: "Haiti",
        i: "ht",
        d: "509",
        c:"HTG"
    }, {
        n: "Honduras",
        i: "hn",
        d: "504",
        c:"HNL"
    }, {
        n: "Hong Kong (香港)",
        i: "hk",
        d: "852",
        c:"HKD"
    }, {
        n: "Hungary (Magyarország)",
        i: "hu",
        d: "36",
        c:"HUF "
    }, {
        n: "Iceland (Ísland)",
        i: "is",
        d: "354",
        c:"ISK"
    }, {
        n: "India (भारत)",
        i: "in",
        d: "91",
        c:"INR"
    }, {
        n: "Indonesia",
        i: "id",
        d: "62",
        c:"IDR"
    }, {
        n: "Iran (‫ایران‬‎)",
        i: "ir",
        d: "98",
        c:"IRR"
    }, {
        n: "Iraq (‫العراق‬‎)",
        i: "iq",
        d: "964",
        c:"IQD"
    }, {
        n: "Ireland",
        i: "ie",
        d: "353",
        c:"IEP"
    }, {
        n: "Isle of Man",
        i: "im",
        d: "44",
        c:"GBP "
    }, {
        n: "Israel (‫ישראל‬‎)",
        i: "il",
        d: "972",
        c:"ILS"
    }, {
        n: "Italy (Italia)",
        i: "it",
        d: "39",
        c:"ITL"
    }, {
        n: "Jamaica",
        i: "jm",
        d: "1876",
        c:"JMD"
    }, {
        n: "Japan (日本)",
        i: "jp",
        d: "81",
        c:"JPY"
    }, {
        n: "Jersey",
        i: "je",
        d: "44"
    }, {
        n: "Jordan (‫الأردن‬‎)",
        i: "jo",
        d: "962",
        c:"JOD"
    }, {
        n: "Kazakhstan (Казахстан)",
        i: "kz",
        d: "7"
    }, {
        n: "Kenya",
        i: "ke",
        d: "254",
        c:"KES"
    }, {
        n: "Kiribati",
        i: "ki",
        d: "686"
    }, {
        n: "Kosovo (Kosovë)",
        i: "xk",
        d: "377"
    }, {
        n: "Kuwait (‫الكويت‬‎)",
        i: "kw",
        d: "965"
    }, {
        n: "Kyrgyzstan (Кыргызстан)",
        i: "kg",
        d: "996"
    }, {
        n: "Laos (ລາວ)",
        i: "la",
        d: "856",
        c:"LAK"
    }, {
        n: "Latvia (Latvija)",
        i: "lv",
        d: "371",
        c:"LVL"
    }, {
        n: "Lebanon (‫لبنان‬‎)",
        i: "lb",
        d: "961",
        c:"LBP "
    }, {
        n: "Lesotho",
        i: "ls",
        d: "266",
        c:"LSL"
    }, {
        n: "Liberia",
        i: "lr",
        d: "231",
        c:"LYD"
    }, {
        n: "Libya (‫ليبيا‬‎)",
        i: "ly",
        d: "218",
        c:"LRD"
    }, {
        n: "Liechtenstein",
        i: "li",
        d: "423"
    }, {
        n: "Lithuania (Lietuva)",
        i: "lt",
        d: "370",
        c:"LTL"
    }, {
        n: "Luxembourg",
        i: "lu",
        d: "352",
        c:" LUF"
    }, {
        n: "Macau (澳門)",
        i: "mo",
        d: "853",
        c:"MOP"
    }, {
        n: "Macedonia (FYROM) (Македонија)",
        i: "mk",
        d: "389",
        c:"MKD"
    }, {
        n: "Madagascar (Madagasikara)",
        i: "mg",
        d: "261",
        c:"MGF"
    }, {
        n: "Malawi",
        i: "mw",
        d: "265"
    }, {
        n: "Malaysia",
        i: "my",
        d: "60",
        c:"MYR"
    }, {
        n: "Maldives",
        i: "mv",
        d: "960",
        c:"MVR"
    }, {
        n: "Mali",
        i: "ml",
        d: "223"
    }, {
        n: "Malta",
        i: "mt",
        d: "356",
        c:"MTL"
    }, {
        n: "Marshall Islands",
        i: "mh",
        d: "692"
    }, {
        n: "Martinique",
        i: "mq",
        d: "596"
    }, {
        n: "Mauritania (‫موريتانيا‬‎)",
        i: "mr",
        d: "222",
        c:"MRO"
    }, {
        n: "Mauritius (Moris)",
        i: "mu",
        d: "230",
        c:"MUR"
    }, {
        n: "Mayotte",
        i: "yt",
        d: "262"
    }, {
        n: "Mexico (México)",
        i: "mx",
        d: "52",
        c:"MXP"
    }, {
        n: "Micronesia",
        i: "fm",
        d: "691"
    }, {
        n: "Moldova (Republica Moldova)",
        i: "md",
        d: "373",
        c:"MDL"
    }, {
        n: "Monaco",
        i: "mc",
        d: "377"
    }, {
        n: "Mongolia (Монгол)",
        i: "mn",
        d: "976",
        c:"MNT"
    }, {
        n: "Montenegro (Crna Gora)",
        i: "me",
        d: "382"
    }, {
        n: "Montserrat",
        i: "ms",
        d: "1664"
    }, {
        n: "Morocco (‫المغرب‬‎)",
        i: "ma",
        d: "212",
        c:"MAD"
    }, {
        n: "Mozambique (Moçambique)",
        i: "mz",
        d: "258",
        c:"MZM"
    }, {
        n: "Myanmar (Burma)",
        i: "mm",
        d: "95",
        c:"MMK"
    }, {
        n: "Namibia (Namibië)",
        i: "na",
        d: "264",
        c:"NAD"
    }, {
        n: "Nauru",
        i: "nr",
        d: "674"
    }, {
        n: "Nepal (नेपाल)",
        i: "np",
        d: "977",
        c:"NPR"
    }, {
        n: "Netherlands (Nederland)",
        i: "nl",
        d: "31",
        c:"NLG"
    }, {
        n: "New Caledonia (Nouvelle-Calédonie)",
        i: "nc",
        d: "687"
    }, {
        n: "New Zealand",
        i: "nz",
        d: "64",
        c:"NZD"
    }, {
        n: "Nicaragua",
        i: "ni",
        d: "505",
        c:"NIO"
    }, {
        n: "Niger (Nijar)",
        i: "ne",
        d: "227"
    }, {
        n: "Nigeria",
        i: "ng",
        d: "234",
        c:"NGN" 
    }, {
        n: "Niue",
        i: "nu",
        d: "683"
    }, {
        n: "Norfolk Island",
        i: "nf",
        d: "672"
    }, {
        n: "North Korea (조선 민주주의 인민 공화국)",
        i: "kp",
        d: "850",
        c:"KPW"
    }, {
        n: "Northern Mariana Islands",
        i: "mp",
        d: "1670"
    }, {
        n: "Norway (Norge)",
        i: "no",
        d: "47",
        c:"NOK"
    }, {
        n: "Oman (‫عُمان‬‎)",
        i: "om",
        d: "968",
        c:"OMR"
    }, {
        n: "Pakistan (‫پاکستان‬‎)",
        i: "pk",
        d: "92",
        c:"PKR"
    }, {
        n: "Palau",
        i: "pw",
        d: "680"
    }, {
        n: "Palestine (‫فلسطين‬‎)",
        i: "ps",
        d: "970"
    }, {
        n: "Panama (Panamá)",
        i: "pa",
        d: "507",
        c:"PAB"
    }, {
        n: "Papua New Guinea",
        i: "pg",
        d: "675"
    }, {
        n: "Paraguay",
        i: "py",
        d: "595",
        c:"PYG"
    }, {
        n: "Peru (Perú)",
        i: "pe",
        d: "51",
        c:"PEN"
    }, {
        n: "Philippines",
        i: "ph",
        d: "63",
        c:"PHP"
    }, {
        n: "Pitcairn Islands",
        i: "pn",
        d: "64"
    }, {
        n: "Poland (Polska)",
        i: "pl",
        d: "48",
        c:"PLZ"
    }, {
        n: "Portugal",
        i: "pt",
        d: "351",
        c:"PTE**"
    }, {
        n: "Puerto Rico",
        i: "pr",
        d: "1787"
    }, {
        n: "Qatar (‫قطر‬‎)",
        i: "qa",
        d: "974",
        c:"QAR"
    }, {
        n: "Réunion (La Réunion)",
        i: "re",
        d: "262"
    }, {
        n: "Romania (România)",
        i: "ro",
        d: "40",
        c:"ROL"
    }, {
        n: "Russia (Россия)",
        i: "ru",
        d: "7"
    }, {
        n: "Rwanda",
        i: "rw",
        d: "250",
        c:"RUR"
    }, {
        n: "Saint Barthélemy (Saint-Barthélemy)",
        i: "bl",
        d: "590"
    }, {
        n: "Saint Helena",
        i: "sh",
        d: "290"
    }, {
        n: "Saint Kitts and Nevis",
        i: "kn",
        d: "1869"
    }, {
        n: "Saint Lucia",
        i: "lc",
        d: "1758"
    }, {
        n: "Saint Martin (Saint-Martin (partie française))",
        i: "mf",
        d: "590"
    }, {
        n: "Saint Pierre and Miquelon (Saint-Pierre-et-Miquelon)",
        i: "pm",
        d: "508"
    }, {
        n: "Saint Vincent and the Grenadines",
        i: "vc",
        d: "1784"
    }, {
        n: "Samoa",
        i: "ws",
        d: "685"
    }, {
        n: "San Marino",
        i: "sm",
        d: "378"
    }, {
        n: "São Tomé and Príncipe (São Tomé e Príncipe)",
        i: "st",
        d: "239"
    }, {
        n: "Saudi Arabia (‫المملكة العربية السعودية‬‎)",
        i: "sa",
        d: "966",
        c:"SAR"
    }, {
        n: "Senegal (Sénégal)",
        i: "sn",
        d: "221"
    }, {
        n: "Serbia (Србија)",
        i: "rs",
        d: "381"
    }, {
        n: "Seychelles",
        i: "sc",
        d: "248"
    }, {
        n: "Sierra Leone",
        i: "sl",
        d: "232",
        c:"SLL"
    }, {
        n: "Singapore",
        i: "sg",
        d: "65",
        c:"SGD"
    }, {
        n: "Sint Maarten",
        i: "sx",
        d: "1721"
    }, {
        n: "Slovakia (Slovensko)",
        i: "sk",
        d: "421",
        c:"SKK "
    }, {
        n: "Slovenia (Slovenija)",
        i: "si",
        d: "386",
        c:"SIT"
    }, {
        n: "Solomon Islands",
        i: "sb",
        d: "677"
    }, {
        n: "Somalia (Soomaaliya)",
        i: "so",
        d: "252",
        c:"SOS"
    }, {
        n: "South Africa",
        i: "za",
        d: "27",
        c:"ZAR"
    }, {
        n: "South Georgia & South Sandwich Islands",
        i: "gs",
        d: "500"
    }, {
        n: "South Korea (대한민국)",
        i: "kr",
        d: "82",
        c:"KRW"
    }, {
        n: "South Sudan (‫جنوب السودان‬‎)",
        i: "ss",
        d: "211"
    }, {
        n: "Spain (España)",
        i: "es",
        d: "34",
        c:"ESP"
    }, {
        n: "Sri Lanka (ශ්‍රී ලංකාව)",
        i: "lk",
        d: "94",
        c:"LKR"
    }, {
        n: "Sudan (‫السودان‬‎)",
        i: "sd",
        d: "249",
        c:"SDD"
    }, {
        n: "Suriname",
        i: "sr",
        d: "597"
    }, {
        n: "Svalbard and Jan Mayen (Svalbard og Jan Mayen)",
        i: "sj",
        d: "4779"
    }, {
        n: "Swaziland",
        i: "sz",
        d: "268"
    }, {
        n: "Sweden (Sverige)",
        i: "se",
        d: "46",
        c:"SEK"
    }, {
        n: "Switzerland (Schweiz)",
        i: "ch",
        d: "41",
        c:"CHF"
    }, {
        n: "Syria (‫سوريا‬‎)",
        i: "sy",
        d: "963",
        c:"SYP"
    }, {
        n: "Taiwan (台灣)",
        i: "tw",
        d: "886",
        c:"TWD"
    }, {
        n: "Tajikistan",
        i: "tj",
        d: "992",
        c:"TJR"
    }, {
        n: "Tanzania",
        i: "tz",
        d: "255",
        c:"TZS"
    }, {
        n: "Thailand (ไทย)",
        i: "th",
        d: "66",
        c:"THB"
    }, {
        n: "Timor-Leste",
        i: "tl",
        d: "670"
    }, {
        n: "Togo",
        i: "tg",
        d: "228"
    }, {
        n: "Tokelau",
        i: "tk",
        d: "690"
    }, {
        n: "Tonga",
        i: "to",
        d: "676",
        c:"TOP"
    }, {
        n: "Trinidad and Tobago",
        i: "tt",
        d: "1868"
    }, {
        n: "Tunisia (‫تونس‬‎)",
        i: "tn",
        d: "216",
        c:"TND"
    }, {
        n: "Turkey (Türkiye)",
        i: "tr",
        d: "90",
        c:"TRL"
    }, {
        n: "Turkmenistan",
        i: "tm",
        d: "993",
        c:"TMM"
    }, {
        n: "Turks and Caicos Islands",
        i: "tc",
        d: "1649"
    }, {
        n: "Tuvalu",
        i: "tv",
        d: "688"
    }, {
        n: "Uganda",
        i: "ug",
        d: "256",
        c:"UGX"
    }, {
        n: "Ukraine (Україна)",
        i: "ua",
        d: "380",
        c:"UAH"
    }, {
        n: "United Arab Emirates (‫الإمارات العربية المتحدة‬‎)",
        i: "ae",
        d: "971"
    }, {
        n: "United Kingdom",
        i: "gb",
        d: "44",
        c:"GBP"
    }, {
        n: "United States",
        i: "us",
        d: "1",
        c:"USD"
    }, {
        n: "U.S. Virgin Islands",
        i: "vi",
        d: "1340"
    }, {
        n: "Uruguay",
        i: "uy",
        d: "598",
        c:"UYU"
    }, {
        n: "Uzbekistan (Oʻzbekiston)",
        i: "uz",
        d: "998",
        c:"UZS"
    }, {
        n: "Vanuatu",
        i: "vu",
        d: "678"
    }, {
        n: "Vatican City (Città del Vaticano)",
        i: "va",
        d: "379"
    }, {
        n: "Venezuela",
        i: "ve",
        d: "58",
        c:"VEB"
    }, {
        n: "Vietnam (Việt Nam)",
        i: "vn",
        d: "84",
        c:"VND"
    }, {
        n: "Wallis and Futuna",
        i: "wf",
        d: "681"
    }, {
        n: "Western Sahara (‫الصحراء الغربية‬‎)",
        i: "eh",
        d: "212"
    }, {
        n: "Yemen (‫اليمن‬‎)",
        i: "ye",
        d: "967",
        c:"YER"
    }, {
        n: "Zambia",
        i: "zm",
        d: "260",
        c:"ZMK"
    }, {
        n: "Zimbabwe",
        i: "zw",
        d: "263",
        c:"ZWD"
    } ], function(i, c) {
        c.name = c.n;
        c.iso2 = c.i;
        c.dialCode = c.c;
       // c.currencyCode = c.c;
        delete c.n;
        delete c.i;
        delete c.d;
        delete c.c;
    });
    // JavaScript object mapping dial code to country code.
    // This is used when the user enters a number,
    // to quickly look up the corresponding country code.
    // Generated from the above array using this JavaScript:
    /*
var uniqueDCs = _.unique(_.pluck(intlDataFull.countries, dialCode));
var cCodes = {};
_.each(uniqueDCs, function(dc) {
  cCodes[dc] = _.pluck(_.filter(intlDataFull.countries, function(c) {
    return c[dialCode] == dc;
  }), iso2);
});
 */
    // Then reference this google code project for clash priority:
    // http://libphonenumber.googlecode.com/svn/trunk/javascript/i18n/phonenumbers/metadata.js
    // then updated vatican city to +379
    var allCountryCodes = {
        "USD": [ "us"],
        "CAD": [ "ca"],
        "7": [ "ru", "kz" ],
        "EGP": [ "eg" ],
        "ZAR": [ "za" ],
        "GRD": [ "gr" ],
        "NLG": [ "nl" ],
        "BEF": [ "be" ],
        "FRF": [ "fr","ad" ],
        "ESP": [ "es" ],
        "HUF": [ "hu" ],
        "ITL": [ "it" ],
        "ROL": [ "ro" ],
        "CHF": [ "ch" ],
        "ATS": [ "at" ],
        "GBP": [ "gb", "gg", "im", "je" ],
        "DKK": [ "dk" ],
        "SEK": [ "se" ],
        "NOK": [ "no" ],
        "PLZ": [ "pl" ],
        "DEM": [ "de" ],
        "PEN": [ "pe" ],
        "MXP": [ "mx" ],
        "CUP": [ "cu" ],
        "ARP": [ "ar" ],
        "BRL": [ "br" ],
        "CLP": [ "cl" ],
        "COP": [ "co" ],
        "VEB": [ "ve" ],
        "MYR": [ "my" ],
        "AUD": [ "au", "cc", "cx" ],
        "IDR": [ "id" ],
        "PHP": [ "ph" ],
        "NZD": [ "nz", "pn" ],
        "SGD": [ "sg" ],
        "THB": [ "th" ],
        "JPY": [ "jp" ],
        "KRW": [ "kr" ],
        "VND": [ "vn" ],
        "CNY": [ "cn" ],
        "TRL": [ "tr" ],
        "INR": [ "in" ],
        "PKR": [ "pk" ],
        "AFA": [ "af" ],
        "LKR": [ "lk" ],
        "MMK": [ "mm" ],
        "IRR": [ "ir" ],
        "211": [ "ss" ],
        "MAD": [ "ma", "eh" ],
        "DZD": [ "dz" ],
        "TND": [ "tn" ],
        "LRD": [ "ly" ],
        "GMD": [ "gm" ],
        "221": [ "sn" ],
        "MRO": [ "mr" ],
        "223": [ "ml" ],
        "GNF": [ "gn" ],
        "225": [ "ci" ],
        "226": [ "bf" ],
        "227": [ "ne" ],
        "228": [ "tg" ],
        "229": [ "bj" ],
        "MUR": [ "mu" ],
        "LYD": [ "lr" ],
        "SLL": [ "sl" ],
        "GHC": [ "gh" ],
        "NGN": [ "ng" ],
        "235": [ "td" ],
        "XAF": [ "cm","cf","ga"],
        "238": [ "cv" ],
        "239": [ "st" ],
        "240": [ "gq" ],
        "CDF": [ "cg" ],
        "243": [ "cd" ],
        "AON": [ "ao" ],
        "245": [ "gw" ],
        "246": [ "io" ],
        "248": [ "sc" ],
        "SDD": [ "sd" ],
        "ETB": [ "et" ],
        "SOS": [ "so" ],
        "253": [ "dj" ],
        "KES": [ "ke" ],
        "TZS": [ "tz" ],
        "UGX": [ "ug" ],
        "257": [ "bi" ],
        "MZM": [ "mz" ],
        "ZMK": [ "zm" ],
        "MGF": [ "mg" ],
        "262": [ "re", "yt" ],
        "ZWD": [ "zw" ],
        "NAD": [ "na" ],
        "265": [ "mw" ],
        "LSL": [ "ls" ],
        "267": [ "bw" ],
        "268": [ "sz" ],
        "269": [ "km" ],
        "290": [ "sh" ],
        "291": [ "er" ],
        "297": [ "aw" ],
        "298": [ "fo" ],
        "299": [ "gl" ],
        "GIP": [ "gi" ],
        "PTE": [ "pt" ],
        "LUF": [ "lu" ],
        "IEP**": [ "ie" ],
        "ISK": [ "is" ],
        "ALL": [ "al" ],
        "MTL": [ "mt" ],
        "CYP": [ "cy" ],
        "FIM": [ "fi", "ax" ],
        "BGL": [ "bg" ],
        "LTL": [ "lt" ],
        "LVL": [ "lv" ],
        "EEK": [ "ee" ],
        "MDL": [ "md" ],
        "AMD": [ "am" ],
        "RUR": [ "by","rw" ],
        "377": [ "mc", "xk" ],
        "378": [ "sm" ],
        "379": [ "va" ],
        "UAH": [ "ua" ],
        "381": [ "rs" ],
        "382": [ "me" ],
        "385": [ "hr" ],
        "SIT": [ "si" ],
        "387": [ "ba" ],
        "MKD": [ "mk" ],
        "CZK": [ "cz" ],
        "SKK": [ "sk" ],
        "423": [ "li" ],
        "500": [ "fk", "gs" ],
        "BZD": [ "bz" ],
        "GTQ": [ "gt" ],
        "SVC": [ "sv" ],
        "HNL": [ "hn" ],
        "NIO": [ "ni" ],
        "CRC": [ "cr" ],
        "PAB": [ "pa" ],
        "508": [ "pm" ],
        "HTG": [ "ht" ],
        "590": [ "gp", "bl", "mf" ],
        "591": [ "bo" ],
        "592": [ "gy" ],
        "ECS": [ "ec" ],
        "GYD": [ "gf" ],
        "PYG": [ "py" ],
        "596": [ "mq" ],
        "597": [ "sr" ],
        "UYU": [ "uy" ],
        "670": [ "tl" ],
        "672": [ "nf" ],
        "BND": [ "bn" ],
        "674": [ "nr" ],
        "675": [ "pg" ],
        "TOP": [ "to" ],
        "677": [ "sb" ],
        "678": [ "vu" ],
        "FJD": [ "fj" ],
        "680": [ "pw" ],
        "681": [ "wf" ],
        "682": [ "ck" ],
        "683": [ "nu" ],
        "685": [ "ws" ],
        "686": [ "ki" ],
        "687": [ "nc" ],
        "688": [ "tv" ],
        "689": [ "pf" ],
        "690": [ "tk" ],
        "691": [ "fm" ],
        "692": [ "mh" ],
        "KPW": [ "kp" ],
        "HKD": [ "hk" ],
        "MOP": [ "mo" ],
        "855": [ "kh" ],
        "LAK": [ "la" ],
        "880": [ "bd" ],
        "TWD": [ "tw" ],
        "MVR": [ "mv" ],
        "LBP": [ "lb" ],
        "JOD": [ "jo" ],
        "SYP": [ "sy" ],
        "IQD": [ "iq" ],
        "965": [ "kw" ],
        "SAR": [ "sa" ],
        "YER": [ "ye" ],
        "OMR": [ "om" ],
        "970": [ "ps" ],
        "971": [ "ae" ],
        "ILS": [ "il" ],
        "973": [ "bh" ],
        "QAR": [ "qa" ],
        "975": [ "bt" ],
        "MNT": [ "mn" ],
        "NPR": [ "np" ],
        "TJR": [ "tj" ],
        "TMM": [ "tm" ],
        "AZM": [ "az" ],
        "995": [ "ge" ],
        "996": [ "kg" ],
        "UZS": [ "uz" ],
        "1204": [ "ca" ],
        "1236": [ "ca" ],
        "1242": [ "bs" ],
        "1246": [ "bb" ],
        "1249": [ "ca" ],
        "1250": [ "ca" ],
        "XCD": [ "ai" ],
        "1268": [ "ag" ],
        "1284": [ "vg" ],
        "1289": [ "ca" ],
        "1306": [ "ca" ],
        "1340": [ "vi" ],
        "1343": [ "ca" ],
        "1345": [ "ky" ],
        "1365": [ "ca" ],
        "1387": [ "ca" ],
        "1403": [ "ca" ],
        "1416": [ "ca" ],
        "1418": [ "ca" ],
        "1431": [ "ca" ],
        "1437": [ "ca" ],
        "1438": [ "ca" ],
        "1441": [ "bm" ],
        "1450": [ "ca" ],
        "1473": [ "gd" ],
        "1506": [ "ca" ],
        "1514": [ "ca" ],
        "1519": [ "ca" ],
        "1548": [ "ca" ],
        "1579": [ "ca" ],
        "1581": [ "ca" ],
        "1587": [ "ca" ],
        "1604": [ "ca" ],
        "1613": [ "ca" ],
        "1639": [ "ca" ],
        "1647": [ "ca" ],
        "1649": [ "tc" ],
        "1664": [ "ms" ],
        "1670": [ "mp" ],
        "1671": [ "gu" ],
        "1672": [ "ca" ],
        "1684": [ "as" ],
        "1705": [ "ca" ],
        "1709": [ "ca" ],
        "1721": [ "sx" ],
        "1742": [ "ca" ],
        "1758": [ "lc" ],
        "1767": [ "dm" ],
        "1778": [ "ca" ],
        "1780": [ "ca" ],
        "1782": [ "ca" ],
        "1784": [ "vc" ],
        "1787": [ "pr" ],
        "1807": [ "ca" ],
        "1809": [ "do" ],
        "1819": [ "ca" ],
        "1825": [ "ca" ],
        "1867": [ "ca" ],
        "1868": [ "tt" ],
        "1869": [ "kn" ],
        "1873": [ "ca" ],
        "JMD": [ "jm" ],
        "1902": [ "ca" ],
        "1905": [ "ca" ],
        "4779": [ "sj" ],
        "5997": [ "bq" ],
        "5999": [ "cw" ]
    };
});