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

class New_ticket_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function insert_new_ticket($tk_title,$tk_description,$tk_tags,$tk_created_by,$tk_assigned_to,$tk_priority){
        
        $title = trim(mysql_real_escape_string($tk_title));
        $description = trim(mysql_real_escape_string($tk_description));
        $list_tags = trim(mysql_real_escape_string($tk_tags));
        $created_by = trim(mysql_real_escape_string($tk_created_by));
        $assigned_to = trim(mysql_real_escape_string($tk_assigned_to));
        $priority = trim(mysql_real_escape_string($tk_priority));
        $creation_date = date("Y-m-d H:i:s");
        $table_name = TABLE_PREFIX .'tickets';
        $string_query_tickets = "INSERT INTO $table_name (title,description,created_by,creation_date,assigned_to,priority) VALUES ('$title','$description','$created_by','$creation_date','$assigned_to',$priority)";
        $this->db->query($string_query_tickets);
        $ID_tickets = $this->db->insert_id();
        $tags = array_map('trim',explode(',', $list_tags));
        
        //I'm looking if actual new tags are present in tks_tags table 
        $table_tags = TABLE_PREFIX . 'tags';
        
        $exist_in_tags = array();
        $not_exist_in_tags = array();
        for ($i = 0; $i < sizeof($tags); $i++) {
            $query_tags = "SELECT * FROM $table_tags WHERE name='$tags[$i]'";
            $row = $this->db->query($query_tags);
            $result = $row->_fetch_assoc();
            if(isset($result)){
                $exist_in_tags[] = $result;
            }else{
                $not_exist_in_tags[] = $tags[$i];
            }
        }
        $table_link_tags_tickets = TABLE_PREFIX . 'link_tags_tickets';
        //I insert the new tags
        $table_tags_filters = TABLE_PREFIX .'tags_filters';
        for ($i = 0; $i < sizeof($not_exist_in_tags); $i++) {
            $tag_clean = str_replace(' ','',$not_exist_in_tags[$i]);
            $string_query_tags = "INSERT INTO $table_tags (name) VALUES ('$tag_clean')";
            $this->db->query($string_query_tags);
            $ID_tags = $this->db->insert_id();
            $string_query_link_tags_tickets = "INSERT INTO $table_link_tags_tickets (tag_id,ticket_id) VALUES ('$ID_tags','$ID_tickets')";
            $this->db->query($string_query_link_tags_tickets);
            
            $table_users = TABLE_PREFIX .'users';
            $string_query_users = "SELECT ID FROM $table_users ";
            $rows = $this->db->query($string_query_users);
            while ($row = $rows->_fetch_assoc()) {
                $user_id  =$row['ID'];
                $string_query_filter_tags = "INSERT INTO $table_tags_filters (tag_id,user_id) VALUES ('$ID_tags','$user_id')";
                $this->db->query($string_query_filter_tags);
            }
        }
        
        //I insert the tags that alredy exist in table tks_tags
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