=== WP-Ad-Manager ===
Contributors: valentinas, henrikmelin, kalstrom
Tags: adverts, widgets, admin
Requires at least: 2.9
Tested up to: 3.1
Stable tag: 0.7.5

A complete system for handling advertising, including ad-rotation (with weights), scheduling and support for theme widgets. 

== Description ==

This is fork of Ad-minister, which was created by henrikmelin and kalstrom, but is no longer maintained. This version contains minor fixes, which makes it possible to use on WordPress 3.0x. Also it has improved install.

TODO:

*	Fix impressions and clicks counter
*	Change the way it stores data (now it uses post meta, which is wrong way, should use update_option/get_option)
*	Add proper menu - STARTED!
*	Porto to custom post type


wp-ad-manager is a plugin that adds a easy to use WordPress management system for adverts and other static content. It features:

*	Ad-rotation (with content having different page-view percentages)
*	Schedueling (including multiple time periods)
*	Widget compatible, easily create widgets in which to put your ads
*	Track impressions and clicks of each ad
*	Statistic available in template (e.g. for advertiser, on a password protected page)
*	Optional wrapper code for each position
*	Automatic setup

== Installation ==


1. Upload the 'wp-ad-manager' folder to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in the WordPress admin
1. Go to Wp-ad-manager meniu.
1. If a template have pre-installed advert positions, then they will appear under the 'Positions' tab in the 'Template positions' section. Widget positions can be created here, and once created, they wills show up in Presentation->Widgets, where they can be dragged onto a sidebar.
1. Create adverts/content under the "Create content" tab. Here you can enter the html code and also upload files, in the same manner files are uploaded for a post/page.
