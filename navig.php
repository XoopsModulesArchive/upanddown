<?php

echo '<table align="center" class="magmenu">' . '<tr>';
$nav = 'SELECT id, cat FROM ' . $xoopsDB->prefix('upanddown_cat') . ' where cid =0 ';
$result = $GLOBALS['xoopsDB']->queryF($nav);
$Nmax = 50; // nombre par page
$Ncur = 0; // n° de la fiche courante
$Ndeb = 0; // 1ère fiche transmise par l'URL
if (isset($num)) {
    $Ndeb = (int)$num;
}
$temcount = 1;
// tant qu'il y a des fiches
while (($val = $GLOBALS['xoopsDB']->fetchBoth($result)) && ($Ncur < $Nmax + $Ndeb)) {
    if ($Ncur >= $Ndeb) {
        if ($temcount > 1) {
            echo '<td align="center"><img src="images/centre.gif">';
        }

        echo '<td align="center"><a href="' . $xoopsConfig['xoops_url'] . '/modules/upanddown/galerie.php?uid=' . $val['id'] . '" style="font-size:14px"><b> ' . $val['cat'] . '</b></a>';

        echo '</td>';
    }

    if (6 == $temcount) {
        echo '</tr><tr>';

        $temcount -= 6;

        //      echo"</td></tr><tr>";
    }

    $temcount++;

    $Ncur++;
}
echo '<br></tr></table>';
