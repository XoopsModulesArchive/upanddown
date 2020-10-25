<?php
//id:magalerie/functions.php.dist
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
function openComment($width = '100%')
{
    echo "<table border='0' cellpadding='0' cellspacing='0' border='0' align='center' width='$width'><tr><td><table border='0' cellpadding='4' cellspacing='1' width='100%'><tr align='left'><td class='head' width='20%'>" . _CM_POSTER . "</td><td class='head'>" . _CM_THREAD . '</td></tr>';
}

function showComment(
    $color_number,
    $subject_image,
    $subject,
    $text,
    $post_date,
    $ip_image,
    $reply_image,
    $edit_image,
    $delete_image,
    $username = '',
    $rank_title = '',
    $rank_image = '',
    $avatar_image = '',
    $reg_date = '',
    $posts = '',
    $user_from = '',
    $online_image = '',
    $profile_image = '',
    $pm_image = '',
    $email_image = '',
    $www_image = '',
    $icq_image = '',
    $aim_image = '',
    $yim_image = '',
    $msnm_image = ''
) {
    if (1 == $color_number) {
        $bg = 'even';
    } else {
        $bg = 'odd';
    }

    echo "<tr align='left'><td valign='top' class='$bg' nowrap='nowrap'><b>$username</b><br>$rank_title<br>$rank_image<br>$avatar_image<br><br>$reg_date<br>$posts<br>$user_from<br><br>$online_image</td>";

    echo "<td valign='top' class='$bg'><table width='100%' border='0'><tr><td valign='top'>$subject_image&nbsp;<b>$subject</b></td><td align='right'>" . $ip_image . '' . $reply_image . '' . $edit_image . '' . $delete_image . '</td></tr>';

    echo "<tr><td colspan='2'><p>$text</p></td></tr></table></td></tr>";

    echo "<tr align='left'><td class='$bg' valign='middle'>$post_date</td><td class='$bg' valign='middle'>" . $profile_image . '' . $pm_image . '' . $email_image . '' . $www_image . '' . $icq_image . '' . $aim_image . '' . $yim_image . '' . $msnm_image . '</td></tr>';
}

function closeComment()
{
    echo '</table></td></tr></table>';
}

function vignette($fichier_image, $scale, $max_v, $max_h, $source, $destination, $prefixe)
{
    // MAX_V = HAUTEUR -- MAX_H = LARGEUR

    // le nom de l'image "scalée" commencera par ti_ et le nom du fichier original

    $ti_fichier_image = $fichier_image . $prefixe;

    global $nomfichier;

    $im = imagecreatefromjpeg((string)$source . (string)$fichier_image);

    $v = imagesy($im); // $v prend la hauteur
    $h = imagesx($im); // $h prend la largeur
    //Floor Arrondi à l'entier inférieur

    //ON GERE LA HAUTEUR
    if ($v > $max_v) { // Si la hauteur Img, est plus grand que le max, on reduit
        $taux_hauteur = $v / $max_v;    // On recupere le taux necessaire pour retrecir
        $ti_v = (int)floor($max_v); // ti_v = taille final de la hauteur
        $ti_h = (int)floor($h / $taux_hauteur); // ti_h = taille final de la largeur
    } else {
        $ti_v = $v;
    } // Sinon on fixe la hauteur

    // Si il n'a pas deja subbit une modification de la taille

    if ('' != $ti_h) {
        $h_comp = $ti_h;
    } else {
        $h_comp = $h;
    }

    if ('' != $ti_v) {
        $v_comp = $ti_v;
    } else {
        $v_comp = $v;
    }

    //ON GERE LA LARGEUR

    if ($h_comp > $max_h) {
        $taux_largeur = $h_comp / $max_h;

        $ti_h = (int)floor($max_h);

        $ti_v = (int)floor($v_comp / $taux_largeur);
    } else {
        $ti_h = $h_comp;
    }

    //ON FABRIQUE LA VIGNETTE
    $ti_im = imagecreate($ti_h, $ti_v); //soit ImageCreate ou ImageCreateTrueColor
    imagecopyresized($ti_im, $im, 0, 0, 0, 0, $ti_h, $ti_v, $h, $v);

    imagejpeg($ti_im, (string)$destination . (string)$ti_fichier_image, $scale);

    $nomfichier = $destination . $ti_fichier_image;
}

/*
Exemple pour creer une miniature :
vignette($fic,75,100,200,$path_source,$path_minia,"_st");
 Exemple pour creer une image reduite depassant pas les 500 :
($fic,75,500,500,$path_source,$path_dest_photo,"");
*/

function resize($image, $sm)
{
    // Permet de réduire une image juste à l'affichage

    // Auteur: Lageon Bruno

    // Email:  flashpassion@yahoo.fr

    // Web:    http://www.flashpassion.com

    global $image, $sm_largeur, $sm_hauteur;

    // obtenir la taille de l'image

    $arr = getimagesize((string)$image);

    //$arr = getimagesize("$path$image.$type");

    // initialisation de la variable largeur

    $largeur = $arr[0];

    // initialisation de la variable hauteur

    $hauteur = $arr[1];

    $facteur = ($largeur / $sm);

    // Vérifie si l'image est plus petite que $sm

    if ($largeur < $sm) {
        // si vrai retourne les mêmes valeurs d'origine de l'image

        $sm_largeur = $largeur;

        $sm_hauteur = $hauteur;

    // sinon affecte des nouvelles valeurs
    } else {
        $sm_largeur = ($largeur / $facteur);

        $sm_hauteur = ($hauteur / $facteur);
    }

    return $image;
}

