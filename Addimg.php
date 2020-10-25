<?php

//include ("../../mainfile.php");
include 'header.php';

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
//function Addimg() {
//global $xoopsDB, $xoopsConfig, $xoopsUser, $xoopsTheme, $sendmail, $cat, $titre, $coment, $img, $photo, $photomax, $destination, $photo_size, $photo_name, $hauteur, $largeur;

if ($photo_name) {
    $typephoto[1] = 'gif';

    $typephoto[2] = 'jpg';

    $typephoto[3] = 'png';

    $typephoto[4] = 'GIF';

    $typephoto[5] = 'JPG';

    $typephoto[6] = 'PNG';

    $typephoto[7] = 'PDF';

    $typephoto[8] = 'pdf';

    $typephoto[9] = 'doc';

    $typephoto[10] = 'DOC';

    $typephoto[11] = 'xls';

    $typephoto[12] = 'XLS';

    $typephoto[13] = 'ppt';

    $typephoto[14] = 'PPT';

    $typephoto[15] = 'rtf';

    $typephoto[16] = 'RTF';

    preg_match("\.([^\.]*$)", $photo_name, $elts);

    $extension_fichier = $elts[1];

    if (!in_array($extension_fichier, $typephoto, true)) {
        require XOOPS_ROOT_PATH . '/header.php';

        OpenTable();

        echo '' . _CLA_FILES . " $extension_fichier " . _CLA_FILESTOP . '.';

        CloseTable();

        require XOOPS_ROOT_PATH . '/footer.php';

        exit;
    }

    if ($photo_size > $photomax) {
        $photomax1 = $photomax / 1024;

        require XOOPS_ROOT_PATH . '/header.php';

        OpenTable();

        echo '' . _CLA_YIMG . ' ' . $photo_name . ' ' . _CLA_TOBIG . ' < ';

        printf('%.2f Kb', $photomax1);

        echo '<br><br><div align="center"><input type="button" value="<<<" onclick="history.go(-1)"></div>';

        CloseTable();

        require XOOPS_ROOT_PATH . '/footer.php';

        exit();
    }

    $this_date = time();

    $date = formatTimestamp($this_date, 'ms');

    //$photnom = "$date-$photo_name";

    $photnom = (string)$photo_name;

    $destination = XOOPS_ROOT_PATH . "/modules/upanddown/updown/$cat/$photnom";

    //si la fonction copy ou move_uploaded_file pose des problèmes intervertir les fonctions.

    if (!move_uploaded_file((string)$photo, $destination)) {
        //if(!copy("$photo", $destination)){

        require XOOPS_ROOT_PATH . '/header.php';

        OpenTable();

        echo '' . _CLA_JOIND . '.';

        CloseTable();

        require XOOPS_ROOT_PATH . '/footer.php';

        exit;
    }
}
if (!$cat || !$photnom) {
    include '../../header.php';

    OpenTable();

    echo '<div align="center"><p><b>' . _ERREURCOMM . '</b></p>';

    echo '<input type="button" value="' . _PREC . '" onclick="history.go(-1)"></div>';

    CloseTable();

    include '../../footer.php';
} else {
    if ($xoopsUser) {
        $userid = $xoopsUser->uid();
    } else {
        $userid = '0';
    }

    $rep = XOOPS_ROOT_PATH . "/modules/upanddown/updown/$cat/";

    vignette($photnom, 75, $hauteur, $largeur, $rep, $rep, '-ppm');

    if (!$titre || !$coment) {
        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('upanddown') . " (uid, userid, img, valid, date) values ( '$uid', '$userid', '$photnom', '0', '$this_date')");

        if (1 == $sendmail) {
            $subject = $xoopsConfig['sitename'] . ' - ' . _MODULE_NAME;

            $xoopsMailer = getMailer();

            $xoopsMailer->useMail();

            $xoopsMailer->setToEmails($xoopsConfig['adminmail']);

            $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);

            $xoopsMailer->setFromName($xoopsConfig['sitename']);

            $xoopsMailer->setSubject($subject);

            $xoopsMailer->setBody(_NEWIMAGE . "\n\n" . XOOPS_URL . '/modules/upanddown/admin/index.php');

            $xoopsMailer->send();
        }
    }

    if ($titre || $coment) {
        $myts = MyTextSanitizer::getInstance();

        //$titre = addslashes("$titre");

        $titre = $myts->addSlashes($titre);

        //$coment = addslashes("$coment");

        $coment = $myts->addSlashes($coment);

        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('upanddown') . " (uid, userid, img, valid, date) values ( '$uid', '$userid', '$photnom', '0', '$this_date')");

        $result = $GLOBALS['xoopsDB']->queryF('SELECT id FROM ' . $xoopsDB->prefix('upanddown') . " WHERE img='$photnom' ");

        while (false !== ($ligne = $GLOBALS['xoopsDB']->fetchObject($result))) {
            $lid = (string)$ligne->id;
        }

        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('upanddown_comments') . " (lid, uid, titre, texte, date) values ( '$lid', '$userid', '$titre', '$coment', '$this_date')");

        if (1 == $sendmail) {
            $subject = $xoopsConfig['sitename'] . ' - ' . _MODULE_NAME;

            $xoopsMailer = getMailer();

            $xoopsMailer->useMail();

            $xoopsMailer->setToEmails($xoopsConfig['adminmail']);

            $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);

            $xoopsMailer->setFromName($xoopsConfig['sitename']);

            $xoopsMailer->setSubject($subject);

            $xoopsMailer->setBody(_NEWIMAGE . "\n\n" . XOOPS_URL . '/modules/upanddown/admin/index.php');

            $xoopsMailer->send();
        }
    }

    include '../../header.php';

    OpenTable();

    echo "<table cellpadding=\"6\"><tr><td><img src=\"updown/$cat/$photnom-ppm\"></td>\n" . '<td>' . _REVALID . '<br><div align="right"><a href="index.php">' . _RETOUR . "</a></div></td></tr></table>\n";

    CloseTable();

    include '../../footer.php';
}
//}
