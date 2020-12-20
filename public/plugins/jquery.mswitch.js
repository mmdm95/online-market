/*
 * jQuery mSwitch v.1
 *
 * Copyright (c) 2018 Dario Montalbano
 * 
 * For use this library is necessary include jquery-ui autocomplete in your project 
 * 
 */

(function ($) {
    "use strict";

    // Create the defaults once
    var pluginName = "mSwitch",
        defaults = {
            colorClass: 'm_switch_color',
            onRendered: function () {
            },
            onRender: function (elem) {
            },
            onTurnOn: function (elem) {
                return true;
            },
            onTurnOff: function (elem) {
                return true;
            }
        };

    // The actual plugin constructor
    function MSwitch(elem, options) {
        this.elem = elem;
        this.$elem = $(elem);
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(MSwitch.prototype, {
        init: function () {
            var _this = this;
            if (_this.$elem && _this.$elem.is(":checkbox")) {
                _this.$elem.addClass("m_switch_check");
                var $div = $("<div class='m_switch'/>");
                var $b = $("<b class='m_switch_b m_switch_element'/>");

                _this.$elem.wrap($div);
                $b.insertAfter(_this.$elem);

                if (_this.$elem.is(":checked")) {
                    _this.turnOn(_this.$elem);
                } else {
                    _this.turnOff(_this.$elem);
                }

                if (_this.settings.onRender && typeof _this.settings.onRender == 'function') {
                    _this.settings.onRender(_this.$elem);
                }

                _this.$elem.change(function () {
                    if (_this.$elem.is(":checked")) {
                        if (_this.settings.onTurnOn && typeof _this.settings.onTurnOn == 'function') {
                            _this.settings.onTurnOn($(this));
                        }
                        _this.turnOn($(this));
                    } else if (!_this.$elem.is(":checked")) {
                        if (_this.settings.onTurnOff && typeof _this.settings.onTurnOff == 'function') {
                            _this.settings.onTurnOff($(this));
                        }
                        _this.turnOff($(this));
                    }
                });
            }
        },

        turnOn: function (elem) {
            if (elem.parent(".m_switch").length > 0) {
                if (!elem.parent(".m_switch").hasClass(this.settings.colorClass)) {
                    elem.parent(".m_switch").addClass(this.settings.colorClass);
                }
            }
            if (elem.next("b.m_switch_b").length > 0) {
                if (!elem.next("b.m_switch_b").hasClass("m_switch_checked")) {
                    elem.next("b.m_switch_b").addClass("m_switch_checked");
                }
            }
            if (elem.parent(".m_switch").hasClass(this.settings.colorClass) &&
                elem.next("b.m_switch_b").hasClass("m_switch_checked")) {
                elem.attr('checked', true).prop('checked', true);
            }
        },

        turnOff: function (elem) {
            if (elem.parent(".m_switch").length > 0) {
                if (elem.parent(".m_switch").hasClass(this.settings.colorClass)) {
                    elem.parent(".m_switch").removeClass(this.settings.colorClass);
                }
            }
            if (elem.next("b.m_switch_b").length > 0) {
                if (elem.next("b.m_switch_b").hasClass("m_switch_checked")) {
                    elem.next("b.m_switch_b").removeClass("m_switch_checked");
                }
            }
            if (!elem.parent(".m_switch").hasClass(this.settings.colorClass) &&
                !elem.next("b.m_switch_b").hasClass("m_switch_checked")) {
                elem.removeAttr('checked', false).prop('checked', false);
            }
        },

        isOn: function (elem) {
            var res = false;
            if (elem.is(":checked")) {
                res = true;
            }
            return res;
        },

        options: function (options) {
            if ($.type(options) !== 'object') return;
            $.extend(true, this.settings, options);
        },
    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function (options) {
        var args = Array.prototype.slice.call(arguments, 1);
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new MSwitch(this, options));

                if (options && options.onRendered && typeof options.onRendered == 'function') {
                    options.onRendered($(this));
                }
            } else if ($.isFunction(MSwitch.prototype[options]) && $.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName)[options].apply($.data(this, 'plugin_' + pluginName), args)
            }
        });
    };

}(jQuery));
