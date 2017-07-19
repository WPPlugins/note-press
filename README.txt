=== Note Press ===
Contributors: datainterlock
Tags: note, admin notes, developer notes, notepad, collaboration, clients
Donate link:  https://www.patreon.com/user?u=6474471
Requires at least: 3.9
Tested up to: 4.8
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add, edit and delete multiple notes and display them with icons on the Admin page and Dashboard.


== Description ==
Note Press is an extremely easy to use note system for the WordPress Admin panel. Creating to-do lists, leaving instructions for clients, collecting code snippets or collaborating with other admins are just a few of the uses for Note Press. Unlike other note plugins, Note Press keeps thing extremely simple by using features familiar to all WordPress users. Simply click on the Note Press admin button and your notes are all there listed by date.

* Create notes with the same familiar editor you use to create posts.
* Add images or media to notes
* Create notes and sort them by priority, title, date created or deadline 
* Add a deadline to notes
* Notes are also listed on the WordPress Dashboard
* English and a sample Spanish language file included so you can translate into your own language.


== Installation ==
1. Navigate to the \'Add New\' in the plugins dashboard
2. Search for \'Note Press\'
3. Click \'Install Now\'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the \'Add New\' in the plugins dashboard
2. Navigate to the \'Upload\' area
3. Select `Note_Press.zip` from your computer
4. Click \'Install Now\'
5. Activate the plugin in the Plugin dashboard

= Using HTTP =

1. Download `Note_Press.zip` from http://www.datainterlock.com
2. Extract the \'Note Press` directory to your computer
3. Upload the `Note Press` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard


== Frequently Asked Questions ==
= Who can see Note Press? =

At this time, only the Super Admin has the ability to use Note Press.

= Can the icons be replaced? =

Yes. The icons are stored in the Note_Press/public/icons directory.  To add your own custom icons, simply upload them to that directory and Note Press will find them. Be aware that Note Press is designed for 16x16 icons.  Using icons of any other size will produce unpredictable results.

= What does the search look for? =

Note Press has the ability to search through your notes.  When doing so, it checks for matches in both the title and the contents of the note.  The search is not case-sensitive.

= How many notes can Note Press hold? =

To the limit of your database. Only 5 notes will be displayed in the list at a time though.

= Can media be stored in the notes? =

Yes. Note Press uses the same editor (Tiny MCE) used when creating posts and pages.

= Can shortcodes be used in the notes? =

Yes. Although not all shortcodes have been tested, Note Press does process the contents of your notes for shortcodes.

== Screenshots ==
1. The main Note Press Main panel showing a list of notes.
2. Viewing a note in Note Press with media embedded.
3. Adding a note to Note Press.

== Changelog ==
= 0.1.4 =
Fixed update breaking as a result of the plugin not deactivating during upgrade.

== Changelog ==
= 0.1.3 =
*Boilerplate updated.
*Public funcitons moved to admin menu where they should have been in the first place.
*Fixed strings that were hard coded instead of being in the language files.
*Removed language files that prevented language switching.
*Added list of notes to the dashboard.
*Database view level field removed.
*Database category field removed.
*Database priority field added.
*Database deadline field added.
*Database toUser field added.
*Database userRead field added.
*Fixed column sorting which was broken in 0.1.2 as a result of preparing queries.

= 0.1.2 =
*Security update to reduce risk of SQL injection.

= 0.1.1 =
*Updated to comply with depreciated functions.
*Removed some annoying notices of variables not being set.

= 0.1.0 =
* Initial Beta Release

== Upgrade Notice ==