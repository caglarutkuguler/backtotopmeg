/**
 * Smart Scroll Buttons - admin settings page live preview.
 * Reads the current values straight out of the two config forms and mirrors
 * them onto the demo buttons in views/templates/admin/configure.tpl, so the
 * merchant can see the result before saving.
 */
document.addEventListener('DOMContentLoaded', function () {
    var previewTop = document.getElementById('backtotopmeg-preview-top');
    var previewBottom = document.getElementById('backtotopmeg-preview-bottom');

    if (!previewTop && !previewBottom) {
        return;
    }

    function fieldValue(name, fallback) {
        var checked = document.querySelector('input[name="' + name + '"]:checked');
        if (checked) {
            return checked.value;
        }
        var el = document.querySelector('[name="' + name + '"]');
        return (el && el.value) ? el.value : fallback;
    }

    function render(el, suffix) {
        if (!el) {
            return;
        }

        var theme = fieldValue('theme' + suffix, 'fawesome');
        var size = parseInt(fieldValue('height' + suffix, 40), 10);
        if (!size || size < 16) {
            size = 40;
        }
        if (size > 96) {
            size = 96;
        }

        el.style.backgroundColor = fieldValue('background' + suffix, '#5D5D5D');
        el.style.color = fieldValue('text' + suffix, '#FFFFFF');
        el.style.height = size + 'px';
        el.style.width = size + 'px';
        el.style.lineHeight = size + 'px';
        el.style.fontSize = Math.round(size * 0.45) + 'px';
        el.style.borderRadius = theme === 'default' ? '50%' : '25%';
    }

    function refresh() {
        render(previewTop, '');
        render(previewBottom, '_stb');
    }

    refresh();
    // Poll instead of binding to specific events: the color/switch/radio
    // widgets PrestaShop renders vary across versions, polling stays correct
    // regardless of how a given value changed.
    setInterval(refresh, 300);
});
