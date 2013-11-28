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

class Edit_ticket_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function update_ticket($ID, $tks_title, $tks_description, $tks_tags, $tks_updated_by, $tks_assigned_to, $tks_priority, $tks_state) {
        $title = trim($tks_title);
        $description = trim($tks_description);
        $list_tags = trim($tks_tags);
        $updated_by = trim($tks_updated_by);
        $assigned_to = trim($tks_assigned_to);
        $priority = trim($tks_priority);
        $state = trim($tks_state);
        $update_date = date("Y-m-d H:i:s");
        $ID_tickets = trim($ID);
        $table_ticket = TABLE_PREFIX . 'tickets';
        $string_query_tickets = "UPDATE $table_ticket 
            SET title=?,description=?,updated_by=?, update_date=?,assigned_to=?,priority=?,state=? WHERE ID='$ID_tickets'";
            $values_tickets = array("$title","$description","$updated_by","$update_date","$assigned_to","$priority","$state");
        $this->db->query($string_query_tickets,$values_tickets);
        
        //All Tags in input
        $tags = array_map('trim', explode(',', $list_tags));
        //select database tags for this ticket
        $table_link_tags_tickets = TABLE_PREFIX . 'link_tags_tickets';
        $table_tags = TABLE_PREFIX . 'tags';
        $string_query_all_tags_ticket = "SELECT tags.name, tags.ID
            FROM $table_ticket AS ticket
            JOIN $table_link_tags_tickets AS link  ON link.ticket_id = ticket.ID 
            JOIN $table_tags AS tags ON tags.ID = link.tag_id     
            WHERE ticket.ID='$ID_tickets' AND NOT link.trash='1'";
        $rows = $this->db->query($string_query_all_tags_ticket);
        $old_tags = array();
        $n = 0;
        while ($arr = $rows->_fetch_assoc()) {
            $old_tags[] = $arr;
            $n++;
        }
        //I'm looking for old tags that can be to trash compared to new tags
        $tags_trash = array();
        $counter = 0;

        for ($i = 0; $i < sizeof($old_tags); $i++) {
            for ($j = 0; $j < sizeof($tags); $j++) {
                $tag_clean = str_replace(' ', '', $old_tags[$i]['name']);
                if ($tag_clean == $tags[$j]) {
                    $counter = 1;
                    break;
                }
            }
            if ($counter == 0) {
                $tags_trash[] = $old_tags[$i]['ID'];
            }
            $counter = 0;
        }

        //set flag trash for old tags
        $table_tags_filters = TABLE_PREFIX.'tags_filters';
        
        $tags_filters_to_trash = array();
        for ($i = 0; $i < sizeof($tags_trash); $i++) {
            $string_query = "UPDATE $table_link_tags_tickets SET trash='1' WHERE tag_id='$tags_trash[$i]' AND ticket_id='$ID_tickets'";
            $this->db->query($string_query);
            
            //for tags_filters trash
            $query  = "SELECT ID FROM $table_link_tags_tickets WHERE tag_id='$tags_trash[$i]' AND trash='0'";
            $rows =  $this->db->query($query);
            $num = $rows->num_rows();
            if($num == 0){
                $tags_filters_to_trash[] = $tags_trash[$i];
            }
        }
        //set flag trash for old tags
        for ($i = 0; $i < sizeof($tags_filters_to_trash); $i++) {
            //if the tags not have tickets trash in tags_filters table       
            $string_query_tags_filters = "UPDATE $table_tags_filters SET trash='1' WHERE tag_id='$tags_filters_to_trash[$i]'";
            $this->db->query($string_query_tags_filters);
        }
        //I'm looking for new tags 
        $new_tags = array();
        $new_counter = 0;
        for ($i = 0; $i < sizeof($tags); $i++) {
            for ($j = 0; $j < sizeof($old_tags); $j++) {
                $tag_clean = str_replace(' ', '', $old_tags[$j]['name']);
                if ($tags[$i] == $tag_clean) {
                    $new_counter = 1;
                    break;
                }
            }
            if ($new_counter == 0) {
                $new_tags[] = $tags[$i];
            }
            $new_counter = 0;
        }
        //I check if new tags was in trash 
        $string_query_tags_trash = "SELECT link.tag_id, tags.name 
            FROM $table_link_tags_tickets AS link 
                JOIN $table_tags AS tags ON tags.ID = link.tag_id
                WHERE link.ticket_id='$ID_tickets' AND link.trash = '1'";
        $row_tags_name_trash = $this->db->query($string_query_tags_trash);
        $name_trash = array();
        $i = 0;
        while ($arr = $row_tags_name_trash->_fetch_assoc()) {
            $name_trash[] = $arr;
            $i++;
        }
        $resume_trash = array();
        $actual_new_tags = array();
        $trash_counter = 0;
        for ($i = 0; $i < sizeof($new_tags); $i++) {
            $tag_clean = str_replace(' ', '', $new_tags[$i]);
            for ($j = 0; $j < sizeof($name_trash); $j++) {

                if ($name_trash[$j]['name'] == $tag_clean) {
                    $resume_trash[] = $name_trash[$j];
                    $trash_counter = 1;
                    break;
                }
            }
            if ($trash_counter == 1) {
                $trash_counter = 0;
            } else {
                $actual_new_tags[] = $tag_clean;
            }
        }
        

