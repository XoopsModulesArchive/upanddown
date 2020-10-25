<?php
//id:magalerie/index.php
#################################################
# Magalerie Version 1.6                xoops v2 rcxx
# Projet du --/--/2002          dernière modification: 28/03/2004
# Scripts Home:                 http://www.lespace.org
# auteur           :            bidou
# email            :            bidou@lespace.org
# Site web         :                http://www.lespace.org
# licence          :            Gpl
##################################################
# Merci de laisser cette entête en place.
##################################################
include 'header.php';

include '../../header.php';
require XOOPS_ROOT_PATH . '/modules/upanddown/class/xoopspagenav.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
//require XOOPS_ROOT_PATH."/class/pagenav.php";
?>
    <style>

        .show {
            width: 100%;
            padding: 2px
        }
    </style>
<?php
echo '<table border="0" width="100%"><tr><td><a href="index.php"><b>' . _MA_TITRE . '</b</a></td><td align="right">';

if ('1' == $listcat) {
    echo '<form method="post" action="galerie.php">';

    echo '<b>' . _NAVIGATION . '</b>: ';

    $myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object

    $mytree = new XoopsTree($xoopsDB->prefix('upanddown_cat'), 'id', 'cid');

    $mytree->makeMySelBox('cat', 'id', 0, 1, 'uid', 'submit()');

    echo '</form>';
} else {
    echo '&nbsp;';
}
echo '</td></tr></table>';

$temcount = 1;

$start = 0;
if (!empty($_GET['start'])) {
    $start = $_GET['start'];
}

$nombre = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('upanddown_cat') . " WHERE cid = '0' and valid = '1' ");
$row = $GLOBALS['xoopsDB']->fetchRow($nombre);
$message = $row[0];

$nombrescat = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('upanddown_cat') . " WHERE cid > '0' and valid = '1' ");
$rowsc = $GLOBALS['xoopsDB']->fetchRow($nombrescat);
$messagescat = $rowsc[0];

$query = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('upanddown') . " WHERE valid = '1' ");
$solde = $GLOBALS['xoopsDB']->fetchRow($query);

if ($row[0] > 1) {
    $s = '' . _PLURIEL1 . '';
} else {
    $s = '';
}
if ($rowsc[0] > 1) {
    $s = '' . _PLURIEL1 . '';
} else {
    $s = '';
}

echo '<div align="center"><b>' . _ILYA . ' ' . $row[0] . ' ' . _CAT . '' . $s . ' ';
if ($rowsc[0] >= 1) {
    echo "und $messagescat " . _SOUSCAT . '' . $s . '';
}
echo '<br>' . _TOTALIMG . ' ' . $solde[0] . ' ' . _IMG . '' . $s . ' </b></div><br>';
echo '<table align="center" cellspacing="25" border="0"><tr>';
//while($ligne=$GLOBALS['xoopsDB']->fetchObject($result)) {

$pagenav = new XoopsPageNav($message, $nb, $start, 'start', '');

$result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('upanddown_cat') . "  WHERE cid='0' and valid >= '1' limit " . (int)$start . ',' . $nb);
while (list($id, $cid, $cat, $strimg, $coment, $clic, $alea, $valid) = $xoopsDB->fetchRow($result)) {
    echo '<td align="center" width="33%">';

    $ramdom = $xoopsDB->query('SELECT img FROM ' . $xoopsDB->prefix('upanddown') . " WHERE uid='$id' and valid >= '1' ORDER BY RAND()");

    $row = $xoopsDB->getRowsNum($ramdom);

    [$img] = $xoopsDB->fetchRow($ramdom);

    $rowimage = "updown/$cat/$img-ppm";

    echo "<table cellspacing='10' class=\"outer\">" . '<tr>' . "<td align=\"center\"><a href=\"galerie.php?uid=$id\"><b>$cat</b></a> ($row)</a></td>" . '</tr><tr>' . '<td align="center">';

    if (1 != $alea) {
        if (file_exists((string)$strimg)) {
            $size = getimagesize((string)$strimg);

            echo "<a href=\"galerie.php?uid=$id\"><img src=\"$strimg\" $size[3] title=\"" . _CAT . " $cat\"></a>";
        }
    } else {
        if (file_exists((string)$rowimage)) {
            $size = getimagesize((string)$rowimage);

            echo "<a href=\"galerie.php?uid=$id\"><img src=\"$rowimage\" $size[3] title=\"" . _CAT . " $cat\"></a>";
        }
    }

    echo '</td>' . '</tr><tr>' . '<td align="left">';

    $coment = stripslashes((string)$coment);

    //echo "$coment<HR>";

    if (!empty($coment)) {
        echo (string)$coment;
    }

    $option = $xoopsDB->query('SELECT id, cid, cat FROM ' . $xoopsDB->prefix('upanddown_cat') . " WHERE cid='$id' and valid >= '1' ");

    if (!empty($option)) {
        echo '<HR>';
    }

    while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($option))) {
        $catid = $ligne->id;

        $pathrep = $ligne->cid;

        $souscatname = $ligne->cat;

        $nbcat = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('upanddown') . " WHERE uid = '$catid' and valid = '1'");

        $rowscat = $GLOBALS['xoopsDB']->fetchRow($nbcat);

        $tscat = $rowscat[0];

        echo "<a href=\"galerie.php?uid=$catid&rep=$pathrep\">$souscatname</a> ($tscat) &nbsp;";
    }

    echo '</td>' . '</tr>' . '</table><br>';

    echo '</td>';

    if ($temcount == $themepage) {
        echo '</tr><tr>';

        $temcount -= $themepage;
    }

    $temcount++;
}
echo '</td></tr></table>';

//*******Pagination**********
if ($message > $nb) {
    echo '<div align="center">' . $pagenav->renderNav() . '</div>';
}

if (file_exists('galerie_footer.php')) {
    include 'galerie_footer.php';
}
//closeTable();
include '../../footer.php';
?>
