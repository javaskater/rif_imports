# Importing the Randonnée de Jour and its view in a New Drupal 8

## How I got the manually the randonnee_de_journee content type ?

* This content type was created manually ...
* I added the features module to the Drupal8 Web Site on which I created that content type and its views!
* I explained all that process on my [personal WIKI](http://wiki.jpmena.eu/index.php?title=Php:drupal8:features:install)
  * It is in frecnh
  * I should soon translate it in english
* The created *custom/randonnee_de_journee* folder hierarchy and its content have been copied to my module
  * There are to be found at  *rif_imports/dependencies/custom/randonnee_de_journee*

## How to recreate the content type and its views

* the only thing to do is to activate the **randonnee_de_journee** module which content (from the features export, unmodified since) is  :

``` bash
jpmena@jpmena-HP ~/RIF/d8rif/modules/rif_imports/dependencies/custom (master *=) $ tree randonnee_de_journee/
randonnee_de_journee/
├── config
│   ├── install
│   │   ├── core.base_field_override.node.randonnee_de_journee.promote.yml
│   │   ├── core.base_field_override.node.randonnee_de_journee.title.yml
│   │   ├── core.entity_form_display.node.randonnee_de_journee.default.yml
│   │   ├── core.entity_view_display.node.randonnee_de_journee.default.yml
│   │   ├── core.entity_view_display.node.randonnee_de_journee.teaser.yml
│   │   ├── field.field.node.randonnee_de_journee.body.yml
│   │   ├── field.field.node.randonnee_de_journee.field_animateur.yml
│   │   ├── field.field.node.randonnee_de_journee.field_cle.yml
│   │   ├── field.field.node.randonnee_de_journee.field_date.yml
│   │   ├── field.field.node.randonnee_de_journee.field_gare_arrivee_aller.yml
│   │   ├── field.field.node.randonnee_de_journee.field_gare_arrivee_retour.yml
│   │   ├── field.field.node.randonnee_de_journee.field_gare_depart_retour.yml
│   │   ├── field.field.node.randonnee_de_journee.field_gare_depart.yml
│   │   ├── field.field.node.randonnee_de_journee.field_heure_arrivee_aller.yml
│   │   ├── field.field.node.randonnee_de_journee.field_heure_arrivee_retour.yml
│   │   ├── field.field.node.randonnee_de_journee.field_heure_depart_aller.yml
│   │   ├── field.field.node.randonnee_de_journee.field_heure_depart_retour.yml
│   │   ├── field.field.node.randonnee_de_journee.field_type.yml
│   │   ├── field.storage.node.field_animateur.yml
│   │   ├── field.storage.node.field_cle.yml
│   │   ├── field.storage.node.field_date.yml
│   │   ├── field.storage.node.field_gare_arrivee_aller.yml
│   │   ├── field.storage.node.field_gare_arrivee_retour.yml
│   │   ├── field.storage.node.field_gare_depart_retour.yml
│   │   ├── field.storage.node.field_gare_depart.yml
│   │   ├── field.storage.node.field_heure_arrivee_aller.yml
│   │   ├── field.storage.node.field_heure_arrivee_retour.yml
│   │   ├── field.storage.node.field_heure_depart_aller.yml
│   │   ├── field.storage.node.field_heure_depart_retour.yml
│   │   ├── field.storage.node.field_type.yml
│   │   ├── language.content_settings.node.randonnee_de_journee.yml
│   │   └── node.type.randonnee_de_journee.yml
│   └── optional
│       ├── views.view.randonnees_de_journee.yml
│       └── views.view.rando_via_cle_rif.yml
├── randonnee_de_journee.features.yml
└── randonnee_de_journee.info.yml
```
* as I explained on [my WIKI](http://wiki.jpmena.eu/index.php?title=Php:drupal8:features:install#Effet_de_la_d.C3.A9sintallation_du_module_g.C3.A9n.C3.A9r.C3.A9) uninstalling the module does not remove the _Randonnée de Journée_ content type nor the associated views!

# testing the import in Visual Studio Code (Debug Mode)

* I documented _(in french)_ Client PHP Debugging on my [personal WIKI](http://wiki.jpmena.eu/index.php?title=Php:ide#Passage_en_pas_.C3.A0_pas_sur_du_client_php)

## the command to be tested:

```bash
jpmena@jpmena-HP-ProDesk-600-G2-MT ~/RIF/d8devextranet/web $ drush rif-import-adherents --csv="modules/custom/rif_imports/examples/csvfiles/adherents.sample.csv"
```

## adpating  the debug configuration of my current project

* We need to add the following configuration to _${PROJECT_ROOT}/.vscode/launch.json_

```javascript
         {
            "name": "Drush adherents",
            "type": "php",
            "request": "launch",
            "program": "${workspaceRoot}/vendor/drush/drush/drush.php",
            "args": [ "--root=${workspaceRoot}/web", "rif-import-adherents",  "--csv=${workspaceRoot}/web/modules/custom/rif_imports/examples/csvfiles/animateurs.sample.csv" ],
            "port": 8111
        }
```

## defining the php client configuration of XDEBUG

```bash
# exporting the XDEBUG client configuration with 8111 as the port (9000 used by apache/XDebug and 8000 by another Linux Daemon)
jpmena@jpmena-HP-ProDesk-600-G2-MT ~/RIF/d8devextranet/web $ export XDEBUG_CONFIG="remote_enable=1 remote_mode=req remote_port=8111 remote_host=127.0.0.1 remote_connect_back=0"
# once exported we can start our VisualStudioCode
jpmena@jpmena-HP-ProDesk-600-G2-MT ~/RIF/d8devextranet/web $ code
```