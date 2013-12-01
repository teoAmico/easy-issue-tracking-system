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
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard  extends MX_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->module('protect_webpage');
        if (!$this->protect_webpage->is_logged_in()) {
            redirect('homepage');
        }
        if($this->protect_webpage->is_logged_in() && (!$this->protect_webpage->is_admin())){
            redirect('manage_tickets');
        }
    }
    
    public function index(){
        $data['title_page'] = 'Dashboard - Tickets v'.APPLICATION_VERSION;
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
        
        /*in the first column of layout load module*/
        $data['view_file_first_col'] = 'create_user_form';
        $data['module_first_col'] = 'create_user';
        
        
        /*second column data table*/
        $this->load->module('users');
        $data['users_info'] = $this->users->show_all_users('name');
        
        /* load module layout of the page*/
        $data['is_admin'] = TRUE;
        $data['username'] = $sess['name'];
        $data['view_file'] = 'dashboard_layout';
        $data['module'] = 'dashboard';        
        $this->load->module('templates');
        $this->templates->registered_tmpl($data);        
    }
}
