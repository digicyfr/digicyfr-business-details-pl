# Business Details PL 0.1.0 local QA record

Updated: 2026-11-05. This is a local release candidate, not a public release.

## Exact artifact

- ZIP: `dist/digicyfr-business-details-pl-0.1.0.zip` (12 files, mode 0644)
- SHA-256: `91549cc23873085c24e728bfd030b038a8e1765ade3239c33d5c2e9f7519cb2c`
- Standalone `tools/release.py build` and `verify` passed after the packaged documentation update. The ZIP has one `digicyfr-business-details-pl/` root and matches the manifest and source byte for byte. Compared with the prior `890ae44d` candidate, only bundled `README.md` and `readme.txt` changed; runtime files are byte-identical.

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

- A full screen-reader run remains untested. Complete it before public website release; interactive block-editor insertion passed on a runtime-identical ZIP before the final documentation-only rebuild.
- The administrator settings form was submitted over HTTP in Chromium, exercising the normal `options.php` nonce path. A direct forged contributor POST was not tested; the integration fixture checks the page capability guard.
- No upgrade migration path exists yet because 0.1.0 is the first version. Older WordPress/PHP versions and multisite network behavior were not tested.
- PHPStan is not configured. Independent runtime review passed on the prior `890ae44d` ZIP. The current ZIP changed only bundled documentation, then passed fresh integration and editor insertion checks.
