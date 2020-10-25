<?php

include '../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
require XOOPS_ROOT_PATH . '/include/cp_functions.php';
if ($xoopsUser) {
    $xoopsModule = XoopsModule::getByDirname('upanddown');

    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);

        exit();
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);

    exit();
}
if (file_exists(XOOPS_ROOT_PATH . '/modules/upanddown/language/' . $xoopsConfig['language'] . '/admin.php')) {
    require XOOPS_ROOT_PATH . '/modules/upanddown/language/' . $xoopsConfig['language'] . '/admin.php';
} else {
    require XOOPS_ROOT_PATH . '/modules/upanddown/language/deutsch/admin.php';
}
require_once XOOPS_ROOT_PATH . '/modules/upanddown/functions.php';
require XOOPS_ROOT_PATH . '/modules/upanddown/cache/config.php';
