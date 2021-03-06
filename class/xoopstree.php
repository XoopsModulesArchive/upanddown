<?php
// $Id: xoopstree.php,v 1.1 2006/03/27 12:13:02 mikhail Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://xoops.codigolivre.org.br>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://xoops.codigolivre.org.br/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

class XoopsTree
{
    public $table;     //table with parent-child structure
    public $id;    //name of unique id for records in table $table
    public $pid;     // name of parent id used in table $table
    public $order;    //specifies the order of query results
    public $title;     // name of a field in table $table which will be used when  selection box and paths are generated
    public $db;

    //constructor of class XoopsTree

    //sets the names of table, unique id, and parend id

    public function __construct($table_name, $id_name, $pid_name)
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();

        $this->table = $table_name;

        $this->id = $id_name;

        $this->pid = $pid_name;
    }

    // returns an array of first child objects for a given id($sel_id)

    public function getFirstChild($sel_id, $order = '')
    {
        $arr = [];

        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $this->db->query($sql);

        $count = $this->db->getRowsNum($result);

        if (0 == $count) {
            return $arr;
        }

        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $arr[] = $myrow;
        }

        return $arr;
    }

    // returns an array of all FIRST child ids of a given id($sel_id)

    public function getFirstChildId($sel_id)
    {
        $idarray = [];

        $result = $this->db->query('SELECT ' . $this->id . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '');

        $count = $this->db->getRowsNum($result);

        if (0 == $count) {
            return $idarray;
        }

        while (list($id) = $this->db->fetchRow($result)) {
            $idarray[] = $id;
        }

        return $idarray;
    }

    //returns an array of ALL child ids for a given id($sel_id)

    public function getAllChildId($sel_id, $order = '', $idarray = [])
    {
        $sql = 'SELECT ' . $this->id . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $this->db->query($sql);

        $count = $this->db->getRowsNum($result);

        if (0 == $count) {
            return $idarray;
        }

        while (list($r_id) = $this->db->fetchRow($result)) {
            $idarray[] = $r_id;

            $idarray = $this->getAllChildId($r_id, $order, $idarray);
        }

        return $idarray;
    }

    //returns an array of ALL parent ids for a given id($sel_id)

    public function getAllParentId($sel_id, $order = '', $idarray = [])
    {
        $sql = 'SELECT ' . $this->pid . ' FROM ' . $this->table . ' WHERE ' . $this->id . '=' . $sel_id . '';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $this->db->query($sql);

        [$r_id] = $this->db->fetchRow($result);

        if (0 == $r_id) {
            return $idarray;
        }

        $idarray[] = $r_id;

        $idarray = $this->getAllParentId($r_id, $order, $idarray);

        return $idarray;
    }

    //generates path from the root id to a given id($sel_id)

    // the path is delimetered with "/"

    public function getPathFromId($sel_id, $title, $path = '')
    {
        $result = $this->db->query('SELECT ' . $this->pid . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$sel_id");

        if (0 == $this->db->getRowsNum($result)) {
            return $path;
        }

        [$parentid, $name] = $this->db->fetchRow($result);

        $myts = MyTextSanitizer::getInstance();

        $name = htmlspecialchars($name, ENT_QUOTES | ENT_HTML5);

        $path = '/' . $name . $path . '';

        if (0 == $parentid) {
            return $path;
        }

        $path = $this->getPathFromId($parentid, $title, $path);

        return $path;
    }

    //makes a nicely ordered selection box

    //$preset_id is used to specify a preselected item

    //set $none to 1 to add a option with value 0

    public function makeMySelBox($title, $order = '', $preset_id = 0, $none = 0, $sel_name = '', $onchange = '')
    {
        if ('' == $sel_name) {
            $sel_name = $this->cid;
        }

        $myts = MyTextSanitizer::getInstance();

        echo "<select name='" . $sel_name . "'";

        if ('' != $onchange) {
            echo " onchange='" . $onchange . "'";
        }

        echo ">\n";

        $sql = 'SELECT ' . $this->id . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=0';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $this->db->query($sql);

        if ($none) {
            echo "<option value='0'>----</option>\n";
        }

        while (list($catid, $name) = $this->db->fetchRow($result)) {
            $sel = '';

            if ($catid == $preset_id) {
                $sel = " selected='selected'";
            }

            echo "<option value='$catid'$sel>$name</option>\n";

            $sel = '';

            $arr = $this->getChildTreeArray($catid);

            foreach ($arr as $option) {
                $option['prefix'] = str_replace('.', '--', $option['prefix']);

                $catpath = $option['prefix'] . '&nbsp;' . htmlspecialchars($option[$title], ENT_QUOTES | ENT_HTML5);

                if ($option[$this->id] == $preset_id) {
                    $sel = " selected='selected'";
                }

                echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";

                $sel = '';
            }
        }

        echo "</select>\n";
    }

    //generates nicely formatted linked path from the root id to a given id

    public function getNicePathFromId($sel_id, $title, $funcURL, $path = '')
    {
        $sql = 'SELECT ' . $this->pid . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$sel_id";

        $result = $this->db->query($sql);

        if (0 == $this->db->getRowsNum($result)) {
            return $path;
        }

        [$parentid, $name] = $this->db->fetchRow($result);

        $myts = MyTextSanitizer::getInstance();

        $name = htmlspecialchars($name, ENT_QUOTES | ENT_HTML5);

        $path = "<a href='" . $funcURL . '&' . $this->id . '=' . $sel_id . "'>" . $name . '</a>&nbsp;:&nbsp;' . $path . '';

        if (0 == $parentid) {
            return $path;
        }

        $path = $this->getNicePathFromId($parentid, $title, $funcURL, $path);

        return $path;
    }

    //generates id path from the root id to a given id

    // the path is delimetered with "/"

    public function getIdPathFromId($sel_id, $path = '')
    {
        $result = $this->db->query('SELECT ' . $this->pid . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$sel_id");

        if (0 == $this->db->getRowsNum($result)) {
            return $path;
        }

        [$parentid] = $this->db->fetchRow($result);

        $path = '/' . $sel_id . $path . '';

        if (0 == $parentid) {
            return $path;
        }

        $path = $this->getIdPathFromId($parentid, $path);

        return $path;
    }

    public function getAllChild($sel_id = 0, $order = '', $parray = [])
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $this->db->query($sql);

        $count = $this->db->getRowsNum($result);

        if (0 == $count) {
            return $parray;
        }

        while (false !== ($row = $this->db->fetchArray($result))) {
            $parray[] = $row;

            $parray = $this->getAllChild($row[$this->id], $order, $parray);
        }

        return $parray;
    }

    public function getChildTreeArray($sel_id = 0, $order = '', $parray = [], $r_prefix = '')
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';

        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }

        $result = $this->db->query($sql);

        $count = $this->db->getRowsNum($result);

        if (0 == $count) {
            return $parray;
        }

        while (false !== ($row = $this->db->fetchArray($result))) {
            $row['prefix'] = $r_prefix . '.';

            $parray[] = $row;

            $parray = $this->getChildTreeArray($row[$this->id], $order, $parray, $row['prefix']);
        }

        return $parray;
    }
}
