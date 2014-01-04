=== Plugin Name ===
Contributors: GhostToast
Tags: responsive, media query
Requires at least: 3.5.1
Tested up to: 3.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress plugin which displays responsive stage based on browser width to aid in building CSS media queries.

== Description ==

Ghost-Responsive-Stages
=======================

WordPress plugin which displays responsive stage based on browser width:

1. `>= 960px`
1. `768px - 959px`
1. `480px - 767px`
1. `<= 479px`

This helps in building CSS media queries, as you can quickly see what stage you are in when resizing your browser. 

Options include ability to switch which corner the flag is displayed in, colors (so as to be distinguishable 
from your design), as well as whether to show only to admin or not.

== Installation ==

1. Upload zip to the `/wp-content/plugins/` directory
1. Extract/Decompress
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Check Tools => Ghost Stages in the Admin menu to configure options

== Frequently Asked Questions ==

= What are media queries? =

If you have to ask that, you probably shouldn't be using this plugin yet.

= Will everyone see this, or just me? =

That depends on whether you have that option selected. You can have this for "admin only" or completely public.
This is meant to be a development tool, so after you're done you will probably want to disable the plugin.

= What if my media queries don't align to the ones you've defined? =

I suppose you'll have to edit the plugin in that case. If there becomes a high demand, I may try to build a
way for each stage to be custom defined, or possibly add/remove stages.

== Changelog ==

= 1.0 =
* Plugin released
