Multipload Addon
================


Das AddOn bietet die Möglichkeit, mehrere Dateien auf einmal in den Medienpool hochzuladen

![screen20161222_medienpool_dev](https://cloud.githubusercontent.com/assets/189407/21421913/8dbd05c4-c835-11e6-8f24-595068cdd486.png)

Installation
-------

* Addon herunterladen, entpacken und ggf. umbenennen zu 'mulitupload'
* Das Addon in das Redaxo5 AddOns Verzeichnis /redaxo/src/addons/ ablegen
* Im REDAXO CMS Backend unter Addons das multiupload-Addon installieren

Last Changes
-------
### Version 2.0.2-dev ####
- Changes
* PHP 7 ready
* multiupload nun auch im Medienpool möglich (als eigener Tab)
* Der Standard "Single Upload - Tab" wird durch das multiupload Addon ausgeblendet
* PlugIn: PreCompressor hinzugefügt
* Der PreCompressor verkleinert die Bilder nach dem upload auf eine vorab eingestellte Breite. Dabei wird das Originalbild überschrieben. Dieses Plugin wurde von R4 auf R5 portiert und in den multiupload integriert.

Credits / Respekt
-------

* nightstomp, Hirbod Mirjavadi (multiupload REDAXO CMS 4)
* @darwin26 für den REDAXO 5 Port
* DECAF, Dirk Schürjohann (PreCompressor REDAXO CMS 4)
* [REDAXO CMS](http://www.redaxo.org)
