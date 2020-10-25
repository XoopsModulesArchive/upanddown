<?php

include 'admin_header.php';

//$op = "index";

//if (!empty($_GET['op'])) {
//        $op = $_GET['op'];
//}

if ('confirm_img' == $op) {
    OpenTable();

    xoops_cp_header();

    echo "<form method=\"post\" action=\"$PHP_SELF?op=del_img\">\n";

    //        echo "<input type=\"hidden\" name=\"op\" value=\"del_img\">\n";

    echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

    echo "<input type=\"hidden\" name=\"uid\" value=\"$uid\">\n";

    echo "<input type=\"hidden\" name=\"image\" value=\"$image\">\n";

    echo '<div align="center"><b>' . _CONFDEL . " $id</b><br>" . _GARDE . "<br><br><img src=\"$image-ppm\"><br><br>\n";

    echo '<input type="submit" value="' . _YES . "\">&nbsp\n";

    echo '<input type="button" value="' . _NO . "\" onclick=\"history.go(-1)\"></div>\n";

    echo "</form>\n";

    CloseTable();

    xoops_cp_footer();
}
////////////////////////////////////////////////
if ('confirm_cat' == $op) {
    //function confirm_cat(){

    //global $xoopsDB, $id, $cat, $img;

    OpenTable();

    xoops_cp_header();

    echo 'Sind Sie sicher Sie wollen diese Kategorie und alle damit verbundenen Dateien löschen?';

    echo "<form method=\"post\" action=\"$PHP_SELF?op=del_cat\">\n";

    echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

    echo '<input type="submit" value="' . _YES . "\">&nbsp\n";

    echo '<input type="button" value="' . _NO . "\" onclick=\"history.go(-1)\"></div>\n";

    echo "</form>\n";

    CloseTable();

    xoops_cp_footer();
}
///////////////////////////////////////////////////////////
if ('del_img' == $op) {
    $supp = 'DELETE FROM ' . $xoopsDB->prefix('upanddown') . " WHERE id='$id' ";

    $GLOBALS['xoopsDB']->queryF($supp);

    $supp_com = 'DELETE FROM ' . $xoopsDB->prefix('upanddown_comments') . " WHERE lid='$id' ";

    $GLOBALS['xoopsDB']->queryF($supp_com);

    echo $GLOBALS['xoopsDB']->error();

    $Fnm = (string)$image;

    $Fnm2 = "$image-ppm";

    unlink($Fnm);

    unlink($Fnm2);

    redirect_header('listing.php?uid=' . $uid . '', 1, 'Datenbank wurde erfolgreich aktualisiert!');

    exit();
}

if ('del_cat' == $op) {
    //function del_cat(){

    //global $xoopsDB, $id, $img;

    $supp = 'DELETE FROM ' . $xoopsDB->prefix('upanddown_cat') . " WHERE ID='$id' ";

    $GLOBALS['xoopsDB']->queryF($supp);

    echo $GLOBALS['xoopsDB']->error();

    redirect_header($GLOBALS['HTTP_REFERER'], 1, 'Datenbank wurde erfolgreich aktualisiert!');

    exit();
}

function confirm_coment()
{
    global $xoopxDB, $id, $lid;

    echo "<form method=\"post\" action=\"commentaire.php\">\n";

    echo "<input type=\"hidden\" name=\"op\" value=\"delete\">\n";

    echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

    echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">\n";

    echo '<div align="center"><b>' . _CONFIRM . "</b><br><br>\n";

    echo '<input type="submit" value="' . _YES . "\">&nbsp\n";

    echo '<input type="button" value="' . _NO . "\" onclick=\"history.go(-1)\"></div>\n";

    echo "</form>\n";
}//Fin de la function confirm

function delete_coment()
{
    global $xoopsUser, $xoopsDB, $id, $lid;

    if ($xoopsUser) {
        if ($xoopsUser->isAdmin()) {
            $supp = 'DELETE FROM ' . $xoopsDB->prefix('upanddown_comments') . " WHERE id=$id";

            $GLOBALS['xoopsDB']->queryF($supp);

            echo $GLOBALS['xoopsDB']->error();
        }
    }

    redirect_header("commentaire.php?lid=$lid", 1, _AJOUR);

    exit();
}//Fin de la function delete

/*
switch ($op) {
   case 'confirm_coment':
       confirm_coment();
      break;
   case 'delete_coment':
       delete_coment();
      break;
   case 'del_img':
       del_img();
      break;
   default:
      confirm_img();
      break;
   }
*/
