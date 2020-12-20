/**
 * A very nice URI manipulation library
 *
 * @author MMDM https://github.com/mmdm95
 * @licence Under MIT license
 */
(function () {
    'use strict';

    /**
     * @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/keys
     */
    if (!Object.keys) {
        Object.keys = (function () {
            'use strict';
            var hasOwnProperty = Object.prototype.hasOwnProperty,
                hasDontEnumBug = !({toString: null}).propertyIsEnumerable('toString'),
                dontEnums = [
                    'toString',
                    'toLocaleString',
                    'valueOf',
                    'hasOwnProperty',
                    'isPrototypeOf',
                    'propertyIsEnumerable',
                    'constructor'
                ],
                dontEnumsLength = dontEnums.length;

            return function (obj) {
                if (typeof obj !== 'function' && (typeof obj !== 'object' || obj === null)) {
                    throw new TypeError('Object.keys called on non-object');
                }

                var result = [], prop, i;

                for (prop in obj) {
                    if (hasOwnProperty.call(obj, prop)) {
                        result.push(prop);
                    }
                }

                if (hasDontEnumBug) {
                    for (i = 0; i < dontEnumsLength; i++) {
                        if (hasOwnProperty.call(obj, dontEnums[i])) {
                            result.push(dontEnums[i]);
                        }
                    }
                }
                return result;
            };
        }());
    }

    /**
     * @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/entries
     */
    if (!Object.entries) {
        Object.entries = function (obj) {
            var ownProps = Object.keys(obj),
                i = ownProps.length,
                resArray = new Array(i); // preallocate the Array
            while (i--)
                resArray[i] = [ownProps[i], obj[ownProps[i]]];

            return resArray;
        };
    }

    /**
     * Pass in the objects to merge as arguments.
     * For a deep extend, set the first argument to `true`.
     *
     * @see https://gomakethings.com/vanilla-javascript-version-of-jquery-extend/
     * @returns {any}
     */
    function extend() {
        var
            extended = {},
            deep = false,
            i = 0,
            length = arguments.length;

        // Check if a deep merge
        if (Object.prototype.toString.call(arguments[0]) === '[object Boolean]') {
            deep = arguments[0];
            i++;
        }

        // Merge the object into the extended object
        var merge = function (obj) {
            for (var prop in obj) {
                if (Object.prototype.hasOwnProperty.call(obj, prop)) {
                    // If deep merge and property is an object, merge properties
                    if (deep && Object.prototype.toString.call(obj[prop]) === '[object Object]') {
                        extended[prop] = extend(true, extended[prop], obj[prop]);
                    } else {
                        extended[prop] = obj[prop];
                    }
                }
            }
        };

        // Loop through each object and conduct a merge
        for (; i < length; i++) {
            var obj = arguments[i];
            merge(obj);
        }

        return extended;

    }

    var
        utils = {
            /**
             * @see https://stackoverflow.com/a/9436948/12154893
             * @param o
             * @returns {boolean}
             */
            isString: function (o) {
                return o && "[object String]" === Object.prototype.toString.call(o);
            },

            /**
             * @param o
             * @returns {boolean}
             */
            isArray: function (o) {
                return o && '[object Array]' === Object.prototype.toString.call(o);
            },

            /**
             * @param o
             * @returns {boolean}
             */
            isObject: function (o) {
                return o && '[object Object]' === Object.prototype.toString.call(o);
            },

            /**
             * @param o
             * @returns {*|boolean}
             */
            isFunction: function (o) {
                return o && '[object Function]' === Object.prototype.toString.call(o);
            },

            /**
             * @param o
             * @returns {boolean}
             */
            isDefined: function (o) {
                return null !== o && 'undefined' !== typeof o;
            },

            /**
             * @param o
             * @returns {boolean}
             */
            isset: function (o) {
                return 'undefined' !== typeof o;
            },

            /**
             * @param o
             * @returns {boolean}
             */
            isNull: function (o) {
                return null === o;
            },

            /**
             * Follow RFC3986
             *
             * @param str
             * @returns {string}
             */
            encodeUri: function (str) {
                return encodeURIComponent(str).replace(/[!'()*]/g, function (c) {
                    return '%' + c.charCodeAt(0).toString(16);
                });
            },

            /**
             * @param str
             * @returns {string}
             */
            decodeUri: function (str) {
                return decodeURIComponent(str.replace(/\+/g, " "));
            },

            /**
             * @param arr
             * @returns {*}
             */
            uniqueArray: function (arr) {
                return arr.filter(function (value, index, self) {
                    return self.indexOf(value) === index;
                });
            },

            /**
             * @param arr
             * @returns {*}
             */
            definedArray: function (arr) {
                var _ = this;
                return arr.filter(function (value) {
                    return _.isDefined(value);
                });
            },

            /**
             * @see https://stackoverflow.com/a/4215753/12154893
             * @param arr
             * @param [unique]
             * @param [definedValues]
             * @param [encoded]
             */
            toObjectRecursively: function (arr, unique, definedValues, encoded) {
                var self = this, rv = {}, i, len, t;
                encoded = !(false === encoded);
                unique = true === unique;
                if (this.isObject(arr)) {
                    for (i in arr) {
                        if (arr.hasOwnProperty(i)) {
                            rv[i] = this.toObjectRecursively(arr[i], unique, definedValues, encoded);
                        }
                    }
                } else if (this.isArray(arr)) {
                    // if defined values are only required
                    if (true === definedValues) {
                        arr = self.definedArray(arr);
                    }
                    // if unique values are only required
                    if (unique) {
                        arr = self.uniqueArray(arr);
                    }
                    len = arr.length;
                    // then convert it to array
                    var newArr = [];
                    for (i = 0; i < len; ++i) {
                        if (this.isObject(arr[i]) || this.isArray(arr[i])) {
                            newArr[i] = this.toObjectRecursively(arr[i], unique, definedValues, encoded);
                        } else {
                            t = arr[i];
                            if (true === t) {
                                t = 1;
                            } else if (false === t) {
                                t = 0;
                            }
                            if (encoded) {
                                newArr[i] = this.encodeUri(t);
                            } else {
                                newArr[i] = this.decodeUri(t);
                            }
                        }
                    }
                    rv = newArr;
                } else {
                    t = arr;
                    if (true === t) {
                        t = 1;
                    } else if (false === t) {
                        t = 0;
                    }
                    if (encoded) {
                        rv = this.encodeUri(t);
                    } else {
                        rv = this.decodeUri(t);
                    }
                }
                return rv;
            },

            /**
             * @param obj
             * @returns {number}
             */
            objSize: function (obj) {
                var size = 0, key;
                for (key in obj) {
                    if (obj.hasOwnProperty(key)) ++size;
                }
                return size;
            },
        },
        helper = {
            /**
             * @param obj
             * @param keys
             * @param v
             * @param [replace]
             * @param [unique]
             * @returns {*}
             */
            setIntoObject: function (obj, keys, v, replace, unique) {
                var key, last, o;
                if (!utils.isString(keys)) return;
                replace = true === replace;
                unique = true === unique;
                keys = keys.split('.');
                while (keys.length > 1) {
                    key = keys.shift();
                    if (!utils.isObject(obj[key])) {
                        if (obj[key]) {
                            obj[key] = {
                                0: obj[key],
                            };
                        } else {
                            obj[key] = {};
                        }
                    }
                    obj = obj[key];
                }
                last = keys.shift();
                o = utils.toObjectRecursively(v, unique, true);
                if ((obj[last]) && !replace) {
                    if (utils.isObject(obj[last]) && utils.isObject(o)) {
                        obj[last] = extend(true, obj[last], o);
                    } else if (!utils.isObject(obj[last]) && !utils.isObject(o)) {
                        var newArr = [];
                        obj[last] = newArr.concat(obj[last], o);
                        if (unique) {
                            obj[last] = utils.uniqueArray(obj[last]);
                        }
                    } else {
                        obj[last] = o;
                    }
                } else {
                    obj[last] = o;
                }
            },

            /**
             * @param obj
             * @param keys
             * @param [prefer]
             * @param [decoded]
             * @param [unique]
             * @returns {*}
             */
            getFromObject: function (obj, keys, prefer, decoded, unique) {
                var key, val;
                prefer = prefer ? prefer : null;
                decoded = true === decoded;
                if (!utils.isString(keys)) return utils.toObjectRecursively(obj, true === unique, null, !decoded);

                keys = keys.split('.');
                while (keys.length > 1) {
                    key = keys.shift();
                    if (!utils.isDefined(obj[key])) {
                        return prefer;
                    }
                    obj = obj[key];
                }

                val = obj[keys.shift()];
                if (val) {
                    return utils.toObjectRecursively(val, true === unique, null, !decoded);
                }
                return prefer;
            },

            /**
             * @param obj
             * @param keys
             * @param [value]
             * @param [strict]
             */
            removeFromObject: function (obj, keys, value, strict) {
                var key, last;
                if (!utils.isString(keys)) return;
                strict = true === strict;
                keys = keys.split('.');
                while (keys.length > 1) {
                    key = keys.shift();
                    if (!utils.isDefined(obj[key])) {
                        return;
                    }
                    obj = obj[key];
                }

                last = keys.shift();
                if (value) {
                    if ((strict && value !== obj[last]) || (!strict && value != obj[last])) {
                        if (utils.isObject(obj[last])) {
                            var o;
                            for (o in obj[last]) {
                                if (obj[last].hasOwnProperty(o) && ((strict && obj[last][o] === value) || (!strict && obj[last][o] == value))) {
                                    delete obj[last][o];
                                }
                            }
                            if (0 === utils.objSize(obj[last])) {
                                delete obj[last];
                            }
                        } else if (utils.isArray(obj[last])) {
                            var i, len;
                            len = obj[last].length;
                            for (i = 0; i < len; ++i) {
                                if (!utils.isDefined(obj[last][i])) break;
                                if ((strict && obj[last][i] === value) || (!strict && obj[last][i] == value)) {
                                    obj[last][i].splice(i, 1);
                                    --i;
                                }
                            }
                        }
                    }
                } else {
                    delete obj[last];
                }
            },

            /**
             * @param obj
             * @param keys
             * @param [isNullOK]
             * @returns {boolean}
             */
            hasInObject: function (obj, keys, isNullOK) {
                var key, last, res;
                if (!utils.isString(keys)) return false;
                keys = keys.split('.');
                while (keys.length > 1) {
                    key = keys.shift();
                    if (!utils.isDefined(obj[key])) {
                        return false;
                    }
                    obj = obj[key];
                }
                res = true;
                last = keys.shift();
                if (!utils.isDefined(obj[last])) res = false;
                if (true === isNullOK && utils.isNull(obj[last])) res = true;
                return res;
            },
        };

    window.UriParser = (function () {
        function UriParser() {
            //------------------------------------------
            // PRIVATE VARIABLES
            //------------------------------------------
            this.regexp = {
                removeQuestionMark: /^\?/,
                slashToDot: /[\[]([^\]\[]*)[\]]/g,
                dotToSlash: /[.]([\w]*)/g,
            };
            this.obj = {};
            this.search = '?';
            this.numberedQuery = false;
            this.changeStateFn = function () {
            };
            this.canCallChangeState = true;

            //------------------------------------------
            // PRIVATE FUNCTIONS
            //------------------------------------------

            var checkQueryStringValue = function (k, v, encoded) {
                encoded = true === encoded;
                return !utils.isNull(v) && !utils.isObject(v) && !utils.isArray(v)
                    ? (encoded
                        ? (utils.encodeUri(k) + "=" + utils.encodeUri(v))
                        : (utils.decodeUri(k) + "=" + utils.decodeUri(v)))
                    : (encoded
                        ? (utils.encodeUri(k))
                        : (utils.decodeUri(k)));
            };

            /**
             * @see https://stackoverflow.com/a/1714899/12154893
             * @param obj
             * @param [prefix]
             * @param [encoded]
             * @param [numbered]
             * @returns {string}
             */
            this.querySearchMaker = function (obj, prefix, encoded, numbered) {
                var self = this, str = [], p, k, v, t;
                encoded = true === encoded;
                numbered = true === numbered;
                if (utils.isObject(obj)) {
                    // this for can handle both object and array
                    // but it's not good enough therefor, separate
                    // its logic for object, array and pure value
                    for (p in obj) {
                        if (obj.hasOwnProperty(p)) {
                            k = prefix ? prefix + "[" + p + "]" : p;
                            v = obj[p];
                            t = checkQueryStringValue(k, v, encoded);
                            str.push((!utils.isNull(v) && (utils.isObject(v) || utils.isArray(v)))
                                ? self.querySearchMaker(v, k, encoded, numbered)
                                : t);
                        }
                    }
                } else if (utils.isArray(obj)) {
                    if (prefix) {
                        obj.forEach(function (val, idx) {
                            if (numbered) {
                                k = prefix + "[" + idx + "]";
                            } else {
                                k = prefix + "[]";
                            }
                            v = val;
                            t = checkQueryStringValue(k, v, encoded);
                            str.push((!utils.isNull(v) && utils.isObject(v))
                                ? self.querySearchMaker(v, k, encoded, numbered)
                                : t);
                        });
                    }
                } else { // usually this part is not needed but for sake of having default, it's here
                    if (prefix) {
                        k = prefix;
                        v = obj;
                        t = checkQueryStringValue(k, v, encoded);
                        str.push(t);
                    }
                }
                return str.join("&");
            };

            /**
             * Initializer
             *
             * NOTE:
             *   onStateChange callback is empty in here,
             *   so, don't worry at all for first call of callback
             */
            this.init = function () {
                var self = this, url;
                url = location.search.replace(self.regexp.removeQuestionMark, '');
                if (!url) {
                    return;
                }
                self.parse(url);
                self.updateSearch();
            };

            //------------------------------------------
            // CALL THINGS
            //------------------------------------------
            this.init();
        }

        UriParser.prototype = extend(UriParser.prototype, {
            /**
             * @param query
             * @param [overwrite]
             * @returns {UriParser}
             */
            parse: function (query, overwrite) {
                var self = this, i, len, all, parts, converted, afterEqualSign;
                if (!utils.isString(query)) return self;
                query = query.replace(self.regexp.removeQuestionMark, '');
                if (!query) {
                    return self;
                }

                self.canCallChangeState = false;

                // if want to overwrite the object
                if (true === overwrite) {
                    self.clear();
                }
                //-----
                all = query.split('&');
                len = all.length;
                for (i = 0; i < len; ++i) {
                    parts = all[i].split('=');
                    converted = parts[0].replace(self.regexp.slashToDot, function (match, p1) {
                        if (p1.length) {
                            return '.' + p1;
                        }
                        return p1;
                    });
                    afterEqualSign = parts[1];
                    if (true === afterEqualSign) {
                        afterEqualSign = 1;
                    } else if (false === afterEqualSign) {
                        afterEqualSign = 0;
                    }
                    self.push(converted, afterEqualSign ? afterEqualSign : null);
                }
                // call on change state hook
                self.changeStateFn.apply(this, []);
                self.canCallChangeState = true;
                return self;
            },

            /**
             * @param key
             * @param value
             * @param [replace]
             * @param [unique]
             * @returns {UriParser}
             */
            push: function (key, value, replace, unique) {
                var self = this;

                helper.setIntoObject(self.obj, key, value, replace, unique);

                if (self.canCallChangeState) {
                    // call on change state hook
                    self.changeStateFn.apply(this, []);
                }
                return self;
            },

            /**
             * @param obj
             * @returns {UriParser}
             */
            pushObj: function (obj) {
                var self = this, o;

                if (!utils.isObject(obj)) return self;

                for (o in obj) {
                    if (obj.hasOwnProperty(o)) {
                        self.push(o, obj[o]);
                    }
                }

                if (self.canCallChangeState) {
                    // call on change state hook
                    self.changeStateFn.apply(this, []);
                }
                return self;
            },

            /**
             * @param obj
             * @returns {UriParser}
             */
            pushObjOnly: function (obj) {
                var self = this;

                if (!utils.isObject(obj)) return self;

                self.canCallChangeState = false;

                self.obj = utils.toObjectRecursively(obj);

                // call on change state hook
                self.changeStateFn.apply(this, []);
                self.canCallChangeState = true;
                return self;
            },

            /**
             * @param [key]
             * @param [value]
             * @param [strict]
             * @returns {UriParser}
             */
            clear: function (key, value, strict) {
                var self = this;

                if (key) {
                    helper.removeFromObject(self.obj, key, value, strict);
                } else {
                    self.obj = {};
                }

                if (self.canCallChangeState) {
                    // call on change state hook
                    self.changeStateFn.apply(this, []);
                }
                return self;
            },

            /**
             * @param key
             * @param [isNullOK]
             * @returns {boolean}
             */
            has: function (key, isNullOK) {
                var self = this;
                if (!key) return false;
                return helper.hasInObject(self.obj, key, isNullOK);
            },

            /**
             * @param [key]
             * @param [prefer]
             * @param [unique]
             * @param [encoded]
             * @returns {*}
             */
            get: function (key, prefer, unique, encoded) {
                var self = this;
                encoded = true === encoded;
                return helper.getFromObject(self.obj, key, prefer, !encoded, unique);
            },

            /**
             * @param [key]
             * @param [unique]
             * @param [encoded]
             * @param [numbered]
             * @returns {*}
             */
            query: function (key, unique, encoded, numbered) {
                var self = this;
                encoded = true === encoded;
                numbered = !(false === numbered);
                return '?' + self.querySearchMaker(
                    key ? helper.getFromObject(self.obj, key, {}, !encoded, unique) : self.obj,
                    key ? key.replace(self.regexp.dotToSlash, '[$1]') : null,
                    encoded,
                    numbered
                );
            },

            /**
             * @param callback
             * @returns {UriParser}
             */
            onStateChange: function (callback) {
                var self = this;
                if (utils.isFunction(callback)) {
                    self.changeStateFn = callback;
                }
                return self;
            },

            /**
             * Store [url] and [turbolinks] keys by default
             *
             * @param [stateObj]
             * @returns {UriParser}
             */
            pushState: function (stateObj) {
                var self = this;
                stateObj = stateObj && utils.isObject(stateObj) ? stateObj : {};
                self.updateSearch();

                // in case you are using Turbolinks
                stateObj.turbolinks = true;
                stateObj.url = self.search;

                if ('?' === self.search) {
                    return self;
                }

                history.pushState(stateObj, '', self.search);
                return self;
            },

            /**
             *
             * @param [stateObj]
             * @returns {UriParser}
             */
            replaceState: function (stateObj) {
                var self = this;
                stateObj = stateObj && utils.isObject(stateObj) ? stateObj : {};
                self.updateSearch();

                history.replaceState(stateObj, '', self.search);
                return self;
            },

            /**
             * @param answer
             * @returns {UriParser}
             */
            updateSearchWithNumberedQuery: function (answer) {
                this.numberedQuery = true === answer;
                return this;
            },

            /**
             * Update search/query string with global object
             * @returns {UriParser}
             */
            updateSearch: function () {
                if (this.numberedQuery) {
                    this.search = this.query(null, true, false, true);
                } else {
                    this.search = this.query(null, true, false, false);
                }
                return this;
            },
        });

        return UriParser;
    })();
})();