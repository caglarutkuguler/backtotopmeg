# Smart Scroll Buttons

A Back to Top button and a Scroll to Bottom button for PrestaShop, each one independently configurable, with a preview in the settings page that updates as you change things — before you save.

**Compatibility:** PrestaShop 1.5 and above, including PrestaShop 8 and 9.

**Installable zip:** the archive GitHub generates on the releases page is a source snapshot, not an installable module. Download the ready-to-install zip from [megventure.com](https://megventure.com/en/free-modules/63-prestashop-back-to-top-scroll-buttons-8691246246509.html).

## What it does

- **Two buttons, independent of each other.** Back to Top appears once the visitor has scrolled down. Scroll to Bottom appears while there is still page below. Enable either, both, or neither — every setting below exists separately for each.
- **Works in the back office too.** Long configuration screens and product lists have the same problem as long storefront pages, so each button can be switched on for the back office independently of the front office.
- **Live preview.** The settings page renders a mock shop page with your current colours, size, position and animation applied. You see the result before saving, not after.
- **Appearance settings, per button:** background colour, icon colour, width and height in pixels, distance from the edges (X and Y margin), and z-index for themes that already stack floating elements.
- **Appear animation, per button:** none, fade, zoom, spin, or spin-inverse.
- **Shape, per button:** a Font Awesome chevron, or a plain shape that needs no icon font.
- **Scroll duration, per button** — how long the scroll animation takes, in milliseconds. Set it low for an instant jump.

## What it deliberately does not do

- **No database table.** Everything is stored as PrestaShop configuration values, each under a `BTTM_` prefix so it cannot collide with another module's settings. Uninstalling removes those rows and nothing else.
- **No tracking.** The module collects nothing about visitors and makes no external requests.
- **No per-page rules.** The buttons are either on for the storefront or off. If you need them hidden on specific controllers, that is a theme-level CSS job.

## Installation

1. Back Office → Modules → Upload a module, select the zip, install.
2. Open **Configure**. Both buttons are on by default, in front office and back office, in grey with white icons.
3. Change what you want, watch the preview, save.

## Notes

The **Font Awesome** shape uses your theme's icon font. Most PrestaShop themes ship it; if yours does not, the chevron will not render — switch that button's shape to **default**, which draws without an icon font.

Both buttons carry an `aria-label`, so a screen reader announces them rather than reading an empty link.

If your theme already has a floating element in the same corner — a chat bubble, a cookie bar, a back-to-top of its own — either move this button with the X/Y margins or raise its z-index. Both are settings; no CSS override needed.

## Tests

```
php tests/ConfigurationKeyTest.php
```

No PrestaShop required — `Configuration`, `Tools` and `Module` are stubbed. 21 assertions covering the 2.1.0 configuration-key change: install and uninstall never touch a configuration name this module does not own, and the upgrade carries existing settings across.

## Licence

MIT. See [LICENSE](LICENSE).

© 2007-2026 MEG Venture & Consulting Ltd. · [megventure.com](https://megventure.com/en/free-modules/63-prestashop-back-to-top-scroll-buttons-8691246246509.html)
