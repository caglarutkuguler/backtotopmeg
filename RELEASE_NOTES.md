# Smart Scroll Buttons - Back to Top and Scroll to Bottom — Release notes

_Only customer-relevant changes are listed. Internal refactoring, tests and housekeeping are omitted._

## 2.2.2

### New
- The configuration page links to the free MEG Venture Updates module, which installs updates for this module in one click. The line is hidden once that module is installed

## 2.2.1

### Fixed
- The back-office icons turned into empty boxes on PrestaShop 9. The 2 icons the module draws for itself came from FontAwesome 4, which the back office shipped up to PrestaShop 8. PrestaShop 9 replaced it with Material Symbols Outlined, and FontAwesome now reaches the page only through `themes/default/public/theme.css` — a leftover of the old theme rather than anything the new back office asks for
- PrestaShop 1.5 and 1.6 are the other way round — they carry FontAwesome and no Material icons at all — and this module still supports them, so the templates now pick the set the running core actually has instead of assuming one

## 2.2.0

### New
- A single review-request line on the module's own configuration page. It appears at the earliest 21 days after installing, asks once for a short review on megventure.com, and disappears forever after a click, a "No thanks", or three unanswered views. It makes no outbound request of any kind and stores nothing beyond three prefixed configuration values, which uninstalling removes
