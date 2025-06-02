=== Blocks for ACF Fields ===
Contributors: gamaup
Tags: acf, advanced custom field, meta field, meta field block, acf block
Requires at least: 6.5
Tested up to: 6.8
Stable Tag: 1.2.0
Requires PHP: 7.4
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The easiest way to display ACF fields in the WordPress block editor — no coding required!

== Description ==
Blocks for ACF Fields lets you effortlessly load and display Advanced Custom Fields (ACF) inside the WordPress block editor using a single, flexible block. Whether you're dealing with text, images, URLs, or complex field types, this plugin makes it simple — all without writing a single line of code.

= Features =
* **No Code Needed** – Add ACF field content to your pages or templates right from the editor.
* **One Block for All Fields** – Load almost any ACF field type using just a single, versatile block.
* **Rich Typography for Text Fields** – Display string-based fields with full typography controls and formatting options.
* **Image Field Support** – Render image fields as image blocks, with all the same styling and options as WordPress core image blocks.
* **URL Fields as Buttons** – Show URL-returned fields as clickable buttons that automatically match your theme's design.
* **Supports Most ACF Field Types** – Including text, image, post object, terms, users, and more.
* **Supports All Field Locations** – Works with post fields, options pages, term fields, and user fields.
* **Full Site Editing Ready** – Fully compatible with the WordPress Site Editor for building custom templates and theme parts.

= Upgrade to PRO and unlock these features: =
* **Repeater & nested field support** – Unlock powerful layout possibilities by rendering repeater fields — including deeply nested structures — in a visual, block-based format.
* **Support for Group fields** – Easily access and display sub fields inside group fields — even when nested deeply within complex field structures.
* **Display Gallery fields as image grid** – Transform gallery fields into beautiful, responsive image grids with styling and layout controls.
* **Display Gallery fields as carousel** – Present image galleries as interactive carousels/sliders for a more dynamic visual experience.
* **Use Post Object and Relationship fields as post loops** – Turn the fields into loops and access their custom fields with nested blocks.
* **Load User fields as user loops** – Loop through users and display their associated ACF fields.
* **Load Taxonomy fields as term loops** – Loop through taxonomy terms and display their associated ACF fields, and also show its name or description within the loop using exclusive Term Field block.

[Upgrade to PRO Version](https://www.acffieldblocks.com/pro/?utm_source=wordpress.org&utm_medium=wp%20plugins%20repository&utm_campaign=BlocksforACFFields%20Pro%20Upgrade)

== Installation ==
= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t even need to leave your web browser. 

1. Go to your WordPress Plugin installation menu (Dashboard > Plugins > Add New)
2. In the search field type Blocks for ACF Fields and press enter.
3. \"Install Now\" and then click \"Active\"

= Manual installation =

For Manual installation, you download our product from WordPress directory uploading it to your web-server via your FTP or CPanel application.

1. Download the plugin and unzip it
2. Using an FTP program or CPanel upload the unzipped plugin folder to your WordPress installation’s wp-content/plugins/ directory.
3. Activate the plugin from the Plugins menu (Dashboard > Plugins > Installed Plugins) within the WordPress admin.

== Frequently Asked Questions ==

= What are the requirements to use this plugin? =

You need to have WordPress version 6.5+ and Advanced Custom Fields plugin version 6.1.0 or newer.

= Do I need the pro version of Advanced Custom Fields? =

No, you can still use the free version of Advanced Custom Fields as long as it is version 6.1.0 or newer.

= Who is this plugin for? =

This plugin is built with developers in mind — perfect for those who want to save time without sacrificing flexibility. At the same time, it's intuitive and easy enough for end users to use without technical knowledge.

= Which ACF field types are supported? =

This plugin supports most field types, including text, image, URL, true/false, select, date/time, and more. However, the following fields are not supported in the free version: Repeater, Group, Gallery, Google Maps, Icon, Flexible Content, and oEmbed.

= Can this plugin save or update ACF field values? =

No. This plugin is read-only — it's designed solely to display ACF field values in the block editor. Creating or saving field data should be done through the ACF interface or other editing tools.

= Does this plugin support the Site Editor? =

Yes, of course.

== Screenshots ==

1. Load Fields Inside a Query Block

2. Select Field to Load

3. Field Settings

== Changelog ==

= 1.2.0 =
*May 12th, 2025*

* PRO version initial release

= 1.1.4 =
*May 3rd, 2025*

* FIX: Class Fields not found

= 1.1.3 =
*May 2nd, 2025*

* FIX: Error on ACF Image from previous update

= 1.1.2 =
*May 1st, 2025*

* FIX: Hide ACF Button if the value is empty
* FIX: ACF Button was not loading the text correctly if using another field option
* UPDATE: Refactor some field helpers

= 1.1.1 =
*Apr 23rd, 2025*

* FIX: Error loading block in the Pattern editor

= 1.1.0 =
*Apr 20th, 2025*

* NEW: New block "ACF Field" to load any field type, previously type separated blocks are hidden from the inserter
* NEW: Hooks to filter the output of the fields
* UPDATE: Added shadow support for ACF Image block
* UPDATE: Added UL & OL as tag options to ACF Text block, expanding the customization options to display multiple-valued fields as a list
* UPDATE: Make sure all editor components updated to avoid deprecation warning
* UPDATE: Remove Open in New Tab option to linked email fields
* FIX: New lines was not rendered on Textarea
* FIX: Post Object & Relationship fields were not rendered correctly on the front side
* FIX: Date field value ​​(Date, DateTime, Time) were not formatted according to the date format in the field settings.

= 1.0.0 =
*Sep 17th, 2024*

* Initial Release