        //I resume trash name 
        $resume_tags_fliters = array();
        for ($i = 0; $i < sizeof($resume_trash); $i++) {
            $tag_id = $resume_trash[$i]['tag_id'];
            $query  = "SELECT ID FROM $table_link_tags_tickets WHERE tag_id='$tag_id' AND trash='0'";
            $rows =  $this->db->query($query);
            $num = $rows->num_rows();
            if($num == 0){
                $resume_tags_fliters[] = $tag_id;
            }
            
            
            
            $string_query = "UPDATE $table_link_tags_tickets SET trash='0' WHERE tag_id='$tag_id' AND ticket_id='$ID_tickets'";
            $this->db->query($string_query);
            
             
            
        }

        //set flag trash for old tags
        for ($i = 0; $i < sizeof($resume_tags_fliters); $i++) {
            $string_query_tags_filters = "UPDATE $table_tags_filters SET trash='0' WHERE tag_id='$resume_tags_fliters[$i]'";
            $this->db->query($string_query_tags_filters);
        }
        
        //I'm looking if actual new tags are present in tks_tags table 
        $exist_in_tags = array();
        $not_exist_in_tags = array();
        for ($i = 0; $i < sizeof($actual_new_tags); $i++) {
            $query_tags = "SELECT * FROM $table_tags WHERE name=?";
            $values = array("$actual_new_tags[$i]");
            $row = $this->db->query($query_tags,$values);
            $result = $row->_fetch_assoc();
            if(isset($result)){
                $exist_in_tags[] = $result;
            }else{
                $not_exist_in_tags[] = $actual_new_tags[$i];
            }
        }
        
        //I insert the new tags
        for ($i = 0; $i < sizeof($not_exist_in_tags); $i++) {
            $tag_clean = str_replace(' ', '', $not_exist_in_tags[$i]);
            $string_query_tags = "INSERT INTO $table_tags (name) VALUES (?)";
            $values_tags = array("$tag_clean");
            $this->db->query($string_query_tags,$values_tags);
            $ID_tags = $this->db->insert_id();
            $string_query_link_tags_tickets = "INSERT INTO $table_link_tags_tickets (tag_id,ticket_id) VALUES ('$ID_tags','$ID_tickets')";
            $this->db->query($string_query_link_tags_tickets);
            $table_tags_filters = TABLE_PREFIX .'tags_filters';
            $table_users = TABLE_PREFIX .'users';
            $string_query_users = "SELECT ID FROM $table_users ";
            $rows = $this->db->query($string_query_users);
            while ($row = $rows->_fetch_assoc()) {
                $user_id  =$row['ID'];
                $string_query_filter_tags = "INSERT INTO $table_tags_filters (tag_id,user_id) VALUES ('$ID_tags','$user_id')";
                $this->db->query($string_query_filter_tags);
            }
        }
        //I insert the tags that alredy exist in table tk_tags
        for ($i = 0; $i < sizeof($exist_in_tags); $i++) {
            $ID_tags = $exist_in_tags[$i]['ID'];
            $string_query_link_tags_tickets = "INSERT INTO $table_link_tags_tickets (tag_id,ticket_id) VALUES ('$ID_tags','$ID_tickets')";
            $this->db->query($string_query_link_tags_tickets);
            
            //update tags in filters if is trash
            $string_query_tags_filters = "UPDATE $table_tags_filters SET trash='0' WHERE tag_id='$ID_tags'";
            $this->db->query($string_query_tags_filters);
        }
        
        
        return true;
    }

}

?>
