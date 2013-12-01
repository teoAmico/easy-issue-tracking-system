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

class Users extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    /*
     * I use this function for the login
     */

    public function login_user($username = null, $password = null) {
        //select user in database
        $db_result = $this->users_model->select_user($username, $password);
        // I use check = 0-1-2 for user not exist, exist but blocked, exist and is active
        if ($db_result == NULL) { //user not exists
            return $check= 0;
        } else {  //user exists
            //check if is blocked
            if ($db_result['state'] == 0) {
               return $check = 1; 
            } else {
                $tickets_info = $this->users_model->user_info_tickets($db_result['ID']);
                //set session data
                $session_data = array(
                    'user_id' => $db_result['ID'],
                    'name' => $db_result['name'],
                    'logged_in' => TRUE,
                    'user_level' => $db_result['level'],
                    'creation_date' => $db_result['creation_date'],
                    //not use 
                    'created_open' =>$tickets_info['created_open'],
                    'created_inprogress' =>$tickets_info['created_inprogress'],
                    'created_closed' =>$tickets_info['created_closed'],
                    'assigned_open' =>$tickets_info['assigned_open'],
                    'assigned_inprogress' =>$tickets_info['assigned_inprogress'],
                    'assigned_closed' =>$tickets_info['assigned_closed'],
                    'updated' =>$tickets_info['updated']
                );
                $this->session->set_userdata($session_data);

                return $check = 2;
            }
        }
    }

    /* Order by:
     * - name
     * - creation_date
     * - level 
     *  */

    public function show_all_users($order_by = 'name') {
        $db_result = $this->users_model->select_all_users($order_by);
        return $db_result;
    }
    
    
    public function name_available($username = null) {
        $db_result = $this->users_model->select_name_if_exists($username);
        return $db_result;
    }

    public function registered_user($username = null, $password = null, $level = 1) {
        $db_result = $this->users_model->insert_user($username, $password, $level);
        return $db_result;
    }

    public function show_user($username = null, $password = null) {
        $db_result = $this->users_model->select_user($username, $password);
        return $db_result;
    }

    public function show_user_from_ID($ID) {
        $db_result = $this->users_model->select_user_from_ID($ID);
        return $db_result;
    }

    public function is_the_only_admin($ID, $level) {
        //I check if this user is an administrator
        $user_data = $this->users_model->select_user_from_ID($ID);
        if (($user_data['level'] == 0) && ($level == 1)) { // level = 1 because you try to change is level in standard 
            //check how many admin there are
            $sum = $this->users_model->count_admin();
            if ($sum == 1) { // I use 1 because I check is there is only one administrator
                return true;
            }
        }
        //Is not the only one administrator or you don't want to change the administrator level
        return false;
    }

    public function can_blocked_admin($ID, $state) {
        //check if the user is an administrator
        $user_data = $this->users_model->select_user_from_ID($ID);
        if (($user_data['level'] == 0) && ($state == 0)) {
            //check how many admin there are
            $sum = $this->users_model->count_admin();
            if ($sum == 1) {
                //there is only one admin can't be blocked
                return false;
            }
            return true;
        }
        return true;
        
    }

    public function update_user_data($ID, $user, $pass, $level, $state) {
        $db_result = $this->users_model->update_user($ID, $user, $pass, $level, $state);
        return $db_result;
    }

    public function name_available_edit_user($username, $ID) {
        $count = $this->users_model->count_name($username, $ID);
        if($count == 0){
            return true;
        }else{
            return false;
        }
    }

    /*
     * Select all users return array with ID and Name ordered by Name
     */

    public function all_users($ID) {
        $db_result = $this->users_model->select_users($ID);
        return $db_result;
    }
    
    public function all_users_not_blocked($ID) {
        $db_result = $this->users_model->select_users_not_blocked($ID);
        return $db_result;
    }
    
    public function updated_user($username,$password,$ID){
        $db_result = $this->users_model->update_username_and_or_password($username,$password,$ID);
        return $db_result;
    }
    
    public function user_info($ID){
        $db_result = $this->users_model->user_info_tickets($ID);
        return $db_result;
    }
}
