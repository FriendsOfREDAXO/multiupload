<?php

/**
 * multiupload/imageoptimizer Plugin.
 *
 * @author Friends Of REDAXO
 *
 * @var rex_addon
 */
rex_extension::register('MEDIA_ADDED', function (rex_extension_point $ep) {

    $config = rex_plugin::get('multiupload', 'imageoptimizer')->getConfig();
    $params = $ep->getParams();
    $params['subject'] = $ep->getSubject();

    if ($config['optimize_uploaded_files']) {
        $uploaded_image = rex_path::media().$params['filename'];

        $tg = new ImageOptimizer\TypeGuesser\SmartTypeGuesser();
        $detected_type = $tg->guess($uploaded_image);

        $factory = new \ImageOptimizer\OptimizerFactory([
            'ignore_errors' => true,
            'pngquant_bin' => $config['pngquant_bin'],
            'pngcrush_bin' => $config['pngcrush_bin'],
            'pngout_bin' => $config['pngout_bin'],
            'optipng_bin' => $config['optipng_bin'],
            'advpng_bin' => $config['advpng_bin'],
            'jpegtran_bin' => $config['jpegtran_bin'],
            'jpegoptim_bin' => $config['jpegoptim_bin'],
            'gifsicle_bin' => $config['gifsicle_bin'],
        ]);

        if ($detected_type == ImageOptimizer\TypeGuesser\TypeGuesser::TYPE_JPEG) {
            if ($config['jpeg_mode']) {
                $optimizer = $factory->get($config['jpeg_mode']);
                $optimizer->optimize($uploaded_image);
            }
        }

        if ($detected_type == ImageOptimizer\TypeGuesser\TypeGuesser::TYPE_PNG) {
            if ($config['png_mode']) {
                $optimizer = $factory->get($config['png_mode']);
                $optimizer->optimize($uploaded_image);
            }
        }

        if ($detected_type == ImageOptimizer\TypeGuesser\TypeGuesser::TYPE_GIF) {
            if ($config['gif_mode']) {
                $optimizer = $factory->get($config['gif_mode']);
                $optimizer->optimize($uploaded_image);
            }
        }

        $filesize = filesize($uploaded_image);

        $mediaSQL = rex_sql::factory();
        $mediaSQL->setDebug(false);
        $mediaSQL->setTable(rex::getTable('media'));
        $mediaSQL->setWhere('filename="'.$params['filename'].'"');
        $mediaSQL->setValue('filesize', $filesize);
        $mediaSQL->update();
    }

    return $params;

}, rex_extension::EARLY);
