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

class Edit_user extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->module('protect_webpage');
        if (!$this->protect_webpage->is_logged_in()) {
            redirect('homepage');
        }
        if($this->protect_webpage->is_logged_in() && (!$this->protect_webpage->is_admin())){
            redirect('tickets');
        }
    }

    public function user($ID = NULL) {

        //select data by ID from db
        $this->load->module('users');
        $user = $this->users->show_user_from_ID($ID);
        $edit_view = $this->load->view('edit_user_form', '', true);
        $edit_bt_create_user = $this->load->view('edit_bt_create_user', '', true);
        $result = array('edit_view' => $edit_view, 'bt_create_user'=>$edit_bt_create_user,'name' => $user['name'], 'ID' => $user['ID'], 'level' => $user['level'], 'state' => $user['state']);
        echo json_encode($result);
    }

    public function update() {
        $ID = $this->input->post('ID');
        $state = $this->input->post('state');
        $level = $this->input->post('level');
        $user = $this->input->post('user');
        $pass = $this->input->post('pass');
        // I check if this user is the only admnistrator in case I want to change his level
        $this->load->module('users');
        $is_the_only_admin = $this->users->is_the_only_admin($ID, $level);
        $can_blocked_admin = $this->users->can_blocked_admin($ID,$state);
        
        if ($is_the_only_admin) {
            //is the only admin can't be standard user.   check can be 0-1-2-3 see jquery ajax script  
            $result = array('check' => 0, 'change_name' => 0, 'name' => $user, 'level' => $level, 'ID' => $ID, 'name_available' => 1, 'state' => $state);
            
            echo json_encode($result);
            
        }elseif($can_blocked_admin){ //yes I can blocked an admin
            //check is this username exists
            $name_available = $this->users->name_available_edit_user($user,$ID);
            if ($name_available) {  
                //update user data
                $this->users->update_user_data($ID, $user, $pass, $level,$state);
                // check if current user change is name for navbar and update session
                $session_user_id = $this->session->userdata('user_id');
                $change_nav_name = 0; //a flag for know if the name is change in jquery
                if ($session_user_id == $ID) {
                    $change_nav_name = 1;
                    //update session data
                    $session_data = array(
                        'name' => $user,
                        'user_level' => $level
                    );
                    $this->session->set_userdata($session_data);
                }

                $result = array('check' => 2, 'change_name' => $change_nav_name, 'name' => $user, 'level' => $level, 'ID' => $ID,'name_available' => 1, 'state' => $state);
                echo json_encode($result);
            }else{
                //name isn't available
                $result = array('check' => 3, 'change_name' => 0, 'name' => $user, 'level' => $level, 'ID' => $ID, 'name_available' => 0, 'state' => $state);
                echo json_encode($result);
            }
            
            
        } else {
            //is the only admin can't be blocked set check value 1 for right warning msg with jquery
            $result = array('check' => 1, 'change_name' => 0, 'name' => $user, 'level' => $level, 'ID' => $ID, 'name_available' => 1, 'state' => $state);
            echo json_encode($result);
        }
    }

}
