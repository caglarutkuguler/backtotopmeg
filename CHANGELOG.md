# Changelog

All notable changes to **Smart Scroll Buttons** (`backtotopmeg`).

## 2.2.1

### Fixed

- **The back-office icons turned into empty boxes on PrestaShop 9.** The 2
  icons the module draws for itself came from FontAwesome 4, which the back
  office shipped up to PrestaShop 8. PrestaShop 9 replaced it with Material
  Symbols Outlined, and FontAwesome now reaches the page only through
  `themes/default/public/theme.css` — a leftover of the old theme rather than
  anything the new back office asks for.

  An icon-font class does not name a picture; it selects a private-use code
  point that means nothing without that exact font file. So the moment the
  font is not there the browser has nothing to fall back to and draws a
  placeholder box. That makes the failure abrupt rather than gradual, and it
  shows up first on a page load with a freshly cleared asset cache.

  The icons now come from the set the core loads for its own interface, where
  the icon name is the element’s text rather than a class. They are sized
  down from its 24px default and set back to inheriting the surrounding text
  colour, so they sit exactly where the FontAwesome ones did. Nothing in the
  interface moves or changes name.

  PrestaShop 1.5 and 1.6 are the other way round — they carry FontAwesome
  and no Material icons at all — and this module still supports them, so
  the templates now pick the set the running core actually has instead of
  assuming one.

## 2.2.0

### Added

- A single review-request line on the module's own configuration page. It
  appears at the earliest 21 days after installing, asks once for a short
  review on megventure.com, and disappears forever after a click, a
  "No thanks", or three unanswered views. It makes no outbound request of any
  kind and stores nothing beyond three prefixed configuration values, which
  uninstalling removes.

## 2.1.0

### Fixed

- **Settings were stored under names another module could own.** Every setting
  went into the shop-wide `ps_configuration` table under a bare name:
  `background`, `text`, `theme`, `effect`, `width`, `height`, `margin_x`,
  `margin_y`, `z_index`, `scrollAnimation`, `front_enable`, `back_enable` and
  their `_stb` counterparts. Nothing about those names says which module wrote
  them, so a second module writing `theme` or `background` would silently change
  how the buttons looked, and this module would read a value it had never
  written. Every setting now lives under a `BTTM_` prefix.
- **Uninstalling could delete another module's settings.** This is the worse
  half of the same bug and it was destructive rather than cosmetic: `uninstall()`
  called `Configuration::deleteByName()` on those same bare names, so removing
  this module removed whatever else happened to be stored as `theme`,
  `background`, `text`, `width`, `height` or `effect`. Uninstall now deletes only
  the module's own `BTTM_` rows.

### Changed

- `upgrade-2.1.0.php` copies existing values from the old names to the new ones,
  so a merchant's configuration survives the upgrade untouched.
- **The old rows are deliberately left in place.** The module cannot tell whether
  the row called `theme` was written by it or by something else, and deleting
  someone else's setting to tidy up our own is not a trade worth making. Nothing
  reads them any more. If you want them gone, remove them by hand once you have
  checked what owns them.

### Added

- `tests/ConfigurationKeyTest.php` - 21 assertions covering the key mapping, that
  install and uninstall never touch a bare name, that the upgrade carries values
  across, and that running the upgrade twice does not overwrite a setting the
  merchant changed afterwards. Runs with plain `php`, no PrestaShop needed.

## 2.0.0

Two independent buttons - Back to Top and Scroll to Bottom - each with its own
colours, size, margins, z-index, scroll duration, appear animation and shape,
switchable separately for the front office and the back office, with a live
preview in the settings page.
