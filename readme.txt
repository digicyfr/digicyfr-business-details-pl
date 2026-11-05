=== Digicyfr Business Details PL ===
Tags: business, poland, nip, company, shortcode
Requires at least: 7.1
Tested up to: 7.1
Requires PHP: 8.3
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html

Show selected, owner-entered Polish business details in a block or shortcode. No external lookup or account is needed.

== Description ==

Enter your legal name, address, NIP, optional REGON and KRS, and contact details once in Settings. Insert a block or shortcode on any page to show only the fields you choose. Empty fields remain hidden.

NIP format and checksum are checked locally. This does not verify registration, VAT status, or legal compliance. The plugin has no telemetry or remote requests. See README.md for its data and uninstall behavior.

Source code and issue tracker: https://github.com/digicyfr/digicyfr-business-details-pl

== Installation ==

1. Upload the plugin ZIP under Plugins > Add New > Upload Plugin and activate it.
2. Open Settings > Business Details PL and enter the details you want to make available.
3. Add the Business Details PL block or `[digicyfr_business_details fields="name,address,nip"]` to a page.

== Frequently Asked Questions ==

= Does this check whether a company or NIP is registered? =

No. NIP validation checks only ten-digit format and checksum.

= Which data is removed on uninstall? =

The plugin's business details option is deleted. Export or back up your details first if you need to keep them.

== Changelog ==

= 0.1.0 =

* First release candidate: owner settings, NIP syntax feedback, selective block and shortcode, English and Polish strings.
