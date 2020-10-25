<?php
#########################################################
#  Copyright © 2002-2004, bidou lespace.org                                      #
#  postmaster@lespace.org - http://www.lespace.org                          #
#                                                                                                  #
#  Licence : GPL                                                                            #
#  Merci de laisser ce copyright en place...                                        #
#########################################################
include 'admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

$gop = 'showedit_cat';

if (!empty($_GET['gop'])) {
    $gop = $_GET['gop'];
}

if ('showedit' == $gop) {
    xoops_cp_header();

    OpenTable();

    echo '<a href="index.php"><b>Administration Index</b></a>';

    if (!empty($uid)) {
        echo " , <a href=\"listing.php?uid=$uid\"><b>Zurück</b></a><br><br>";
    } else {
        echo '<br><br>';
    }

    $val = 'SELECT * FROM ' . $xoopsDB->prefix('upanddown') . " WHERE id='$id' ";

    $result = $xoopsDB->query($val);

    echo '<table width="100%"><tr><td>';

    echo '<b>' . _EDIMG . '</b></td><td align="right">';

    echo "<form method=\"post\" action=\"listing.php?gop=transf&imgid=$id\">";

    echo '' . _MOVECAT . ' Neue ID =&nbsp;';

    $myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object

    $mytree = new XoopsTree($xoopsDB->prefix('upanddown_cat'), 'id', 'cid');

    $mytree->makeMySelBox('cat', 'id', 0, 1, 'newuid', 'submit()');

    echo '</form>';

    echo '</td></tr></table>';

    echo " <form action=\"$PHP_SELF?gop=update\" method=\"post\">";

    while (list($id, $uid, $userid, $img, $clic, $note, $vote, $valid, $date) = $xoopsDB->fetchRow($result)) {
        $catname = multiname($uid);

        $pathimg = linkretour($uid);

        $image = '../updown/' . $pathimg . '' . $catname . "/$img";

        $size = getimagesize((string)$image);

        $size_ppm = getimagesize("$image-ppm");

        $date = formatTimestamp($date, 's');

        //        echo"<input type=\"hidden\" name=\"gop\" value=\"update\">"

        echo "<input type=\"hidden\" name=\"id\" value=\"$id\">" . "<input type=\"hidden\" name=\"date\" value=\"$date\">";

        echo '<table border="0" cellspacing="2" cellpadding="2" align="center">'
             . '<tr><td rowspan=9 valign="top" align="center">'
             . _ID
             . " $id<br><a href='javascript:openWithSelfMain(\"../show-pop.php?id=$id\",\"popup\", $size[0], $size[1]);'><img src=\"$image-ppm\" $size_ppm[3]></a><br>$date</td>"
             . '<td><b>'
             . _NOM
             . ':</b></td><td>';

        echo "<input type=\"hidden\" name=\"uid\" value=\"$userid\">";

        if (0 != $userid) {
            $poster = new XoopsUser($userid);

            $uname = '' . $poster->uname() . '';
        } else {
            $uname = (string)$xoopsConfig[anonymous];
        }

        echo "<a href=\"../../../userinfo.php?uid=$userid\"> $uname</a>";

        echo '</td></tr>'
             . '<tr><td><b>'
             . _CAT
             . ':</b></td><td>'
             . $pathimg
             . ''
             . $catname
             . '</td></tr>'
             . '<tr><td><b>'
             . _IMG
             . ":</b></td><td>$img</td></tr>"
             //."<tr><td><b>"._DESCRIP.":</b></td><td><textarea name=coment style=\"width: 200px; height: 50px;\">$coment</textarea></td></tr>"
             . '<tr><td><b>'
             . _HITS
             . ":</b></td><td><input type=text name=clic value=\"$clic\" maxlength=50 style=\"width: 30px;\"></td></tr>"
             . '<tr><td><b>'
             . _NOT
             . ":</b></td><td><input type=text name=note value=\"$note\" maxlength=50 style=\"width: 30px;\"></td></tr>"
             . '<tr><td><b>'
             . _VOT
             . ":</b></td><td><input type=text name=vote value=\"$vote\" maxlength=50 style=\"width: 30px;\"></td></tr>"
             . '<tr><td><b>'
             . _VISIB
             . ":</b></td><td>\n";

        if (0 != $valid) {
            echo '<p><input type=radio name=valid value="1" checked> ' . _YES . '&nbsp;' . '<input type=radio name=valid value="0"> ' . _NO . '</p>';
        } else {
            echo '<input type="checkbox" name="validemail" value="1"> ' . _VALIDEMAIL . ' ';

            echo '<p><input type=radio name=valid value="1">  ' . _YES . '&nbsp;' . '<input type=radio name=valid value="0" checked> ' . _NO . '</p>';
        }

        echo '</td></tr>' . '</table>';

        echo '<div align="center"><input type=submit value="Update">' //."<a href=\"index.php?cat=$cat\"> "._CLOSE."</a>"
             . '<a href="delete.php?op=confirm_img&id=' . $id . '"> <img src="../images/admin_del.gif"></a></div>' . '</form>';
    }

    CloseTable();

    xoops_cp_footer();
}
////////////////////////////////////////////////////////////////////////////////////////////////////////
if ('showedit_cat' == $gop) {
    xoops_cp_header();

    OpenTable();

    echo '<a href="index.php"><b>Administration &Uuml;bersicht</b></a><br><br>';

    echo '<p><b>' . _EDITCAT . "</b>&nbsp;<a href='javascript:openWithSelfMain(\"../docs/Edit_imag.html\",\"popup\", 480, 400);'>?</a></p>\n";

    echo "<form method=\"post\" action=\"$PHP_SELF\">";

    echo '<b>' . _CHOICAT . '</b>: ';

    $myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object

    $mytree = new XoopsTree($xoopsDB->prefix('upanddown_cat'), 'id', 'cid');

    $mytree->makeMySelBox('cat', 'id', 0, 1, 'id', 'submit()');

    echo '</form>';

    if (!empty($id)) {
        $catname = multiname($id);

        $requete = 'select * from ' . $xoopsDB->prefix('upanddown_cat') . " WHERE id='$id' ";

        $result = $xoopsDB->query($requete);

        while (list($id, $cid, $cat, $img, $coment, $clic, $alea, $valid) = $xoopsDB->fetchRow($result)) {
            echo "<form action=\"$PHP_SELF?gop=updatecat\" method=\"post\">\n"
                 . "<input type=\"hidden\" name=\"id\" value=\"$id\">\n"
                 . "<table border=\"0\" cellpadding=\"6\">\n"
                 . "<tr><td><b>ID:</b></td><td>$id</td></tr>\n"
                 . "<tr><td><b>Kategorie:</b></td><td><input type=hidden name=cid value=\"$cid\" maxlength=50 style=\"width: 200px;\"> $cid: $cat</td></tr>\n"

                 . "<tr><td rowspan=\"2\" valign=\"top\"><b>Datei:</b></td>\n";

            if (0 == $cid) {
                if (1 == $alea) {
                    echo "<td><input type=radio name=alea value=\"1\" checked> <input type=hidden name=\"img\" value=\"\"  style=\"width: 175px;\">Zufallsauswahl.</td>\n";

                    echo "</tr><tr>\n" . "<td><input type=radio name=alea value=\"0\"> <input type=text name=\"img\" value=\"$img\"  style=\"width: 175px;\"></p></td>\n";
                } else {
                    echo "<td><input type=radio name=alea value=\"1\"> Zufallsauswahl.</td>\n";

                    echo "</tr><tr>\n" . "<td><input type=radio name=alea value=\"0\" checked> <input type=text name=\"img\" value=\"$img\"  style=\"width: 175px;\"></p></td>\n";
                }
            } else {
                echo "<td>&nbsp;</td>\n";

                echo "</tr><tr>\n" . "<td>&nbsp;</td>\n";
            }

            echo "</td></tr>\n" . '<tr><td><b>' . _DESCRIP . '</b></td><td>';

            $coment = stripslashes((string)$coment);

            if (0 == $cid) {
                echo "<input type=text name=\"coment\" value=\"$coment\"  style=\"width: 200px;\">";
            } else {
                echo '&nbsp;<input type=hidden name="coment" value="NULL">';
            }

            echo '</td>' . "</tr><tr><td><br><b>Sichtbar:</b></td><td>\n";

            if ('1' == $valid) {
                echo "<p><input type=radio name=valid value=\"1\" checked>Ja\n" . "<input type=radio name=valid value=\"0\">Nein</p>\n";
            } else {
                echo "<p><input type=radio name=valid value=\"1\">Ja\n" . "<input type=radio name=valid value=\"0\" checked>Nein</p>\n";
            }

            echo "</td></tr>\n" . "<tr><td colspan=2><input type=submit value=\"Bestätigen\"></td></tr>\n" . "</table>\n" . "</form>\n";

            echo "<a href=\"delete.php?op=confirm_cat&id=$id\">Diese Kategorie löschen</a>";
        }
    }//fin du if empty

    CloseTable();

    xoops_cp_footer();
}

