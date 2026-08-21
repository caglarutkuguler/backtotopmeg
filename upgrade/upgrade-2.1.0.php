<?php
/**
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2007-2026 MEG Venture & Consulting Ltd.
 * @license   https://opensource.org/licenses/MIT MIT License
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * 2.1.0 moves every setting from a bare configuration name to a BTTM_ prefixed one.
 *
 * Up to 2.0.0 the settings lived in ps_configuration under names like
 * 'background', 'text', 'theme', 'width' and 'effect'. Those are generic enough
 * that another module may be using the same row.
 *
 * What this step does NOT do is delete the old rows. This module cannot tell
 * whether the row called 'theme' was written by it or by something else, and
 * deleting someone else's setting to tidy up our own is not a trade worth
 * making. The values are copied to the new names; the old rows are simply left
 * alone and never read again. A merchant who wants them gone can remove them by
 * hand once they have checked what owns them.
 *
 * @param Backtotopmeg $module
 *
 * @return bool
 */
function upgrade_module_2_1_0($module)
{
    $keys = [
        'front_enable',
        'back_enable',
        'background',
        'text',
        'effect',
        'height',
        'width',
        'margin_x',
        'margin_y',
        'scrollAnimation',
        'theme',
        'z_index',
        'front_enable_stb',
        'back_enable_stb',
        'background_stb',
        'text_stb',
        'height_stb',
        'width_stb',
        'margin_x_stb',
        'margin_y_stb',
        'scrollAnimation_stb',
        'theme_stb',
        'z_index_stb',
    ];

    foreach ($keys as $key) {
        $old = Configuration::get($key);

        // Nothing stored under the old name: nothing to carry over.
        if ($old === false || $old === null) {
            continue;
        }

        $new = Backtotopmeg::CONF_PREFIX . Tools::strtoupper($key);

        // A fresh install has already written defaults under the new name.
        // The merchant's own value wins over a default, so only fill a gap.
        if (Configuration::get($new) === false) {
            Configuration::updateValue($new, $old);
        }
    }

    return true;
}
