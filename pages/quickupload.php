<?php

echo rex_view::title('multiupload');

$upload = new rex_mediapool_multiupload;
$upload->setCallback("complete", "multiuploadEditFile");
echo $upload->createUploadForm();


