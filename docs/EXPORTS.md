# Exporting

## Drush export configuration:
``` bash
#the command:
jpmena@jpmena-P34 ~/RIF/importations $ drush @d8rif.dev config-export --destination=~/RIF/d8rif/modules/rif_imports/config/d8 | tee export.log
jpmena@jpmena-P34 ~/RIF/importations $ drush @d8rif.dev config-export --destination=~/RIF/d8rif/modules/rif_imports/config/d8 | tee export.log
Configuration successfully exported to /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8.
#if I look at what has been created relative to my custom node/entity!!!
jpmena@jpmena-P34 ~/RIF/importations $ ll ~/RIF/d8rif/modules/rif_imports/config/d8/*randonnee*.yml
-rw-rw-r-- 1 jpmena jpmena  443 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/core.base_field_override.node.randonnee_de_journee.promote.yml
-rw-rw-r-- 1 jpmena jpmena  388 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/core.base_field_override.node.randonnee_de_journee.title.yml
-rw-rw-r-- 1 jpmena jpmena 3127 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/core.entity_form_display.node.randonnee_de_journee.default.yml
-rw-rw-r-- 1 jpmena jpmena 3248 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/core.entity_view_display.node.randonnee_de_journee.default.yml
-rw-rw-r-- 1 jpmena jpmena 1630 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/core.entity_view_display.node.randonnee_de_journee.teaser.yml
-rw-rw-r-- 1 jpmena jpmena  540 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/field.field.node.randonnee_de_journee.body.yml
-rw-rw-r-- 1 jpmena jpmena  785 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/field.field.node.randonnee_de_journee.field_animateur.yml
-rw-rw-r-- 1 jpmena jpmena  538 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/field.field.node.randonnee_de_journee.field_cle.yml
-rw-rw-r-- 1 jpmena jpmena  449 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/field.field.node.randonnee_de_journee.field_date.yml
-rw-rw-r-- 1 jpmena jpmena  502 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/field.field.node.randonnee_de_journee.field_gare_arrivee_aller.yml
-rw-rw-r-- 1 jpmena jpmena  485 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/field.field.node.randonnee_de_journee.field_gare_arrivee_retour.yml
-rw-rw-r-- 1 jpmena jpmena  477 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/field.field.node.randonnee_de_journee.field_gare_depart_aller.yml
-rw-rw-r-- 1 jpmena jpmena  481 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/field.field.node.randonnee_de_journee.field_gare_depart_retour.yml
-rw-rw-r-- 1 jpmena jpmena  512 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/field.field.node.randonnee_de_journee.field_heure_arrivee_aller.yml
-rw-rw-r-- 1 jpmena jpmena  516 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/field.field.node.randonnee_de_journee.field_heure_arrivee_retour.yml
-rw-rw-r-- 1 jpmena jpmena  508 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/field.field.node.randonnee_de_journee.field_heure_depart_aller.yml
-rw-rw-r-- 1 jpmena jpmena  512 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/field.field.node.randonnee_de_journee.field_heure_depart_retour.yml
-rw-rw-r-- 1 jpmena jpmena  499 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/field.field.node.randonnee_de_journee.field_type.yml
-rw-rw-r-- 1 jpmena jpmena  281 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/language.content_settings.node.randonnee_de_journee.yml
-rw-rw-r-- 1 jpmena jpmena  373 août   4 10:27 /home/jpmena/RIF/d8rif/modules/rif_imports/config/d8/node.type.randonnee_de_journee.yml
#I create the place for my new configuration
jpmena@jpmena-P34 ~/RIF/importations $ cd ~/RIF/d8rif/modules/rif_imports/config/d8/
jpmena@jpmena-P34 ~/RIF/d8rif/modules/rif_imports/config/d8 (master *=) $ mkdir randonnee_de_journee
```
##Taking only the enntities related to my Randonnnees de Jour:

