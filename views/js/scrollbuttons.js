/**
 * Smart Scroll Buttons
 * Single vanilla-JS controller shared by the Back to Top and Scroll to Bottom
 * buttons, on both the front office and the back office.
 */
(function () {
    'use strict';

    var KNOWN_EFFECTS = ['none', 'spin', 'spin-inverse', 'zoom'];

    function applyCommonStyles(el, settings, verticalSide) {
        var theme = settings.theme === 'default' ? 'default' : 'fawesome';

        el.classList.add('ps-scrollbtn--theme-' + theme);
        if (KNOWN_EFFECTS.indexOf(settings.effect) !== -1) {
            el.classList.add('ps-scrollbtn--effect-' + settings.effect);
        }

        el.style.backgroundColor = settings.background;
        el.style.color = settings.color;
        el.style.height = settings.height + 'px';
        el.style.width = settings.width + 'px';
        el.style.lineHeight = settings.height + 'px';
        el.style.fontSize = Math.round(settings.height * 0.45) + 'px';
        el.style.zIndex = settings.zIndex;
        el.style.right = settings.marginX + 'px';
        el.style[verticalSide] = settings.marginY + 'px';
    }

    function bindClick(el, settings, getTargetScrollTop) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var top = getTargetScrollTop();
            if (settings.scrollAnimation > 0) {
                window.scrollTo({ top: top, behavior: 'smooth' });
            } else {
                window.scrollTo(0, top);
            }
        });
    }

    function initBackToTop(settings) {
        if (!settings || !settings.enabled) {
            return;
        }
        var el = document.getElementById('backToTop');
        if (!el) {
            return;
        }

        applyCommonStyles(el, settings, 'bottom');

        function refresh() {
            el.classList.toggle('ps-scrollbtn--visible', window.scrollY > 600);
        }

        window.addEventListener('scroll', refresh, { passive: true });
        refresh();

        bindClick(el, settings, function () {
            return 0;
        });
    }

    function initScrollToBottom(settings) {
        if (!settings || !settings.enabled) {
            return;
        }
        var el = document.getElementById('scrollToBottom');
        if (!el) {
            return;
        }

        applyCommonStyles(el, settings, 'top');

        function refresh() {
            var nearBottom = (window.scrollY + window.innerHeight) >= (document.documentElement.scrollHeight - 2);
            el.classList.toggle('ps-scrollbtn--visible', !nearBottom);
        }

        window.addEventListener('scroll', refresh, { passive: true });
        window.addEventListener('resize', refresh);
        refresh();

        bindClick(el, settings, function () {
            return document.documentElement.scrollHeight;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initBackToTop(window.backToTopSettings);
        initScrollToBottom(window.scrollToBottomSettings);
    });
})();
