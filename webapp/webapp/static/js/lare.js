/*!
 * lare.js v1.0.0 by @lare-team
 * Copyright (c) 2015 lare-team
 *
 * http://www.iekadou.com/programming/lare.js/
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
(function ($) {
    'use strict';
    var version = "1.0.0";
    var supportedVersion = "1.0.0";

    function fnLare(selector, options) {
        return this.on('click.lare', selector, function (event) {
            handleClick(event, options);
        });
    }

    function fnLareReady(func) {
        // calls func everytime a link is followed. Not called on popstate.
        if (!$.isReady) {
            $(document).ready(func);
        }
        return $(document).on('lare:done', func);
    }

    function fnLareReadyOnce(func) {
        if (!$.isReady) {
            return $(document).ready(func);
        } else {
            return $(document).one('lare:done', func);
        }
    }

    function fnLareAlways(func) {
        // same as fnLareReady but will be called on popstate, too.
        if (!$.isReady) {
            $(document).ready(func);
        }
        return $(document).on('lare:end', func);
    }

    function request(link, options) {
        // enables pure request calls
        if (typeof link === 'string') {
            var link_element = document.createElement('A');
            link_element.href = link;
            link = link_element;
        }

        if (link.tagName.toUpperCase() !== 'A') {
            throw '$.fn.lare requires an anchor element';
        }

        // ignore cross origin links and pass relative IE links (cause they IE can't do code)
        if ((location.protocol !== link.protocol || location.hostname !== link.hostname) && !(link.protocol === ':' && link.hostname === '')) {
            return;
        }

        // ignore anchors on the same page
        if (link.hash && link.href.replace(link.hash, '') === location.href.replace(location.hash, '')) {
            return;
        }

        // ignore empty anchor 'foo.html#'
        if (link.href === location.href + '#') {
            return;
        }

        var url = $.isFunction(link.href) ? link.href() : link.href;

        var xhr_url = url;
        if (xhr_url.indexOf('?lare') == -1 && xhr_url.indexOf('&lare') == -1) {
            xhr_url += url.indexOf('?') == -1 ? '?lare' : '&lare';
        }

        var defaults = {
            url: xhr_url,
            plainUrl: url,
            type: 'GET',  // always GET since we currently not support other methods
            dataType: 'html'
        };

        var opts = fnLare.options = $.extend(true, {}, $.ajaxSettings, defaults, $.fn.lare.defaults, options);

        if (!namespace) {
            namespace = $('body').data('lare-namespace') || '';
        }

        var timeoutTimer;

        opts.beforeSend = function (xhr, settings) {
            if (!fire('lare:beforeSend', [xhr, settings])) {
                return false;
            }

            xhr.setRequestHeader('X-LARE', namespace);
            xhr.setRequestHeader('X-LARE-VERSION', opts.version);

            if (settings.timeout > 0) {
                timeoutTimer = setTimeout(function () {
                    if (fire('lare:timeout', [xhr, opts])) {
                        xhr.abort('timeout');
                    }
                }, settings.timeout);

                // clear timeout setting so jQuery's internal timeout isn't invoked
                settings.timeout = 0;
            }

            return true;
        };

        // create lare state for initial page load
        if (!fnLare.state) {
            fnLare.state = {
                id: uniqueId(),
                namespace: namespace,
                url: window.location.href,
                title: document.title
            };
            window.history.replaceState(fnLare.state, fnLare.state.title, fnLare.state.url);
        }

        var xhr = fnLare.xhr;

        // cancel the current running lare request if there is one
        if (xhr && xhr.readyState < 4) {
            xhr.onreadystatechange = $.noop;
            xhr.abort();
        }

        // go-go-lare
        xhr = fnLare.xhr = $.ajax(opts);

        if (xhr.readyState > 0) {
            fire('lare:start', [opts]);
            fire('lare:send', [opts]);
        }

        xhr.done(function (data, textStatus, jqXHR) {
            fire('lare:success', [opts]);

            var supportedVersion = (typeof opts.supportedVersion === 'function') ? opts.supportedVersion() : opts.supportedVersion;
            var backendVersion = jqXHR.getResponseHeader('X-LARE-VERSION');

            // If there is a layout version mismatch, hard load the new url
            if (supportedVersion && backendVersion && compareVersions(supportedVersion, backendVersion) < 0) {
                loadHard(opts.plainUrl);
                return;
            }

            var head_match = data.match(/<lare-head>([\s\S.]*)<\/lare-head>/i);
            var body_match = data.match(/<lare-body>([\s\S.]*)<\/lare-body>/i);

            // if response data doesn't fit, hard load the new url
            if (!head_match && !body_match) {
                loadHard(opts.plainUrl);
                return;
            }
            fire('lare:success', [data, textStatus, jqXHR, opts]);

            // Clear out any focused controls before inserting new page contents.
            document.activeElement.blur();

            var stateId = uniqueId();

            if (head_match) {
                var $head = $(parseHTML(head_match[0]));
                var head_parts = processLareHead('forward', $head.children(), null, null);
                var apply_head_parts = head_parts[0];
                var revert_head_parts = head_parts[1];
                var remove_head_parts = head_parts[2];
            }

            if (body_match) {
                var $body = $(parseHTML(body_match[0]));
                var body_parts = processLareBody($body.children());
                var apply_body_parts = body_parts[0];
                var revert_body_parts = body_parts[1];
            }

            var namespace_match = data.match(/<lare-namespace>([\s\S.]*)<\/lare-namespace>/i);
            if (namespace_match) {
                namespace = $(parseHTML(namespace_match[0].replace(/(\r\n|\n|\r|\s|\t)/gm, ''))).html();
                $('body').attr('data-lare-namespace', namespace);
            }

            // FF bug: Won't autofocus fields that are inserted via JS.
            // This behavior is incorrect. So if there's no current focus, autofocus
            // the last field.
            //
            // http://www.w3.org/html/wg/drafts/html/master/forms.html
            $(document).find('input[autofocus], textarea[autofocus]').last().focus();

            if (typeof opts.scrollTo === 'number') {
                $(window).scrollTop(opts.scrollTo);
            }

            // enrich current state information with removal instructions
            $.extend(fnLare.state, {
                head_revert: head_match ? revert_head_parts : null,
                head_remove: head_match ? remove_head_parts : null,
                body_revert: body_match ? revert_body_parts : null
            });
            if (opts.push || opts.replace) {
                window.history.replaceState(fnLare.state, fnLare.state.title, fnLare.state.url);
            }

            fnLare.state = {
                id: stateId,
                namespace: namespace,
                // by pushing the plainUrl - lare param doesn't appear in the navigation bar and prevents browser caching bugs - #33
                url: opts.plainUrl,
                title: document.title,
                head_apply: head_match ? apply_head_parts : null,
                body_apply: body_match ? apply_body_parts : null
            };

            if (opts.push) {
                window.history.pushState(fnLare.state, fnLare.state.title, fnLare.state.url);
            }
            else if (opts.replace) {
                window.history.replaceState(fnLare.state, fnLare.state.title, fnLare.state.url);
            }

            fire('lare:done', [data, textStatus, jqXHR, opts]);
        });

        xhr.fail(function (jqXHR, textStatus, errorThrown) {
            if (textStatus !== 'abort' && fire('lare:fail', [jqXHR, textStatus, errorThrown, opts])) {
                loadHard(opts.plainUrl);
            }
        });

        xhr.always(function () {
            if (timeoutTimer) {
                clearTimeout(timeoutTimer);
            }

            fire('lare:always', [opts]);
            fire('lare:end', [opts]);
        });
    }

    function handleClick(event, options) {
        var link = event.currentTarget;

        if (link.tagName.toUpperCase() !== 'A') {
            throw '$.fn.lare requires an anchor element';
        }

        // middle click, cmd click, and ctrl click should open links in a new tab as normal.
        if (event.which > 1 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        // open new window for target _blank
        if (link.target != '' && link.target != '_self') {
            return;
        }

        // ignore prevented links
        if (event.isDefaultPrevented()) {
            return;
        }

        if (!fire('lare:click')) {
            event.preventDefault();
            return;
        }

        request(link, options);

        event.preventDefault();
    }

    function processLareHeadElements(elements, append) {
        var apply_head_parts = [];
        var revert_head_parts = [];
        var remove_head_parts = [];

        if (elements && elements.length > 0) {
            $.each(elements, function (index, value) {
                var $value = $(value);

                // only applied on push
                if ($value.is('title')) {
                    document.title = $value.text();
                }
                else if ($value.is('meta')) {
                    var $meta;
                    var name = $value.attr('name');
                    var property = $value.attr('property');

                    if (name) {
                        $meta = $('head > meta[name="' + name + '"]');
                    }
                    else if (property) {
                        $meta = $('head > meta[property="' + property + '"]');
                    }

                    if ($meta !== undefined) {
                        if ($meta.length > 0) {
                            remove_head_parts.push(outerHTML($meta));
                            $meta.remove();
                        }
                        else {
                            revert_head_parts.push(outerHTML($value));
                        }

                        if (append === true) {
                            $('head').append($value);
                            apply_head_parts.push(outerHTML($value));
                        }
                    }
                }
                else if ($value.is('link')) {
                    var link_href = $value.attr('href');
                    if (link_href) {
                        var $link = $('head > link[href="' + link_href + '"]');

                        if ($link.length > 0) {
                            remove_head_parts.push(outerHTML($link));
                            $link.remove();
                        }
                        else {
                            revert_head_parts.push(outerHTML($value));
                        }

                        if (append === true) {
                            $('head').append($value);
                            apply_head_parts.push(outerHTML($value));
                        }
                    }
                }
                else if ($value.is('script')) {
                    var script_src = $value.attr('src');
                    if (script_src) {
                        var $script = $('head > script[src="' + script_src + '"]');

                        if ($script.length > 0) {
                            remove_head_parts.push(outerHTML($script));
                            $script.remove();
                        }
                        else {
                            revert_head_parts.push(outerHTML($value));
                        }

                        if (append === true) {
                            $('head').append($value);
                            apply_head_parts.push(outerHTML($value));
                        }
                    }
                }
                else if ($value.is('style')) {
                    if (append === true) {
                        $('head').append($value);
                        apply_head_parts.push(outerHTML($value));
                        revert_head_parts.push(outerHTML($value));
                    }
                }
            });
        }

        return [apply_head_parts, revert_head_parts, remove_head_parts];
    }

    function processLareHead(direction, apply_elements, revert_elements, remove_elements) {
        var apply_head_parts = [];
        var revert_head_parts = [];
        var remove_head_parts = [];

        // cleanup head elements
        if (direction === 'forward') {
            $('head > [data-remove-on-lare]').each(function () {
                var $this = $(this);
                remove_head_parts.push(outerHTML($this));
                $this.remove();
            });
        }
        else if (direction === 'back') {
            var revert_result = processLareHeadElements(revert_elements, false);
            var remove_result = processLareHeadElements(remove_elements, true);

            // there are no apply elements on back processing
            $.extend(revert_head_parts, revert_result[1]);
            $.extend(remove_head_parts, remove_result[2]);
        }

        var apply_result = processLareHeadElements(apply_elements, true);
        $.extend(apply_head_parts, apply_result[0]);
        $.extend(revert_head_parts, apply_result[1]);
        $.extend(remove_head_parts, apply_result[2]);

        return [apply_head_parts, revert_head_parts, remove_head_parts];
    }

    function processLareBody(elements) {
        var apply_body_parts = [];
        var revert_body_parts = [];

        if (elements && elements.length > 0) {
            $.each(elements, function (index, value) {
                var $value = $(value);
                var id = $value.attr('id');
                if (id) {
                    var $target = $('#' + id);
                    if ($target.length > 0) {
                        revert_body_parts.push(outerHTML($target));
                        try {
                            $target.html($value.html());
                        }
                        catch (error) {
                            if (window.console) {
                                console.error(error);
                            }
                        }
                        apply_body_parts.push(outerHTML($target));
                    }
                }
            });
        }

        return [apply_body_parts, revert_body_parts];
    }

    // helper to trigger jQuery events
    function fire(type, args) {
        var event = $.Event(type);
        $(document).trigger(event, args);
        return !event.isDefaultPrevented();
    }

    // wrapper to save some arguments
    function parseHTML(html) {
        return $.parseHTML(html, document, true)
    }

    // helper to get the outer html
    function outerHTML(element) {
        return element.clone().wrap('<p>').parent().html();
    }

    // generate a unique id for state object based on the current timestamp
    function uniqueId() {
        return (new Date()).getTime();
    }

    // hard load to new state without lare
    // replace would brick expected history behavior - see: #17
    function loadHard(url) {
        window.location.href = url;
    }

    // takes care of the back and forward functionality
    function onLarePopstate(event) {
        var state = event.state;

        if (state) {
            // when coming forward from a separate history session, will get an
            // initial pop with a state we are already at. Skip reloading the current page.
            if (initialPop && initialURL == state.url) {
                return;
            }

            fire('lare:start', [fnLare.options]);

            // title is always set, no check
            document.title = state.title;

            // determine whether to go back or forth
            var direction = fnLare.state.id < state.id ? 'forward' : 'back';

            // null check inside
            processLareHead(direction, state.head_apply, state.head_revert, state.head_remove);

            var body = direction === 'forward' ? state.body_apply : state.body_revert;
            if (body && body.length > 0) {
                processLareBody(body);
            }

            fnLare.state = state;
            namespace = state.namespace;

            // force reflow / relayout before the browser tries to restore the scroll position.
            document.body.offsetHeight;

            fire('lare:end', [fnLare.options]);
        }
        initialPop = false;
    }

    // helper to compare versions
    function compareVersions(v1, v2) {
        var v1parts = v1.split('.');
        var v2parts = v2.split('.');

        while (v1parts.length < v2parts.length) v1parts.push("0");
        while (v2parts.length < v1parts.length) v2parts.push("0");

        for (var i = 0; i < v1parts.length; ++i) {
            var v1part = parseInt(v1parts[i]),
                v2part = parseInt(v2parts[i]);
            if (v1part < v2part) return 1;
            if (v1part > v2part) return -1;
        }
        return 0;
    }

    var initialPop = true;
    var initialURL = window.location.href;
    var initialState = window.history.state;
    var namespace;

    // initialize $.fnLare.state if possible
    // happens when reloading a page and coming forward from a different session history.
    if (initialState) {
        fnLare.state = initialState;
    }

    // non-webkit browsers don't fire an initial popstate event
    if ('state' in window.history) {
        initialPop = false;
    }

    // add the state property to jQuery's event object so we can use it in $(window).on('popstate', ...)
    if ($.inArray('state', $.event.props) < 0) {
        $.event.props.push('state');
    }

    // enables pushState behavior
    function enable() {
        $.fn.lare = fnLare;
        $.fn.lareReady = fnLareReady;
        $.fn.lareReadyOnce = fnLareReadyOnce;
        $.fn.lareAlways = fnLareAlways;
        $.fn.lare.click = handleClick;
        $.fn.lare.request = request;
        $.fn.lare.enable = $.noop;
        $.fn.lare.disable = disable;
        $.fn.lare.defaults = {
            timeout: 2000,
            push: true,
            replace: false,
            scrollTo: 0,
            supportedVersion: supportedVersion,
            version: version
        };
        $(window).on('popstate.lare', onLarePopstate);
    }

    // disable pushState behavior
    function disable() {
        $.fn.lare = function () {
            return this;
        };
        $.fn.lareReady = function (func) {
            return $(document).ready(func);
        };
        $.fn.lareReadyOnce = function (func) {
            return $(document).ready(func);
        };
        $.fn.lareAlways = function (func) {
            return $(document).ready(func);
        };
        $.fn.lare.enable = enable;
        $.fn.lare.disable = $.noop;
        $(window).off('popstate.lare', onLarePopstate);
    }

    // is lare supported by this browser?
    $.support.lare = window.history && window.history.pushState && window.history.replaceState &&
        // pushState isn't reliable on iOS until 5
    !navigator.userAgent.match(/((iPod|iPhone|iPad).+\bOS\s+[1-4]|WebApps\/.+CFNetwork)/);
    // initial executes enable / disable lare when the script gets loaded
    $.support.lare ? enable() : disable();
})(jQuery);
