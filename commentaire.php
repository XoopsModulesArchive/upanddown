<?php
//id:magalerie/commentaire.php
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

if ('0' == $magcoment) {
    redirect_header('index.php', 1, '' . _NOPERM . '');

    exit();
}
if (!$xoopsUser) {
    if ('0' == $anonymes) {
        redirect_header('index.php', 1, '' . _NOPERM . '');

        exit();
    }
}

//function commentaire () {
global $xoopsConfig, $xoopsDB, $xoopsModule, $xoopsUser, $xoopsTheme, $lid, $start, $index, $allowhtml, $allowsmileys, $allowbbcode;
include '../../header.php';

if ($xoopsUser) {
    $userid = $xoopsUser->uid();
} else {
    $userid = '0';
}

$nombre = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('upanddown_comments') . " WHERE lid=$lid");
$row = $GLOBALS['xoopsDB']->fetchRow($nombre);
if ($row[0] > 1) {
    $s = '' . _PLURIEL1 . '';

    $ent = '' . _PLURIEL2 . '';

    $x = '' . _PLURIEL3 . '';
} else {
    $s = '';

    $ent = '';

    $x = '';
}

echo '<div align="center">';
echo '<br>';
echo '<b>' . _COMMENTAIRE . ": $row[0] " . _ENTRE . '' . $s . "</b></div>\n";
echo "<br><table border=\"0\" cellspacing=\"1\" cellpadding=\"10\" align=\"center\" class=\"outer\">\n"
     . "<tr class=\"itemHead\">\n"
     . '<td colspan="2"><div class="itemHead"><span class="itemTitle"><b>'
     . _COMMAJOUT
     . '</b></span></div></td>'
     . '</tr><tr class="even">'
     . "<td class=\"bottom\">\n";

echo "<form method=\"post\" action=\"comment2.php?op=ajouter\">\n";
echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">\n";
echo "<input type=\"hidden\" name=\"uid\" value=\"$userid\">\n";
//        echo "<div align=\"center\"><b>"._COMMAJOUT."</b></div>\n";
echo '<p><b>' . _NOM . ":&nbsp;</b>\n";
if ($xoopsUser) {
    $username = $xoopsUser->uname();

    echo "<b>$username</b></p>\n";
} else {
    echo "<b>$xoopsConfig[anonymous]</b> <a href=\"../../register.php\"> (" . _INSCRIPT . ")</a>\n";
}
echo "<p><b>*Titel der Nachricht:</b> <input type=\"text\" name=\"titre\"></p>\n";
echo '</td>';
//                $req = $GLOBALS['xoopsDB']->queryF("SELECT uid, img FROM ".$xoopsDB->prefix("upanddown")." where id='$lid'");
//                 while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($req)))
//                {

//                $cat=multiname($ligne->uid);
//                $linkimg=linkretour($ligne->uid);
//                echo "<input type=\"hidden\" name=\"cat\" value=\"$ligne->cat\">\n";
//                echo "<input type=\"hidden\" name=\"img\" value=\"$ligne->img\">\n";
//                echo "<div align=\"center\">";
//                $image= "upanddown/".$linkimg."".$cat."/$ligne->img-ppm";
//                $size = getimagesize("$image"); //recherche du format de l'image
//                if(file_exists("$image")){
//                echo "<img src=\"$image\" $size[3]>";
//                }else {
//                echo  "<img src=\"images/vignette.gif\" width=\"90\" height=\"70\">";
//                }
//                echo "</div>";
//                }
echo '</tr><tr class="even"><td>';
$test = 'texte';
require_once '../../include/xoopscodes.php';
if (1 == $allowbbcode) {
    xoopsCodeTarea((string)$test, $cols = 50, $rows = 10);
} else {
    echo "<textarea id='texte' name='texte' wrap='virtual' cols='50' rows='10'></textarea><br>";
}
if (1 == $allowsmileys) {
    xoopsSmilies('texte');
}

echo " <p align=\"center\">\n" . ' <input type="submit" name="Submit" value="' . _ENVOITURE . "\">\n" . "</p>\n" . "</form>\n" . "</td>\n" . "</tr>\n" . "</table><br>\n";

include '../../footer.php';
//}//Fin de la function commentaire
