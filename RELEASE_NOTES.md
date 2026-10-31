# Smart Scroll Buttons - Back to Top and Scroll to Bottom — Release notes

_Only customer-relevant changes are listed. Internal refactoring, tests and housekeeping are omitted._

## 2.2.1

### Fixed
- The back-office icons turned into empty boxes on PrestaShop 9. The 2 icons the module draws for itself came from FontAwesome 4, which the back office shipped up to PrestaShop 8. PrestaShop 9 replaced it with Material Symbols Outlined, and FontAwesome now reaches the page only through `themes/default/public/theme.css` — a leftover of the old theme rather than anything the new back office asks for
- PrestaShop 1.5 and 1.6 are the other way round — they carry FontAwesome and no Material icons at all — and this module still supports them, so the templates now pick the set the running core actually has instead of assuming one

## 2.2.0

### New
- A single review-request line on the module's own configuration page. It appears at the earliest 21 days after installing, asks once for a short review on megventure.com, and disappears forever after a click, a "No thanks", or three unanswered views. It makes no outbound request of any kind and stores nothing beyond three prefixed configuration values, which uninstalling removes

## 2.1.0

### Improved
- The old rows are deliberately left in place. The module cannot tell whether the row called `theme` was written by it or by something else, and deleting someone else's setting to tidy up our own is not a trade worth making. Nothing reads them any more. If you want them gone, remove them by hand once you have checked what owns them

### Fixed
- Settings were stored under names another module could own. Every setting went into the shop-wide `ps_configuration` table under a bare name: `background`, `text`, `theme`, `effect`, `width`, `height`, `margin_x`, `margin_y`, `z_index`, `scrollAnimation`, `front_enable`, `back_enable` and their `_stb` counterparts. Nothing about those names says which module wrote them, so a second module writing `theme` or `background` would silently change how the buttons looked, and this module would read a value it had never written. Every setting now lives under a `BTTM_` prefix
