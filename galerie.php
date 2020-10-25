<?php
//id:magalerie/galerie.php
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

$this_date = time();
$this_date = formatTimestamp($this_date, 'ms');
?>
    <script language="JavaScript">
        function OuvrirFenetre(url, nom, details) {
            window.open(url, nom, details)
        }
    </script>
    <style>
        .magmenu {
            width: 10%
        }

        .magshow {
            width: 100%
        }
    </style>
<?php

$GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('upanddown_cat') . " SET clic=clic+1 WHERE id='$uid' ");

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
echo '<div align="center">';

if ('1' == $navigcat) {
    include 'navig.php';
}
echo '</div>';

$temcount = 1;
$catname = multiname($uid);
$pathimg = linkretour($uid);
//Récupération du nombre des données
$nombre = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) as img FROM ' . $xoopsDB->prefix('upanddown') . " WHERE uid='$uid' and valid='1'");
$row = $GLOBALS['xoopsDB']->fetchRow($nombre);
$message = $row[0];

//ordre de classement par defaut
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

$result = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('upanddown') . " WHERE uid='$uid' and valid='1' ORDER BY $mysqlorderby limit " . (int)$start . ',' . $nb);
$pagenav = new XoopsPageNav($message, $nb, $start, 'start', 'uid=' . $uid . '&orderby=' . $orderby . '');

if ($row[0] > 1) {
    $s = '' . _PLURIEL1 . '';

    $ent = '' . _PLURIEL2 . '';

    $x = '' . _PLURIEL3 . '';
} else {
    $s = '';

    $ent = '';

    $x = '';
}
echo '<br><div align="center"><b>' . _CAT . ': ' . $pathimg . '' . $catname . ' </b>' . $row[0] . ' ' . _IMG . '' . $s . '';

