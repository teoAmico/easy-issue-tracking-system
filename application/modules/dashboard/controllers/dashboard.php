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
        $sess = $this->session->all_userdata();
        $data['creation_date'] = $sess['creation_date'];
        $data['created_open'] = $sess['created_open'];
        $data['created_inprogress'] = $sess['created_inprogress'];
        $data['created_closed'] = $sess['created_closed'];
        $data['assigned_open'] = $sess['assigned_open'];
        $data['assigned_inprogress'] = $sess['assigned_inprogress'];
        $data['assigned_closed'] = $sess['assigned_closed'];
        $data['updated'] = $sess['updated'];
        
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
