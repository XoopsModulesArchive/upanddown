<?php

$result = $xoopsDB->query('SELECT id FROM ' . $xoopsDB->prefix('upanddown') . " WHERE valid >= '1' and uid = '$uid'");
$x = 1;
$menge = $GLOBALS['xoopsDB']->getRowsNum($result);
while (false !== ($val = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $bild[$x] = $val['id'];

    $y = $x;

    $x++;
}
echo '<br>';

for ($x = 1; $x < ($y + 1); $x++) {
    if ($bild[$x] == $id) {
        $aktuell = $x;

        if ($aktuell > 1) {
            $img_ret = $bild[$x - 1];
        }

        if ($aktuell < $menge) {
            $img_next = $bild[$x + 1];
        }
    }
}

echo '<table border="0" cellspacing="0" cellpadding="0" align="center" class="navig">';
if ($aktuell > 1) {
    echo '<td align="left" width="50"><a href="' . $PHP_SELF . '?id=' . $img_ret . '">' . '<img src="images/back.gif" width="16" height="17" border="0" title="' . _PREC . '"></a></td>';
} else {
    echo '<td align="center" width="50">&nbsp;</td>';
}
echo '<td align="center" width="50"><a href="galerie.php?uid=' . $uid . '"><img src="images/retour.gif" width="46" height="14" border="0" title="' . _LERETOUR . ' ' . _CHOISIPAYS . " $cat\"></a></td>";
if ($aktuell < $menge) {
    echo '<td align="right" width="50"><a href="' . $PHP_SELF . '?id=' . $img_next . '"><img src="images/suiv.gif" width="16" height="17" border="0" title="' . _SUIV . '"></a></td>';
} else {
    echo '<td align="center" width="50">&nbsp;</td>';
}
echo '</table>';
