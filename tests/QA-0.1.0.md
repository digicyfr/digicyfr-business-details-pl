# Business Details PL 0.1.0 QA record

Updated: 2026-11-05. Version 0.1.0 is a stable self-hosted GitHub release. This QA record distinguishes automated checks from untested human and representative-site checks.

## Release decision (2026-11-05)

The published ZIP passed exact-artifact installation, integration, normal browser settings/public-block flows, keyboard and 390px checks, and plugin-owned axe-core scans. Digicyfr accepted browser automation for this release decision. Human screen-reader, inconclusive admin contrast, representative business-site, older WordPress/PHP and multisite checks remain untested; no WCAG conformance claim is made. The stable label does not change the tagged source, protected release branch or ZIP bytes.

## Exact artifact

- ZIP: `dist/digicyfr-business-details-pl-0.1.0.zip` (12 files, mode 0644)
- SHA-256: `91549cc23873085c24e728bfd030b038a8e1765ade3239c33d5c2e9f7519cb2c`
- Standalone `tools/release.py build` and `verify` passed after the packaged documentation update. The ZIP has one `digicyfr-business-details-pl/` root and matches the manifest and source byte for byte. Compared with the prior `890ae44d` candidate, only bundled `README.md` and `readme.txt` changed; runtime files are byte-identical.

## Post-tag browser QA on the exact public GitHub ZIP (2026-11-05)

- Downloaded the `v0.1.0` ZIP and `.sha256` sidecar from GitHub; `sha256sum --check` passed. Standalone `tools/release.py verify --archive` matched all packaged source bytes and SHA-256 `91549cc23873085c24e728bfd030b038a8e1765ade3239c33d5c2e9f7519cb2c`.
- Installed, activated, deactivated and reactivated that downloaded ZIP in a fresh disposable WordPress 7.1/PHP 8.3.33/MariaDB 11.4.13 site.
- In Chromium, the administrator entered synthetic Polish business details and saved through the normal `options.php` form. The saved NIP normalized from `012-345-6789` to `0123456789`; the address `ul. Testowa 7\A` retained its backslash. All eight visible settings had associated labels. [Saved settings screenshot](evidence/0.1.0/settings-saved.png).
- A second normal form submission with invalid repeated-digit NIP `1111111111` showed the NIP validation message and retained the previous valid NIP. [Invalid NIP screenshot](evidence/0.1.0/invalid-nip-kept.png).
- A synthetic published page containing `digicyfr/business-details` with only `name` and `nip` selected rendered exactly those two populated fields. [Desktop](evidence/0.1.0/published-block.png) and [390px](evidence/0.1.0/published-block-390px.png) screenshots. The [settings page at 390px](evidence/0.1.0/settings-390px.png) and public page both had 390px document width with no horizontal page overflow.
- In a new page editor, the Block Inserter found **Business Details PL** and clicking it inserted `digicyfr/business-details` with all eight default fields. [Editor screenshot](evidence/0.1.0/editor-inserted.png). This inserted draft was not published; the separate synthetic page above was published. No page JavaScript errors were recorded in either browser session.
- The disposable site and volumes were removed. Manual NVDA/VoiceOver review, representative live business details, and older WordPress/PHP or multisite environments remain untested. NIP validation is mathematical only, not a registry or VAT lookup.

## Exact-ZIP automated accessibility check (2026-11-05)

On another fresh disposable WordPress 7.1/PHP 8.3.33 site, Playwright Chromium with axe-core 4.10.3 scanned WCAG 2 A/AA, 2.1 AA and 2.2 AA rules. [Machine-readable report](evidence/0.1.0/axe-audit-2026-11-05.json) records violations, incomplete checks, keyboard order, accessibility tree names and widths.

- The plugin settings page and its form had **zero axe violations**. A synthetic public page and the `dl.digicyfr-business-details-pl` component had zero plugin-specific violations; the isolated component had zero violations and zero incomplete checks. The full public page had one `list` violation in the Twenty Twenty-Five navigation; the same rule/selector failed on a WordPress core post without this plugin's block.
- Axe marked several admin form text and textarea color-contrast nodes **incomplete**, requiring visual or assistive-technology follow-up. The WordPress core General Settings page also had incomplete contrast nodes. This is not a pass claim for those contrast nodes.
- Tab from Legal name reached Address; all eight setting fields had associated labels. The browser accessibility tree exposed Legal name, NIP and the synthetic values. The settings and public pages each had document width 390px at a 390px viewport, and no page JavaScript errors occurred.
- This automated scan is not a human NVDA/VoiceOver test. The disposable site and volumes were removed; the published ZIP was unchanged.

## Automated checks passed

