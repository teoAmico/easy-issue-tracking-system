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

class Settings extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->module('protect_webpage');
        if (!$this->protect_webpage->is_logged_in()) {
            redirect('homepage');
        }
        if ($this->protect_webpage->is_admin()) {
            redirect('manage_tickets');
        }
    }

    public function index($data = null) {
        $data['title_page'] = 'Settings - Tickets v'.APPLICATION_VERSION;
        $sess = $this->session->all_userdata();
        $this->load->module("users");
        $user_info = $this->users->user_info($sess['user_id']);
        $data['creation_date'] = $sess['creation_date'];
        $data['created_open'] = $user_info['created_open'];
        $data['created_inprogress'] = $user_info['created_inprogress'];
        $data['created_closed'] = $user_info['created_closed'];
        $data['assigned_open'] = $user_info['assigned_open'];
        $data['assigned_inprogress'] = $user_info['assigned_inprogress'];
        $data['assigned_closed'] = $user_info['assigned_closed'];
        $data['updated'] = $user_info['updated'];
        $data['is_admin'] = FALSE;
        $data['is_standard'] = TRUE;

        /* load module layout of the page */
        $data['username'] = $sess['name'];
        $data['view_file'] = 'settings_view';
        $data['module'] = 'settings';
        $this->load->module('templates');
        $this->templates->registered_tmpl($data);
    }

    public function update_user() {
        $sess = $this->session->all_userdata();
        $name = $sess['name'];
        $user = $this->input->post('user', TRUE);
        $this->load->module('users');
        if ($name != $user) {
            //I check if the new username already exist
            
            $name_exit = $this->users->name_available($user);
            if ($name_exit > 0) {
                
                $data['msg_error'] = "This username already exist";
                $this->index($data);
                return false;
            }
        }
        //make update
        $pass = $this->input->post('pass', TRUE);
        $ID = $sess['user_id'];
        $this->users->updated_user($user,$pass,$ID);
        //updated session name
        $this->session->set_userdata('name', $user);
        
        if($pass){
            $data['msg_success'] = "The username and password have been changed.";
        }else{
            $data['msg_success'] = "The username has been changed.";
            
        }
        
        $this->index($data);
    }

}
