<?php
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
$requete = $GLOBALS['xoopsDB']->queryF('select id, uid, img from ' . $xoopsDB->prefix('upanddown') . " WHERE id = '$id' ");
while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($requete))) {
    $uid = $ligne->uid;

    $img = $ligne->img;

    $cat = multiname($uid);

    $pathimg = linkretour($uid);

    $img = 'updown/' . $pathimg . '' . $cat . '/' . $img . '';
}
$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('upanddown') . " SET clic=clic+1 WHERE id='$id'");

echo "<html>\n" . " <head><title>UpandDown</title></head>\n" . " <body style=\"background-color: #FFFFFF; vertical-align: middle; text-align: center; margin: 0;\">\n" . "  <center>\n" . "<img src=\"$img\">\n" . "  </center>\n" . " </body>\n" . "</html>\n";
