<?php

include 'admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
$op = 'list';

if (!empty($_GET['op'])) {
    $op = $_GET['op'];
}

if ('list' == $op) {
    xoops_cp_header();

    OpenTable();

    echo '<a href="index.php"><b>Administration &Uuml;bersicht</b></a><br><br>';

    if (!is_writable(XOOPS_ROOT_PATH . '/modules/upanddown/updown')) {
        echo " <h3 style='color:#ff0000;'>" . sprintf(_WARNINNOTWRITEABLE . ' ' . XOOPS_ROOT_PATH . '/modules/upanddown/updown ' . _2WARNINNOTWRITEABLE . '') . '</h3>';
    }

    echo '<div align="center"><b>' . _MODULE . '</b></div><br>';

    echo '<form method="post" action="charge.php?op=checkrep">' . '' . _NEWREP . ' <input type="submit" value="' . _ADD . '">' . '</form>';

    //%%%%%%%%%%%%%%%%%%%%%%

    list_souscat('charge.php?op=souscat', '----');

    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

    echo '<form method="post" action="charge.php?op=chimg">';

    echo '' . _NEWIMAGE . ' &nbsp;';

    $myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object

    $mytree = new XoopsTree($xoopsDB->prefix('upanddown_cat'), 'id', 'cid');

    $mytree->makeMySelBox('cat', 'cat', 0, 1, '', 'submit()');

    echo '</form>';

    CloseTable();

    xoops_cp_footer();
}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
if ('checkrep' == $op) {
    xoops_cp_header();

    OpenTable();

    echo "<div align=\"center\"><br><a href='charge.php'><b>Zurück</b></a><br><HR></div>";

    $path = XOOPS_ROOT_PATH . '/modules/upanddown/updown';

    $rep = "$path/";

    if (!is_writable((string)$path)) {
        echo " <h3 style='color:#ff0000;'>" . sprintf(_WARNINNOTWRITEABLE . ' CHMOD 0777 <br>' . XOOPS_ROOT_PATH . '/modules/upanddown/updown') . '</h3>';

        exit;
    }

    $dir = opendir($rep);

    while ($f = readdir($dir)) {
        if (is_dir($rep . $f)) {
            $requete = 'select cat from ' . $xoopsDB->prefix('upanddown_cat') . " where cat='$f'";

            $result = $xoopsDB->query($requete);

            while (list($img) = $xoopsDB->fetchRow($result)) {
                $f = retour($img);
            }

            if ('1' != $f && '.' != $f && '..' != $f) {
                $insert = $GLOBALS['xoopsDB']->queryF('INSERT INTO ' . $xoopsDB->prefix('upanddown_cat') . " SET cat='$f', img='', coment='', clic='0', alea='1', valid='1'");

                echo "<b>$f</b> " . _REPOK . "<br> $insert[cat]";
            }
        }
    }

    closedir($dir);

    if (empty($insert)) {
        echo '' . _NOOPREP . '';
    }

    CloseTable();

    xoops_cp_footer();
}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
if ('souscat' == $op) {
    xoops_cp_header();

    OpenTable();

    $cat = multiname($uid);

    echo "<div align=\"center\"><br><a href='charge.php'><b>Zurück</b></a><br><HR></div>";

    $rep2 = XOOPS_ROOT_PATH . "/modules/upanddown/updown/$cat";

    $rep = XOOPS_ROOT_PATH . "/modules/upanddown/updown/$cat/";

    if (!is_writable((string)$rep2)) {
        echo " <h3 style='color:#ff0000;'>" . sprintf(_WARNINNOTWRITEABLE . ' CHMOD 0777 <br>' . XOOPS_ROOT_PATH . "/modules/upanddown/updown/$cat") . '</h3>';

        exit;
    }

    $dir = opendir($rep);

    while ($f = readdir($dir)) {
        if (is_dir($rep . $f)) {
            //$requid="select id from ".$xoopsDB->prefix("upanddown_souscat")." where cat='$cat'";

            $requete = 'select cat from ' . $xoopsDB->prefix('upanddown_cat') . " where cat='$f'";

            $result = $xoopsDB->query($requete);

            while (list($img) = $xoopsDB->fetchRow($result)) {
                $f = retour($img);
            }

            if ('1' != $f && '.' != $f && '..' != $f) {
                $insert = $GLOBALS['xoopsDB']->queryF('INSERT INTO ' . $xoopsDB->prefix('upanddown_cat') . " SET  cid='$uid', cat='$f', img='', coment='', clic='0', alea='1', valid='1'");

                echo "<b>$f</b> " . _REPOK . "<br> $insert[cat]";
            }
        }
    }

    closedir($dir);

    if (empty($insert)) {
        echo '' . _NOOPREP . '';
    }

    CloseTable();

    xoops_cp_footer();
}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
if ('chimg' == $op) {
    xoops_cp_header();

    OpenTable();

    $cat = checkname($id);

    $ramdom = $xoopsDB->query('SELECT cid FROM ' . $xoopsDB->prefix('upanddown_cat') . " WHERE cat='$cat' ");

    $row = $xoopsDB->getRowsNum($ramdom);

    [$cid] = $xoopsDB->fetchRow($ramdom);

    if ('0' !== $cid) {
        $cidname = checkrename($cid);

        $rep = XOOPS_ROOT_PATH . "/modules/upanddown/updown/$cidname/$cat/";

        $chmod = XOOPS_ROOT_PATH . "/modules/upanddown/updown/$cidname/$cat";
    } else {
        $cid = '0';

        $rep = XOOPS_ROOT_PATH . "/modules/upanddown/updown/$cat/";
    }

    echo "<div align=\"center\"><br><a href='charge.php'><b>Zurück</b></a><br><HR></div>";

    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
    } else {
        $uid = '0';
    }

    $dir = opendir($rep);

    $i = 0;

    while ($f = readdir($dir)) {
        if (is_file($rep . $f)) {
            $sub = mb_substr(mb_strrchr((string)$f, '.'), 4);

            $extension = mb_substr(mb_strrchr((string)$f, '.'), 1);

            if (!$sub) {
                $requete = 'select img from ' . $xoopsDB->prefix('upanddown') . " WHERE img='$f' ";

                $result = $xoopsDB->query($requete);

                $nb = $xoopsDB->getRowsNum($result);

                while (list($img) = $xoopsDB->fetchRow($result)) {
                    $f = retour($img);
                }

                if ('1' != $f) {
                    $date = time();

                    $insert = $GLOBALS['xoopsDB']->queryF('INSERT INTO ' . $xoopsDB->prefix('upanddown') . " SET uid='$id', userid='1', img='$f', vote='0', valid='1', date='$date' ") or $eh::show('0013');

                    echo "<b>$f</b> " . _YESIMG . '<br> ';

                    if ($insert) {
                        vignette($f, 75, $hauteur, $largeur, $rep, $rep, '-ppm');
                    }
                }

                $i++;
            }
        }//fin while
    }

    closedir($dir);

    if (empty($insert)) {
        echo '' . _NOIMG . '';
    }

    CloseTable();

    xoops_cp_footer();
}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
