# Business Details PL 0.1.0 release candidate

SHA-256 of `digicyfr-business-details-pl-0.1.0.zip`: `91549cc23873085c24e728bfd030b038a8e1765ade3239c33d5c2e9f7519cb2c`.

This first version stores administrator-entered Polish business details, checks NIP format and checksum locally, and displays selected populated fields with a server-rendered block or shortcode. English and Polish strings are bundled. Uninstall removes the saved option.

Verified on WordPress 7.1 and PHP 8.3.33 with a fresh exact-ZIP install, activation/deactivation/reactivation, logic and WordPress integration tests. On 2026-11-05, the downloaded public GitHub ZIP passed Chromium normal `options.php` saves, invalid-NIP feedback with prior value preserved, published name/NIP block output, 390px settings/public layout and interactive Block Inserter insertion. [QA evidence](tests/QA-0.1.0.md) includes screenshots and exact scope.

This is a public GitHub prerelease. Before website production publication, complete a manual screen-reader check and representative business-site validation. Older WordPress/PHP versions and multisite network behavior were not tested. NIP validation does not verify registration or VAT status. Updates are manual ZIP installs; no automatic updater is provided.
