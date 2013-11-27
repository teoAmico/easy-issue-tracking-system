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

class Login extends MX_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($data = null) {
        $this->load->view('login_form', $data);
    }

    public function auth($data = null) {
        $this->form_validation->set_rules('user', 'username', 'required');
        $this->form_validation->set_rules('pass', 'password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->module('homepage');
            $this->homepage->index();
        } else {
            $user = $this->input->post('user',TRUE);
            $pass = $this->input->post('pass',TRUE);
            $this->load->module('users');
            $is_loging_in = $this->users->login_user($user, $pass);
            if ($is_loging_in ==2) {
                //redirect to tickets page
               redirect(base_url('manage_tickets')); 
            } else if($is_loging_in == 0) {
                $data['msg'] = "This username or password doesn't exist on this server"; 
                $this->load->module('homepage');
                $this->homepage->index($data);
            }else{
                $data['msg'] = "This username is blocked. Please, contact the administrator."; 
                $this->load->module('homepage');
                $this->homepage->index($data);
            }
            
        }
    }

}
