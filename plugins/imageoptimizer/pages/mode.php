<?php

/**
 * multiupload/imageoptimizer Plugin.
 *
 * @author FriendsOfREDAXO
 *
 * @var rex_plugin
 */
echo rex_view::title($this->i18n('imageoptimizer_title'));
$func = rex_request('func', 'string');

$error = array();
$success = array();
$info = '';

// save settings
if (rex_post('btn_test', 'string') != '') {
    $source_path = rex_path::plugin('multiupload', 'imageoptimizer', 'tests_source');
    $test_path = rex_path::plugin('multiupload', 'imageoptimizer', 'tests');

    rex_dir::copy($source_path, $test_path);

    $newConfig = array();
    $newConfig = rex_post('settings', 'array');

    $conf = rex_plugin::get('multiupload', 'imageoptimizer')->getConfig();
    $factory = new \ImageOptimizer\OptimizerFactory([
        'ignore_errors' => false,
        'pngquant_bin' => $conf['pngquant_bin'],
        'pngcrush_bin' => $conf['pngcrush_bin'],
        'pngout_bin' => $conf['pngout_bin'],
        'optipng_bin' => $conf['optipng_bin'],
        'advpng_bin' => $conf['advpng_bin'],
        'jpegtran_bin' => $conf['jpegtran_bin'],
        'jpegoptim_bin' => $conf['jpegoptim_bin'],
        'gifsicle_bin' => $conf['gifsicle_bin'],
    ]);

    if ($newConfig['jpeg_mode']) {
        try {
            $oldsize = filesize($source_path.'/test.jpg');

            $optimizer = $optimizer = $factory->get($newConfig['jpeg_mode']);
            $optimizer->optimize($test_path.'/test.jpg');

            $newsize = filesize($test_path.'/test.jpg');

            // calculate difference
            $percentChange = (1 - $oldsize / $newsize) * 100;
            $success[] = "JPEG OK: " . round($percentChange, 2) ." %";

        } catch (Exception $e) {
            $error[] = "JPEG ERROR:";
            $error[] = $e->getMessage();
        }
    }

    if ($newConfig['png_mode']) {
        try {
            $oldsize = filesize($source_path.'/test.png');

            $optimizer = $optimizer = $factory->get($newConfig['png_mode']);
            $optimizer->optimize($test_path.'/test.png');

            $newsize = filesize($test_path.'/test.png');

            // calculate difference
            $percentChange = (1 - $oldsize / $newsize) * 100;
            $success[] = "PNG OK: " . round($percentChange, 2) ." %";

        } catch (Exception $e) {
            $error[] = "PNG ERROR:";
            $error[] = $e->getMessage();
        }
    }

    if ($newConfig['gif_mode']) {
        try {
            $oldsize = filesize($source_path.'/test.gif');

            $optimizer = $optimizer = $factory->get($newConfig['gif_mode']);
            $optimizer->optimize($test_path.'/test.gif');

            $newsize = filesize($test_path.'/test.gif');

            // calculate difference
            $percentChange = (1 - $oldsize / $newsize) * 100;
            $success[] = "GIF OK: " . round($percentChange, 2) ." %";

        } catch (Exception $e) {
            $error[] = "GIF ERROR:";
            $error[] = $e->getMessage();
        }
    }

    if (empty($error)) {
        $success[] = rex_i18n::msg('imageoptimizer_settings_had_no_error');
    }

    rex_dir::delete($test_path);
}

if (rex_post('btn_save', 'string') != '' || rex_post('btn_test', 'string') != '') {
    $tempConfig = array();
    $newConfig = array();
    $newConfig = rex_post('settings', 'array');

    if(!isset($newConfig['optimize_uploaded_files'])) {
        $newConfig['optimize_uploaded_files'] = 0;
    }

    rex_plugin::get('multiupload', 'imageoptimizer')->setConfig($newConfig);
}

$config = rex_plugin::get('multiupload', 'imageoptimizer')->getConfig();

if (!empty($error)) {
    echo rex_view::error(implode('<br />', $error));
}

