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

// get config data
$conf = rex_plugin::get('multiupload', 'imageoptimizer')->getConfig();

$error = array();
$success = '';
$info = '';

$test_path = rex_path::plugin('multiupload', 'imageoptimizer', 'tests');

try {

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

    $optimizer = $optimizer = $factory->get('jpegoptim');
    //$optimizer->optimize($test_path.'/test.png');
    $optimizer->optimize($test_path.'/test.jpg');
    //$optimizer->optimize($test_path.'/test.gif');

    //optimized file overwrites original one
} catch (Exception $e) {
    $error[] = $e->getMessage();
}

//////////////////////////////////////////////////////////
// parse info fragment
$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('imageoptimizer_headline_pathsettings'), false);
echo $fragment->parse('core/page/section.php');

// save settings
if (rex_post('btn_save', 'string') != '') {
    $tempConfig = array();
    $newConfig = array();
    $newConfig = rex_post('settings', 'array');

    if (rex_plugin::get('multiupload', 'imageoptimizer')->setConfig($newConfig)) {
        $success = rex_i18n::msg('imageoptimizer_save_success');
    } else {
        $error[] = rex_i18n::msg('imageoptimizer_save_error');
    }
}

$config = rex_plugin::get('multiupload', 'imageoptimizer')->getConfig();

if (!empty($error)) {
    echo rex_view::error(implode('<br />', $error));
}

if ($info != '') {
    echo rex_view::info($info);
}

if ($success != '') {
    echo rex_view::success($success);
}

$content = '';
$formElements = array();

$content .= '<fieldset>';

$n = [];
$n['label'] = '<label for="pngquant_bin">PNG-Quant</label>';
$n['field'] = '<input class="form-control" id="pngquant_bin" type="text" name="settings[pngquant_bin]" value="'.$config['pngquant_bin'].'" />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="pngcrush_bin">PNG-Crush</label>';
$n['field'] = '<input class="form-control" id="pngcrush_bin" type="text" name="settings[pngcrush_bin]" value="'.$config['pngcrush_bin'].'" />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="pngout_bin">PNG-Out</label>';
$n['field'] = '<input class="form-control" id="pngout_bin" type="text" name="settings[pngout_bin]" value="'.$config['pngout_bin'].'" />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="optipng_bin">Opti-PNG</label>';
$n['field'] = '<input class="form-control" id="optipng_bin" type="text" name="settings[optipng_bin]" value="'.$config['optipng_bin'].'" />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="advpng_bin">AdvPNG</label>';
$n['field'] = '<input class="form-control" id="advpng_bin" type="text" name="settings[advpng_bin]" value="'.$config['advpng_bin'].'" />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="jpegtran_bin">JPEG-Tran</label>';
$n['field'] = '<input class="form-control" id="jpegtran_bin" type="text" name="settings[jpegtran_bin]" value="'.$config['jpegtran_bin'].'" />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="jpegoptim_bin">JPEG-Optim</label>';
$n['field'] = '<input class="form-control" id="jpegoptim_bin" type="text" name="settings[jpegoptim_bin]" value="'.$config['jpegoptim_bin'].'" />';
$formElements[] = $n;


$n = [];
$n['label'] = '<label for="gifsicle_bin">GIFsicle</label>';
$n['field'] = '<input class="form-control" id="gifsicle_bin" type="text" name="settings[gifsicle_bin]" value="'.$config['gifsicle_bin'].'" />';
$formElements[] = $n;


$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/form.php');
$content .= '</fieldset>';

$formElements = array();

$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="btn_save" value="'.rex_i18n::msg('imageoptimizer_submit').'">'.rex_i18n::msg('imageoptimizer_submit').'</button>';
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
