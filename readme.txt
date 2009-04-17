=== GD Broken Report ===
Contributors: gdragon
Donate link: http://www.dev4press.com/donate/
Version: 1.1.0
Tags: admin, report, broken, email, template, exclude, insert, video
Requires at least: 2.5.0
Tested up to: 2.7.1
Stable tag: trunk

Add report broken post to preset email address using templates.

== Description ==
Plugin adds report broken post option to each post and page. Report option is rendered on all posts and pages by default, but you can add categories, posts or pages to exclude report from. You can also add report link in different parts of blog. All this is controled through settings panel.

Plugin uses 2 templates for integration and 1 template for email. Integration templates are in the *integrate* folder. You can style them anyway you want. Only retain %URL% tag in *report_broken.txt* so that the URL can be added by the plugin. Email template supports few tags and is located in *templates* folder.

Once the post is reported broken, email is sent to address provided in the plugin settings. Plugin adds meta element *gd_broken_report* to the reported post. Once you fix the problem, delete that custom meta element.

= Included Languages =
* English
* Serbian

= Century Hits =
Plugin is created for *Century Hits* website, but Will, website owner decided to offer this plugin for free to WordPress community. So, check out his wbesite: http://www.centuryhits.com/

== Installation ==

= Requirements =
* PHP: 4.4.x or 5.x.x
* mySQL: 4.0, 4.1 or 5.x
* WordPress: 2.5.0 or newer

* Upload folder `gd-broken-report` to the `/wp-content/plugins/` directory
* Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
Nothing here yet.

== Screenshots ==
1. Settings panel

== Configuration ==
Nothing here yet.

= Century Hits =
* http://www.centuryhits.com/

= Website =
* Plugin Home: http://www.dev4press.com/
* Plugin Page: http://www.dev4press.com/plugins/gd-broken-report/

= Communities =
* WordPress Extend: http://wordpress.org/extend/plugins/gd-broken-report/