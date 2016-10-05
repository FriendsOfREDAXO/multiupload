<?php

echo rex_view::title('Rex5 Multiupload');

$upload = new rex_mediapool_multiupload;
$upload->setCallback("complete", "multiuploadEditFile");
echo $upload->createUploadForm();


