=== Plugin Name ===
Contributors: @kamiyeye
Donate link: http://www.themenow.com/plugins/ultimate-post-thumbnails
Tags: featured image, multiple post thumbnails, responsive
Requires at least: 4.6
Tested up to: 4.7
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The easiest way to add multiple featured images (and lightbox) to WordPress.

== Description ==

Seamlessly WordPress integrated multipe featured images plugin, re-designed the Featured Image functionality of WordPress, turns single post thumbnail to a responsive slider of multiple post thumbnails, automatically match theme style, has two built-in lightboxes, comes with a drag-and-drop backend, and no theme modification required!

Install and ready to use!

Features:

* Bring multiple featured images support for WordPress
* Compatible with existent featured image
* Responsive thumbnail slider
* Match theme style
* Thumbnail link control
* Thumbnail open method control
* PrettyPhoto Lightbox

Features of the premium version:

* Unlimited featured images
* Custom post types support
* Custom thumbnail link
* Advanced slider settings
* Built-in slider styles
* Dedicated lightbox
* Lightbox themes
* Smart image size
* Custom Image Ratio
* PhotoSwipe lightbox
* Visual Composer integration
* WooCommerce support
* Fast and professional support

More information at [Offical Site](http://www.themenow.com/plugins/ultimate-post-thumbnails).

== Installation ==

1. Upload ultimate-post-thumbnails/ to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. That's it

== Frequently Asked Questions ==

= Where do I upload multiple featured images? =

The Featured Image option box you already familiar with

= There's no Featured Image option box =

Your theme doesn't support Featured Image, check out WordPress Documentation - [Post Thumbnails article](https://codex.wordpress.org/Post_Thumbnails") for more details

= I have uploaded more than one featured image, but the frontend page shows still only one =

Your theme does support Featured Image (only the backend), but used a nonstandard way to display the featured image, which is normally a poorly developed theme downloaded from third-party websites.

A theme should use the [WordPress Thumbnail Function](https://developer.wordpress.org/reference/functions/the_post_thumbnail/) to display post thumbnails.

= Post thumbnail disappeared/mispositioned =

Some themes require an absolutely positioned image, in which case you may not see your multiple post thumbnails slider as Ultimate Post Thumbnails is positioned relatively by default, change it to Absolute in thumbnail settings will fix it.

If it doesn't work, have a check if the default featured image is deleted, which has a green tick mark on the cornor, some themes rely on it to decide display the featured image or not, fail to find it will make them misunderstand that there's no featured image.

= Theme style isn't completely inherited =

Try changing the option "Settings > Thumbnails > Inherit Theme Style" to see if it works for you, due to the nature differece between a multiple thumbnails slider and a single image, theme style may not be 100% inherited.

== Screenshots ==

1. Multiple featured images
2. Advanced slider settings
3. Plugin settings 1
4. Plugin settings 2
5. Plugin settings 3
6. Visual Composer integration
7. PhotoSwipe Lightbox
8. Smart image size
9. WooCommerce support

== Changelog ==

= 2.1 =
keep the content of v2.2.3, but change version number to 2.1, due to a conflict update message appear on premium version

= 2.2.3 =
remove screenshots from plugin package
fix a position issue with TwentyFourteen

= 2.2.2 =
* new: option Position, fix missing post thumbnails in some themes

= 2.2.1 =
* unlock Prettyphoto lightbox
* fix: default slider button color white

= 2.2 =
* add: banner and icon images
* new: default featured image has a mark now
* change: slider button color option is moved to slider settings
* change: slider button styles are renamed
* change: slider button style "Outline Circle" is changed slightly
* change: menu name changed to "Thumbnails"
* fix: lightbox not working when there's only one featured image
* fix: cannot delete default featured image

= 2.1 =
* new: Visual Composer integration
* new: PhotoSwipe lightbox
* new: smart image size
* new: custom image ratio
* new: option - image size in lightbox
* new: option - choose between PrettyPhoto and PhotoSwipe
* change: thumbnail slider respect its container's width now, images will be stretched if smaller
* change: thumbnails setting are unified, no need to repeat settings on all thumbnails now
* fix: prettyPhoto gallery images issue
* fix: incorrect mini thumbnail size

= 2.0.1 =
* fix: custom link issue
* fix: option 1 in Lightbox Size box

= 2.0 =
* new: re-designed UI
* fix: auto-height not working

= 1.1 =
* New: option "number of featured images" 
* New: image caption support
* Fix: error messages when updating post
* Minor UI changes

= 1.0.8 =
* fix: thumbnails won't save when post types data missing from the database
* clear PHP warning when there is no post types data found in the database

= 1.0.7 =
* fix: post thumbnails don't save on posts of custom types
* better compatibility with Isotope script

= 1.0.6 =
* add: loading icon
* add: options to enable/disable UPT on a post type
* add: HTML template of thumbnail slider - "template-thumbnail-slider.php" 
* update PrettyPhoto library to v3.1.6 to address a security issue
* a few minor changes
* language files updated

= 1.0.5 =
* new admin design
* fix: missing shortcode button in WP 3.9

= 1.0.4 =
* fix: a lightbox error appears when thumbnail links set to "open in current/new window" 
* fix: a warning message with PHP 5.5

= 1.0.3 =
* fix: in some hosts, lightbox doesn't work
* fix: in "horizontal slide" mode, slider doesn't show up sometimes

= 1.0.2 =
* new: lightbox supports webpage now
* new: lightbox supports custom size now
* fix: under "show thumbnail" mode, thumbnail width is incorrect when thumbnail number less than 4

= 1.0.1 =
* removed a notice under debug mode
* rewritten a PHP 5.3+ code to compatible with PHP 5.2

= 1.0
Initial version