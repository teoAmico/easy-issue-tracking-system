<?php
/*
Copyright 2013 © Matteo Amico
This file is part of Tickets.

Tickets is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation either version 2 of the License, or 
(at your option) any later version.

Tickets is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Tickets.  If not, see <http://www.gnu.org/licenses/>.
*/


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Filter_tags_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function list_tags($ID){
        $table_tags_filters = TABLE_PREFIX .'tags_filters';
        $table_tags = TABLE_PREFIX .'tags';
        $string_query = "SELECT $table_tags_filters.selected, $table_tags_filters.tag_id, $table_tags.name FROM $table_tags_filters JOIN $table_tags ON $table_tags_filters.tag_id = $table_tags.ID WHERE $table_tags_filters.user_id='$ID' AND trash='0'";
        $row = $this->db->query($string_query);
        $result = array();
        $count = 0 ;
        while ($arr = $row->_fetch_assoc()) {
            $result[$count] = $arr;
            $count++;
        }
        return $result;
    }
}