function retour($img)
{
    if ($img) {
        $cond = 1;
    }

    return $cond;
}

function linkretour($uid)
{
    global $xoopsDB;

    $demande = 'select cid from ' . $xoopsDB->prefix('upanddown_cat') . " where id='$uid' ";

    $req = $xoopsDB->query($demande);

    while (list($cid) = $xoopsDB->fetchRow($req)) {
        if (0 != $cid) {
            $link = multiname($cid);

            $aref = "$link/";
        } else {
            $aref = '';
        }
    }

    return $aref;
}

function list_cat_form($action)
{
    global $xoopsDB, $cat;

    echo "<form method=\"post\" name=\"$cat\" action=\"$action\">";

    $demande = 'select cat from ' . $xoopsDB->prefix('upanddown_cat') . ' ';

    $req = $xoopsDB->query($demande);

    echo "<select name=\"cat\" onChange='submit()'>";

    echo "<option>$cat</option>";

    while (list($cat) = $xoopsDB->fetchRow($req)) {
        echo "<option value=\"$cat\">" . $cat . '</option>';
    }

    echo '</select>';

    echo '</form>';
}

function multiname($name)
{
    global $xoopsDB, $cid;

    $rete = 'select cat from ' . $xoopsDB->prefix('upanddown_cat') . " where id='$name'";

    $result = $xoopsDB->query($rete);

    while (list($rowcat) = $xoopsDB->fetchRow($result)) {
        //echo "$rowcat";

        return $rowcat;
    }
}

function checkrename()
{
    global $xoopsDB, $cid;

    $rete = 'select cat from ' . $xoopsDB->prefix('upanddown_cat') . " where id='$cid'";

    $result = $xoopsDB->query($rete);

    while (list($rowcat) = $xoopsDB->fetchRow($result)) {
        //echo "$rowcat";

        return $rowcat;
    }
}

function checkname()
{
    global $xoopsDB, $id;

    $rete = 'select cat from ' . $xoopsDB->prefix('upanddown_cat') . " where id='$id'";

    $result = $xoopsDB->query($rete);

    while (list($rowcat) = $xoopsDB->fetchRow($result)) {
        //echo "$rowcat";

        return $rowcat;
    }
}

function list_souscat($action, $texte)
{
    global $xoopsDB;

    echo "<form method=\"post\" name=\"uid\" action=\"$action\">";

    echo '' . _NEWSOUSREP . ' &nbsp;';

    $demande = 'select id, cat from ' . $xoopsDB->prefix('upanddown_cat') . " where cid='0' ";

    $req = $xoopsDB->query($demande);

    echo "<select name=\"uid\" onChange='submit()'>";

    echo "<option value=\"#\">$texte</option>";

    while (list($id, $cat) = $xoopsDB->fetchRow($req)) {
        if (0 != $cid) {
            $cat = "&nbsp;&nbsp;--$cat";
        }

        echo "<option value=\"$id\">" . $cat . '</option>';
    }

    echo '</select>';

    echo '</form>';
}

function list_cat($action, $texte)
{
    global $xoopsDB;

    echo "<form method=\"post\" name=\"cat\" action=\"$action\">";

    $demande = 'select cat from ' . $xoopsDB->prefix('upanddown_cat') . " where cid='0' ";

    $req = $xoopsDB->query($demande);

    echo "<select name=\"cat\" onChange='submit()'>";

    echo "<option>$texte</option>";

    while (list($cat) = $xoopsDB->fetchRow($req)) {
        echo "<option value=\"$cat\">" . $cat . '</option>';
    }

    echo '</select>';

    echo '</form>';
}

function convertorderbyin($orderby)
{
    if ('dateA' == $orderby) {
        $orderby = 'date ASC';
    }

    if ('hitsA' == $orderby) {
        $orderby = 'clic ASC';
    }

    if ('noteA' == $orderby) {
        $orderby = 'note ASC';
    }

    if ('dateD' == $orderby) {
        $orderby = 'date DESC';
    }

    if ('hitsD' == $orderby) {
        $orderby = 'clic DESC';
    }

    if ('noteD' == $orderby) {
        $orderby = 'note DESC';
    }

    return $orderby;
}

function convertorderbytrans($orderby)
{
    if ('clic ASC' == $orderby) {
        $orderbyTrans = '' . _MD_POPULARITYLTOM . '';
    }

    if ('clic DESC' == $orderby) {
        $orderbyTrans = '' . _MD_POPULARITYMTOL . '';
    }

    if ('date ASC' == $orderby) {
        $orderbyTrans = '' . _MD_DATEOLD . '';
    }

    if ('date DESC' == $orderby) {
        $orderbyTrans = '' . _MD_DATENEW . '';
    }

    if ('note ASC' == $orderby) {
        $orderbyTrans = '' . _MD_RATINGLTOH . '';
    }

    if ('note DESC' == $orderby) {
        $orderbyTrans = '' . _MD_RATINGHTOL . '';
    }

    return $orderbyTrans;
}

function convertorderbyout($orderby)
{
    if ('date ASC' == $orderby) {
        $orderby = 'dateA';
    }

    if ('clic ASC' == $orderby) {
        $orderby = 'clicA';
    }

    if ('note ASC' == $orderby) {
        $orderby = 'noteA';
    }

    if ('date DESC' == $orderby) {
        $orderby = 'dateD';
    }

    if ('clic DESC' == $orderby) {
        $orderby = 'clicD';
    }

    if ('note DESC' == $orderby) {
        $orderby = 'noteD';
    }

    return $orderby;
}
