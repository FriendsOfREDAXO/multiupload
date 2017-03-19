<?php

/**
 * multiupload/upload_precompressor Plugin.
 * @author Friends Of REDAXO
 * @package redaxo
 * @var rex_addon $this
 */


rex_extension::register('MEDIA_ADDED', function (rex_extension_point $ep) {
    $params = $ep->getParams();
    $params['subject'] = $ep->getSubject();

    $scalable_mime_types = array('image/jpeg', 'image/jpg', 'image/pjpeg');
    $config = rex_plugin::get('multiupload', 'upload_precompressor')->getConfig();


    if (in_array($params['type'], $scalable_mime_types) && $params['ok']) {
        // check if image needs scaling
        if ($params['width'] > $config['max_pixel'] || $params['height'] > $config['max_pixel']) {
            if ($params['width'] > $params['height']) {
                $ratio = $config['max_pixel'] / $params['width'];
            } else {
                $ratio = $config['max_pixel'] / $params['height'];
            }

            $newwidth = round($params['width'] * $ratio);
            $newheight = round($params['height'] * $ratio);

            # load image
            $image = imagecreatetruecolor($newwidth, $newheight);
            $source = imagecreatefromjpeg(rex_path::media() . $params['filename']);

            # resize
            imagecopyresampled($image, $source, 0, 0, 0, 0, $newwidth, $newheight, $params['width'], $params['height']);
            imagejpeg($image, rex_path::media() . $params['filename'], $config['jpg_quality']);

            # update media db
            $size = getimagesize(rex_path::media() . $params['filename']);
            $filesize = filesize(rex_path::media() . $params['filename']);

            $mediaSQL = rex_sql::factory();
            $mediaSQL->setDebug(FALSE);
            $mediaSQL->setTable(rex::getTable('media'));
            $mediaSQL->setWhere('filename="' . $params['filename'] . '"');
            $mediaSQL->setValue('filesize', $filesize);
            $mediaSQL->setValue('width', $size[0]);
            $mediaSQL->setValue('height', $size[1]);
            $mediaSQL->update();

            rex_media_manager::deleteCache($params['filename']);
        }
    }
    return $params;

}, rex_extension::EARLY);
