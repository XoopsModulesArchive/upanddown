<?php
//id:magalerie/show.php
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

//global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsTheme, $id, $cat, $img, $allowbbcode, $allowhtml, $allowsmileys, $magcoment, $anonymes, $start, $ecard, $nbms, $popup;
require XOOPS_ROOT_PATH . '/header.php';
//require XOOPS_ROOT_PATH."/class/xoopsuser.php";
?>
    <style>
        .magshowinfo {
            border: 2px solid #00639C;
            width: 450px
        }

        .navig {
            width: 150px
        }
    </style>
<?php
$requete = $GLOBALS['xoopsDB']->queryF('select * from ' . $xoopsDB->prefix('upanddown') . " WHERE id = '$id' ");
while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($requete))) {
    $img_id = $ligne->id;

    $uid = $ligne->uid;

    $userid = $ligne->userid;

    $img = $ligne->img;

    //$cat = checkname($uid);

    $clic = $ligne->clic;

    $row_clic = $clic += '1';

    $note = $ligne->note;

    $vote = $ligne->vote;

    //$desc = $ligne->coment;

    $count = 7;

    $startdate = (time() - (86400 * $count));

    if ($startdate < $ligne->date) {
        if (1 == $ligne->valid) {
            $nouveau = '&nbsp;<img src="' . XOOPS_URL . '/modules/upanddown/images/new.gif" title="' . _MD_NEWTHISWEEK . '">';
        }
    }

    $cat = multiname($uid);

    $pathimg = linkretour($uid);

    $image = 'updown/' . $pathimg . '' . $cat . '/' . $img . '';

    $date = formatTimestamp($ligne->date, 's');

    $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('upanddown') . " SET clic = $row_clic WHERE id='$img_id' ");
}
echo '<b><a href="index.php">' . _CAT . ":</a><a href=\"galerie.php?uid=$uid\"> " . $pathimg . '' . $cat . '</a></b>';
echo '<div align="center">';
include 'navig_cat_show.php';

if (file_exists((string)$image)) {
    if ('.doc' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.DOC' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 5), 5)) {
        echo '<p><img src="images/word.gif" width="70" height="70"></p>';

        echo '<p>Word-Datei</p>';
    } elseif ('.pdf' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.PDF' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 5), 5)) {
        echo '<p><img src="images/acrobat.gif" width="70" height="70"></p>';

        echo '<p>PDF-Datei</p>';
    } elseif ('.xls' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.XLS' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 5), 5)) {
        echo '<p><img src="images/excel.gif" width="70" height="70"></p>';

        echo '<p>Excel-Datei</p>';
    } elseif ('.ppt' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.PPT' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 5), 5)) {
        echo '<p><img src="images/powerpoint.gif" width="70" height="70"></p>';

        echo '<p>Powerpoint-Datei</p>';
    } elseif ('.rtf' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.RTF' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 5), 5)) {
        echo '<p><img src="images/rtf.gif" width="70" height="70"></p>';

        echo '<p>RTF-Datei</p>';
    } else {
        $size = getimagesize((string)$image);

        echo "<p><img src=\"$image\" $size[3]></p>";
    }
} else {
    $size = getimagesize($image);

    $image = resize((string)$image, (string)$showmax);

    echo " <p align=\"center\"><img src='$image' height='$sm_hauteur' width='$sm_largeur' border='0'></p>";
}

include 'navig_cat_show.php';

if ($userid > 0) {
    $postimg = new XoopsUser($userid);

    if ($postimg->isActive()) {
        if (1 == $postimg->uid()) {
            $postername = (string)$xoopsConfig[sitename];

            echo '<br>';
        } else {
            $postername = '<a href="../../userinfo.php?uid=' . $postimg->uid() . '"> ' . $postimg->uname() . '</a>';

            echo '<br>' . _MGPOSTER . " $postername<br><br>";
        }
    }
} else {
    $postername = (string)$xoopsConfig[anonymous];

    echo '<br>' . _MGPOSTER . " $postername<br><br>";
}

echo '<table border="0" cellspacing="0" cellpadding="0" align="center" class="magshowinfo">' . '<tr>' . '<td class="even"><b>Download:</b> <b><a href="' . XOOPS_URL . "/modules/upanddown/updown/$pathimg$cat/$img\">$img</a></b> $nouveau</td>";
echo '<td class="even" width="40%"><b>' . _CLICS . '';
if ($clic >= 2) {
    echo '' . _PLURIEL1 . '';
}
echo ":</b> $clic";
if ($clic >= 50) {
    echo '&nbsp;<img src ="' . XOOPS_URL . '/modules/upanddown/images/pop.gif">';
}
echo '</td>' //        ."<td rowspan=\"4\" valign=\"top\" class=\"even\"><center><b>"._DESCRIP.":</b></center> &nbsp;&nbsp;$desc</td>"
     . '</tr>' . '<tr>' . '<td class="even"><b>' . _FORMAT . ":</b> $size[0] x $size[1]</td>" . '<td class="even"><b>' . _RATE . '';
