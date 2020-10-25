<?php
#####################################################################################
# Magalerie Version v1.5-xoops_rc2                                                                                                        #
# Projet du --/--/2002                  dernière modification: 02/03/20023                                                       #
# Scripts Home        :                         http://www.lespace.org                                                                         #
# auteur                  :                    bidou                                                                                           #
# email                    :                    bidou@lespace.org                                                                                #
# Site web                 :        http://www.lespace.org                                                                           #
#avec                :        mouff                                                                        #
#email                :        mouffreilly@hotmail.com                                                        #
# Site web        :          http://www.mouff.fr.fm                                                        #
# licence          :            Gpl                                                                                                      #
#####################################################################################
include 'admin_header.php';
$op = 'Config';

if (!empty($_GET['op'])) {
    $op = $_GET['op'];
}

if ('Config' == $op) {
    xoops_cp_header();

    OpenTable();

    include '../cache/config.php';

    include 'admin-menu.php';

    echo '<br><b>' . _CONF . "</b><form method='post' action='menuconf.php?op=SaveConfig'>";

    echo '<ul>';

    echo '' . _NBVIGN . " <input type='text' value='$nb' name='conf_nb' style='width:25px; text-align:right'><br>";

    echo '<br>';

    echo '' . _NBPPAGE . " <input type='text' value='$themepage' name='conf_themepage' style='width:25px; text-align:right'><br>";

    if (1 == $listcat) {
        $listcat1 = "selected='selected'";

        $listcat0 = '';
    } else {
        $listcat1 = '';

        $listcat0 = "selected='selected'";
    }

    echo '<br>' . _LISTCAT . " <select name='conf_listcat'><option value='1' " . $listcat1 . '>' . _YES . "</option><option value='0' " . $listcat0 . '>' . _NO . '</option></select><br>';

    if (1 == $navigcat) {
        $navigcat1 = "selected='selected'";

        $navigcat0 = '';
    } else {
        $navigcat1 = '';

        $navigcat0 = "selected='selected'";
    }

    echo '<br>' . _NAVIGCAT . " <select name='conf_navigcat'><option value='1' " . $navigcat1 . '>' . _YES . "</option><option value='0' " . $navigcat0 . '>' . _NO . '</option></select><br>';

    if (1 == $anonymes) {
        $anonymes1 = "selected='selected'";

        $anonymes0 = '';
    } else {
        $anonymes1 = '';

        $anonymes0 = "selected='selected'";
    }

    echo '<br>' . _ANONPOST . " <select name='conf_anonymes'><option value='1' " . $anonymes1 . '>' . _YES . "</option><option value='0' " . $anonymes0 . '>' . _NO . '</option></select><br>';

    if (1 == $validup) {
        $validup1 = "selected='selected'";

        $validup0 = '';
    } else {
        $validup1 = '';

        $validup0 = "selected='selected'";
    }

    echo '</ul>';

    echo '<br><b>' . _MENUCONF . '</b>';

    echo '<ul>' . _UPFILE . " <select name='conf_validup'><option value='1' " . $validup1 . '>' . _YES . "</option><option value='0' " . $validup0 . '>' . _NO . '</option></select><br>';

    echo '<br>';

    echo '' . _MAXUP . " <input type='text' value='$photomax' name='conf_photomax' style='width:50px; text-align:right'>&nbsp; " . _SOIT . ' ';

    $photomax1 = $photomax / 1024;

    printf('%.2f Kb', $photomax1);

    echo '<br>';

    echo '<br>' . _MAXHAUT . " <input type='text' value='$hauteur' name='conf_hauteur' style='width:50px; text-align:right'>";

    echo '<br>';

    echo '<br>' . _MAXLARG . " <input type='text' value='$largeur' name='conf_largeur' style='width:50px; text-align:right'>";

    echo '<br><br>' . _SHOWMAX . " <input type='text' value='$showmax' name='conf_showmax' style='width:25px; text-align:right'><br>";

    echo '' . _SHOWMAX_EX . '';

    echo '</ul>';

    echo '<br><b>' . _CONFCOMM . '</b>';

    echo '<ul>';

    if (1 == $magcoment) {
        $magcoment1 = "selected='selected'";

        $magcoment0 = '';
    } else {
        $magcoment1 = '';

        $magcoment0 = "selected='selected'";
    }

    echo '' . _COMENTPOST . " <select name='conf_magcoment'><option value='1' " . $magcoment1 . '>' . _YES . "</option><option value='0' " . $magcoment0 . '>' . _NO . '</option></select><br>';

    echo '<br>';

    echo '' . _NBCOMPPAGE . " <input type='text' value='$nbms' name='conf_nbms' style='width:25px; text-align:right'><br>";

    echo '<br>';

    if (1 == $allowbbcode) {
        $allowbbcode1 = "selected='selected'";

        $allowbbcode0 = '';
    } else {
        $allowbbcode1 = '';

        $allowbbcode0 = "selected='selected'";
    }

    echo '' . _BBCODE . " <select name='conf_allowbbcode'><option value='1' " . $allowbbcode1 . '>' . _YES . "</option><option value='0' " . $allowbbcode0 . '>' . _NO . '</option></select><br>';

    echo '<br>';

    if (1 == $allowhtml) {
        $allowhtml1 = "selected='selected'";

        $allowhtml0 = '';
    } else {
        $allowhtml1 = '';

        $allowhtml0 = "selected='selected'";
    }

    echo '' . _HTML . " <select name='conf_allowhtml'><option value='1' " . $allowhtml1 . '>' . _YES . "</option><option value='0' " . $allowhtml0 . '>' . _NO . '</option></select><br>';

    echo '<br>';

    if (1 == $allowsmileys) {
        $allowsmileys1 = "selected='selected'";

        $allowsmileys0 = '';
    } else {
        $allowsmileys1 = '';

        $allowsmileys0 = "selected='selected'";
    }

    echo '' . _SMILE . " <select name='conf_allowsmileys'><option value='1' " . $allowsmileys1 . '>' . _YES . "</option><option value='0' " . $allowsmileys0 . '>' . _NO . '</option></select><br>';

    echo "<br><input type='submit' name='submit' value='Los!'></form";

    CloseTable();

    xoops_cp_footer();
}