////////////////////////////////////////////////////////////////////////////////////
if ('update' == $gop) {
    if (!empty($validemail)) {
        if (1 == $validemail) {
            $result = $GLOBALS['xoopsDB']->queryF('SELECT email FROM ' . $xoopsDB->prefix('users') . " WHERE uid='$uid' ");

            while (list($email) = $xoopsDB->fetchRow($result)) {
                if (0 != $valid) { //echo "$email";
                    $subject = $xoopsConfig['sitename'] . ' - ' . _GALIMG;
                }

                $xoopsMailer = getMailer();

                $xoopsMailer->useMail();

                $xoopsMailer->setToEmails($email);

                $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);

                $xoopsMailer->setFromName($xoopsConfig['sitename']);

                $xoopsMailer->setSubject(_NOTIFICATION);

                $xoopsMailer->setBody(_NEW_IMG_COMENT . "\n" . XOOPS_URL . "/modules/upanddown/show.php?id=$id\n\n" . _CONTEST . _IFERREUR);

                $xoopsMailer->send();
            }
        }
    }

    if (!$id) {
        redirect_header($GLOBALS['HTTP_REFERER'] . "&id=$id", 3, _NOPERM);

        exit();
    } elseif (!$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('upanddown') . " SET  clic='$clic', note='$note', vote='$vote', valid='$valid' where id ='$id'")) {
        die('Aktualisieren von UpandDown  nicht möglich!<br>' . $GLOBALS['xoopsDB']->errno() . ': ' . $GLOBALS['xoopsDB']->error());
    }  

    redirect_header($GLOBALS['HTTP_REFERER'] . "&id=$id", 1, '<b> Datenbank wurde erfolgreich aktualisiert!</b>');

    echo (string)$id;

    exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ('edit_comment' == $gop) {
    //function edit_comment() {

    //global $xoopsDB, $id, $cat, $img;

    xoops_cp_header();

    OpenTable();

    include 'admin-menu.php';

    echo '<br>';

    $catid = multiname($uid);

    $pathimg = linkretour($uid);

    $cat = (string)$pathimg . $catid . '';

    $image = "../updown/$cat/$img-ppm";

    $requete = $GLOBALS['xoopsDB']->queryF('select * from ' . $xoopsDB->prefix('upanddown_comments') . " WHERE id='$id' ");

    while (list($id, $lid, $posterid, $titre, $texte, $date) = $xoopsDB->fetchRow($requete)) {
        $myts = MyTextSanitizer::getInstance();

        $titre = stripslashes((string)$titre);

        $titre = htmlspecialchars($titre, ENT_QUOTES | ENT_HTML5);

        $texte = stripslashes((string)$texte);

        $texte = htmlspecialchars($texte, ENT_QUOTES | ENT_HTML5);

        $date = formatTimestamp($date, 's');

        echo '<b>Kommentar bearbeiten.</b>';

        echo "<form method=\"post\" action=\"$PHP_SELF?gop=update_coment\">";

        //$cat=$uid;

        echo "<input type=\"hidden\" name=\"uid\" value=\"$uid\">";

        echo "<input type=\"hidden\" name=\"img\" value=\"$img\">";

        echo '<table cellspacing="2" cellpadding="2" border="0">' . '<tr>';

        echo '<td>ID des Kommentars: </td>' . "<td><input type=\"hidden\" name=\"id\" value=\"$id\">$id</td><td rowspan=\"4\" align=\"center\">";

        if (file_exists((string)$image)) {
            $size = getimagesize($image);

            echo "<a href=\"../show.php?id=$lid\" title=\"" . _GALIMG . "\"><img src=\"$image\" $size[3]></a>";
        }

        echo '</td>';

        echo '</tr><tr>';

        echo '<td>lid:</td>' . "<td>$lid</td>";

        echo '</tr><tr>';

        echo '<td>uid: </td>';

        if (0 != $posterid) {
            $poster = new XoopsUser($posterid);

            $uname = '' . $poster->uname() . '';
        } else {
            $uname = (string)$xoopsConfig[anonymous];
        }

        echo "<td>$uname</td>";

        //echo "<td><input type=\"text\" name=\"uid\" value=\"$uid ".$poster->uname()."\">".$poster->uname()."</td>";

        echo '</tr><tr>';

        echo '<td  colspan="2">&nbsp;</td>';

        echo '</tr><tr>';

        echo '<td>titre: </td>' . "<td  colspan=\"2\"><input type=\"text\" name=\"titre\" value=\"$titre\"></td>";

        echo '</tr><tr>';

        echo '<td>texte: </td>' . "<td  colspan=\"2\"><textarea id='texte' name='texte' wrap='virtual' cols='50' rows='10'>$texte</textarea></td>";

        echo '</tr><tr>';

        echo "<td>date: $date</td>" . '<td  colspan="2"><input type="submit" value="Los!"></td>';

        echo '</tr></table>';

        echo '</form>';
    }

    CloseTable();

    xoops_cp_footer();
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////
if ('update_coment' == $gop) {
    //function update_coment() {

    //global $xoopsDB, $id, $lid, $uid, $titre, $texte, $date, $cat, $img;

    echo "<input type=\"hidden\" name=\"uid\" value=\"$uid\">";

    echo "<input type=\"hidden\" name=\"img\" value=\"$img\">";

    $myts = MyTextSanitizer::getInstance();

    $titre = addslashes((string)$titre);

    $titre = $myts->addSlashes($titre);

    $texte = addslashes((string)$texte);

    $texte = $myts->addSlashes($texte);

    if (!$titre || !$texte) {
    } elseif (!$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('upanddown_comments') . " SET titre='$titre', texte='$texte' WHERE id='$id' ")) {
        die('Aktualisierung von UpandDown nicht möglich!<br>' . $GLOBALS['xoopsDB']->errno() . ': ' . $GLOBALS['xoopsDB']->error());
    } else {
        redirect_header('edit.php?gop=edit_comment&id=' . $id . '&uid=' . $uid . "&img=$img", 1, 'Datenbank wurde erfolgreich aktualisiert!');
    }

    exit();
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//showedit_cat();

if ('updatecat' == $gop) {
    //function updatecat() {

    if ('' != $coment) {
        $coment = addslashes((string)$coment);
    }

    if (!$id) {
    } elseif (!$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('upanddown_cat') . " SET img='$img', coment='$coment', alea='$alea', valid='$valid' WHERE id='$id'")) {
        die('Aktualisierung von UpandDown nicht möglich!<br>' . $GLOBALS['xoopsDB']->errno() . ': ' . $GLOBALS['xoopsDB']->error());
    } else {
        redirect_header('edit.php?id=' . $id . '', 1, 'Datenbank wurde erfolgreich aktualisiert!');
    }

    exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
switch ($gop) {
   case 'update_coment':
      update_coment();
      break;

   case 'edit_comment':
      edit_comment();
      break;

   case 'updatecat':
      updatecat();
      break;

   default:
      showedit_cat();
      break;

      }
      */