if ($vote >= 2) {
    echo '' . _PLURIEL1 . '';
}
echo ":</b> $vote ";
if (0 != $vote) {
    $diff = floor(($note / $vote));

    if ($diff > 0) {
        $rank_img = 'rank3dbf8e94a6f70.gif';
    }

    if ($diff > 2) {
        $rank_img = 'rank3dbf8e9e7d88d.gif';
    }

    if ($diff > 4) {
        $rank_img = 'rank3dbf8ea81e642.gif';
    }

    if ($diff > 6) {
        $rank_img = 'rank3dbf8eb1a72e7.gif';
    }

    if ($diff > 8) {
        $rank_img = 'rank3dbf8edf15093.gif';
    }

    if ($diff > 9) {
        $rank_img = 'rank3dbf8ee8681cd.gif';
    }
}
//$rank_size = getimagesize("$rank_img");
if (0 != $vote) {
    echo "&nbsp;<img src=\"../../uploads/$rank_img\" width=\"73\" height=\"13\" title=\"$note " . _POINTS . '"></td>';
} else {
    echo '&nbsp;<img src="../../uploads/rank3e632f95e81ca.gif" width="73" height="13">';
}

echo '</td>' . '</tr>' . '<tr>' . '<td  class="even"><b>' . _CAT . ':</b> ' . $pathimg . "$cat</td>" . '<td class="even" align="center">';
//        if ( $popup!=0){
//        echo "<a href='javascript:openWithSelfMain(\"show-pop.php?id=$id\",\"popup\",$size[0],$size[1])'><img src=\"images/print.gif\" width=\"14\" height=\"11\" title=\""._IMPRIM."\"></a>&nbsp;&nbsp;&nbsp;";
//        }
//        if ( $ecard!=0){
//        echo "<a href=\"carte.php?id=$id\"><img src=\"images/friend.gif\" width=\"14\" height=\"11\" title=\""._ENVOY."\"></a>";
//        }
echo '</td></tr>' . '</table>';

echo '<form method="post" action="vote.php">';
//."<input type=\"hidden\" name=\"op\" value=\"vote\">\n";
echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
echo '<input type="hidden" name="cat" value="' . $pathimg . "$cat\">";
echo "<br><b>Datei bewerten:</b> <select name=\"note\" onChange='submit()'>"
     . '<option selected>--</option>'
     . '<option value="1">1</option>'
     . '<option value="2">2</option>'
     . '<option value="3">3</option>'
     . '<option value="4">4</option>'
     . '<option value="5">5</option>'
     . '<option value="6">6</option>'
     . '<option value="7">7</option>'
     . "<option value=\"8\">8</option>\n"
     . "<option value=\"9\">9</option>\n"
     . "<option value=\"10\">10</option>\n"
     . "</select>\n";

$nombre = $GLOBALS['xoopsDB']->queryF('SELECT count(*) as id from ' . $xoopsDB->prefix('upanddown_comments') . " WHERE lid=$id");
$row = $GLOBALS['xoopsDB']->fetchRow($nombre);
if ($row[0] > 1) {
    $s = '' . _PLURIEL1 . '';
} else {
    $s = '';
}
if ('1' == $magcoment) {
    if ($xoopsUser) {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><a href=\"commentaire.php?lid=$id\" title=\"" . _COMMAJOUT . '">' . _COMMENTAIRE . '' . $s . "</a>: $row[0] " . _ENTRE . '' . $s . "</b>\n";
    } else {
        if ('1' == $anonymes) {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><a href=\"commentaire.php?lid=$id\" title=\"" . _COMMAJOUT . '">' . _COMMENTAIRE . '' . $s . "</a>: $row[0] " . _ENTRE . '' . $s . "</b>\n";
        }
    }
}
echo "</form><br>\n";

if ($row[0] < 1) {
    $magcoment = 0;
}

