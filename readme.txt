=== WP-SpamShield Anti-Spam ===
Contributors: RedSand
Donate link: http://www.redsandmarketing.com/wp-spamshield-donate/
Tags: akismet, all-in-one, antispam, anti-spam, block spam, captcha, comment, comment spam, comments, contact, contact form, contact forms, form, forms, javascript, login, multisite, protection, register, registration, registration spam, security, signup, signup spam, spam, spam blocker, spam filter, trackback, trackbacks, user registration spam, widget
Requires at least: 3.8
Tested up to: 4.1
Stable tag: trunk
License: GPLv2

An extremely powerful WordPress anti-spam plugin that eliminates comment spam, trackback spam, contact form spam & registration spam.

== Description ==

An extremely powerful and user-friendly WordPress anti-spam plugin that eliminates comment spam, trackback spam, contact form spam & registration spam.

= An All-in-one Spam Solution for WordPress - Without CAPTCHAS =
No CAPTCHA's, challenge questions or other inconvenience to site visitors - it works silently in the background and simply makes WordPress spam disappear.

= Documentation / Tech Support =
* Documentation: [Plugin Homepage](http://www.redsandmarketing.com/plugins/wp-spamshield/)
* Tech Support: [WP-SpamShield Support](http://www.redsandmarketing.com/plugins/wp-spamshield/support/)

= How It Works =
Most of the spam hitting your blog originates from bots, but quite a bit comes from humans too. This plugin works like a firewall to ensure that your commenters are in fact, human, and that those humans aren't spamming you.

= Two Layers of Spam Blocking =
There are two layers of anti-spam protection that work together to *block both automated and human spam*: a **JavaScript/Cookies Layer**, and an **Algorithmic Layer**. The first layer uses a dynamic combo of JavaScript and cookies to weed out the humans from spambots, preventing 100% of automated spam from ever getting to your site. Even if bot authors could engineer a way to break through the JavaScript/Cookies Layer, the Algorithmic Layer would still stop almost all of the spam that the JavaScript Layer blocks, and provides close to a fully redundant backstop. This JavaScript Layer utilizes multiple randomly generated keys, and is algorithmically enhanced to ensure that spambots won't beat it. The powerful Algorithmic Layer consists of over 100 advanced filters, and eliminates *trackback spam* and *most human spam as well*. And, it does all that without hindering legitimate comments and trackbacks.

= No More Wasted Time Sifting Through the Spam Queue =
This type of solution works silently in the background, with no inconveniences. You won't have to waste valuable time sifting through a spam queue any more, because there won't be anything there.

WP-SpamShield is different from other anti-spam plugins in that it *BLOCKS* spam at the front door and doesn't allow it into the WordPress database¹ at all. Many other anti-spam plugins simply label a comment as spam, leaving you to sort through a queue, which wastes your valuable time. **WP-SpamShield will give you back your time!**

¹*Not allowing these into the database improves security by potentially preventing SQL injection exploit attacks through automated spam comment submissions. It also keeps your WordPress database slimmer and more efficient (keeping your site running faster) by not allowing the thousands upon thousands of spam comments into it, which could bloat the database and potentially corrupt it.*

= ZERO False Positives =
It does all this with ZERO false positives, because of the method used to block spam. This leads to fewer frustrated website visitors, and less work for you. If a comment/contact form/registration gets blocked as spam, the user is given instant feedback and has a chance to correct their comment/contact form/registration and try again. We are committed to keeping the promise of zero false positives.

= 100% Trackback Validation and Spam Blocking =
The trackback validation contains a filter that compares the client IP address of the incoming trackback against the IP address of the server where the link is supposedly coming from. If they don't match, then it is guaranteed spam, without fail. This alone eliminates more than 99.99% of trackback spam. Trackback spammers don't send spam out from the same server where their clients' websites reside. There are algorithmic filters in place to ensure 100% trackback spam blocking. Although it's far more rare, the plugin protects again pingback spam as well. You can be confident that only legitimate trackbacks and pingbacks will get through.

= Includes a Spam-Free Contact Form =
Includes drop-in spam-free contact form, with easy one-click installation. Easy to use - no configuration necessary, but you can configure it if you like. (See [Installation](https://wordpress.org/plugins/wp-spamshield/installation/) for info.)

= WordPress Registration Spam Blocking =
The plugin also includes powerful protection from user registration spam on your site's WordPress registration page. Once you install WP-SpamShield, you don't have to worry about bots or spammy users signing up any more. (Note: This protects the *WordPress registration form* only, not the registration forms of other plugins. See [this FAQ](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_faqs_13) for more info.

= Why Not Just Use a CAPTCHA? =
The concept of using a CAPTCHA as an anti-spam solution in this day and age is flawed for several reasons: 

1. It's an outdated concept that has far outlived its usefulness, and was originally developed before user-friendliness was a high priority.
2. It goes in the exact opposite direction of user-friendly design principles. Think about it. Users of your website have to type in numbers and letters obscured by squiggly lines and symbols, only to be told they are wrong several times, even after typing in the correct answer. This is proven to hurt website business and revenue because of the negative feelings it causes. People simply don't like CAPTCHAS.
3. CAPTCHAS can be defeated.
4. Why use a CAPTCHA when there are better solutions that don't inconvenience your website users?

= Optimized and Scalable =
This plugin has an extremely low overhead and won't slow down your site, unlike some other anti-spam plugins. Each of the filters in the plugin have been benchmarked, and when processing comments for spam, the fastest filters are put at the front of the stack. Once a comment tests positive for spam, the testing process terminates and will not engage the remaining filters. Additionally, as mentioned above, by keeping spam out of the WordPress database altogether, WP-SpamShield helps keep your database slimmer and more efficient, which in turn helps keep your site running faster. This efficiency helps keep the server load down, and helps improve the overall performance of your site.

= Free for Commercial and Personal Sites =
No cost, no hidden fees. This plugin is **free** for **both Commercial and Personal** use. If you find that this plugin benefits you, and you're so inclined, then feel free to [make a donation](http://www.redsandmarketing.com/wp-spamshield-donate/).

= Responsive and Helpful Tech Support =
If you have any issues with the plugin, we are here to help. Simply submit a support request at the [WP-SpamShield Support Page](http://www.redsandmarketing.com/plugins/wp-spamshield/support/), and we'll help you diagnose and fix the issue quickly.

= Additional Features =
1. A counter on your dashboard to keep track of all the spam it's blocking. The numbers will show how effective this plugin is.
2. See what's been blocked! "Blocked Comment Logging Mode", a temporary diagnostic mode that logs blocked spam (comments, trackbacks, registrations, and contact form submissions) for 7 days, then turns off automatically. If you want to see what spam has been blocked, or verify that everything is working, turn this on and see what WP-SpamShield is protecting your blog from.
3. Multiple languages available and more on the way. Currently includes Dutch (nl_NL), French (fr_FR), German (de_DE), and Serbian (sr_RS) translations. Ready for translation into other languages.
4. Easy to install - truly plug and play. Just upload and activate. (Installation Status on the plugin admin page to let you know if plugin is installed correctly.)
5. Compatible with popular cache plugins, including WP Super Cache and others. Not all anti-spam plugins can say that.
6. Display your blocked spam stats on your blog. Customizable widgets for graphic counters to display spam stats, in multiple colors, sizes and options.
7. Works in WordPress Multisite as well. (See the related [FAQ](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_faqs_3) for details.)
8. Enhanced Comment Blacklist option. Instead of just sending comments to moderation as with WordPress's default Comment Blacklist functionality, with this turned on, anything that matches a string in the blacklist will be **completely blocked**. The Enhanced Comment Blacklist has some improvements over the default WordPress blacklist functionality, and adds a link in the comment & contact form notification emails that will let you blacklist a spammer's IP with one click.
9. WP-SpamShield Whitelist option. Allows you to specify certain users who you want to let bypass the spam filters.
10. This plugin is legal to use in Germany and the EU, and does not violate European privacy laws. It does not use any type of cloud-based service, spam data is not transmitted from your server to any other server, and all anti-spam processing happens directly on your website's server.
11. A truly plug and play replacement and upgrade for WP-SpamFree. (A far more advanced fork of WP-SpamFree with dramatically improved page load speed, security, and spam blocking power, by its original author.) It will import your old data from WP-SpamFree automatically upon installation and activation, and features you were using on your site previously such as contact forms and spam stats will continue to work without any changes to pages, posts, or theme.

= Languages Available =

* Dutch (nl_NL)
* French (fr_FR)
* German (de_DE)
* Serbian (sr_RS)

= Requirements =

* WordPress 3.8 or higher (Recommended: WordPress 4.0 or higher)
* PHP 5.3 or higher (Recommended: PHP 5.4 or higher)

> #### **WordPress Blogging Without Spam**
> How does it feel to blog without being bombarded by comment spam? If you're happy with the WP-SpamShield WordPress anti-spam plugin, please let others know by giving it a good rating!

== Installation ==

= Installation Instructions =

**Option 1:** Install the plugin directly through the WordPress Admin Dashboard (Recommended) 

1. Go to *Plugins* -> *Add New*. 

2. Type *WP-SpamShield* into the Search box, and click *Search Plugins*. 

3. When the results are displayed, click *Install Now*. 

4. When it says the plugin has successfully installed, click **Activate Plugin** to activate the plugin (or you can do this on the Plugins page). 

**Option 2:** Install .zip file through WordPress Admin Dashboard

1. Go to *Plugins* -> *Add New* -> *Upload*.

2. Click *Choose File* and find `wp-spamshield.zip` on your computer's hard drive.

3. Click *Install Now*.

4. Click **Activate Plugin** to activate the plugin (or you can do this on the Plugins page).

**Option 3:** Install .zip file through an FTP Client (Recommended for Advanced Users Only)

1. After downloading, unzip file and use an FTP client to upload the enclosed `wp-spamshield` directory to your WordPress plugins directory (usually `/wp-content/plugins/`) on your web server.

2. Go to your Plugins page in the WordPress Admin Dashboard, and find this plugin in the list.

3. Click **Activate** to activate the plugin.

= Next Steps After Installation = 

1. Check to make sure the plugin is installed properly. Many support requests for this plugin originate from improper installation and can be easily prevented. To check proper installation status, go to the WP-SpamShield page in your Admin. It's a submenu link under the *Settings*. Go the the 'Installation Status' area near the top and it will tell you if the plugin is installed correctly. If it tells you that the plugin is not installed correctly, please double-check what directory you have installed WP-SpamShield in, delete any WP-SpamShield files you have uploaded to your server, re-read the Installation Instructions, and start the Installation process over. If it is installed correctly, then move on to the next step.

2. Select desired configuration options.

3. If you are using front-end anti-spam plugins (CAPTCHA's, challenge questions, etc), be sure they are *disabled* since there's no longer a need for them, and these could likely conflict. (Back-end anti-spam plugins like Akismet are fine, although unnecessary.)

4. Install a contact form if you like. (See below)

**You're done! Sit back and see what it feels like to live without comment spam, trackback spam, and registration spam!**

**NOTE: If you're using a caching plugin, you will need to clear the plugin's cache after you install WP-SpamShield.**

= Displaying Stats on Your Blog =
Want to show off your spam stats on your blog and tell others about WP-SpamShield? It's easy, just add a widget and drag and drop it where you like, in several color and size options. You have a choice of the regular size counters in 5 colors, the small counter in 5 colors, or the End Blog Spam graphic. ( `</BLOGSPAM>` )

As of version 1.8.4, there is also a new customizable widget that has a number of color and style options, including a custom color chooser.

Now you can show spam stats on your blog without knowing any code.

= Adding a Contact Form to Your Blog =
First create a distinct page (not post) where you want to have your contact form. Then, go into the editor and click the tab for the "Text" editor (not "Visual" editor). Then click the button that says "WPSS Contact Form". It's that easy. You can also manually insert the following shortcode if you prefer: `[spamshieldcontact]`

The page you place the contact form on should have its own URL, and not be used on the homepage of your site. It also cannot be implemented as part of a widget or theme element, such as a footer, sidebar, etc.

There is no need to configure the form. It allows you to simply drop it into the page you want to install it on. However, there are a few basic configuration options. You can choose whether or not to include Phone and Website fields, whether they should be required, add a drop down menu with up to 10 options, set the width and height of the Message box, set the minimum message length, set the form recipient, enter a custom message to be displayed upon successful contact form submission, and choose whether or not to include user technical data in the email.

Please visit the plugin documentation for more info on [contact form installation and use](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_adding_contact_form).

= Configuration Information =

Please visit the plugin documentation for detailed [configuration information](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_configuration).

== Frequently Asked Questions ==

Please see the full [FAQ's](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_faqs).

Also, see the [troubleshooting guide](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_troubleshooting).

If you have any further questions, please submit them on the main [WP-SpamShield Support Page](http://www.redsandmarketing.com/plugins/wp-spamshield/support/).

== Changelog ==

= 1.8.8 =
*released 04/13/15*

* Fixed a bug in one of the comment spam filters. Previously, if WordPress Discussion settings have "Comment author must fill out name and e-mail" unchecked, and a user submitted a comment where the author name and author URL were both blank, it would incorrectly be blocked. (The comments would go through if there was a URL.) This is fixed in this version.
* Updated the spam filters.

= 1.8.7 =
*released 04/08/15*

* Updated the spam filters.

= 1.8.6 =
*released 04/04/15*

* Fixed a minor bug in the new custom widget.
* Updated the spam filters.

= 1.8.5 =
*released 04/02/15*

* Updated the spam filters.

= 1.8.4 =
*released 03/30/15*

* Added new widgets. Converted the spam stat counter graphics to widgets, so they are much easier to add to your site now. No more messing around with code. There are a number of new widget options to check out. 
* Added a new customizable widget that has a number of color and style options, including a custom color chooser.
* Fixed a few potential issues with UTF-8 and multibyte support.
* Made a small fix to the contact form thank you message that will help multi-language users.
* Made the comment spam blocking process a little more efficient.
* Added info to the settings page with info on how much time the plugin has saved you.
* Made various code improvements.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.8.3 =
*released 03/24/15*

* Added a contact form quicktag so users can just click a button in the editor to add a contact form to pages. No more manually inserting shortcodes.
* Made various code improvements.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.8.2 =
*released 03/16/15*

* Increased minimum required WordPress version to 3.8 and added a minimum required PHP version of 5.3, as we are no longer supporting PHP 5.2. The PHP team [stopped supporting PHP 5.2 back in 2011](http://php.net/archive/2011.php#id2011-08-23-1), and even PHP 5.3 reached its end of life in August 2014. WordPress has a current minimum requirement of 5.2.4, but PHP 5.4 is recommended - see the [WordPress requirements](https://wordpress.org/about/requirements/). It's extremely important that users stay up to date with the most recent version of WordPress (currently 4.1.1) and a reasonably up-to-date version of PHP for security, functionality, and website performance. (Not only are the newer versions more secure, but they are faster, so its a double win.) We recommend PHP 5.4 or higher. Also, see [PHP Unsupported Branches](http://php.net/eol.php) for more info.
* Fixed XHTML validation error in the hidden input fields on the forms.
* Internationalized the formatting of numbers used throughout the plugin so users in different countries will see numbers formatted according to their local customs. This will show in the blocked spam stats on the dashboard, spam counter widgets, and in the blocked comments log.
* Updated the spam filters.

= 1.8.1 =
*released 03/11/15*

* Updated the French (fr_FR) translation.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.8 =
*released 03/09/15*

* Made some improvements to the spam blocking algorithm.
* Made a number of various code and performance improvements.
* Improved some of the error messages.
* Fixed a couple bugs introduced in 1.7.9, including a bug in the contact form that incorrectly detected spam servers, and a compatibility issue with the new registration anti-spam feature and certain multisite configurations. This feature was rolled back while we look into the compatibility issues.
* Made a few improvements to the blocked comment logging functionality.
* Updated the spam filters.

= 1.7.9 =
*released 03/06/15*

* Improved the registration spam protection capabilities.
* Made a few improvements to the blocked comment logging functionality.
* Removed some unnecessary technical info from notification emails that had previously been added by the plugin.
* Made various code improvements.
* Added new filters to the spam blocking algorithm.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.7.8 =
*released 02/27/15*

* Updated the Dutch (nl_NL) and  German (de_DE) translations.
* Made various code improvements.
* Added new filters to the spam blocking algorithm.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.7.7 =
*released 02/21/15*

* Added an uninstall function that completely uninstalls the plugin and removes all options, data, and traces of its existence when it is deleted through the dashboard.
* Added a fix to prevent certain rare situations from triggering duplicate emails to be sent by the contact form.
* Added a fix to ensure contact form is implemented properly, and strictly enforce not being used in widgets or sidebars. Please see the [contact form documentation](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_adding_contact_form) for more info and usage instructions.
* Made various code improvements.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.7.6 =
*released 02/15/15*

* Updated the spam filters.

= 1.7.5 =
*released 02/11/15*

* Removed some deprecated filters from the spam blocking algorithm.
* Updated the spam filters.

= 1.7.4 =
*released 02/06/15*

* Added WP-Spamshield Whitelist, a feature that allows you to whitelist specific email addresses that you would like to let bypass spam filters in the comments and contact forms.
* Added option to allow keywords in comment author "Name" fields.  This option is useful for sites with users that go by pseudonyms, or for sites that simply want to allow business names and keywords to be used in the comment "Name" field.
* Made some improvements to the UI of the settings page.
* Improved some of the error messages to make them more helpful.
* Made various code improvements.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.7.3 =
*released 02/03/15*

* Improved the Yahoo fix for the contact forms (implemented in 1.4.3) and restored "Reply-To" functionality. In version 1.4.3, I had to modify how the plugin handles the email address of the contact form submitter, in order to fix an issue with contact form submissions for users with `@yahoo.com` email addresses not getting sent. (See info on 1.4.3 update.) The contact form emails will still come from an email address that looks like `wpspamshield [dot] noreply [at] yourdomain [dot] com`. The difference now is that the "Reply-To" field is set to the contact form submitter's email address, so you can just click "Reply" in your email app, like you could before version 1.4.3. This should still avoid any spam problems with properly configured SPF records on your domain (and Yahoo's DMARC policy), while allowing contact form submissions from `@yahoo.com` email addresses. 
* Updated the French (fr_FR) translation.
* Made various code improvements.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.7.2 =
*released 01/30/15*

* Added compatibility for 2 additional caching plugins: Cachify and Gator Cache. As of this release, the following 12 cache plugins are supported (in order of popularity): WP Super Cache, W3 Total Cache, Quick Cache, Hyper Cache, WP Fastest Cache, DB Cache Reloaded Fix, Cachify, DB Cache Reloaded, Hyper Cache Extended, WP Fast Cache, Lite Cache, and Gator Cache.
* Updated the Dutch (nl_NL) and  German (de_DE) translations.
* Made various minor code improvements.
* Updated the spam filters.

= 1.7.1 =
*released 01/27/15*

* Made various code improvements.
* Updated the translation files.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.7 =
*released 01/22/15*

* Updated the spam filters.

= 1.6.9 =
*released 01/19/15*

* Added option to disable registration anti-spam protection.
* Made various code improvements.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.6.8 =
*released 01/16/15*

* Updated the spam filters.

= 1.6.7 =
*released 01/13/15*

* Minor bug fix and improvement to the JetPack compatibility fix.
* Updated the spam filters.

= 1.6.6 =
*released 01/09/15*

* Updated the spam filters.

= 1.6.5 =
*released 01/04/15*

* Upgraded the code for the spam counter widget. The previous widget code was written prior to WordPress 2.8 and needed to be upgraded. (In WordPress 2.8 a better, more efficient widget API was added. After that, the old code still worked with minor modifications.) In WordPress version 4.1 a couple issues started appearing. (At least that's when they were first reported.) When using the Customizer feature in the Dashboard, PHP errors related to undefined indexes would appear in logs. After upgrading to this version, any previously placed WP-SpamShield widgets will disappear from your site and will need to be re-inserted. One benefit of the upgraded code is that now multiple instances of the widget can be inserted.
* Made various minor code improvements.
* Increased minimum required WordPress version to 3.7. It's extremely important that users stay up to date with the most recent version of WordPress (currently 4.1) for security and functionality.
* Minor update to the translation files.
* Added new filters to the spam blocking algorithm.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.6.4 =
*released 12/30/14*

* Updated the spam filters.

= 1.6.3 =
*released 12/21/14*

* Updated the spam filters.

= 1.6.2 =
*released 12/18/14*

* Improved some of the filters in the spam blocking algorithm.
* Increased minimum required WordPress version to 3.6. It's extremely important that users stay up to date with the most recent version of WordPress (currently 4.1) for security and functionality.
* Updated the spam filters.

= 1.6.1 =
*released 12/15/14*

* Updated the spam filters.

= 1.6 =
*released 12/11/14*

* Added a compatibility fix to prevent certain conflicting plugins from triggering duplicate emails to be sent by the contact form.
* Updated the spam filters.

= 1.5.9 =
*released 11/23/14*

* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.5.8 =
*released 11/13/14*

* Updated the spam filters.

= 1.5.7 =
*released 11/05/14*

* Updated the spam filters.

= 1.5.6 =
*released 10/23/14*

* Fixed a bug that caused some legitimate comments to be rejected on comment sub-pages if the site was using the option to break comments into pages but not using permalinks.
* Minor update to the Dutch Translation (nl_NL).
* Updated the spam filters.

= 1.5.5 =
*released 09/24/14*

* Made various minor code improvements.
* Made some minor tweaks to the translation files and corresponding code.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.5.4 =
*released 09/15/14*

* Added a new improved blacklist function for the Enhanced Comment Blacklist feature, so it no longer uses the built-in WordPress blacklist function - wp_blacklist_check() (which is very old and has some flaws). Enhanced Comment Blacklist feature now works on WP-SpamShield contact forms as well. Please see [documentation](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_configuration_enhanced_comment_blacklist) for more information.
* Added "Blacklist the IP Address" link to contact form emails.
* Added a fix to prevent network activation when used in multisite, and added network admin notice to explain. The plugin can be used in multisite just fine, but will need to be activated individually per site for now. Once we can get time to adapt the plugin to multisite more specifically, we can remove this restriction.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.5.3 =
*released 09/09/14*

* Added Dutch Translation (nl_NL). Thank you to Martin Teley for doing the Dutch translation.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.5.2 =
*released 09/05/14*

* Improved some of the filters in the spam blocking algorithm.
* Increased minimum required WordPress version to 3.5 (which is already almost 2 years old). It's extremely important that users stay up to date with the most recent version of WordPress (currently 4.0) for security and functionality.
* Updated the spam filters.

= 1.5.1 =
*released 09/01/14*

* Fixed a bug that caused some legitimate comments to be rejected on comment sub-pages if the site was using both the option to use permalinks and the option to break comments into pages.

= 1.5 =
*released 08/31/14*

* Added several efficient new trackback spam filters to further improve speed in processing trackbacks and blocking spam, which means even lower server load and improved overall scalability.
* Updated the text on the spam counter in the dashboard and settings page. Previously it said "spam comments", and now it just says "spam", since the plugin has evolved over time to block multiple types of spam. The counter includes all blocked spam types, not just comments: comment spam, trackback/pingback spam, contact form spam, and user registration spam.
* Improved human spam protection.
* Removed the M2 feature as it's an old feature that has been deprecated and is no longer needed.
* Made several improvements to the filters in the spam blocking algorithm.
* Updated the spam filters.

= Changelog =
For a complete list of changes to the plugin, view the [Version History/Changelog](http://www.redsandmarketing.com/plugins/wp-spamshield/version-history/).

== Upgrade Notice ==
= 1.8.8 =
Fixed a bug in one of the comment spam filters, and updated the filters in the spam blocking algorithm. Please see Changelog for details.

== Other Notes ==

[Troubleshooting Guide](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_troubleshooting) | [WP-SpamShield Support Page](http://www.redsandmarketing.com/plugins/wp-spamshield/support/)

= Troubleshooting Guide / Support =

If you're having trouble getting things to work after installing the plugin, please see the [troubleshooting guide](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_troubleshooting) as most issues can be fixed by checking this.

= Updates / Documentation =
For updates and documentation, visit the [WP-SpamShield homepage](http://www.redsandmarketing.com/plugins/wp-spamshield/).

= WordPress Security Note =
As with any WordPress plugin, for security reasons, you should only download plugins from the author's site and from official WordPress repositories. When other sites host a plugin that is developed by someone else, they may inject code into that could compromise the security of your blog. We cannot endorse a version of this that you may have downloaded from another site. If you have downloaded the "WP-SpamShield" plugin from another site, please download the current release from the from the [official WP-SpamShield page on WordPress.org](https://wordpress.org/plugins/wp-spamshield/).