if ('SaveConfig' == $op) {
    //function SaveConfig() {

    //global $xoopsDB, $conf_nb, $conf_themepage, $conf_anonymes, $conf_validup, $conf_photomax, $conf_hauteur, $conf_largeur, $conf_sendmail, $conf_ecard, $conf_popup, $conf_magcoment, $conf_nbms, $conf_allowbbcode, $conf_allowhtml, $conf_allowsmileys, $conf_listcat, $conf_navigcat;

    if ('' != $_POST[(string)$nb]) {
        $message = htmlspecialchars($_POST[(string)$nb], ENT_QUOTES | ENT_HTML5);
    }

    if (!is_writable(XOOPS_ROOT_PATH . '/modules/upanddown/cache/config.php')) {
        // attempt to chmod 666

        if (!chmod(XOOPS_ROOT_PATH . '/modules/upanddown/cache/config.php', 0666)) {
            xoops_cp_header();

            OpenTable();

            printf(_MUSTWABLE, '<b>' . XOOPS_ROOT_PATH . '/modules/upanddown/cache/config.php</b>');

            CloseTable();

            xoops_cp_footer();

            exit();
        }
    }

    $fp = fopen('../cache/config.php', 'wb');

    fwrite(
        $fp,
        '<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
#Ce fichier doit etre ouvert en ecriture (chmod 0666)
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//Nombre de fichier par page
$nb=' . $conf_nb . ';
//Nombre de colonne par page
$themepage=' . $conf_themepage . ';
//afficher la liste déroulante (navigation galerie.php)
$listcat=' . $conf_listcat . ';
//afficher le menu standard (navigation galerie.php)
$navigcat=' . $conf_navigcat . ';
//Autoriser les anonymes a utiliser toutes les options de magalerie 1=oui 0=non
$anonymes=' . $conf_anonymes . ';
//Autoriser l upload 1=oui 0=non
$validup=' . $conf_validup . ';
//Taille maximum des fichiers uploader
$photomax=' . $conf_photomax . ';
//Hauteur max de la vignette
$hauteur=' . $conf_hauteur . ';
//largeur max de la vignette
$largeur=' . $conf_largeur . ';
//largeur max de la grande image
$showmax=' . $conf_showmax . ';
//Autoriser les commentaires 1=oui 0=non
$magcoment=' . $conf_magcoment . ';
//Nombre de commentaires par page
$nbms=' . $conf_nbms . ';
//Si commentaires autorisés, autoriser les BBCode 1=oui 0=non
$allowbbcode=' . $conf_allowbbcode . ';
//Si commentaires autorisés, autoriser le html 1=oui 0=non
$allowhtml=' . $conf_allowhtml . ';
//Si commentaires autorisés, autoriser les smilies 1=oui 0=non
$allowsmileys=' . $conf_allowsmileys . ';

?>'
    );

    fclose($fp);

    redirect_header('menuconf.php', 10, 'Datenbank wurde erfolgreich aktualisiert!');
}
