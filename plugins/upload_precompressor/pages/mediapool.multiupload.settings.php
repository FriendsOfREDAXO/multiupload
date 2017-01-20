<?php

/**
 * multiupload/upload_precompressor Plugin.
 * @author Friends Of REDAXO
 * @package redaxo
 * @var rex_addon $this
 */


echo rex_view::title($this->i18n('upload_precompressor_title'));

$error = array();
$success = '';
$info = '';

$scalable_mime_types = array('image/jpeg', 'image/jpg', 'image/pjpeg');
$func = rex_request('func', 'string');

// save settings
if (rex_post('btn_save', 'string') != '') {
    $tempConfig = array();
    $newConfig = array();
    $newConfig = rex_post('settings', 'array');

    if (isset($newConfig['max_pixel']) && $newConfig['max_pixel'] > 0) {
        $tempConfig['max_pixel'] = $newConfig['max_pixel'];
    }

    if (isset($newConfig['jpg_quality']) && $newConfig['jpg_quality'] > 0 AND $newConfig['jpg_quality'] <= 100) {
        $tempConfig['jpg_quality'] = $newConfig['jpg_quality'];
    } else {
        $error[] = rex_i18n::msg('upload_precompressor_check_jpgquality ');
    }

    if (empty($error) && rex_plugin::get('multiupload', 'upload_precompressor')->setConfig($tempConfig)) {
        $success = rex_i18n::msg('upload_precompressor_save_success');
    } else {
        $error[] = rex_i18n::msg('upload_precompressor_save_error');
    }
}

// get config data
$config = rex_plugin::get('multiupload', 'upload_precompressor')->getConfig();


$mediaSQL = rex_sql::factory();
$mediaSQL->setDebug(FALSE);
$mediaSQL->setTable(rex::getTable('media'));
$where = "(";
foreach ($scalable_mime_types as $type) {
    $where .= 'filetype="' . $type . '" OR ';
}
$where = substr($where, 0, strlen($where) - 3) . ') ';
$where .= "AND (width > " . $config['max_pixel'] . " OR height > " . $config['max_pixel'] . ")";
$mediaSQL->setWhere($where . ' ORDER BY pixel ASC');
$mediaSQL->select('*, (width * height) AS pixel');
$files = $mediaSQL->getArray();


if (rex_get('scale') == 'scale') {
    ob_end_clean();

    $initial = rex_get('initial');
    $progress = $initial - count($files);

    if ($progress) {
        $td_width = round(($progress / $initial) * 100);
    } else {
        $td_width = 0;
    }
    $td2_width = 100 - $td_width;

    if (rex_post('btn_update', 'string') != '' || rex_get('update_continue')) {
        ob_start();

    }
}


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
$n['label'] = '<label for="max_pixel">'.rex_i18n::msg('upload_precompressor_maxlength').'</label>';
$n['field'] = '<input class="form-control" id="max_pixel" type="text" name="settings[max_pixel]" value="' . $config['max_pixel'] . '" />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="jpg_quality">'.rex_i18n::msg('upload_precompressor_jpgquality').'</label>';
$n['field'] = '<input class="form-control" id="jpg_quality" type="text" name="settings[jpg_quality]" value="' . $config['jpg_quality'] . '" />';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/form.php');
$content .= '</fieldset>';



$formElements = array();

$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="btn_save" value="'.rex_i18n::msg('upload_precompressor_submit').'">'.rex_i18n::msg('upload_precompressor_submit').'</button>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('flush', true);
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');


// section
$fragment = new rex_fragment();
$fragment->setVar('class', 'info', false);
$fragment->setVar('title', rex_i18n::msg('upload_precompressor_title_settings'), false);
$fragment->setVar('body', $content, false);
$fragment->setVar('buttons', $buttons, false);
$content = $fragment->parse('core/page/section.php');


echo '
    <form action="' . rex_url::currentBackendPage() . '" method="post">
        ' . $content . '
    </form>
';