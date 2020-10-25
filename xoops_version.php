<?php

$modversion['name'] = _MAGALERIE_NAME;
$modversion['version'] = 1.6;
$modversion['description'] = _MAGALERIE_DESC;
$modversion['credits'] = 'Basiert auf Magalerie v1.6 pour xoop 2.0 (version-rc3)';
$modversion['author'] = 'bidou<br>bidou@lespace.org - http://www.lespace.org/';
$modversion['help'] = '';
$modversion['license'] = 'GPL see LICENSE';
$modversion['official'] = 0;
$modversion['image'] = 'images/galerie.png';
$modversion['dirname'] = 'upanddown';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = 'upanddown';
$modversion['tables'][1] = 'upanddown_cat';
$modversion['tables'][2] = 'upanddown_comments';

// Menu
$modversion['hasMain'] = 1;

$modversion['blocks'][1]['file'] = 'bloc.php';
$modversion['blocks'][1]['name'] = _MAGALERIE_NAME;
$modversion['blocks'][1]['description'] = 'UpandDown-Block';
$modversion['blocks'][1]['show_func'] = 'b_magalerie_show';
