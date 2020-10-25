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

include '../../mainfile.php';

function ajouter()
{
    global $xoopsConfig, $xoopsDB, $xoopsUser, $sendmail, $cat, $img, $lid, $uid, $titre, $texte;

    $myts = MyTextSanitizer::getInstance();

    $this_date = time();

    $titre = addslashes((string)$titre);

    $titre = $myts->addSlashes($titre);

    $texte = addslashes((string)$texte);

    $texte = $myts->addSlashes($texte);

    if ($xoopsUser) {
        $userid = $xoopsUser->uid();
    } else {
        $userid = '0';
    }

    if (!$titre || !$texte) {
        echo '<div align="center"><b>' . _ERREURCOMM . "</b><br><br>\n";

        echo '<input type="button" value="' . _PREC . "\" onclick=\"javascript:history.go(-1)\"></div>\n";
    } else {
        $GLOBALS['xoopsDB']->queryF('insert into ' . $xoopsDB->prefix('upanddown_comments') . " (lid, uid, titre, texte, date) values ('$lid', '$userid', '$titre', '$texte', '$this_date')") || die('' . _ORDIE . '');

        if (1 == $sendmail) {
            $subject = $xoopsConfig['sitename'] . ' - ' . _MODULE_NAME;

            $xoopsMailer = getMailer();

            $xoopsMailer->useMail();

            $xoopsMailer->setToEmails($xoopsConfig['adminmail']);

            $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);

            $xoopsMailer->setFromName($xoopsConfig['sitename']);

            $xoopsMailer->setSubject($subject);

            $xoopsMailer->setBody(_NEWCOMENT . "\n\n" . XOOPS_URL . "/modules/upanddown/show.php?id=$lid");

            $xoopsMailer->send();
        }

        redirect_header("show.php?id=$lid", 1, '' . _MERCI . '');

        exit();
    }
}//Fin de la function ajouter

function confirm()
{
    global $xoopsConfig, $xoopxDB, $xoopsTheme, $id, $lid;

    echo "<form method=\"post\" action=\"comment2.php\">\n";

    echo "<input type=\"hidden\" name=\"op\" value=\"delete\">\n";

    echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

    echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">\n";

    echo '<div align="center"><p><b>' . _CONFIRM . "</b></p><br>\n";

    echo '<input type="submit" value="' . _YES . "\">&nbsp\n";

    echo '<input type="button" value="' . _NO . "\" onclick=\"history.go(-1)\"></div>\n";

    echo "</form>\n";
}//Fin de la function confirm

//confirm();
function delete()
{
    global $xoopsUser, $xoopsDB, $id, $lid;

    if ($xoopsUser) {
        if ($xoopsUser->isAdmin()) {
            $supp = 'DELETE FROM ' . $xoopsDB->prefix('upanddown_comments') . " WHERE id=$id";

            $GLOBALS['xoopsDB']->queryF($supp);

            echo $GLOBALS['xoopsDB']->error();
        }
    }

    redirect_header("show.php?id=$lid", 1, _AJOUR);

    exit();
}//Fin de la function delete

//closeTable();
//include ("../../footer.php");

switch ($op) {
    //        default:
    //                commentaire();
    //                      break;

    case 'ajouter':
        ajouter();
        break;
    case 'confirm':
        confirm();
        break;
    case 'delete':
        delete();
        break;
}
