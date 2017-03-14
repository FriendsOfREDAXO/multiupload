Multipload Addon
================

Das AddOn bietet die Möglichkeit, mehrere Dateien auf einmal in den Medienpool hochzuladen.

![Screenshot](https://raw.githubusercontent.com/FriendsOfREDAXO/multiupload/assets/screenshot.jpg)


PlugIns
-------

Dieses AddOn enthält zwei sehr nützliche PlugIns.

* ImageOptimizer
* PreCompressor

Der PreCompressor kann Bilder automatisch verkleinern. Die Werte können im Plugin eingestellt werden. Es werden keine zusätzlichen Systemlibraries benötigt.

Der ImageOptimizer ist komplexer und benötigt diverse Kommandozeilentools. Diese lassen sich mit Root-Zugriff auf dem Server (oder als Anfrage beim Provider) installieren (apt-get install jpegoptim pngquant pngcrush pngout advancecomp). Auf DomainFactory können diese Tools sehr einfach selbst kompiliert werden. https://www.df.eu/forum/threads/80529-jpegoptim-optipng-auf-ManagedServern.

Jpegtran muss aktuell auf neueren Sytemen selbst kompiliert werden, da keine Packages verfügbar sind (zumindest für Debian)

Der ImageOptimizer unterstützt alle gängigen Bildtypen. Die Optimizer sind teilweise verlustfrei und teilweise verlustetbehaftet. Es steht in den Einstellungen detailliert dabei.

- jpegoptim (verlustfrei)
- jpegtrans (orientation-fix only)
- pngquant (verlustbehaftet, aber sehr gute Qualität bei kleiner Dateigröße)
- optipng (verlustfrei, geringe Ergebnisse)
- pngcrush (verlustfrei, geringe Ergebnisse)
- pngout (verlustfrei, geringe Ergebnisse)
- advpng / advcompress (nur Komprimierung, verlustfrei, geringe Ergebnisse)
- Mix-Modus: pngquant, optipng, pngcrush, advcomp (verlustbehaftet, aber sehr gute Qualität bei kleinster Dateigröße)

Die Pfade zu den Tools sind voreingestellt (normale Defaults). Falls (wie bei DomainFactory) selbst kompiliert wird, müssen die Pfade entsprechend angepasst werden (meistens /opt/...)

Der ImageOptimizer stellt einen Media Manager Effekt zur Verfügung. Dieser sollte immer als letzter Effektschritt eingebaut werden (funktioniert auch dazwischen, könnte jedoch Sideeffects haben, die noch ungetestet sind). Des Weiteren kann zusätzlich jedes Bild nach dem Upload optimiert werden. Möchte man auf der Website keinen Media Manager nutzen, empfiehlt sich die Upload-Einstellung.

Bitte schaue dir hierzu die Einstellungsoptionen in der Plugin-Page an.

Installation
-------

* Addon herunterladen, entpacken und ggf. umbenennen zu 'mulitupload'
* Das Addon in das Redaxo5 AddOns Verzeichnis /redaxo/src/addons/ ablegen
* Im REDAXO CMS Backend unter Addons das multiupload-Addon installieren

Beachte: dieses AddOn enthält nützliche Plugins

Last Changes
-------
### Version 2.1.1 ####
* diverse kleine Bugs und Einstellungsfehler gefixed
* Plugin: imageoptimizer hinzugefügt

### Version 2.0.1-dev ####

- Changes
* multiupload nun auch im Medienpool möglich (als eigener Tab)
* Der Standard "Single Upload - Tab" wird durch das multiupload Addon ausgeblendet
* PlugIn: PreCompressor hinzugefügt
* Der PreCompressor verkleinert die Bilder nach dem upload auf eine vorab eingestellte Breite. Dabei wird das Originalbild überschrieben. Dieses Plugin wurde von R4 auf R5 portiert und in den multiupload integriert.

Credits / Respekt
-------

* nightstomp, Hirbod Mirjavadi (multiupload REDAXO CMS 4)
* DECAF, Dirk Schürjohann (PreCompressor REDAXO CMS 4)
* [REDAXO CMS](http://www.redaxo.org)
