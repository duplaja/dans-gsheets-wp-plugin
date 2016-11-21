=== Dan's GSheets Data Embedder ===
Contributors: duplaja
Donate link: https://www.wptechguides.com/donate/
Tags: embed, Google Sheets, data, gsheets, spreadsheet, dan
Requires at least: 4.0.1
Tested up to: 4.6.1
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Dan's GSheets is used to embed Google Sheets data from individual cells or ranges, in a manner that will update on page reload.

== Description ==

Dan's Google Sheets Data Embedder was created out of a need for non-technical users to move complex calculations outside WordPress and into a more familiar spreedsheet environment, as well as the ability to share just the pieces of information needed from a spreadsheet rather than sharing the entire document.

Dan's GSheets allows you to embed the values for either an individual cell, or for ranges directly via shortcode. No need to import data or manage it directly in WordPress. All you need is a public Google Sheets Document (or multiple!) and a free, easy to get API key.

Features

* Displays public Google Sheet Document data in an easy-to-format view

* Live updates on page load. Just change your data in your sheet, and it's live on site!

* Displays cells or ranges of cells from any public Google Sheet

* Individual cells are displayed as spans

* Spans of cells displayed as tables, with optional headers

* Can target any tab within a Google Sheets document

* Able to use shortcode multiple times per post / page

* All options are configured via shortcode

* Ability to store and use unlimited Google Sheet Documents

Shortcodes:

* Basic: `[dansheet]` (defaults to first document, default tab name)

* Single Cell: `[dansheet file=1 sheetname="Sheet1" cell=A1 class="gsheets-special"]`

*Range of Cells: `[dansheet file=1 sheetname=Sheet1 cell=A1:C2 theaders="Col 1,Col 2,Col 3" class="gsheets-special2"]`

Optional Attributes

* file=# (number of the Google Doc you have set in the settings page)

* sheetname= name of sheet in doc

* cell (mandatory)= Cell Number or range, with :

* class=”custom class name or names here

For help creating an API key to use with this plugin, either check out the settings page in plugin, or the FAQ tab here.


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/dans-gsheets` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Head over to the Dan's GSheets settings page, found on the Dashboard sidebar on the Tools sub-menu.

== Frequently Asked Questions ==

= How do I create my API key? =

To create API key, visit Google Developers Console. https://console.developers.google.com/ 
Then, follow bellow;

* Create new project (or use project you created before).

* Check "APIs & auth" -> "Credentials" on side menu.

* Hit "Create new Key" button on "Public API access" section.

* Choose "Browser key" and keep blank on referer limitation.

* Set this key on the plugin's setting page.

= How do I find the ID for the folder I want to share? =

Once you have set the folder as public, you can find the id with the following:

* Visit https://sheets.google.com/, while logged in to your account.

* Enter the sheets document that you have shared publically

* Find the folder ID from the url, after the /folders/. Example: https://docs.google.com/spreadsheets/d/xxxxxxxxxxxxxxxxxxx/edit , where xxxxxxxxxxxxxxx is the key.

= My values aren't updating when I change them in Google Sheets? =

* You may have caching enabled. Either exclude the site from your caching plugin, or you will have to manually flush your caching plugin after you make a change to your Google Sheet.

== Screenshots ==

1. Settings Page

2. Single Cell and Range Examples with Shortcodes

3. Public Sheet the 2nd screenshot is drawing from

== Dependencies and Liscencing ==

Depends on Google Sheets JSON API v4

== Changelog ==

= 1.0 =
* Initial Plugin Release

== Upgrade Notice ==

= 1.0 =
* Initial Plugin Release
