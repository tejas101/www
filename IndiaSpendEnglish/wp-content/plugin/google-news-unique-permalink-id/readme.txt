=== Plugin Name ===
Contributors: brainsap-dev
Donate link: http://www.brainsap-development.com/plugins-resources/wordpress-plugin-google-news-unique-permalink-id/#donate
Tags: permalink, google news, unique id
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.0.4

This Plugin automatically adds 5 Digit Unique IDs to Permalinks ready for use with Google News.

== Description ==

For a more detailed description please visit the [Google News Unique Permalink ID Plugin Homepage](http://www.brainsap-development.com/plugins-resources/wordpress-plugin-google-news-unique-permalink-id/ "Google News Unique Permalink ID Plugin Homepage"). Note that there is also a [German description for Google News Unique Permalink ID](http://www.brainsap-development.com/de/plugins-resources/wordpress-plugin-google-news-unique-permalink-id/ "German description for Google News Unique Permalink ID")

This Plugin will generate Permalinks formatted for Google News, i.e. `myblog.org/this-is-a-very-interesting-post-12345` or `myblog.org/this-is-a-very-interesting-post-for-real-45678`

*Requirements - **Important**:*

* Settings > Permalinks has to be set to "Custom Structure" **/%postname%** (do not forget the leading slash!)


*Features:*

* Adds a unique 5-digit ID to all Permalinks on your Blog to make it work properly with Google News

*Example:*

* You add a Post with the name "This is a very Interesting Post"
    -> The Permalink will look like this: `myblog.org/this-is-a-very-interesting-post-12345` where 12345 is a randomly generated unique ID

This Plugin is powered by Brainsap Development - [Web Development Services](http://www.brainsap-development.com/ "Web Development Services")

== Installation ==

1. Upload `google-news-unique-permalink-id.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in Wordpress
3. Settings > Permalinks has to be set to "Custom Structure" **/%postname%** (don't forget the leading slash)

== Frequently Asked Questions ==

Checkout the [Google News Unique Permalink ID FAQ](http://www.brainsap-development.com/plugins-resources/wordpress-plugin-google-news-unique-permalink-id/#faq "Google News Unique Permalink ID FAQ") for further information.

== Changelog ==

= 1.0.4 =
* Fixed a bug which did not properly change the slug on editing scheduled posts.

= 1.0.3 =
* Unique IDs will now be appended to scheduled/planned posts.

= 1.0.2 =
* Prevent function from being created when it already exists, Changed Plugin URLs and Readme.

= 1.0.1 =
* Fixed a bug which added Unique IDs to Pages. Unique IDs should be only added to Posts now properly.

= 1.0 =
* Plugin Created

== Upgrade Notice ==

= 1.0.4 =
* Minor Bugfix

= 1.0.3 =
* Improvement of Functionality

= 1.0.2 =
* Minor Bugfix

= 1.0.1 =
* Important Bugfix

= 1.0 =
* Plugin Created