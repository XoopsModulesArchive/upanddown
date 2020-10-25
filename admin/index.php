<?php
#####################################################
#  Auteur, bidou http://www.lespace.org  © 2002     #
#  postmaster@lespace.org - http://www.lespace.org  #
#                                                   #
#  Licence : GPL                                    #
#  Merci de laisser ce copyright en place...        #
#####################################################
include 'admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
$ModName = 'upanddown';
xoops_cp_header();
OpenTable();

echo '<table width="100%" border="0"><tr><td width="70%">';
echo '<h3>UpandDown Administration</h3></td><td>';

$req = $GLOBALS['xoopsDB']->queryF('SELECT id FROM ' . $xoopsDB->prefix('upanddown') . " where valid='0' ");
$nbval = $xoopsDB->getRowsNum($req);

if ($nbval > 1) {
    $s = '' . _PLURIEL1 . '';
} else {
    $s = '';
}
echo "$nbval " . _ATTENTE . '' . $s . '<br>';
while (list($valid) = $xoopsDB->fetchRow($req)) {
    echo '<a href="edit.php?gop=showedit&id=' . $valid . '"><img src="../images/admin_edit.gif" border=0 title=' . _EDIT . '></a>&nbsp;';
}
echo '</td></tr></table><HR>';

echo '<a href="menuconf.php">Konfiguration</a><HR>';

echo '<table width="100%"><tr><td width="200">';
echo 'Dateien auflisten</td><td>';
echo '';

echo '<form method="post" action="listing.php">';
echo '<b>' . _CHOICAT . '</b><br> ';
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix('upanddown_cat'), 'id', 'cid');
$mytree->makeMySelBox('cat', 'id', 0, 1, 'uid', 'submit()');
echo '</form> ';
echo '</td></tr></table><HR>';

echo '<table width="100%"><tr><td width="200">';
echo 'Kategorien bearbeiten </td><td>';
echo '<form method="post" action="edit.php">';
echo '<b>' . _CHOICAT . '</b>:<br> ';
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix('upanddown_cat'), 'id', 'cid');
$mytree->makeMySelBox('cat', 'id', 0, 1, 'id', 'submit()');
echo '</form>';
echo '</td></tr></table><HR>';

echo '<a href="charge.php">Kategorien/Dateien hinzufügen (Automatische Aktualisierung)</a><HR>';

echo '<table width="100%"><tr><td width="200">';
echo 'Datei bearbeiten</td><td>';
echo '<form method="post" action="edit.php?gop=showedit">';
echo '<b>ID:</b> <input type="text" name="id"style="width:30px">&nbsp;<input type="submit" value="Los!">';
echo '</form>';
echo '</td></tr></table><HR>';
CloseTable();
xoops_cp_footer();
