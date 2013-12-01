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

class Users_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        
    }

    public function select_user($username, $password) {
        $user = trim($username);
        $pass = sha1(trim($password));
        $table_name = TABLE_PREFIX .'users';
        $string_query = "SELECT * FROM $table_name WHERE name=? AND password=?";
        $values = array("$user","$pass");
        $obj_query = $this->db->query($string_query,$values);
        $result = $obj_query->_fetch_assoc();
        return $result;
    }

    public function select_all_users($order_by) {
        $table_name = TABLE_PREFIX .'users';
        $string_query = "SELECT ID, name , creation_date, level, state FROM $table_name ORDER BY ?";
        $values = array("$order_by");
        $rows = $this->db->query($string_query, $values);
        $result = array();
        $i = 0;
        while ($arr = $rows->_fetch_assoc()) {
            $result[$i] = $arr;
            $i++;
        }
        return $result;
    }

    public function select_name_if_exists($username) {
        $table_name = TABLE_PREFIX .'users';
        $user = trim($username);
        $string_query = "SELECT ID FROM $table_name WHERE name=?";
        $values = array("$user");
        $obj_query = $this->db->query($string_query,$values);
        $result = $obj_query->num_rows();
        return $result;
    }

    public function insert_user($username, $password, $level_user) {
        $table_name = TABLE_PREFIX .'users';
        $user = trim($username);
        $pass = sha1(trim($password));
        $level = 1;
        if (is_numeric($level_user) && $level_user != 1) {
            $level = 0;
        }
        $date = date("Y-m-d H:i:s");
        $string_query_new_user = "INSERT INTO $table_name (name,password, creation_date, level) VALUES (?,?, ?,?)";
        $values_new_user = array("$user","$pass","$date","$level");
        $this->db->query($string_query_new_user,$values_new_user);
        $ID_user = $this->db->insert_id();
        $table_name = TABLE_PREFIX .'filters';
        $string_query_filter_new_user = "INSERT INTO $table_name (user_id) VALUES ('$ID_user')";
        $this->db->query($string_query_filter_new_user);
        return true;
    }

    public function select_user_from_ID($ID) {
        $table_name = TABLE_PREFIX .'users';
        $string_query = "SELECT * FROM $table_name WHERE ID='$ID'";
        $obj_query = $this->db->query($string_query);
        $result = $obj_query->_fetch_assoc();
        return $result;
    }

    public function count_admin() {
        $table_name = TABLE_PREFIX .'users';
        $string_query = "SELECT ID FROM $table_name WHERE level='0'";
        $obj_query = $this->db->query($string_query);
        $result = $obj_query->num_rows();
        return $result;
    }

    public function update_user($ID, $username, $password, $user_level,$user_state) {
        $table_name = TABLE_PREFIX .'users';
        $user = trim($username);
        $pass = sha1(trim($password));
        $level = 1;
        if ($user_level != 1) {
            $level = 0;
        }
        if (strlen(trim($password)) > 1) {        
            $string_query = "UPDATE $table_name SET name=?, password=?, level=?, state=? WHERE ID='$ID' ";
            $values = array("$user","$pass","$level","$user_state");
            $this->db->query($string_query,$values);
            return true;
        } else {
            $string_query = "UPDATE $table_name SET name=?, level=?, state=? WHERE ID='$ID' ";
            $values = array("$user","$level","$user_state");
            $this->db->query($string_query,$values);
            return true;
        }
    }

    public function update_username_and_or_password($username,$password,$ID){
        $table_name = TABLE_PREFIX .'users';
        $user = trim($username);
        $pass = sha1(trim($password));
        if (strlen(trim($password)) > 1) {        
            $string_query = "UPDATE $table_name SET name=?, password=? WHERE ID='$ID' ";
            $values = array("$user","$pass");
            $this->db->query($string_query,$values);
            return true;
        } else {
            $string_query = "UPDATE $table_name SET name=? WHERE ID='$ID' ";
            $values = array("$user");
            $this->db->query($string_query,$values);
            return true;
        }
    }
    public function count_name($username, $ID) {
        $table_name = TABLE_PREFIX .'users';
        $user = trim($username);
        $string_query = "SELECT ID FROM $table_name WHERE name=? AND NOT  ID='$ID'";
        $values = array("$user");
        $obj_query =  $this->db->query($string_query,$values);
        $result = $obj_query->num_rows();
        return $result;
    }

    public function select_users($ID) {
        $table_name = TABLE_PREFIX .'users';
        $string_query = "SELECT ID, name FROM $table_name WHERE ID NOT LIKE '$ID' ORDER BY name";
        $rows = $this->db->query($string_query);
        $result = array();
        $i = 0;
        while ($arr = $rows->_fetch_assoc()) {
            $result[$i] = $arr;
            $i++;
        }
        return $result;
    }
    
    public function select_users_not_blocked($ID) {
        $table_name = TABLE_PREFIX .'users';
        $string_query = "SELECT ID, name FROM $table_name WHERE ID NOT LIKE '$ID'  AND NOT state='0' ORDER BY name";
        $rows = $this->db->query($string_query);
        $result = array();
        $i = 0;
        while ($arr = $rows->_fetch_assoc()) {
            $result[$i] = $arr;
            $i++;
        }
        return $result;
    }

    public function user_info_tickets($ID){
        $table_tickets = TABLE_PREFIX. 'tickets';
        $result = array();
        $string_query_assiged_to_open = "SELECT ID FROM $table_tickets WHERE assigned_to='$ID' AND state ='0'";
        $obj_query_open = $this->db->query($string_query_assiged_to_open);
        $result['assigned_open'] = $obj_query_open->num_rows();
        $string_query_assiged_to_inprogress = "SELECT ID FROM $table_tickets WHERE assigned_to='$ID' AND state ='1'";
        $obj_query_inprogress = $this->db->query($string_query_assiged_to_inprogress);
        $result['assigned_inprogress'] = $obj_query_inprogress->num_rows();
        $string_query_assiged_to_closed = "SELECT ID FROM $table_tickets WHERE assigned_to='$ID' AND state ='2'";
        $obj_query_closed = $this->db->query($string_query_assiged_to_closed);
        $result['assigned_closed'] = $obj_query_closed->num_rows();
        
        $string_query_created_by_open = "SELECT ID FROM $table_tickets WHERE created_by='$ID' AND state ='0'";
        $obj_query_created_open = $this->db->query($string_query_created_by_open);
        $result['created_open'] = $obj_query_created_open->num_rows();
        $string_query_created_by_inprogress = "SELECT ID FROM $table_tickets WHERE created_by='$ID' AND state ='1'";
        $obj_query_created_inprogress = $this->db->query($string_query_created_by_inprogress);
        $result['created_inprogress'] = $obj_query_created_inprogress->num_rows();
        $string_query_created_by_closed = "SELECT ID FROM $table_tickets WHERE created_by='$ID' AND state ='2'";
        $obj_query_created_closed = $this->db->query($string_query_created_by_closed);
        $result['created_closed'] = $obj_query_created_closed->num_rows();
        
        $string_query_updated= "SELECT ID FROM $table_tickets WHERE updated_by='$ID'";
        $obj_query_updated = $this->db->query($string_query_updated);
        $result['updated'] = $obj_query_updated->num_rows();
        return $result;
        
    }
    
}