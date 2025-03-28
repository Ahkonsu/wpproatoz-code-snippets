  ___  _     _                         
 / _ \| |   | |                        
/ /_\ \ |__ | | _____  _ __  ___ _   _ 
|  _  | '_ \| |/ / _ \| '_ \/ __| | | |
| | | | | | |   < (_) | | | \__ \ |_| |
\_| |_/_| |_|_|\_\___/|_| |_|___/\__,_|
                                       
                                      
		
		
		\||/
                |  @___oo
      /\  /\   / (__,,,,|
     ) /^\) ^\/ _)
     )   /^\/   _)
     )   _ /  / _)
 /\  )/\/ ||  | )_)
<  >      |(,,) )__)
 ||      /    \)___)\
 | \____(      )___) )___
  \______(_______;;; __;;;
=== WpproAtoZ Elementor Snippets ===
Contributors: WP Pro A to Z
Donate link: http://WPProAtoZ.com/donate/
Tags: elementor, snippets
Requires at least: 6.0
Tested up to: 6.7.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to choose from an assortment of code snippets that are useful when building out your website especially if you use Elementor. The following snippets are available.


== Description ==

This plugin allows you to turn on and off of a number of code snippets that provide actions or filter to makke things happen. 


This plugin also features a configuration page where you can easilly toggle item on and off.


This plugin is still expanding and will cover more areas as time goes on. 

These are the features so far 
- Enable Hide elementor Page Title Filter. This one is to hide the Page/Post title though out the whole site/
- Enable Elementor Load More Display Fix Filter	
- Enable Preserve Excerpt Formatting Filter	
- Enable Hide featured images on a post by post basis Action.	
- Enable Add custom CSS to hide the featured image Action.

== Installation ==



###Updgrading From A Previous Version###



To upgrade just install it once and look for when it needs an update and update in the usual way. 


###Installing The Plugin###


1. Download the plugin ZIP file from the [releases page](https://github.com/Ahkonsu/wpproatoz-code-snippets/releases/).
2. Upload it to your WordPress site via the **Plugins** > **Add New** > **Upload Plugin**.
3. Activate the plugin through the **Plugins** menu in WordPress.


Then just visit your admin area and activate the plugin.



**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)



###Using The Plugin###



Visit the configurations page at Tools >> WPPro Code Snippets to choose which snippetts are available to turn off and on.


== Screenshots ==

screenshot1.png



== ChangeLog ==

Version 1.0 - March 28, 2025
Initial Release Features
Elementor enhancements:
Option to remove page/post titles site-wide

Fix for Elementor load more pagination issues

Featured image hiding option on a per-post basis

Custom excerpt formatting preservation

Plugin update checking via GitHub

Admin settings page with toggle controls

Menu link in plugin list for quick settings access

Added Features and Enhancements
Security Enhancements
Added nonce verification for settings form

Implemented secure meta box saving with:
Nonce verification

Autosave protection

User capability checks

Safe data handling

Better Documentation
Added PHPDoc comments for:
ele_disable_page_title()

pre_handle_404()

Additional Features
Bulk enable/disable option:
Toggle all features with a single checkbox

jQuery-powered interface

Settings export/import:
Export settings to JSON

Import settings from JSON

Secure handling with nonce verification

Performance Optimizations
Conditional hook loading:
Elementor features only load when Elementor is active

Meta box only loads in admin

Excerpt filter only on relevant pages (home, archive, single)

CSS only on single posts

Optimized featured image hiding CSS with context check

Final Enhancements
Version control:
Added WPPROATOZ_VERSION constant

Cleanup on uninstall (options and post meta)

Error handling:
Try-catch for update checker with debug logging

Internationalization:
All strings made translatable

Text domain loading support

Debug mode:
Toggle option in settings

Feature activation logging

Integration with WordPress debug system

Notes
All original comments preserved

Maintains compatibility with WordPress 6.0+ and PHP 8.0+

Translation-ready (requires language files in /languages folder)

Debug mode requires WP_DEBUG and WP_DEBUG_LOG enabled

This changelog reflects the evolution from the initial code to a fully-featured version 1.0, incorporating security, usability, performance, and maintainability improvements. Let me know if you'd like to adjust anything in the changelog or if you're ready to package this for release!



**version 1.0**
Inital full release after beta testing.

**version 0.5.6**
Added a support page and otheer upgrades

**version 0.5.5**
cleaned up readme and adjusted code for plugin ran additional tests

**version 0.5.4**
some additiona tests the auto update function now working

**version 0.5.3**
checking the autoupdate


**version 0.5.2**
added the code update chek from github


**version 0.5.0**
This is the beta version


== Notes ==

notes
Contributions by:
John, Carl