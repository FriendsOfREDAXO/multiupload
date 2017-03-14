<?php

class rex_effect_image_optimizer extends rex_effect_abstract
{
    public function execute()
    {
        $this->media->asImage();
        $format      = $this->media->getFormat();

        $filepath = rex_path::cache('imageoptimizer.' . strtolower($format));

        switch ($format) {
            case 'jpeg':
                imagejpeg($this->media->getImage(), $filepath);
                break;
            case 'png':
                imagepng($this->media->getImage(), $filepath);
                break;
            case 'gif':
                imagegif($this->media->getImage(), $filepath);
                break;
        }

        $config = rex_plugin::get('multiupload', 'imageoptimizer')->getConfig();

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

        if ($config['jpeg_mode'] && $format == "jpeg" || $config['jpeg_mode'] && $format == "jpg") {
            try {
                $optimizer = $optimizer = $factory->get($config['jpeg_mode']);
                $optimizer->optimize($filepath);
            } catch (Exception $e) {}
        }

        if ($config['png_mode'] && $format == "png") {
            try {
                $optimizer = $optimizer = $factory->get($config['png_mode']);
                $optimizer->optimize($filepath);
            } catch (Exception $e) {}
        }

        if ($config['gif_mode'] && $format == "gif") {
            try {
                $optimizer = $optimizer = $factory->get($config['gif_mode']);
                $optimizer->optimize($filepath);
            } catch (Exception $e) {}
        }

        $this->media->setImage(imagecreatefromstring(file_get_contents($filepath)));
        unlink($filepath);
    }
}

?>
