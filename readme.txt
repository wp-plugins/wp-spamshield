=== WP-SpamShield Anti-Spam ===
Contributors: RedSand
Donate link: http://www.redsandmarketing.com/wp-spamshield-donate/
Tags: antispam, anti-spam, block spam, comment, comment spam, comments, contact, contact form, form, forms, javascript, login, multisite, register, registration, security, signup, spam, spam filter, user registration spam, trackback
Requires at least: 3.7
Tested up to: 4.1
Stable tag: trunk
License: GPLv2

A powerful, user-friendly, all-in-one anti-spam plugin that eliminates comment spam & registration spam. Includes spam-blocking contact form.

== Description ==

**An extremely powerful and user friendly WordPress anti-spam plugin that stops blog spam cold, including comment spam, trackback and pingback spam, contact form spam, and registration spam.** See what it's like to run a WordPress site without spam! Includes spam-blocking contact form feature. **WP-SpamShield is an all-in-one spam solution for WordPress.**

= A Powerful Weapon Against: Comment Spam, Trackback Spam, Contact Form Spam, and Registration Spam - Without CAPTCHAS =
Comment spam has been a huge problem for bloggers since the inception of blogs, and it just doesn't seem to go away. The worst kind, and most prolific, is automated spam that comes from bots. Well, finally there is an anti-spam plugin for WordPress that provides an effective solution, without CAPTCHA's, challenge questions, or other inconvenience to site visitors. **WP-SpamShield eliminates comment spam, trackback spam, contact form spam, and user registration spam.**