- PHP syntax: all plugin and test PHP files with PHP 8.3.33.
- Plugin logic tests: NIP format/checksum, repeated-digit rejection, invalid-input preservation, backslash preservation, escaping, empty fields, and block/shortcode parity.
- WordPress Coding Standards 3.4.1 `WordPress` ruleset on runtime PHP: zero errors and warnings.
- JavaScript syntax (`node --check`), block JSON parse, Polish MO catalog load.
- Current exact ZIP installed, activated, deactivated, and reactivated in a fresh disposable WordPress 7.1/PHP 8.3.33 site on 2026-11-05.
- Current exact-ZIP WordPress integration passed again on 2026-11-05: Settings API option registration, synthetic option saves including the single unslash done by `options.php`, NIP/email/URL validation, block and shortcode output, hostile stored-value escaping, contributor rejection by the settings page, Polish translation load, and option removal on uninstall.
- Browser check on the runtime-identical prior ZIP: the Settings page loaded in Chromium; an HTTP save through `options.php` preserved `C:\Projekt` and normalized `012-345-6789` to `0123456789`; a second save with an invalid repeated-digit NIP kept the prior value and showed a visible error. A published page containing the Business Details block rendered only the selected name and NIP, and its desktop layout was visually inspected. The dedicated disposable site and volumes were removed after the check.
- Additional keyboard check on the runtime-identical prior ZIP in a fresh disposable WordPress 7.1/PHP 8.3.33 site: every visible setting had an associated label; Tab moved in order from legal name through address, NIP, REGON, KRS, email, phone, website and Save. Enter on Save submitted the normal `options.php` form and the edited name persisted with a visible success notice. At a 390px viewport the document scroll width was 390px.
- Runtime-identical documentation-only `8c2169e2` ZIP interactive editor insertion passed in Chromium on WordPress 7.1/PHP 8.3.33. After closing the new-page pattern dialog, the Block Inserter found **Business Details PL**; clicking it inserted `digicyfr/business-details` with default fields, no block error, and no page JavaScript error.

## Plugin Check

WordPress.org Plugin Check ran against the runtime-identical prior ZIP installed in WordPress 7.1. It reported two distribution-specific findings: `plugin_updater_detected` (error: `Update URI` is prohibited for a WordPress.org-hosted plugin) and `load_plugin_textdomainFound` (warning: WordPress.org-hosted plugins do not need bundled translation loading). This candidate is distributed from Digicyfr, with a unique `Update URI` and its own Polish MO file; those choices are intentional for self-hosted distribution. A later WordPress.org submission needs a separate channel-specific review.

## Remaining review

- A full human screen-reader run remains untested. Interactive block-editor insertion passed on the exact public ZIP as documented above; automated checks do not replace human assistive-technology review.
- The administrator settings form was submitted over HTTP in Chromium, exercising the normal `options.php` nonce path. A direct forged contributor POST was not tested; the integration fixture checks the page capability guard.
- No upgrade migration path exists yet because 0.1.0 is the first version. Older WordPress/PHP versions and multisite network behavior were not tested.
- PHPStan is not configured. Independent runtime review passed on the prior `890ae44d` ZIP. The current ZIP changed only bundled documentation, then passed fresh integration and editor insertion checks.

## Live Digicyfr website delivery — 5 November 2026

- The live [English](https://www.digicyfr.com/en/wordpress-plugins/digicyfr-business-details-pl) and [Polish](https://www.digicyfr.com/pl/wordpress-plugins/digicyfr-business-details-pl) pages both returned HTTP 200, with the current stable v0.1.0 ZIP link, matching SHA-256, screenshot and canonical metadata; neither carried `noindex`. Both URLs appeared in the live sitemap.
- The live screenshot URL `/images/wordpress-plugins/digicyfr-business-details-pl.png` returned a PNG. `https://digicyfr.com/plugins/digicyfr-business-details-pl/` followed the `Update URI` redirect to the English detail page.
- Following the ZIP and checksum links from the live pages returned the unchanged public GitHub release asset and sidecar. ZIP SHA-256: `91549cc23873085c24e728bfd030b038a8e1765ade3239c33d5c2e9f7519cb2c`. A freshly downloaded ZIP from the live Polish page passed deterministic ZIP inspection and a clean disposable WordPress 7.1/PHP 8.3.33 install, activation, deactivation and reactivation.
- Website production commit: [`9dfb9728`](https://github.com/digicyfr/cyfr/commit/9dfb9728c92e7d4437cfe2f8368b54ce917c0008). Distribution remains a manual ZIP install/update; this is not a WordPress.org or automatic-update claim. Human screen-reader and representative-host checks remain follow-up work for broader claims.
