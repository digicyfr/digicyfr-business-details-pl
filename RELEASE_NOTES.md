# Business Details PL 0.1.0 release candidate

SHA-256 of `digicyfr-business-details-pl-0.1.0.zip`: `91549cc23873085c24e728bfd030b038a8e1765ade3239c33d5c2e9f7519cb2c`.

This first version stores administrator-entered Polish business details, checks NIP format and checksum locally, and displays selected populated fields with a server-rendered block or shortcode. English and Polish strings are bundled. Uninstall removes the saved option.

Verified on WordPress 7.1 and PHP 8.3.33 with a fresh exact-ZIP install, activation/deactivation/reactivation, logic and WordPress integration tests. Chromium Block Inserter selection and insertion passed on a runtime-identical ZIP before the final documentation-only rebuild. No plugin PHP, JavaScript, CSS or translation bytes changed between those packages.

Before public release, complete a manual screen-reader check. Older WordPress/PHP versions and multisite network behavior were not tested. NIP validation does not verify registration or VAT status. Updates are manual ZIP installs; no automatic updater is provided. See `tests/QA-0.1.0.md` for full evidence.