if ('1' == $validup) {
    if ($xoopsUser) {
        echo "&nbsp;(<a href=\"formulaire.php?uid=$uid\">" . _ADDIMG . '</a>)';
    } else {
        if ('1' == $anonymes) {
            echo "&nbsp;(<a href=\"formulaire.php?uid=$uid\">" . _ADDIMG . '</a>)';
        }
    }
}
echo '<br><br>' . _MD_SORTBY . '
              ' . _MD_DATE . " (<a href='galerie.php?uid=$uid&orderby=dateA'><img src=\"images/up.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a><a href='galerie.php?uid=$uid&orderby=dateD'><img src=\"images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
              " . _MD_RATING . " (<a href='galerie.php?uid=$uid&orderby=noteA'><img src=\"images/up.gif\" width=\"11\" height=\"14\"  border=\"0\" align=\"middle\"></a><a href='galerie.php?uid=$uid&orderby=noteD'><img src=\"images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
              " . _MD_POPULARITY . " (<a href='galerie.php?uid=$uid&orderby=hitsA'><img src=\"images/up.gif\" width=\"11\" height=\"14\"  border=\"0\" align=\"middle\"></a><a href='galerie.php?uid=$uid&orderby=hitsD'><img src=\"images/down.gif\" width=\"11\" height=\"14\" border=\"0\" align=\"middle\"></a>)
                      ";
echo '<b><br>';
printf(_MD_CURSORTEDBY, (string)$orderbyTrans);
echo '<br>';

//echo "<br>".$pagenav->renderNav()."";
if ($nb != $row[0]) {
    echo '<br>' . $pagenav->renderNav() . '</div>';
}

echo '<table width="100%" cellspacing="25" align="center"><tr>';

while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($result))) {
    $imgid = $ligne->id;

    $img = $ligne->img;

    $note = $ligne->note;

    $vote = $ligne->vote;

    $date = formatTimestamp($ligne->date, 's');

    $count = 7;

    $startdate = (time() - (86400 * $count));

    if ($startdate < $ligne->date) {
        $nouveau = '&nbsp;<img src="' . XOOPS_URL . '/modules/upanddown/images/new.gif" width="28" height="11" title="' . _MD_NEWTHISWEEK . '"><br>';
    }

    echo '<td align="center" width="33%">';

    if ($ligne->clic > 1) {
        $s1 = '' . _PLURIEL1 . '';
    } else {
        $s1 = '';
    }

    if ($ligne->vote > 1) {
        $s2 = '' . _PLURIEL1 . '';
    } else {
        $s2 = '';
    }

    echo "<table align=\"center\" border=\"0\" cellspacing='8' class=\"outer\">" . '<tr>' . "<td colspan=\"2\" align=\"left\">$nouveau <b>" . _ADDED . "</b> $date";

    $count = 7;

    $startdate = (time() - (86400 * $count));

    if ($startdate < $date) {
        if (1 == $status) {
            echo '&nbsp;<img src="' . XOOPS_URL . '/modules/upanddown/images/newred.gif" alt="' . _MD_NEWTHISWEEK . '">';
        }
    }

    echo '</td>' . '</tr><tr>' . '<td colspan="2" align="center">';

    $image = 'updown/' . $pathimg . '' . $catname . '/' . $img . '-ppm';

    if (file_exists((string)$image)) {
        if ('.doc' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.DOC' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4)) {
            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '"><img src="images/word.gif" width="70" height="70" title="' . _ALT2 . '"></a><br><br>';

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . "\">$img</a><br><br>";

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '">Word-Datei</a></td>';
        } elseif ('.gif' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.GIF' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4)) {
            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '"><img src="images/gif.gif" width="70" height="70" title="' . _ALT2 . '"></a><br><br>';

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . "\">$img</a><br><br>";

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '">Gif-Datei</a></td>';
        } elseif ('.pdf' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.PDF' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4)) {
            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '"><img src="images/acrobat.gif" width="70" height="70" title="' . _ALT2 . '"></a><br><br>';

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . "\">$img</a><br><br>";

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '">PDF-Datei</a></td>';
        } elseif ('.xls' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.XLS' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4)) {
            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '"><img src="images/excel.gif" width="70" height="70" title="' . _ALT2 . '"></a><br><br>';

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . "\">$img</a><br><br>";

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '">Excel-Datei</a></td>';
        } elseif ('.ppt' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.PPT' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4)) {
            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '"><img src="images/powerpoint.gif" width="70" height="70" title="' . _ALT2 . '"></a><br><br>';

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . "\">$img</a><br><br>";

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '">Powerpoint-Datei</a></td>';
        } elseif ('.rtf' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.RTF' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4)) {
            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '"><img src="images/rtf.gif" width="70" height="70" title="' . _ALT2 . '"></a><br><br>';

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . "\">$img</a><br><br>";

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '">RTF-Datei</a></td>';
        } elseif ('.png' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.PNG' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4)) {
            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '"><img src="images/png.gif" width="70" height="70" title="' . _ALT2 . '"></a><br><br>';

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . "\">$img</a><br><br>";

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '">PNG-Datei</a></td>';
        } elseif ('.jpg' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4) || '.JPG' == mb_substr(mb_strtolower($img), (mb_strlen($img) - 4), 4)) {
            $size = getimagesize((string)$image);

            echo "<a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . "\"><img src=\"$image\" $size[3]></a><br><br><a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . "\">$img</a><br><br><a href=\"show.php?id=$imgid\" title=\"" . _ALT2 . '">JPG-Datei</a></td>';
        }
    }

    echo '</tr><tr>' . '<td>' . $ligne->clic . ' ' . _CLICS . '' . $s1 . '</td>';

    if (0 != $vote) {
        $diff = floor(($note / $vote));

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

    echo '<td align="right">';

    if (0 != $vote) {
        echo "<img src=\"../../uploads/$rank_img\" width=\"73\" height=\"13\" title=\"$note " . _POINTS . '"></td>';
    } else {
        echo "<img src=\"../../uploads/rank3e632f95e81ca.gif\" width=\"73\" height=\"13\" title=\"$note " . _POINTS . '">';
    }

    echo '</tr><tr>' . '<td colspan="2">';

    $solde = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('upanddown_comments') . " where lid='$imgid'");

    $total = $GLOBALS['xoopsDB']->fetchRow($solde);

    if ($total[0] > 0) {
        $com = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('upanddown_comments') . " where lid='$imgid' LIMIT 1");

        while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($com))) {
            $nbcar = 23;

            $dernierthread = $ligne->titre;

            $myts = MyTextSanitizer::getInstance();

            $dernierthread = htmlspecialchars($dernierthread, ENT_QUOTES | ENT_HTML5);

            if (mb_strlen($dernierthread) > $nbcar) {
                $dernierthread = mb_substr($dernierthread, 0, $nbcar) . ' ...';
            }

            echo (string)$dernierthread;
        }

        echo '&nbsp;(' . $total[0] . ')';
    } else {
        echo '' . $total[0] . ' ' . _COMMENTAIRE . '';
    }

    echo '</td>' . '</tr>' . '</table><br>';

    echo '</td><td>';

    if ($temcount == $themepage) {
        echo '</tr><tr>';

        $temcount -= $themepage;
    }

    $temcount++;
}
echo '</td></tr></table></div>';
if ($nb != $row[0]) {
    echo '<div align="center">' . $pagenav->renderNav() . '</div>';
}

if (file_exists('galerie_footer.php')) {
    include 'galerie_footer.php';
}
//closeTable();
include '../../footer.php';
?>
