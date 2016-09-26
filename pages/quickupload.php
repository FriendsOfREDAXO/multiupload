<?php

/**
 * rex5_multiupload Addon.
 *
 * @author kolloch.c@idowapro.de
 *
 * @package redaxo\idowapro
 *
 * @var rex_addon $this
 */


echo rex_view::title('Rex5 Multiupload');

$upload = new rex_mediapool_multiupload;
$upload->setCallback("complete", "multiuploadEditFile");
echo $upload->createUploadForm();


