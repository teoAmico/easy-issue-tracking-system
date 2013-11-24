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

class Filters_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select_filters_by_user($ID) {
        $table_filters = TABLE_PREFIX . 'filters';
        $string_query = "SELECT * FROM $table_filters WHERE user_id='$ID'";
        $rows = $this->db->query($string_query);
        $result = $rows->_fetch_assoc();
        return $result;
    }

    public function update_filters($ID = null, $data_to_update = array()) {
        $table_filters = TABLE_PREFIX . 'filters';
        $created = 'NULL';
        $assigned = 'NULL';
        $updated ='NULL';
        if($data_to_update[0] != 0){
            $created = "'".$data_to_update[0]."'";
        }
        if($data_to_update[1] != 0){
            $assigned = "'".$data_to_update[1]."'";
        }
        if($data_to_update[2] != 0){
            $updated = "'".$data_to_update[2]."'";
        }
        
        
        
       
        $low = 0;
        $normal = 0;
        $high = 0;
        for($i=0;$i<  sizeof($data_to_update[3]);$i++){
            if($data_to_update[3][$i] == 0){
                $low = 1;
            }
            if($data_to_update[3][$i] == 1){
                $normal = 1;
            }
            if($data_to_update[3][$i] == 2){
                $high = 1;
            }
        }
        $open = 0;
        $inprogress =0;
        $closed = 0;
        for($i=0;$i<  sizeof($data_to_update[4]);$i++){
            if($data_to_update[4][$i] == 0){
                $open = 1;
            }
            if($data_to_update[4][$i] == 1){
                $inprogress = 1;
            }
            if($data_to_update[4][$i] == 2){
                $closed = 1;
            }
        }
        
        $string_query_update_filters = "UPDATE $table_filters 
            SET created_by=$created,assigned_to=$assigned,updated_by=$updated,open='$open',
                inprogress='$inprogress',closed='$closed',low='$low',normal='$normal',high='$high'
                WHERE user_id='$ID'";
        $this->db->query($string_query_update_filters);

        //update tags filters table
        $table_tags_filters = TABLE_PREFIX . 'tags_filters';

        $string_query_reset_tags_filters = "UPDATE $table_tags_filters SET selected='0' WHERE user_id='$ID'";
        $this->db->query($string_query_reset_tags_filters);
        $tags = '';
        for ($i = 0; $i < sizeof($data_to_update[5]); $i++) {
            if ($i+1 == sizeof($data_to_update[5])) {
                $tags = $tags . "'".$data_to_update[5][$i]."'"; //without ','
            } else {
                $tags = $tags . "'".$data_to_update[5][$i] ."'". ',';
            }
        }
        if($data_to_update[5] == NULL){
           $tags = 'NULL'; 
        }
        $string_query_updated_tags_filters = "UPDATE $table_tags_filters SET selected='1' WHERE tag_id IN ($tags) AND  user_id='$ID'";
        $this->db->query($string_query_updated_tags_filters);

        return true;
    }

}