if (1 == $magcoment) {
    require XOOPS_ROOT_PATH . '/modules/upanddown/class/xoopspagenav.php';

    openComment();

    $message = $row[0];

    //        if(!isset($start)) $start=0;

    $start = 0;

    if (!empty($_GET['start'])) {
        $start = $_GET['start'];
    }

    $pagenav = new XoopsPageNav($message, $nbms, $start, 'start', "id=$img_id");

    $q = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('upanddown_comments') . " WHERE lid=$id limit " . (int)$start . ',' . $nbms);

    while (false !== ($value = $GLOBALS['xoopsDB']->fetchObject($q))) {
        $usercoment = $value->uid;

        if (0 != $usercoment) {
            $poster = new XoopsUser($usercoment);

            if (!$poster->isActive()) {
                $posteruser = 0;
            } else {
                $posteruser = 1;
            }

            $uname = $poster->uname();

            $rank = $poster->rank();

            $posts = $poster->posts();

            $user_from = $poster->user_from();

            $reg_date = _JOINED;

            $reg_date .= formatTimestamp($poster->user_regdate(), 's');

            $posts = _POSTS;
        } else {
            $uname = (string)$xoopsConfig[anonymous];

            $rank = '';

            $rank['title'] = '';

            $posts = '';

            $reg_date = '';

            $posteruser = 0;

            $user_from = '';
        }

        if (0 != $posteruser) {
            $rank['image'] = "<img src='" . XOOPS_URL . '/uploads/' . $rank['image'] . "'>";
        } else {
            $rank['image'] = '';
        }

        if (0 != $posteruser) {
            //if ( $poster->user_avatar() != "" && $posteruser!=0 ) {

            $avatar_image = "<img src='" . XOOPS_URL . '/uploads/' . $poster->user_avatar() . "' title=''>";
        } else {
            $avatar_image = '';
        }

        if (0 != $posteruser) {
            $online_image = "<span style='color:#ee0000;font-weight:bold;'>" . _MD_ONLINE . '</span>';
        } else {
            $online_image = '';
        }

        if (0 != $posteruser) {
            $profile_image = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $poster->uid() . "'><img src='" . XOOPS_URL . "/images/icons/profile.gif' title='" . _PROFILE . "'></a>";
        } else {
            $profile_image = '';
        }

        if (0 != $posteruser) {
            $pm_image = "<a href=\"javascript:openWithSelfMain('" . XOOPS_URL . '/pmlite.php?send2=1&amp;to_userid=' . $poster->uid() . "','pmlite',360,400);\"><img src='" . XOOPS_URL . "/images/icons/pm.gif' title='" . sprintf(_SENDPMTO, $poster->uname()) . "'></a>";
        } else {
            $pm_image = '';
        }

        if (0 != $posteruser) {
            $email_image = "<a href='mailto:" . $poster->email() . "'><img src='" . XOOPS_URL . "/images/icons/email.gif' title='" . sprintf(_SENDEMAILTO, $poster->uname()) . "'></a>";
        } else {
            $email_image = '';
        }

        if (0 != $posteruser) {
            $www_image = "<a href='" . $poster->url() . "' target='_blank'><img src='" . XOOPS_URL . "/images/icons/www.gif' title='" . _VISITWEBSITE . "' target='_blank'></a>";
        } else {
            $www_image = '';
        }

        if (0 != $posteruser) {
            $icq_image = "<a href='http://wwp.icq.com/scripts/search.dll?to=" . $poster->user_icq() . "'><img src='" . XOOPS_URL . "/images/icons/icq_add.gif' title='ICQ'></a>";
        } else {
            $icq_image = '';
        }

        if (0 != $posteruser) {
            $aim_image = "<a href='aim:goim?screenname=" . $poster->user_aim() . '&message=Hi+' . $poster->user_aim() . "+Are+you+there?'><img src='" . XOOPS_URL . "/images/icons/aim.gif' title='AIM'></a>";
        } else {
            $aim_image = '';
        }

        if (0 != $posteruser) {
            $yim_image = "<a href='http://edit.yahoo.com/config/send_webmesg?.target=" . $poster->user_yim() . "&.src=pg'><img src='" . XOOPS_URL . "/images/icons/yim.gif' title='YIM'></a>";
        } else {
            $yim_image = '';
        }

        if (0 != $posteruser) {
            $msnm_image = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $poster->uid() . "'><img src='" . XOOPS_URL . "/images/icons/msnm.gif' title='MSNM'></a>";
        } else {
            $msnm_image = '';
        }

        if ($xoopsUser) {
            $adminview = $xoopsUser->isAdmin();
        } else {
            $adminview = 0;
        }

        if ($adminview) {
            $delete_image = "<a href='comment2.php?op=confirm&id=" . $value->id . '&lid=' . $img_id . "'><img src='" . XOOPS_URL . "/images/icons/delete.gif' title='" . _XTG_DELETEPOST . "'></a>";

            $edit_image = "<a href='admin/edit.php?gop=edit_comment&id=" . $value->id . "&uid=$uid&img=" . $img . "'><img src='" . XOOPS_URL . "/images/icons/edit.gif' border='0'></a>";
        } else {
            $delete_image = '';

            $edit_image = '';
        }

        $myts = MyTextSanitizer::getInstance();

        $titre = stripslashes((string)$value->titre);

        $titre = htmlspecialchars($titre, ENT_QUOTES | ENT_HTML5);

        $texte = stripslashes((string)$value->texte);

        $texte = $myts->displayTarea($texte, $allowhtml, $allowsmileys, $allowbbcode);

        $date = formatTimestamp($value->date, 's');

        static $color_number;

        if (1 == $color_number) {
            $color_number = 2;
        } else {
            $color_number = 1;
        }

        showComment(
            $color_number,
            '',
            $titre,
            $texte,
            $date,
            '',
            '',
            $edit_image,
            $delete_image,
            $uname,
            $rank['title'],
            $rank['image'],
            $avatar_image,
            $reg_date,
            $posts,
            $user_from,
            $online_image,
            $profile_image,
            $pm_image,
            $email_image,
            $www_image,
            $icq_image,
            $aim_image,
            $yim_image,
            $msnm_image
        );
    }

    closeComment();

    echo '<br>' . $pagenav->renderNav() . '</div>';
}// fin du$magcoment!=0

echo '<br>';

if (file_exists('galerie_footer.php')) {
    include 'galerie_footer.php';
}

//CloseTable();
require XOOPS_ROOT_PATH . '/footer.php';

?>
