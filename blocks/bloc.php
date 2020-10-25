<?php

function b_linkretour($uid)
{
    global $xoopsDB;

    $demande = 'select cid from ' . $xoopsDB->prefix('upanddown_cat') . " where id='$uid' ";

    $req = $xoopsDB->query($demande);

    while (list($cid) = $xoopsDB->fetchRow($req)) {
        if (0 != $cid) {
            $link = multiname($cid);

            $aref = "$link/";
        } else {
            $aref = '';
        }
    }

    return $aref;
}

function b_multiname($name)
{
    global $xoopsDB, $cid;

    $rete = 'select cat from ' . $xoopsDB->prefix('upanddown_cat') . " where id='$name'";

    $result = $xoopsDB->query($rete);

    while (list($rowcat) = $xoopsDB->fetchRow($result)) {
        return $rowcat;
    }
}

function b_magalerie_show()
{
    //require_once (XOOPS_ROOT_PATH."/modules/upanddown/functions.php");

    require_once XOOPS_ROOT_PATH . '/modules/upanddown/language/deutsch/modinfo.php';

    global $xoopsConfig, $xoopsDB, $xoopsUser;

    $block = [];

    $block['title'] = _MAGALERIE_BNAME;

    $block['content'] = '';

    $ramdom = $xoopsDB->query('SELECT id, uid, img FROM ' . $xoopsDB->prefix('upanddown') . " WHERE valid >= '1' ORDER BY RAND()");

    $row = $xoopsDB->getRowsNum($ramdom);

    [$id, $uid, $img] = $xoopsDB->fetchRow($ramdom);

    $pathimg = b_linkretour($uid);

    $catname = b_multiname($uid);

    $rowimage = $xoopsConfig['root_path'] . 'modules/upanddown/updown/' . $pathimg . '' . $catname . '/' . $img . '-ppm';

    if (file_exists((string)$rowimage)) {
        $size = getimagesize((string)$rowimage);

        $block['content'] .= '<div align="center"><a href="'
                             . $xoopsConfig['xoops_url']
                             . "/modules/upanddown/show.php?id=$id\"><img src=\""
                             . $xoopsConfig['xoops_url']
                             . '/modules/upanddown/updown/'
                             . $pathimg
                             . ''
                             . $catname
                             . '/'
                             . $img
                             . "-ppm\" $size[3] title=\""
                             . $pathimg
                             . ''
                             . $catname
                             . '/'
                             . $img
                             . '"></a></div>';
    }

    return $block;
}
