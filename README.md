# Digicyfr Business Details PL

Display business details entered by a site administrator in a server-rendered block or shortcode. It is free software under GPL-2.0-or-later and does not require a Digicyfr account or any external service.

## Requirements and installation

The 0.1.0 candidate has been developed for WordPress 7.1 and PHP 8.3. Compatibility with older versions is not claimed. Install the ZIP through **Plugins → Add New → Upload Plugin**, then open **Settings → Business Details PL**. Enter only the details you intend to show publicly.

For a page or post, insert the **Business Details PL** block and use its sidebar toggles to choose fields. The block renders saved data on the public page. In the editor it shows the selected field names, not the public values.

Alternatively, use a shortcode:

```text
[digicyfr_business_details]
[digicyfr_business_details fields="name,address,nip"]
```

Allowed field keys are `name`, `address`, `nip`, `regon`, `krs`, `email`, `phone`, and `website`. The shortcode and block show only selected, populated fields. Empty selections produce no markup. Data is escaped on output. Website links accept only HTTP or HTTPS.

## NIP and data limits

The settings page accepts ten-digit NIP values with optional spaces or hyphens and checks the Polish checksum locally. Invalid input produces an error and retains the previous saved NIP. This is a **syntax check**, not a registry lookup, business verification, VAT status check, or legal disclosure review. REGON and KRS are owner-entered text; this plugin does not verify them.

All values are stored in the site's WordPress database as one `digicyfr_business_details_pl_settings` option. Name, address, NIP, REGON, KRS, email, phone, and website may include personal data for a sole trader. The plugin sends no data to Digicyfr or third parties, uses no telemetry, and does not load remote assets. The selected values are public wherever the block or shortcode is placed. Only users with `manage_options` can change the settings. **Uninstalling deletes this plugin's option**, including on each site in a multisite network. Back up the data first if you need to keep it.

## Updates and support

This standalone ZIP uses a unique WordPress `Update URI` to avoid name collisions. Version 0.1.0 has no automatic update service; install a later ZIP manually after reviewing its changelog and backing up the site. The [public source repository](https://github.com/digicyfr/digicyfr-business-details-pl) accepts bug reports and contributions. Report security issues privately using [SECURITY.md](https://github.com/digicyfr/digicyfr-business-details-pl/blob/main/SECURITY.md). Do not include real business or customer data in reports.

## Development and release

From this repository's root, `python3 tools/release.py build` reads `release-files.txt` and makes a deterministic ZIP and SHA-256 file in `dist/`; `python3 tools/release.py verify` checks the archive against current source bytes. Run `php tests/run.php` and `tests/run-wordpress-integration.sh dist/digicyfr-business-details-pl-0.1.0.zip` before release. See the [release process](https://github.com/digicyfr/digicyfr-business-details-pl/blob/main/RELEASE.md) for the release gates. The plugin has no runtime dependency on its repository tools or other Digicyfr plugins.
