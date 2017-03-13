<?php

/**
 * multiupload Addon.
 * @author Friends Of REDAXO
 * @package redaxo
 * @var rex_addon $this
 */


$addon = rex_addon::get('multiupload');

// --- DYN
$properties = array (
    'folder' => rex_path::media(),
    'sync_cats' => '1',
    'instant_upload_start' => '1',
    'upload_simultaneously' => '5',
    'clear_uploadlist_automatically' => '0',
    'clear_file_after_finish' => '1',
    'show_footnote' => '0',
    'php_debug' => '0',
    'javascript_debug' => '0',
);

foreach($properties as $key => $val) {
    $addon->setProperty($key,$val);
}
// --- /DYN


// --- HEADER
if (rex::isBackend() && rex::getUser()) {
    rex_view::addCssFile($addon->getAssetsUrl('fileuploader.css'));
    rex_view::addJSFile($addon->getAssetsUrl('fileuploader.js'));

    $_REX_HACK_OPENER = "";
    $_REX_HACK_OPENER = rex_request('opener_input_field', 'string');

    $header =
    PHP_EOL.'  '.
    PHP_EOL.'  <!-- multiupload -->'.
    PHP_EOL.'  <script type="text/javascript">var lastMediaPoolOpener = "'.$_REX_HACK_OPENER.'";</script>'.
    PHP_EOL.'  <!-- ###MULTIUPLOAD_EP_REPLACE### -->'.
    PHP_EOL.'  <!-- /multiupload -->'.PHP_EOL;


    rex_extension::register('OUTPUT_FILTER', function(rex_extension_point $ep) use ($header){
        $suchmuster = '</head>';
        $ersetzen = $header ."\n</head>";
        $ep->setSubject(str_replace($suchmuster, $ersetzen, $ep->getSubject()));
    });

    // Medienpool upload + sync Page deaktivieren
    rex_extension::register('PAGES_PREPARED', function () {
        /*
        $page = rex_be_controller::getPageObject('mediapool/upload');
        if ($page) {
            $page->setHidden(true);
            $page->setHasLayout(true);
            $page->setSubPath($this->getPath("pages/mediapool.multiupload.quickupload.php"));
        }

        $page = rex_be_controller::getPageObject('mediapool/sync');
        if ($page) {
            $page->setHidden(true);
        }
        */
    });
}
// --- /HEADER
