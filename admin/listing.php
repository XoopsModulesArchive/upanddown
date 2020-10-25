<?php
#####################################################
#  Auteur, bidou http://www.lespace.org  © 2002 ,2004    #
#  postmaster@lespace.org - http://www.lespace.org  #
#                                                   #
#  Licence : GPL                                    #
#  Merci de laisser ce copyright en place...        #
#####################################################
include 'admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
$ModName = 'upanddown';

$gop = 'index';

if (!empty($_GET['gop'])) {
    $gop = $_GET['gop'];
}

if ('index' == $gop) {
    xoops_cp_header();

    OpenTable();

    require XOOPS_ROOT_PATH . '/modules/upanddown/class/xoopspagenav.php';

    echo '<a href="index.php"><b>Administration &Uuml;bersicht</b></a><br><br>';

    echo '<br><table width="100%" cellspacing="0" cellpadding="0" border="0">' . '<tr><td width="20%">';

    echo "<form method=\"post\" action=\"$PHP_SELF\">";

    echo '<b>' . _CHOICAT . '</b>: ';

    $myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object

    $mytree = new XoopsTree($xoopsDB->prefix('upanddown_cat'), 'id', 'cid');

    $mytree->makeMySelBox('cat', 'id', 0, 1, 'uid', 'submit()');

    echo '</form>';

    $row = $GLOBALS['xoopsDB']->queryF('SELECT id FROM ' . $xoopsDB->prefix('upanddown') . ' ');

    $nbt = $xoopsDB->getRowsNum($row);

    if ($row > 1) {
        $s = '' . _PLURIEL1 . '';
    }

    echo '</td><td width="60%" align="center">' . _NBT . ' ' . $nbt . ' ' . _IMG . '' . $s . '</td>' . '<td width="20%">';

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

    echo '</td>' . '</tr></table>';

    if (!empty($uid)) {
        $temcount = 1;

        $start = 0;

        if (!empty($_GET['start'])) {
            $start = $_GET['start'];
        }

        $orderby = 'dateD';

        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        $mysqlorderby = convertorderbyin($orderby);

        $orderbyTrans = convertorderbytrans($mysqlorderby);

        $linkorderby = convertorderbyout($orderby);

        $nblist = 10;

        $nombre = $xoopsDB->query('SELECT COUNT(id) FROM ' . $xoopsDB->prefix('upanddown') . " WHERE uid='$uid' ");

        $result = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('upanddown') . " WHERE uid='$uid' ORDER BY $mysqlorderby limit " . (int)$start . ',' . $nblist);

        $nbresult = $GLOBALS['xoopsDB']->fetchRow($nombre);

        $message = $nbresult[0];

        $pagenav = new XoopsPageNav($message, $nblist, $start, 'start', 'uid=' . $uid . '&orderby=' . $orderby . '');

        $catname = multiname($uid);

        $pathimg = linkretour($uid);

        echo "<div align=\"center\"><b>$nbresult[0]</b> " . _RESULT . " <b>$catname</b><br><br>";

        echo '' . _MD_SORTBY . '&nbsp;&nbsp;

              ' . _MD_DATE . " (<a href='index.php?uid=" . $uid . "&orderby=dateA'><img src=\"../images/up.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a><a href='index.php?uid=$uid&orderby=dateD'><img src=\"../images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
              " . _MD_RATING . " (<a href='index.php?uid=$uid&orderby=voteA'><img src=\"../images/up.gif\" width=\"11\" height=\"14\"  border=\"0\" align=\"middle\"></a><a href='index.php?uid=$uid&orderby=voteD'><img src=\"../images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
              " . _MD_POPULARITY . " (<a href='index.php?uid=$uid&orderby=hitsA'><img src=\"../images/up.gif\" width=\"11\" height=\"14\"  border=\"0\" align=\"middle\"></a><a href='index.php?uid=$uid&orderby=hitsD'><img src=\"../images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
                      ";

        echo '<b><br>';

        printf(_MD_CURSORTEDBY, (string)$orderbyTrans);

        echo '</b></div>';

        if (!$GLOBALS['xoopsDB']->getRowsNum($result)) { //echo ""._CHOICAT."\n";
            echo '<br>';
        }

        echo '<br><table border="0" cellspacing="0" cellpadding="0" width="100%"><tr>';

        while (false !== ($val = $GLOBALS['xoopsDB']->fetchBoth($result))) {
            $image = '../updown/' . $pathimg . '' . $catname . "/$val[img]";

            if (file_exists((string)$image)) {
                $size = getimagesize((string)$image);
            }

            if (file_exists("$image-ppm")) {
                $size2 = getimagesize("$image-ppm");
            }

            echo '<td align="center">'
                 . "<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" class=\"bg2\" width=\"100%\">\n"
                 . "<tr class=bg3>\n"
                 . "<td align=\"center\"><b> $val[img]</b></td>\n"
                 . '<td align=center><b>'
                 . _SEE
                 . "</b></td>\n"
                 . '<td align=center><b>'
                 . _DIT
                 . "</b></td>\n"
                 . '<td align=center><b>'
                 . _SUPP
                 . "</b></td>\n"
                 . "</tr>\n";

            echo "<tr class=\"bg4\">\n"
                 . "<td rowspan=\"2\" width=\"180\" align=\"center\"><img src=\"$image-ppm\"></td>\n"
                 //."<td rowspan=\"2\" valign=\"top\">&nbsp;</td>\n"
                 . '<td align="center" valign="top">'
                 . "&nbsp;<a href='javascript:openWithSelfMain(\"../show-pop.php?id=$val[id]\",\"image\",$size[0],$size[1]);'><img src=\"../images/admin_show.gif\" border=0 alt=Ansehen></a>"
                 . "</td>\n"
                 . '<td align="center" valign="top" height="20"><a href="edit.php?gop=showedit&id='
                 . $val['id']
                 . "&uid=$uid\">"
                 . "<img src=\"../images/admin_edit.gif\" border=0 alt=Bearbeiten></a></td>\n";

            echo '<td align="center" valign="top" height="20">';

            echo "<form method=\"post\" action=\"delete.php?op=confirm_img\">\n";

            //echo "<input type=\"hidden\" name=\"op\" value=\"confirm_img\">\n";

            echo '<input type="hidden" name="id" value="' . $val['id'] . "\">\n";

            echo "<input type=\"hidden\" name=\"uid\" value=\"$uid\">\n";

            echo "<input type=\"hidden\" name=\"image\" value=\"$image\">\n";

            echo '<input type="image" src="../images/admin_del.gif" border="0" alt="L&ouml;schen">';

            echo "</form>\n";

            echo "</td>\n" . "</tr>\n";

            echo '<tr><td colspan="4" valign="top" class="bg4">';

            echo "<form method=\"post\" action=\"$PHP_SELF?gop=transf&imgid=" . $val['id'] . '">';

            echo '' . _MOVECAT . '';

            $myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object

            $mytree = new XoopsTree($xoopsDB->prefix('upanddown_cat'), 'id', 'cid');

            $mytree->makeMySelBox('cat', 'id', 0, 1, 'newuid', 'submit()');

            echo '</form>';

            echo '<b>' . _CAT . ":</b> $catname<br>" . '<b>ID:</b> ' . $val['id'] . '<br>' . '<b>' . _HITS . ":</b> $val[clic]<br>" . '<b>' . _VOT . ":</b> $val[note]<br>";

            if ('1' == $val['id']) {
                $valid = _YES;
            } else {
                $valid = _NO;
            }

            echo '<b>' . _VISIB . ":</b> $valid</td></tr>";

            echo "</table><br></td>\n";

            if (2 == $temcount) {
                echo '</tr><tr>';

                $temcount -= 2;
            }

            $temcount++;
        }

        echo "</tr></table>\n";

        echo '<div align=center><br>' . $pagenav->renderNav() . '</div>';
    }//fin du if empty

    CloseTable();

    xoops_cp_footer();
}

if ('transf' == $gop) {
    echo "newuid=$newuid imgid=$imgid";

    $modif = 'select uid, img from ' . $xoopsDB->prefix('upanddown') . " where id='$imgid' ";

    $real = $xoopsDB->query($modif);

    while (list($uid, $img) = $xoopsDB->fetchRow($real)) {
        $old_pathimg = linkretour($uid);

        $old_cat = multiname($uid);

        $new_pathimg = linkretour($newuid);

        $new_cat = multiname($newuid);

        if (!$newuid || !$imgid) {
            redirect_header($GLOBALS['HTTP_REFERER'], 3, _NOPERM);

            exit();
        } elseif (!$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('upanddown') . " SET  uid='$newuid' where id ='$imgid'")) {
            die('Aktualisierung von UpandDown nicht möglich!<br>' . $GLOBALS['xoopsDB']->errno() . ': ' . $GLOBALS['xoopsDB']->error());
        }  

        $photo = XOOPS_ROOT_PATH . '/modules/upanddown/updown/' . $old_pathimg . '' . $old_cat . "/$img";

        $destination = XOOPS_ROOT_PATH . '/modules/upanddown/updown/' . $new_pathimg . '' . $new_cat . "/$img";

        copy((string)$photo, $destination);

        $photo2 = XOOPS_ROOT_PATH . '/modules/upanddown/updown/' . $old_pathimg . '' . $old_cat . "/$img-ppm";

        $destination2 = XOOPS_ROOT_PATH . '/modules/upanddown/updown/' . $new_pathimg . '' . $new_cat . "/$img-ppm";

        copy((string)$photo2, $destination2);

        $Fnm = XOOPS_ROOT_PATH . '/modules/upanddown/updown/' . $old_pathimg . '' . $old_cat . "/$img";

        $Fnm2 = XOOPS_ROOT_PATH . '/modules/upanddown/updown/' . $old_pathimg . '' . $old_cat . "/$img-ppm";

        unlink($Fnm);

        unlink($Fnm2);

        redirect_header("listing.php?uid=$newuid", 1, "<b>$img Datenbank wurde erfolgreich aktualisiert!</b>");

        exit();
    }
}
