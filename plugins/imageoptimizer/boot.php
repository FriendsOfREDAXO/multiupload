<?php

/**
 * multiupload/upload_imageoptimizer Plugin.
 *
 * @author FriendsOfREDAXO
 *
 * @var rex_addon
 */
if (rex::isBackend()) {
    if (rex_addon::get('media_manager')->isAvailable()) {
        rex_media_manager::addEffect('rex_effect_image_optimizer');
    }

    $config = $this->getConfig();

    require_once __DIR__.'/extensions/extension.upload_imageoptimizer.inc.php';
}
