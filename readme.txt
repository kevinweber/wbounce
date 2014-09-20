=== wBounce ===
Contributors: kevinweber
Donate link: http://kevinw.de/donate/wBounce/
License: MIT
Tags: admin, newsletter, exit popup, exit popups, ab-testing, roi, conversion, conversion rate optimisation, free, plugin, wordpress, marketing, landing page
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 1.2.1

wBounce improves bounce rate to boost conversions and sales. The free alternative to Bounce Exchange for WordPress.

== Description ==
wBounce is the free exit popup software for WordPress, evolved by digital marketer [Kevin Weber](http://kevinw.de). This plugin bases on [Ouibounce]( http://carlsednaoui.github.io/ouibounce/) by Carl Sednaoui.

Exit popups are not only "in vogue", they are provably increasing conversions and therefore boost marketing, signups and sales. wBounce displays an inline popup before the user leaves your site. ("Inline popup" means that this is NOT one of those out-dated, annoying popups windows.) Inline popups catch the visitor's attention even if they are in the process of leaving your site.

wBounce is the free alternative to charged services like Bounce Exchange or OptinMonster that are often used for landing page optimisation. wBounce is lightweight and renounces unnecessary scripts. You decide which features will be developed and implemented next.

One concern in everyone's interest: Make sure to provide VALUE when you use wBounce and don't spam your visitors.

This plugin makes it extremely easy to implement exit popups. You don't have to manually "hack" your WordPress theme. Just activate and modify it via your admin backend.

Demo and more information on the developer's website: [kevinw.de/wbounce](http://kevinw.de/wbounce)

= Features: =
* Set custom content via backend
* Determine sensitivity, cookie expiration, hesitation, ... 
* Add custom CSS
* Set default status: Define if wBounce should be fired on posts and/or pages by default. You can override the default setting on every post and page individually.

= Future features: =
* Determine cookie domain, delay, and more!
* Display popup on enter
* Templates
* Styling options (display "x" icon to close the popup, set background transparency, ...)
* Define custom content for pages and posts individually
* Intelligent timer (e.g., display popup when the user is inactive for a certain time period)
* Event tracking to measure wBounce with Google Analytics (a tutorial is going to be created, too)
* A/B testing with Google Analytics
* Bulk edit
* ... feel free to contact me and suggest new features


== Installation ==

1. Upload wBounce into you plugin directory (/wp-content/plugins/) and activate the plugin through the 'Plugins' menu in WordPress.
2. Configure the plugin via the admin backend.
3. Optionally: Sign up to the wBounce newsletter to get notified about major updates.


== Frequently Asked Questions ==

= Does wBounce work with MailChimp, aWeber, GetResponse? =
Yes! You can use any form from every newsletter service since you can insert HTML code into the "wBounce content" text field. Simply copy the form code that's provided by MailChimp (or any other newsletter service) into the "wBounce content" text field.

Additionally, you can add CSS using the "Custom CSS" text field.


== Changelog ==

= 1.2.1 =
* Fixed broken post view

= 1.2 =
* Improvement: Added support for shortcodes that are inserted into the "wBounce content" text area.
* New feature: Hesitation. wBounce waits x milliseconds before showing the model when the user's cursor leaves the window.
* Improvement: Only load scripts and CSS when they are actually needed.
* Improvement: Merged CSS from two files into one.
* Fixed "unexpected T_PAAMAYIM_NEKUDOTAYIM".

= 1.1.1 =
* New feature: Deactivate wBounce for pages and posts individually ("wBounce status").
* New feature: Define if wBounce should be fired on posts and/or pages by default.
* New feature: The wBounce status can be seen in an additional column on the overview for posts and pages.
* New feature: Sensitivity.
* New feature: Cookie expiration. wBounce sets a cookie by default to prevent the modal from appearing more than once per user. You can add a cookie expiration (in days) to adjust the time period before the modal will appear again for a user.
* wBounce is ready for WordPress 4.0.

= 1.0 =
* Plugin goes public.
* First available features: Admin panel to customise settings. Insert content/code + example template. Test mode. Aggressive mode. Timer. Custom CSS.


== Upgrade Notice ==

= 1.0 =
* Plugin goes public.


== Screenshots ==

1. Screenshot of a site that uses wBounce.
2. Admin panel tab "content".
3. Admin panel tab "options".
4. Meta box besides post editor (v1.1).
5. Post column displays status (v1.1).