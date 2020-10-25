<?php
//id:magalerie/appercu.php
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
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
if ('0' == $validup) {
    redirect_header('index.php', 1, '' . _NOPERM . '');

    exit();
}
if (!$xoopsUser) {
    if ('0' == $anonymes) {
        redirect_header('index.php', 1, '' . _NOPERM . '');

        exit();
    }
}

//function ajoutimg(){

//global $xoopsUser, $xoopsDB, $xoopsConfig, $xoopsTheme, $catid, $cat, $photomax, $allowsmileys, $allowbbcode;
include $xoopsConfig['root_path'] . 'header.php';
$catname = multiname($uid);
$pathimg = linkretour($uid);
$cat = '' . $pathimg . '' . $catname . '';
$photomax1 = $photomax / 1024;

//OpenTable();

echo '<div align="center"><b>' . _AJOUTER . ' ' . $cat . "</b></div>\n";
echo "<br>\n";
echo "<table align=\"center\" class=\"outer\">\n"
     . "<tr>\n"
     . '<td colspan="2" width="100%">'
     . '<div class="itemHead"><span class="itemTitle"><b>'
     . _COMPL
     . '</b></span></div>'
     . "</tr>\n"
     . "<tr class=\"even\">\n"
     . '<td width="25%" class="even">'
     . _CHOISIRCAT
     . "</td>\n"
     . "<td width=\"70%\" class=\"even\">\n";
//list_cat("formulaire.php",""._LISTCAT."");
echo "<form method=\"post\" action=\"$PHP_SELF\">";
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix('upanddown_cat'), 'id', 'cid');
$mytree->makeMySelBox('cat', 'id', 0, 1, 'uid', 'submit()');
echo '</form>';

echo "<form method=\"post\" action=\"Addimg.php\" ENCTYPE=\"multipart/form-data\" NAME=\"add\">\n";
//."<input type=\"hidden\" name=\"go\" value=\"Addimg\">\n";
echo "</td>\n" . "</tr>\n" . "<tr class=\"even\">\n" . '<td>' . _CHOISIPAYS . "</td>\n" . "<td><input type=\"hidden\" name=\"cat\" value=\"$cat\"><input type=\"hidden\" name=\"uid\" value=\"$uid\"><b>" . $cat . "</b>\n";
echo "</td></tr>\n" . "<tr class=\"even\">\n" . '<td>' . _IMGFILE . ':<br>' . _LIMIT . '<br>';
printf('%.2f Kb', $photomax1);
echo "</td>\n" . '<td><INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="10000000"><input type=file name="photo">';
echo "</td>\n";
echo "</tr>\n";
echo '</table>';

echo '<br><div align="center"><b>Kommentar veröffentlichen</b> (optional)</div><br>';
echo '<table align="center" cellspacing="2" cellpadding="4" class="outer">';
echo "<tr class=\"itemHead\">\n" . '<td colspan="2"><div class="itemHead"><span class="itemTitle"><b>' . _COMMAJOUT . "</b></span></div></td>\n" . '</tr><tr class="even"><td><b>' . _NOM . '</b></td><td>';
if ($xoopsUser) {
    $nom = $xoopsUser->uname();

    echo "$nom</td>";
} else {
    $nom = 'Anonym';

    echo "$nom <a href=\"../../register.php\"> (" . _INSCRIPT . ")</a></td>\n";
}
echo "</tr>\n";
echo "<tr class=\"even\">\n" . '<td>' . _NOMCAM . "</td>\n" . "<td><input type=\"text\" name=\"titre\"></td>\n";

echo "</tr>\n" . "<tr class=\"even\">\n" . '<td valign="top">' . _DESCRIPTCAM . "</td>\n" . "<td><br>\n";
include '../../include/xoopscodes.php';
if (1 == $allowbbcode) {
    xoopsCodeTarea('coment', $cols = 50, $rows = 10);
} else {
    echo "<textarea id='coment' name='coment' wrap='virtual' cols='50' rows='10'></textarea><br>";
}
if (1 == $allowsmileys) {
    xoopsSmilies('coment');
}
echo "</td>\n";
echo "</tr>\n";

echo "</table>\n";
echo '<div align="center"><br><input type="submit" name="add" value="' . _ENVOITURE . "\">&nbsp;&nbsp;</div>\n";
echo "</form>\n";
echo "<br><br>\n";
echo '<br>';

//CloseTable();
include '../../footer.php';
//}//fin validup
//}//fin function
