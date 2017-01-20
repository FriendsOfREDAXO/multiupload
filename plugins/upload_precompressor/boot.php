<?php

/**
 * multiupload/upload_precompressor Plugin.
 * @author Friends Of REDAXO
 * @package redaxo
 * @var rex_addon $this
 */


if (rex::isBackend()) {
    $config = $this->getConfig();

    require_once __DIR__ . '/extensions/extension.upload_precompressor.inc.php';

}
