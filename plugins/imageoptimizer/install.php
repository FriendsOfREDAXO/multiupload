<?php

/**
 * multiupload/imageoptimizer Plugin.
 * @author Friends Of REDAXO
 * @package redaxo
 * @var rex_addon $this
 */


if (!$this->hasConfig()) {
    $this->setConfig('optimize_uploaded_files', true);
    $this->setConfig('pngquant_bin', '/usr/bin/pngquant');
    $this->setConfig('pngcrush_bin', '/usr/bin/pngcrush');
    $this->setConfig('pngout_bin', '/usr/bin/pngout');
    $this->setConfig('optipng_bin', '/usr/bin/optipng');
    $this->setConfig('advpng_bin', '/usr/bin/advpng');
    $this->setConfig('jpegtran_bin', '/usr/bin/jpegtran');
    $this->setConfig('jpegoptim_bin', '/usr/bin/jpegoptim');
    $this->setConfig('gifsicle_bin', '/usr/bin/gifsicle');
    $this->setConfig('jpeg_mode', 'jpegoptim');
    $this->setConfig('png_mode', 'pngquant');
    $this->setConfig('gif_mode', false);
}