* Following that [Drupal8 Web Resource](https://www.drupal.org/node/2629550)
* I decide to copy only the nodes related to _Randonnee de Jour_ (Day Hike) which means:
``` bash
#he yml file starting with node.type.randonnee_de_journee
jpmena@jpmena-P34 ~/RIF/d8rif/modules/rif_imports/config/d8 (master *=) $ cp node.type.randonnee_de_journee.yml randonnee_de_journee/
#he yml files starting with field.field.node.randonnee_de_journee
jpmena@jpmena-P34 ~/RIF/d8rif/modules/rif_imports/config/d8 (master *=) $ cp -v field.field.node.randonnee_de_journee*.yml randonnee_de_journee/
'field.field.node.randonnee_de_journee.body.yml' -> 'randonnee_de_journee/field.field.node.randonnee_de_journee.body.yml'
'field.field.node.randonnee_de_journee.field_animateur.yml' -> 'randonnee_de_journee/field.field.node.randonnee_de_journee.field_animateur.yml'
'field.field.node.randonnee_de_journee.field_cle.yml' -> 'randonnee_de_journee/field.field.node.randonnee_de_journee.field_cle.yml'
'field.field.node.randonnee_de_journee.field_date.yml' -> 'randonnee_de_journee/field.field.node.randonnee_de_journee.field_date.yml'
'field.field.node.randonnee_de_journee.field_gare_arrivee_aller.yml' -> 'randonnee_de_journee/field.field.node.randonnee_de_journee.field_gare_arrivee_aller.yml'
'field.field.node.randonnee_de_journee.field_gare_arrivee_retour.yml' -> 'randonnee_de_journee/field.field.node.randonnee_de_journee.field_gare_arrivee_retour.yml'
'field.field.node.randonnee_de_journee.field_gare_depart_aller.yml' -> 'randonnee_de_journee/field.field.node.randonnee_de_journee.field_gare_depart_aller.yml'
'field.field.node.randonnee_de_journee.field_gare_depart_retour.yml' -> 'randonnee_de_journee/field.field.node.randonnee_de_journee.field_gare_depart_retour.yml'
'field.field.node.randonnee_de_journee.field_heure_arrivee_aller.yml' -> 'randonnee_de_journee/field.field.node.randonnee_de_journee.field_heure_arrivee_aller.yml'
'field.field.node.randonnee_de_journee.field_heure_arrivee_retour.yml' -> 'randonnee_de_journee/field.field.node.randonnee_de_journee.field_heure_arrivee_retour.yml'
'field.field.node.randonnee_de_journee.field_heure_depart_aller.yml' -> 'randonnee_de_journee/field.field.node.randonnee_de_journee.field_heure_depart_aller.yml'
'field.field.node.randonnee_de_journee.field_heure_depart_retour.yml' -> 'randonnee_de_journee/field.field.node.randonnee_de_journee.field_heure_depart_retour.yml'
'field.field.node.randonnee_de_journee.field_type.yml' -> 'randonnee_de_journee/field.field.node.randonnee_de_journee.field_type.yml'
#he yml files starting with core.entity_view_display.node.randonnee_de_journee
jpmena@jpmena-P34 ~/RIF/d8rif/modules/rif_imports/config/d8 (master *=) $ cp -v core.entity_view_display.node.randonnee_de_journee*.yml randonnee_de_journee/
'core.entity_view_display.node.randonnee_de_journee.default.yml' -> 'randonnee_de_journee/core.entity_view_display.node.randonnee_de_journee.default.yml'
'core.entity_view_display.node.randonnee_de_journee.teaser.yml' -> 'randonnee_de_journee/core.entity_view_display.node.randonnee_de_journee.teaser.yml'
#he yml files starting with core.entity_form_display.node.randonnee_de_journee
jpmena@jpmena-P34 ~/RIF/d8rif/modules/rif_imports/config/d8 (master *=) $ cp -v core.entity_form_display.node.randonnee_de_journee*.yml randonnee_de_journee/
'core.entity_form_display.node.randonnee_de_journee.default.yml' -> 'randonnee_de_journee/core.entity_form_display.node.randonnee_de_journee.default.yml'
# I empty the original destination directory
jpmena@jpmena-P34 ~/RIF/d8rif/modules/rif_imports/config/d8 (master *=) $ rm -rf language
jpmena@jpmena-P34 ~/RIF/d8rif/modules/rif_imports/config/d8 (master *=) $ rm *.yml
# All I want to have left is :
jpmena@jpmena-P34 ~/RIF/d8rif/modules/rif_imports/config/d8 (master *=) $ ll
total 28
drwxrwxr-x 3 jpmena jpmena 20480 août   4 10:46 ./
drwxrwxr-x 3 jpmena jpmena  4096 août   4 10:26 ../
drwxrwxr-x 2 jpmena jpmena  4096 août   4 10:43 randonnee_de_journee/
jpmena@jpmena-P34 ~/RIF/d8rif/modules/rif_imports/config/d8 (master *=) $ ll randonnee_de_journee/
total 92
drwxrwxr-x 2 jpmena jpmena  4096 août   4 10:43 ./
drwxrwxr-x 3 jpmena jpmena 20480 août   4 10:46 ../
-rw-rw-r-- 1 jpmena jpmena  3127 août   4 10:43 core.entity_form_display.node.randonnee_de_journee.default.yml
-rw-rw-r-- 1 jpmena jpmena  3248 août   4 10:41 core.entity_view_display.node.randonnee_de_journee.default.yml
-rw-rw-r-- 1 jpmena jpmena  1630 août   4 10:41 core.entity_view_display.node.randonnee_de_journee.teaser.yml
-rw-rw-r-- 1 jpmena jpmena   540 août   4 10:39 field.field.node.randonnee_de_journee.body.yml
-rw-rw-r-- 1 jpmena jpmena   785 août   4 10:39 field.field.node.randonnee_de_journee.field_animateur.yml
-rw-rw-r-- 1 jpmena jpmena   538 août   4 10:39 field.field.node.randonnee_de_journee.field_cle.yml
-rw-rw-r-- 1 jpmena jpmena   449 août   4 10:39 field.field.node.randonnee_de_journee.field_date.yml
-rw-rw-r-- 1 jpmena jpmena   502 août   4 10:39 field.field.node.randonnee_de_journee.field_gare_arrivee_aller.yml
-rw-rw-r-- 1 jpmena jpmena   485 août   4 10:39 field.field.node.randonnee_de_journee.field_gare_arrivee_retour.yml
-rw-rw-r-- 1 jpmena jpmena   477 août   4 10:39 field.field.node.randonnee_de_journee.field_gare_depart_aller.yml
-rw-rw-r-- 1 jpmena jpmena   481 août   4 10:39 field.field.node.randonnee_de_journee.field_gare_depart_retour.yml
-rw-rw-r-- 1 jpmena jpmena   512 août   4 10:39 field.field.node.randonnee_de_journee.field_heure_arrivee_aller.yml
-rw-rw-r-- 1 jpmena jpmena   516 août   4 10:39 field.field.node.randonnee_de_journee.field_heure_arrivee_retour.yml
-rw-rw-r-- 1 jpmena jpmena   508 août   4 10:39 field.field.node.randonnee_de_journee.field_heure_depart_aller.yml
-rw-rw-r-- 1 jpmena jpmena   512 août   4 10:39 field.field.node.randonnee_de_journee.field_heure_depart_retour.yml
-rw-rw-r-- 1 jpmena jpmena   499 août   4 10:39 field.field.node.randonnee_de_journee.field_type.yml
-rw-rw-r-- 1 jpmena jpmena   373 août   4 10:34 node.type.randonnee_de_journee.yml
```
* don't forget to remove the uuid at the start of the files (we want to start with a new installation) before importing in a new Drupal8 !!!
* I commit the files with the rest of the source code on GitHub !!!