if ($info != '') {
    echo rex_view::info($info);
}

if (!empty($success)) {
    echo rex_view::success(implode('<br />', $success));
}

$content = '';
$formElements = array();

$content .= '<fieldset>';

// Checkbox
$n = [];
$n['label'] = '<label for="optimize_uploaded_files">'.rex_i18n::msg('imageoptimizer_optimize_uploaded_files').'</label>';
$n['field'] = '<input type="checkbox" id="optimize_uploaded_files" name="settings[optimize_uploaded_files]" value="1" '.($config['optimize_uploaded_files'] ? ' checked="checked"' : '').' />';
$formElements[] = $n;

// Select
$n = [];
$n['label'] = '<label for="jpeg_mode">JPEG</label>';
$select = new rex_select();
$select->setId('jpeg_mode');
$select->setAttribute('class', 'form-control');
$select->setName('settings[jpeg_mode]');
$select->addOption(rex_i18n::msg('imageoptimizer_deactivated'), false);
$select->addOption('jpegoptim ('.rex_i18n::msg('imageoptimizer_lossyless').')', 'jpegoptim');
$select->addOption('jpegtran (Orientation-Fix only)', 'jpegtran');
$select->addOption('jpegtran, jpegoptim', 'jpeg');
$select->setSelected($config['jpeg_mode']);
$n['field'] = $select->get();
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="png_mode">PNG</label>';
$select = new rex_select();
$select->setId('png_mode');
$select->setAttribute('class', 'form-control');
$select->setName('settings[png_mode]');
$select->addOption(rex_i18n::msg('imageoptimizer_deactivated'), false);
$select->addOption('optipng ('.rex_i18n::msg('imageoptimizer_lossyless_small').')', 'optipng');
$select->addOption('pngcrush ('.rex_i18n::msg('imageoptimizer_lossyless_small').')', 'pngcrush');
$select->addOption('pngout ('.rex_i18n::msg('imageoptimizer_lossyless_small').')', 'pngout');
$select->addOption('advpng  ('.rex_i18n::msg('imageoptimizer_lossyless_small').')', 'advpng');
$select->addOption('pngquant ('.rex_i18n::msg('imageoptimizer_lossy_best').')', 'pngquant');
$select->addOption('pngquant, optipng, pngcrush, advpng ('.rex_i18n::msg('imageoptimizer_recommended').')', 'png');

$select->setSelected($config['png_mode']);
$n['field'] = $select->get();
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="gif_mode">GIF</label>';
$select = new rex_select();
$select->setId('gif_mode');
$select->setAttribute('class', 'form-control');
$select->setName('settings[gif_mode]');
$select->addOption(rex_i18n::msg('imageoptimizer_deactivated'), false);
$select->addOption('gifsicle', 'gif');
$select->setSelected($config['gif_mode']);
$n['field'] = $select->get();
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/form.php');
$content .= '</fieldset>';

$formElements = array();

$n = [];
$n['field'] = '<button style="margin-right: 20px;" class="hidden-sm hidden-xs btn btn-save rex-form-aligned" type="submit" name="btn_save" value="'.rex_i18n::msg('imageoptimizer_submit').'">'.rex_i18n::msg('imageoptimizer_submit').'</button>';
$formElements[] = $n;

$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="btn_test" value="'.rex_i18n::msg('imageoptimizer_test_setting').'">'.rex_i18n::msg('imageoptimizer_test_setting').'</button>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('flush', true);
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');

// section
$fragment = new rex_fragment();
$fragment->setVar('class', 'info', false);
$fragment->setVar('title', rex_i18n::msg('imageoptimizer_settings'), false);
$fragment->setVar('body', $content, false);
$fragment->setVar('buttons', $buttons, false);
$content = $fragment->parse('core/page/section.php');

echo '
<form action="'.rex_url::currentBackendPage().'" method="post">
'.$content.'
</form>
';

//////////////////////////////////////////////////////////
// parse info fragment
$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('imageoptimizer_headline_modesettings'), false);
echo $fragment->parse('core/page/section.php');
