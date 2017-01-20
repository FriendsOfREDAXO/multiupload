<?php

/**
 * multiupload/upload_precompressor Plugin.
 * @author Friends Of REDAXO
 * @package redaxo
 * @var rex_addon $this
 */


if (!$this->hasConfig()) {
    $this->setConfig('max_pixel', '1200');
    $this->setConfig('jpg_quality', '100');
}
