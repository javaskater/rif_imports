# first download

* I chose to clone [my rif_imports GitHub Module](https://github.com/javaskater/rif_imports) outsite of any existing [Drupal 8](https://www.drupal.org/8) installation :

``` bash
jpmena@jpmena-HP ~/RIF $ git clone https://github.com/javaskater/rif_imports.git
Clonage dans 'rif_imports'...
remote: Counting objects: 270, done.
remote: Compressing objects: 100% (63/63), done.
remote: Total 270 (delta 28), reused 0 (delta 0), pack-reused 196
Réception d\'objets: 100% (270/270), 122.75 KiB | 0 bytes/s, fait.
Résolution des deltas: 100% (121/121), fait.
```

# then call the command with the right parameters (the list is long)

## what are the expected parameters :

* The expected parameters are to be found in the script's help:
  * to print that help _usage_, just call the script without any parameter:

``` bash
jpmena@jpmena-HP ~/RIF/rif_imports/scripts/CMS/Drupal (master *=) $ ./installNewD8.sh
Illegal number of parameters found 0 parameters, should be 7 or 10
Usage: ./installNewD8.sh {Site Name} {Admin USer} {Admin Password} {Admin Email} {PHP absolute path} {Locale} {MySQL-User-Name} {MySQL-User-Password} {MySQL-Database-Name} {MySQL-Host-Name}
 + The first parameter will always be the title that appear on the front page ...
 + The second parameter will always be the username of the super-admin user (user 0) ...
 + The third parameter will always be the password of the super-admin user (user 0) ...
 + The fourth parameter will always be the email address for Drupal8 to communicate with the super-admin user (user 0) ...
 + The fifth parameter will always be the php project absolute path ...
 + The sixth parameter will be the locale (other than english) you want Drupal be running with, locale parameter should be:
 ++ fr for french
 ++ de for german
 + if only 7 parameter then:
 ++ MySQL-User-Name MySQL-User-Password MySQL-Database-Name are taken form the last parameter and MySQL-Host-Name is localhost !!!
 + if 10 parameters then:
 ++ MySQL-User-Name MySQL-User-Password MySQL-Database-Name and MySQL-Host-Name will be completed with the 4 last parameters (in that order) !!!
Install a brand new Drupal8 with a specific local...

```

## Calling the script in my case :

``` bash
jpmena@jpmena-HP ~/RIF/rif_imports/scripts/CMS/Drupal (master=) $ ./installNewD8.sh "Randonneurs Ile de France" adminD8Rif php39Rando57 jeanpierre.mena@gmail.com $HOME/RIF/d8rif fr dru8rif
2016/08/23-10:36:49 -  + first download the latest Drupal 8 version and rename it to /home/jpmena/RIF/d8rif
Project drupal (8.1.8) downloaded to /home/jpmena/RIF/d8rif.                                                                                        [success]
Project drupal contains:                                                                                                                            [success]
 - 1 profile: standard
 - 13 themes: classy, bartik, stark, twig, seven, stable, testing_config_overrides, testing_multilingual_with_english, minimal,
drupal_system_listing_compatible_test, testing, testing_config_import, testing_multilingual
 - 66 modules: quickedit, link, page_cache, field, text, breakpoint, taxonomy, config, filter, forum, update, editor, comment, menu_link_content,
content_translation, ban, user, path, entity_reference, system, ckeditor, contextual, toolbar, statistics, rdf, responsive_image, automated_cron,
history, block, block_content, tracker, help, field_ui, node, migrate, migrate_drupal, big_pipe, hal, inline_form_errors, book, views_ui, options,
image, menu_ui, serialization, file, tour, datetime, syslog, action, telephone, simpletest, config_translation, language, rest, dynamic_page_cache,
contact, dblog, aggregator, locale, basic_auth, search, views, color, migrate_drupal_ui, shortcut

2016/08/23-10:36:53 -  - Download the latest Drupal8 version : OK
2016/08/23-10:36:53 -  + then install the Randonneurs Ile de France with locale fr
You are about to create a /home/jpmena/RIF/d8rif/sites/default/settings.php file and DROP all tables in your 'dru8rif' database. Do you want to continue? (y/n): y
Starting Drupal installation. This takes a while. Consider using the --notify global option.                                                        [ok]
Installation complete.  User name: adminD8Rif  User password: php39Rando57                                                                          [ok]
One translation file imported. 7742 translations were added, 0 translations were updated and 0 translations were removed.                           [status]
Félicitations, vous avez installé Drupal !                                                                                                        [status]
2016/08/23-11:10:00 -  - Installation of Randonneurs Ile de France : OK
2016/08/23-11:10:00 -  + changing rights at /home/jpmena/RIF/d8rif/sites/default for the cache
2016/08/23-11:10:00 -  - changing rights at /home/jpmena/RIF/d8rif/sites/default : OK
2016/08/23-11:10:00 -  + adding complementaries modules and themes
features was not found.                                                                                                                             [warning]
bootstrap was not found.                                                                                                                            [warning]
The following projects provide some or all of the extensions not found:                                                                             [ok]
features
bootstrap
Would you like to download them? (y/n): y
Project features (8.x-3.0-beta7) downloaded to /home/jpmena/RIF/d8rif//modules/features.                                                            [success]
Project features contains 2 modules: features_ui, features.
Project bootstrap (8.x-3.0-rc2) downloaded to /home/jpmena/RIF/d8rif//themes/bootstrap.                                                             [success]
The following projects have unmet dependencies:                                                                                                     [ok]
features requires config_update
Would you like to download them? (y/n): y
Project config_update (8.x-1.1) downloaded to /home/jpmena/RIF/d8rif//modules/config_update.                                                        [success]
Project config_update contains 2 modules: config_update_ui, config_update.
The following extensions will be enabled: features, config_update, bootstrap
Do you really want to continue? (y/n): y
config_update was enabled successfully.                                                                                                             [ok]
features was enabled successfully.                                                                                                                  [ok]
bootstrap was enabled successfully.                                                                                                                 [ok]
2016/08/23-11:10:51 -  - adding complementaries modules and themes : OK
2016/08/23-11:10:51 -  End of the installation ...
2016/08/23-11:10:51 -  Making a Backup of the freshly installed Drupal 8 WebSite ...
Database dump saved to /tmp/drush_tmp_1471943453_57bc131d0c883/dru8rif.sql                                                                          [success]
Archive saved to /home/jpmena/RIF/d8rif_fr_20160823_111051.tar                                                                                      [ok]
/home/jpmena/RIF/d8rif_fr_20160823_111051.tar
2016/08/23-11:11:06 -  - result of Backup : OK (the path for the BackupFile is /home/jpmena/RIF/d8rif_fr_20160823_111051.tar

```

* all that appears on the console can be found in the associated log file which is to be found in the same directory as the script itself.
  * for that launch/installation the file's path is : *rif_imports/scripts/CMS/Drupal/installNewD8.sh_20160823_103649.log*
  * which means that the script has been launched the 23th august 2016 at 10 hours 36 min 49 seconds

## Conclusions

* from the console/Logfile we see that:
  * the script took 35 minutes (start: 10:36:49, end: 11:11:06) it ran:
    * on a Celeron with 16GBytes of DDR2 RAM
    * and an Ubuntu 16.04's LAMP
* You don't need to rerun the script if anything goes wrong:
  * a drush's backup has been created by the script after the install process (to be found at *$HOME/RIF/d8rif_fr_20160823_111051.tar* in my test)
  * to restore that backup will recreate an empty *Drupal 8 / RIF* pre-configured site
    * without this *rif_imports* module...
    * See [Main Documentation Page](../README.md#activate-the-rif_imports-module-into-your-fresh-drupal-installation) for the completion of the installation...
