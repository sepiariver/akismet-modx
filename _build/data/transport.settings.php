<?php

$s = array(
    'akismet.api_key' => '',
    'akismet.key_verified' => false,
    'akismet.modx_useragent' => 'Akismet/MODX/' . PKG_VERSION,
);

$settings = array();

foreach ($s as $key => $value) {
    if (is_string($value) || is_int($value)) { $type = 'textfield'; }
    elseif (is_bool($value)) { $type = 'combo-boolean'; }
    else { $type = 'textfield'; }

    $parts = explode('.',$key);
    if (count($parts) == 1) { $area = 'Default'; }
    else { $area = $parts[0]; }
    
    $settings[$key] = $modx->newObject('modSystemSetting');
    $settings[$key]->set('key', $key);
    $settings[$key]->fromArray(array(
        'value' => $value,
        'xtype' => $type,
        'namespace' => PKG_NAME_LOWER,
        'area' => $area
    ));
}

return $settings;
