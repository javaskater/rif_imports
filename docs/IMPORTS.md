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