= Documentation / Tech Support =
* Documentation: [Plugin Homepage](http://www.redsandmarketing.com/plugins/wp-spamshield/)
* Tech Support: [WP-SpamShield Support](http://www.redsandmarketing.com/plugins/wp-spamshield/support/)

= New Features =
* Internationalization and localization available. Currently includes Dutch (nl_NL), French (fr_FR), German (de_DE), and Serbian (sr_RS) translations. Ready for translation into other languages. Added in Version 1.3.
* Stops User Registration Spam now! Added in Version 1.2.
* Shortcodes for easy contact form implementation.
* Over 10x faster! Tested and verified with benchmarking software.
* For more, please view the changelog.

= Key Features =
1. Virtually eliminates automated comment spam from bots. It works like a firewall to ensure that your commenters are in fact, human.
2. A counter on your dashboard to keep track of all the spam it's blocking. The numbers will show how effective this plugin is.
3. **No CAPTCHA's, challenge questions or other inconvenience to site visitors** - it works silently in the background.
4. Includes drop-in spam-free contact form, with easy shortcode implementation. Easy to use - no configuration necessary. (But you can configure if you like.)
5. Protects your site from user registration spam. No more automated bot signups through the login page on your site.
6. See what's been blocked! "Blocked Comment Logging Mode", a temporary diagnostic mode that logs blocked comments and contact form submissions for 7 days, then turns off automatically. If you want to see what's been blocked, or verify that everything is working, turn this on and see what WP-SpamShield is protecting your blog from.
7. No false positives due to the method of spam blocking, which leads to fewer frustrated readers, and less work for you. (If a comment gets blocked, a legit user has a chance to try again.)
8. You won't have to waste valuable time sifting through a spam queue any more, because there won't be much there, if anything.
9. Powerful trackback and pingback spam protection and validation to ensure that only legitimate ones get through.
10. Easy to install - truly plug and play. Just upload and activate. (Installation Status on the plugin admin page to let you know if plugin is installed correctly.)
11. The beauty of this plugin is the methods of blocking spam. It takes a different approach than most and stops spam at the door.
12. Extremely low overhead and won't slow down your blog (very light database access), unlike some other anti-spam plugins.
13. Compatible with popular cache plugins, including WP Super Cache and others. Not all anti-spam plugins can say that.
14. Display your blocked spam stats on your blog. Widgets and shortcodes for graphic counters to display spam stats, multiple sizes and options.
15. By stopping spam at the front door and keeping the spam out of the WordPress database altogether, WP-SpamShield helps keep your database slimmer and more efficient, which helps your site run faster.
16. Works in WordPress Multisite as well. (See the related [FAQ](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_faqs_3) for details.)
17. Enhanced Comment Blacklist option. Instead of just sending comments to moderation as with WordPress's default Comment Blacklist functionality, with this turned on, anything that matches a string in the blacklist will be **completely blocked**. Also adds a link in the comment notification emails that will let you blacklist a commenter's IP with one click.
18. No cost, no hidden fees. **Free** for **both Commercial and Personal** use.
19. This plugin is legal to use in Germany and the EU, and does not violate European privacy laws. It does not use any type of cloud-based service, data is not transmitted from your server to any other server, and all anti-spam processing happens directly on your website's server.
20. A truly plug and play replacement and upgrade for WP-SpamFree. (A far more advanced fork of WP-SpamFree with dramatically improved page load speed, security, and spam blocking power, by its original author.) It will import your old data from WP-SpamFree automatically upon installation and activation, and features you were using on your site previously such as contact forms and spam stats will continue to work without any changes to pages, posts, or theme.

= How It Works =
Most of the spam hitting your blog originates from bots. Few bots can process JavaScript (JS). Few bots can process cookies. Fewer still, can handle both, especially if you use some clever combinations. In a nutshell, this plugin uses a dynamic combo of JavaScript and cookies to weed out the humans from spambots, preventing 99.99%+ of automated spam from ever getting to your site. Almost 100% of web site visitors will have these turned on by default, so this type of solution works silently in the background, with no inconveniences. There may be a few users (less than 2%) that have JavaScript and/or cookies turned off by default, but they will be prompted to simply turn those back on to post their comment. Overall, the few might be inconvenienced because they have JS and cookies turned off will be far fewer than the 100% who would be annoyed by CAPTCHA's, challenge questions, and other validation methods.

Some would argue that using JS and cookies is too simplistic an approach. Some developers prefer using some type of cloud-based AI to fight bots by trying to figure out if a comment is spam. While that isn't a bad idea, when used alone this method falls short - many spam comments get through that could easily have been stopped, and there are many false positives where non-spam comments get flagged as spam. Others may argue that some spammers have programmed their bots to read JavaScript, etc. In reality, the percentage of bots with these capabilities is still extremely low - less than 1%, and even those that can read, can't fully process it. It's simply a numbers game. Statistics tell us that an effective solution would involve using a technology that few bots can handle, therefore eliminating their ability to spam your site. The important thing in fighting spam is that we create a solution that can reduce spam noticeably and improve the user experience, and a 99.99%+ reduction in spam would definitely make a difference for most bloggers and site visitors.

It's important to know that the particular JS and cookies solution used in the WP-SpamShield anti-spam plugin has evolved quite a bit, and is no longer simple at all. There are two layers of protection, a JavaScript/Cookies Layer, and an Algorithmic Layer. Even if bot authors could engineer a way to break through the JavaScript/Cookies Layer, the Algorithmic Layer would still stop 95% of the spam that the JavaScript Layer blocks. (I'm working to make this 100% for fully redundant protection.) This JavaScript Layer utilizes randomly generated keys, and is algorithmically enhanced to ensure that spambots won't beat it. The powerful Algorithmic Layer is what eliminates trackback/pingback spam, and much human spam as well. And, it does all that without hindering legitimate comments and trackbacks.

The trackback validation contains a filter that compares the client IP address of the incoming trackback against the IP address of the server where the link is supposedly coming from. If they don't match, then it is spam, without fail. This alone eliminates more than 99.99% of trackback spam. Trackback spammers don't send spam out from the same server where their clients' websites reside.

> As of Version 1.2 the plugin also includes powerful protection from user registration spam. Once you install WP-SpamShield, you don't have to worry about bots or spammy users signing up on your site's login page any more.

The bottom line, is that this plugin just plain works, and is a **powerful weapon against spam**.

= Background =
Before I developed this plugin, our team and clients experienced the same frustration you do with comment spam on your WordPress blog. Every blog we manage had comment moderation enabled and various other anti-spam plugins installed, but we still had a ton of comments tagged as spam in the spam queue that we had to sort through. This wasted a lot of valuable time, and we all know, time is money. We needed a solution.

Comment spam stems from an older problem - automated spamming of email contact forms on web sites. I developed a successful fix for this years ago, and later applied it to our WordPress blogs. It was so effective, that I decided to add a few modifications and turn it into a WordPress plugin to be freely distributed. Blogs we manage used to get an excessive number of spam comments show up on the spam queue each day - now the daily average is zero spam comments.

To further the development of this anti-spam plugin, I now study thousands and thousands of potential spam comments from many test blogs and contributors. I use a special diagnostic version of the plugin, which provides much more information on each of these spam comments than what is shown in WordPress. By analyzing patterns and behaviors consistent with spam, I can continually improve the plugin and ensure future accuracy.

> #### **WordPress Blogging Without Spam**
> How does it feel to blog without being bombarded by comment spam? If you're happy with the WP-SpamShield WordPress anti-spam plugin, please let others know by giving it a good rating!

= Languages Available =

* Dutch (nl_NL)
* French (fr_FR)
* German (de_DE)
* Serbian (sr_RS)

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

= For Best Results =
WP-SpamShield was created specifically to stop automated comment spam (which accounts for over 99.9% of comment spam), and we have built in many features that combat human comment spam and completely eliminate trackback/pingback spam. Unfortunately, no plugin can perfectly detect human comment spam. As other experts will tell you, the most effective strategy for blocking spam involves applying a variety of techniques. For best results, *enable comment moderation* in your WordPress Settings. (If you desire a backup, feel free to use Akismet, as the two plugins are compatible, even though it's probably not necessary. I would recommend not using any other spam plugins at the same time, in order to keep keep your web server load down and prevent conflicts.)

= Displaying Stats on Your Blog =
Want to show off your spam stats on your blog and tell others about WP-SpamShield? Simply add the following code to your WordPress theme where you'd like the stats displayed: `<?php if ( function_exists(spamshield_counter) ) { spamshield_counter(1); } ?>` where '1' is the style. Replace the '1' with a number from 1-9 corresponding to one of the background styles you'd like to use. (See plugin homepage for more info.)

To add it to any page or post, add the following shortcode to the page or post where you'd like the stats displayed (using the HTML editing tab, NOT the Visual editor): `[spamshieldcounter style=1]` where '1' is the style. Replace the '1' with a number from 1-9 that corresponds to one of the images below that matches the style you'd like to use. To simply display text stats on your site (no graphic), replace the '1' with '0'.

To add smaller counter to your site, add the following code to your WordPress theme where you'd like the stats displayed: `<?php if ( function_exists(spamshield_counter) ) { spamshield_counter(1); } ?>` where '1' is the style. Replace the '1' with a number from 1-5 that corresponds to the style you'd like to use. (See plugin homepage for more info.)

To add it to any page or post, add the following shortcode to the page or post where you'd like the stats displayed (using the HTML editing tab, NOT the Visual editor): `[spamshieldcountersm style=1]` where '1' is the style. Replace the '1' with a number from 1-5 that corresponds to the style you'd like to use.

Or, you can simply use the widget. It displays stats in the style of small counter #1. Now you can show spam stats on your blog without knowing any code.

= Adding a Contact Form to Your Blog =
First create a page (not post) where you want to have your contact form. Then, insert the following shortcode (using the HTML editing tab, NOT the Visual editor) and you're done: `[spamshieldcontact]`

There is no need to configure the form. It allows you to simply drop it into the page you want to install it on. However, there are a few basic configuration options. You can choose whether or not to include Phone and Website fields, whether they should be required, add a drop down menu with up to 10 options, set the width and height of the Message box, set the minimum message length, set the form recipient, enter a custom message to be displayed upon successful contact form submission, and choose whether or not to include user technical data in the email.

If you want to modify the style of the form using CSS, all the form elements have an ID attribute you can reference in your stylesheet.

**What the Contact Form feature IS:** A simple drop-in contact form that won't get spammed.

**What the Contact Form feature is NOT:** A configurable and full-featured plugin like some other contact form plugins out there.

= Configuration Information =

**Spam Options**

**Blocked Comment Logging Mode**
This is a temporary diagnostic mode that logs blocked comment submissions for 7 days, then turns off automatically. If you want to see what spam has been blocked on your site, this is the option to use. Also, if you experience any technical issues, this will help with diagnosis, as you can email this log file to support if necessary. If you suspect you are having a technical issue, please turn this on right away and start logging data. Then submit a [support request](http://www.redsandmarketing.com/plugins/wp-spamshield/support/), and we'll email you back asking to see the log file so we can help you fix whatever the issue may be. The log is cleared each time this feature is turned on, so make sure you download the file before turning it back on. Also the log is capped at 2MB for security. This feature may use slightly higher server resources, so for best performance, only use when necessary. (Most websites won't notice any difference.)

**Log All Comments**
Requires that Blocked Comment Logging Mode be engaged. Instead of only logging blocked comments, this will allow the log to capture *all* comments while logging mode is turned on. This provides more technical data for comment submissions than WordPress provides, and helps us improve the plugin. If you plan on submitting spam samples to us for analysis, it's helpful for you to turn this on, otherwise it's not necessary. If you have any spam comments that you feel WP-SpamShield should have blocked (usually human spam), then please submit a [support request](http://www.redsandmarketing.com/plugins/wp-spamshield/support/). When we email you back we will ask you to forward the data to us by email.

This extra data will be extremely valuable in helping us improve the spam protection capabilities of the plugin.

**Disable trackbacks.**
Use if trackback spam is excessive. It is recommended that you don't use this option unless you are experiencing an extreme spam attack.

**Disable pingbacks.**
Use if pingback spam is excessive. The disadvantage is a reduction of communication between blogs. When blogs ping each other, it's like saying "Hi, I just wrote about you", and disabling these pingbacks eliminates that ability. It is recommended that you don't use this option unless you are experiencing an extreme spam attack.

**Help promote WP-SpamShield?**
This places a small link under the comments and contact form, letting others know what's blocking spam on your blog. This plugin is provided for free, so this is much appreciated. It's a small way you can give back and let others know about WP-SpamShield.

**Contact Form Options**
These are self-explanatory.

== Frequently Asked Questions ==

Please see the full [FAQ's](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_faqs).

Also, see the [troubleshooting guide](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_troubleshooting).

If you have any further questions, please submit them on the [support page](http://www.redsandmarketing.com/plugins/wp-spamshield/support/).

== Changelog ==

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

= 1.4.9 =
*released 08/24/14*

* Modified the blocked spam error message for certain human spam comment submissions.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.4.8 =
*released 08/18/14*

* Improved some of the filters in the spam blocking algorithm.
* Improved/optimized some code.
* Updated the spam filters.

= 1.4.7 =
*released 08/12/14*

* Added additional security checks.
* Improved some of the user registration and comment spam filters.
* Fixed a few minor bugs.
* Updated the spam filters.

= 1.4.6 =
*released 08/05/14*

* Added a compatibility fix for certain server configurations where some necessary PHP functions are not enabled.
* Updated the spam filters.

= 1.4.5 =
*released 08/04/14*

* Made further improvements to speed in processing comments and blocking spam. (Users likely won't see a noticeable difference for a single comment being processed...after all, we're dealing in milliseconds here, but these speed improvements in v1.4.4 and v1.4.5 will improve overall scalability.)
* Added French Translation (fr_FR). Thank you to Clément for doing the French translation.
* Updated the Serbian Translation (sr_RS).
* Fixed a bug in the proxy detection.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.4.4 =
*released 07/30/14*

* Improved speed in processing comments and blocking spam. Optimized the order of the filters so the fastest ones fire first, leading to even better speed and lower server load, which in turn improves scalability.
* Added Serbian Translation (sr_RS). Thank you to Borisa Djuraskovic of Web Hosting Hub for doing the Serbian translation.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.4.3 =
*released 07/25/14*

* Added a fix for emails sent though contact forms from `@yahoo.com` email addresses. Recently I noticed that contact form submissions from yahoo.com email addresses were not getting sent. After tracking this and doing a bit of testing, I was able to narrow it down to this: emails getting sent through the PHP `mail()` function from users `@yahoo.com` were not making it through. This wasn't limited to one plugin or script, it was universal (at least in my tests). So I consulted some experts in this area, and was able to find out what's going on. It's related to [Yahoo's new DMARC policy](https://help.yahoo.com/kb/postmaster/yahoo-dmarc-policy-sln24050.html). This policy effectively restricts all Yahoo users from using most website contact forms. In this version, I modified how the plugin handles the email address of the contact form submitter. The contact form emails will now come from an email address that looks like `wpspamshield [dot] noreply [at] yourdomain [dot] com`, similar to how WordPress sends out emails to admins. This should also avoid any spam problems with properly configured SPF records on your domain. (Properly set up SPF records should allow the IP Address of your website as an valid sender.) The email address of the person submitting the contact form will now only be included in the body of the contact form email (which it always was) instead of being in the "From" field too.
* Added a fix to the Settings page for when users activate/deactivate Blocked Comment Logging Mode and the "Log All Comments" feature. To use the "Log All Comments" feature requires Blocked Comment Logging Mode to be active or it doesn't do anything. This new fix just syncs up the settings to eliminate confusion, and so users don't have to worry if they checked both boxes or not when they update their settings. If a user checks "Log All Comments" and saves their settings, it will activate Blocked Comment Logging Mode as well. And vice versa...if a user deactivates Blocked Comment Logging Mode, when they save the settings it will clear the check box for "Log All Comments" as well.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.4.2 =
*released 07/22/14*

* There is a relatively new trend where hackers and link-spammers use search engine bots to do their SQL injections and exploits for them (to avoid leaving a trail back to them). Yes, Googlebot is being used for SQL injection exploits. Hackers use software to post a spam comment to a blog that contains a link to a specially crafted exploit URL. If the spam comment gets accepted, when search engine spiders crawl the page, and go to the specially crafted exploit URL, the SQL injection happens. The SQL injection inserts either a link or bad code to the victim site. Now **ALL** links in comment and contact form submissions will now be checked for these kinds of exploit URLs, so that these attacks will not originate from your site. (This includes the comment author website, the contact form sender's website, and the content of both contact forms and comments will be parsed for links...all included links will be checked.) This will also potentially help avoid having Google penalizing your site for linking to bad neighborhoods. (*Automated* spam attempts of this kind were always blocked, but now that we have more intel, the manual human spam attempts of this type will be blocked 100% as well.)
* Added new filters to the spam blocking algorithm.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.3.8 =
*released 07/18/14*

* Added new filters to the spam blocking algorithm.
* Improved some of the filters in the spam blocking algorithm.
* Updated the spam filters.

= 1.3.7 =
*released 07/15/14*

* Fixed a compatibility issue with the JetPack plugin.
* Minor update to the German Translation (de_DE).
* Updated the spam filters.

= 1.3.6 =
*released 07/12/14*

* Added additional security checks.
* Improved the process of [upgrading from WP-SpamFree to WP-SpamShield](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_faqs_10). (WP-SpamFree is the old version of this plugin, which I wrote in 2007, and passed on to other developers in 2010. It is no longer supported, but this plugin will gracefully upgrade from it, and import all your old settings, automatically. All contact forms will continue to work without any modifications.)
* Fixed a couple minor bugs.
* Updated the spam filters.

= 1.3.5 =
*released 07/09/14*

* Fixed a compatibility issue with the JetPack plugin.
* Updated the spam filters.

= 1.3.4 =
*released 07/07/14*

* Updated the spam filters.

= 1.3.3 =
*released 07/03/14*

* Updated the German Translation (de_DE).
* Updated the spam filters.

= 1.3.2 =
*released 06/30/14*

* Added German Translation (de_DE). Thank you to Chris Krzikalla for doing the German translation.
* Updated the spam filters.

= 1.3.1 =
*released 06/28/14*

* Removed the three initial machine translations based on feedback. If anyone would like to donate their talent and a small amount of time to translating, it would be much appreciated. It's not hard - just [contact me](http://www.redsandmarketing.com/plugins/wp-spamshield/support/) and I'll get you set up. I'm happy to give credit and a website link to anyone who's willing to help out.
* Updated the spam filters.

= 1.3 =
*released 06/27/14*

* Prepared the plugin for internationalization and localization, and created .pot file for translation.
* Created three initial translations with Google Translate and some other resources: French (fr_FR), Spanish (es_ES), and German (de_DE). I realize machine translations may not be the best, but I figured I'd at least get the ball rolling.
* Removed documentation from settings page, since the same info is provided on the plugin homepage (in greater detail). The "Quick Navigation - Contents" is still there, it just points to the plugin homepage now. All the info is still available, it just makes it a bit more efficient if I only have to update documentation in one place. Side benefit is that it slims down the plugin file size a little bit.
* Updated the spam filters.

= 1.2.4 =
*released 06/23/14*

* Fixed a compatibility issue with Internet Explorer 9.

= 1.2.3 =
*released 06/22/14*

* Fixed a compatibility issue with the CommentLuv plugin. As of this version, the two plugins are 100% compatible with each other.
* Updated the spam filters.

= 1.2.2 =
*released 06/20/14*

* Updated the spam filters.
* Made an improvement to the implementation of the new semantic filter.

= 1.2.1 =
*released 06/18/14*

* Updated the spam filters.
* Fixed a minor bug in one of the filters.

= 1.2 =
*released 06/18/14*

* Added a powerful new feature to stop user registration spam. No more automated bot signups through the login page on your site..
* Added a new semantic filter to the algorithmic protection layer, for improved protection against human spam.
* Overhauled and improved many of the filters in the spam blocking algorithm.

= 1.1.7.3 =
*released 06/09/14*

* Improved some of the filters in the spam blocking algorithm.

= 1.1.7.2 =
*released 06/06/14*

* Added new filters to the spam blocking algorithm.
* Removed some deprecated filters (which have already been replaced with newer more efficient ones) and reduced main plugin file size significantly.
* Added a feature to clean up the WordPress blacklist. Now if you view the blacklist through the WP-SpamShield Settings page, it will be sorted in order and have duplicate items removed. When you save WP-SpamShield General Options (not Contact Form Options), it will store this cleaned up version in the database so that whenever you view it again, whether there or on the Discussion settings page, you'll see the cleaned up version.
* Fixed a minor bug.

= 1.1.7.1 =
*released 06/04/14*

* Added new filters and improved existing filters in the algorithmic spam protection layer.
* Fixed several minor bugs.
* Made various code improvements.

= 1.1.7 =
*released 05/28/14*

* Reorganized and rewrote some of the code to make it more efficient and improve overall performance of the plugin. This is the fastest version of the plugin to date.
* Added new filters and improved existing filters in the algorithmic spam protection layer.
* Made improvements to the blocked comment logging data.
* Fixed a bug that caused some legitimate comments to be rejected if the page the user commented on contained added arguments (tracking variables, etc.) in the URL query string (ie. "something=value" - for tracking marketing campaigns, etc).
* Fixed several minor bugs that would show notices in the "debug.log" file if WordPress debugging is turned on.

= 1.1.6.3 =
*released 05/20/14*

* Fixed a bug (introduced in 1.1.6) that prevented users from commenting if caching is not active. One area of the plugin had not been updated to the new key generation system, causing the error, but this is now fixed. Everything works fine now, and the upgrades introduced in 1.1.6 will provide improved performance.

= 1.1.6.2 =
*released 05/16/14*

* Changed the implementation of the random key generation and testing, resulting in greater speed and efficiency.
* Fixed a bug that kept the plugin from updating its version number in the database in certain situations when upgrading to a new version of the plugin.

= 1.1.5 =
*released 05/16/14*

* Made a number of code improvements to improve overall performance, efficiency, and speed of the plugin.
* Completely reformatted the blocked comment logging data. Added some relevant technical data that can aid in tech support.
* Added new filters to the spam blocking algorithm.

= 1.1.4.4 =
*released 05/11/14*

* Added new filters to the spam blocking algorithm.
* Fixed several minor bugs.
* Made improvements to the blocked comment logging data. Changed the date displayed from UTC to the local time of the admin, according to WordPress settings.
* Reformatted the log data to make it easier to read, both for users, and for support requests.
* Added total script execution time to the log data so you can see exactly how long it took to run the filters and block a particular comment. Also helps with debugging and support.
* Made improvements to the readability of the contact form emails.

= 1.1.4.3 =
*released 05/08/14*

* Fixed several minor bugs (mostly PHP Notices, not Warnings or Errors) specific to changes made in PHP 5.4 and 5.5.
* Added new filters to the spam blocking algorithm.
* For the last few weeks I've been making it a high priority to increase the strictness of the code because more recent versions of PHP use stricter code, have introduced new errors (including PHP Notices and Warnings) and have deprecated some older functions and functionality. That's why the new bugs have been popping up. I'm fixing them as quickly as possible. :)

= 1.1.4.2 =
*released 05/08/14*

* Fixed a couple minor bugs.
* Replaced all instances of split() function, which is deprecated in PHP 5.3. (Meaning it still works, but is being phased out and will be eliminated in a future version.) The plugin was already fully compatible with PHP 5.2 and below, and this update ensures full compatibility with PHP 5.3+.
* Made some improvements to the validation of user input on the WP-SpamShield options page in the WP admin to keep the database neat and tidy.

= 1.1.4.1 =
*released 05/07/14*

* Fixed a bug that caused a "Division by zero" error in the admin spam stats (if a new install) on some systems.
* Improved some of the existing filters in the algorithmic spam protection layer.

= 1.1.4 =
*released 05/06/14*

* Fixed a couple of minor bugs.
* Made various code improvements, including code efficiency and increased code strictness.
* Added new filters and improved existing filters in the algorithmic spam protection layer.

= 1.1.3.3 =
*released 05/02/14*

* Fixed an issue occurring on some WordPress installations hosted on Microsoft IIS servers that have certain PHP functions disabled.
* Removed WP-SpamShield Options page link from the "Plugins" menu in the WP Admin. Plugin options can still be accessed from the "Settings" menu.
* Added new filters to the spam blocking algorithm.

= 1.1.3.2 =
*released 05/01/14*

* Added new filters and improved existing filters in the algorithmic spam protection layer.
* Made a few improvements to the blocked comment logging.

= 1.1.3.1 =
*released 04/28/14*

* Fixed a bug in one of the spam filters.
* Fixed 2 bugs that caused error messages on certain server configurations.

= 1.1.3 =
*released 04/25/14*

* Added new filters and improved existing filters in the algorithmic spam protection layer.
* Improved the efficiency of the code, and reduced main plugin file size significantly.
* Fixed a couple of bugs.

= 1.1.2.2 =
*released 04/16/14*

* Fixed a minor bug in the blocked comment logging.

= 1.1.2.1 =
*released 04/15/14*

* Added new filters to the spam blocking algorithm.
* Added additional security checks.
* Made a few improvements to the blocked comment logging data.
* Minor bug fix.

= 1.1.1 =
*released 04/11/14*

* Added better trackback spam protection. This version adds a filter that compares the client IP address of the incoming trackback against the IP address of the server where the link is supposedly coming from. If they don't match, then it is spam, *without fail*. This will eliminate more than 99.99% of trackback spam. Trackback spammers don't send spam out from the same server where their clients' websites reside.
* Added new filters to the spam blocking algorithm.
* Made improvements to the overall compatibility with caching plugins.
* Fixed a bug where the plugin was incorrectly detecting proxies.

= 1.1 =
*released 04/07/14*

* Replaced all instances of eregi() function, which is deprecated in PHP 5.3. (Meaning it still works, but is being phased out and will be eliminated in a future version.) The plugin was already fully compatible with PHP 5.2 and below, and this update ensures compatibility with PHP 5.3+.
* Added new filters to the spam blocking algorithm.

= 1.0.1.1 =
*released 04/03/14*

* Added new filters to the spam blocking algorithm.
* Added additional security checks.
* Code improvements and minor bug fixes.

= 1.0.1 =
*released 03/26/14*

* Improved compatibility with popular caching plugins. This version adds detection to see if caching is active or not, and if specific cache plugins are active, and makes adjustments accordingly. As of this release, the following 10 cache plugins are supported (in order of popularity): WP Super Cache, W3 Total Cache, Quick Cache, Hyper Cache, WP Fastest Cache, DB Cache Reloaded Fix, DB Cache Reloaded, Hyper Cache Extended, WP Fast Cache,and Lite Cache.

= 1.0 =
*released 03/10/14* - Over 20 improvements from its predecessor WP-SpamFree...including:

* Over 10x faster! Tested and verified with benchmarking software.
* Reduced the number of database queries.
* Rewrote much of the code for improved performance.
* Improved security.
* Added a number of new spam protection features.
* Added additional info to spam logging for improved diagnostics.
* Added shortcodes for implementing contact form.
* Added shortcodes for displaying spam stats.
* Added display of the average number of spam comments blocked per day to the admin dashboard.

Forked from WP-SpamFree Version 2.1.1.0, 10/10/13

For a complete list of changes to the plugin, view the [Version History](http://www.redsandmarketing.com/plugins/wp-spamshield/version-history/).

== Upgrade Notice ==
= 1.7.4 =
Added two new features, made UI improvements, improved some error messages, made various code improvements, and improved/updated the spam filters. Please see Changelog for details.

== Other Notes ==

[Troubleshooting Guide](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_troubleshooting) | [WP-SpamShield Support Page](http://www.redsandmarketing.com/plugins/wp-spamshield/support/)

= Troubleshooting Guide / Support =

If you're having trouble getting things to work after installing the plugin, here are a few things to check:

1. Check the [FAQ's](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_faqs).

2. If you haven't yet, please upgrade to the latest version.

3. Check to make sure the plugin is installed properly. Many support requests for this plugin originate from improper installation and can be easily prevented. To check proper installation status, go to the WP-SpamShield page in your Admin. It's a submenu link on the Plugins page. Go the the 'Installation Status' area near the top and it will tell you if the plugin is installed correctly. If it tells you that the plugin is not installed correctly, please double-check what directory you have installed WP-SpamShield in, delete any WP-SpamShield files you have uploaded to your server, re-read the Installation Instructions, and start the Installation process over from step 1.

4. Clear your browser's cache, clear your cookies, and restart your browser. Then reload the page.

5. If you are receiving the error message: "Sorry, there was an error. Please enable JavaScript and Cookies in your browser and try again." then you need to make sure JavaScript and cookies are enabled in your browser. (JavaScript is different from Java. Java is not required.) These are enabled by default in web browsers. The status display will let you know if these are turned on or off (as best the page can detect - occasionally the detection does not work.) If this message comes up consistently even after JavaScript and cookies are enabled, then there most likely is an installation problem, plugin conflict, or JavaScript conflict. Read on for possible solutions.

6. If you have multiple domains that resolve to the same server, or are parked on the same hosting account, make sure the domain set in the WordPress configuration options matches the domain where you are accessing the blog from. In other words, if you have people going to your blog using hxxp://www.yourdomain.com/ and the WordPress configuration has: hxxp://www.yourdomain2.com/ you will have a problem (not just with this plugin, but with a lot of things.)

7. Check your WordPress Version. If you are not using the latest version, you should upgrade for a whole slew of reasons, including features and security.

8. Check the options you have selected to make sure they are not disabling a feature you want to use.

9. Make sure that you are not using other front-end anti-spam plugins (CAPTCHA's, challenge questions, etc) since there's no longer a need for them, and these could likely conflict. Also if you were previously using WP-SpamFree, be sure to disable this as well. (Back-end anti-spam plugins like Akismet are fine, although unnecessary.)

10. Visit hxxp://www.yourblog.com/wp-content/plugins/wp-spamshield/js/jscripts.php (where yourblog.com is your blog url) and check two things. **First, see if the file comes up normally or if it comes up blank or with errors.** That would indicate a problem. Submit a support request (see last troubleshooting step) and copy and past any error messages on the page into your message. **Second, check for a 403 Forbidden error.** That means there is a problem with your file permissions. If the files in the wp-spamshield folder don't have standard permissions (at least 644 or higher) they won't work. This usually only happens by manual modification, but strange things do happen. The **AskApache Password Protect Plugin** is known to cause this error. Users have reported that using its feature to protect the /wp-content/ directory creates an .htaccess file in that directory that creates improper permissions and conflicts with WP-SpamShield (and most likely other plugins as well). You'll need to disable this feature, or disable the AskApache Password Protect Plugin and delete any .htaccess files it has created in your /wp-content/ directory before using WP-SpamShield.

11. Check for conflicts with other JavaScripts installed on your site. This usually occurs with with JavaScripts unrelated to WordPress or plugins. However some themes contain JavaScripts that aren't compatible. (And some don't have the call to the `wp_head()` function which is also a problem. Read on to see how to test/fix this issue.) If in doubt, try switching themes. If that fixes it, then you know the theme was at fault. If you discover a conflicting theme, please let us know.

12. Check for conflicts with other WordPress plugins installed on your blog. Although errors don't occur often, this is one of the most common causes of the errors that do occur. I can't guarantee how well-written other plugins will be. First, see the [Known Plugin Conflicts list](http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_known_conflicts). If you've disabled any plugins on that list and still have a problem, then proceed. To start testing for conflicts, temporarily deactivate all other plugins except WP-SpamShield. Then check to see if WP-SpamShield works by itself. (For best results make sure you are logged out and clear your cookies. Alternatively you can use another browser for testing.) If WP-SpamShield allows you to post a comment with no errors, then you know there is a plugin conflict. The next step is to activate each plugin, one at a time, log out, and try to post a comment. Then log in, deactivate that plugin, and repeat with the next plugin. (If possible, use a second browser to make it easier. Then you don't have to keep logging in and out with the first browser.) Be sure to clear cookies between attempts (before loading the page you want to comment on). If you do identify a plugin that conflicts, please let me know so I can work on bridging the compatibility issues.

13. Make sure the theme you are using has the call to `wp_head()` (which most properly coded themes do) usually found in the header.php file. It will be located somewhere before the `</head>` tag. If not, you can insert it before the `</head>` tag and save the file. If you've never edited a theme before, proceed at your own risk: In the WordPress admin, go to Themes (Appearance) - Theme Editor; Click on Header (or header.php); Locate the line with `</head>` and insert `<?php wp_head(); ?>` before it.

14. If have checked all of these, and still can't quite get it working, please submit a support request at the [WP-SpamShield Support Page](http://www.redsandmarketing.com/plugins/wp-spamshield/support/).

= Updates / Documentation =
For updates and documentation, visit the [WP-SpamShield homepage](http://www.redsandmarketing.com/plugins/wp-spamshield/).

= WordPress Security Note =
As with any WordPress plugin, for security reasons, you should only download plugins from the author's site and from official WordPress repositories. When other sites host a plugin that is developed by someone else, they may inject code into that could compromise the security of your blog. We cannot endorse a version of this that you may have downloaded from another site. If you have downloaded the "WP-SpamShield" plugin from another site, please download the current release from the from the [official WP-SpamShield page on WordPress.org](http://wordpress.org/extend/plugins/wp-spamshield/).
