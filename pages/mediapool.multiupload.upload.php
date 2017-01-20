<?php
/**
 * multiupload Addon.
 * @author Friends Of REDAXO
 * @package redaxo
 * @var rex_addon $this
 */
	
$addon = rex_addon::get('multiupload');

require_once rex_path::addon("multiupload", "fragments/action.upload.php");
