multiupload / upload_precompressor
======================

Plugin für das MultiUpload-Addon, das die JPG Dateien während des Hochladens in den Medienpool auf eine vorher festgelegte Breite und JPG-Qualität runterrechnet.
Mit diesem Plugin kann somit verhindert werden, den webspace mit unnötig großen Dateien zu belasten. Ausserdem werden die JPG-Dateien durch den MediaManager ggf. schneller gerendert.

Installation
------------

* Release herunterladen und entpacken.
* Ordner umbenennen in `upload_precompressor`.
* In den Plugins-Ordner des Medienpool AddOns legen: `/redaxo/src/addons/multiupload/plugins`. (falls noch nicht vorhanden /plugins/ Verzeichnis erstellen)

Verwendung
----------

Im Medienpool wurde ein neuer Reiter `Upload PreCompressor` angelegt. Dort können vorab die Einstellungen für die Max. Kantenlänge (px) und die JPG-Qualität (%): gesetzt werden.
Standard Max. Kantenlänge (px) = 1200 und JPG-Qualität (%) = 100

Hinweis
----------

Die Originaldatei wird dabei durch die verkleinerte Datei <b>ersetzt</b>.

todo:
----------

Ein nachträgliches skalieren der JPG-Dateien, wie es in der REX4 Version vorhanden war, ist momentan <b><u>noch nicht</u></b> möglich.

Credits / Respect:
----------
DECAF, Dirk Schürjohann (PreCompressor REDAXO CMS 4)
