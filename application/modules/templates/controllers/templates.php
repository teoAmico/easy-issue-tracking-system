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

class Templates extends MX_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function public_tmpl($data = null) {
        $this->load->view('public_tmpl', $data);
    }

    public function registered_tmpl($data = null) {
        $this->load->view('registered_tmpl', $data);
    }

    public function user_info_ajax() {
        $sess = $this->session->all_userdata();
        $this->load->model('users/users_model','users_model');
        $tickets_info = $this->users_model->user_info_tickets($sess['user_id']);
                //set session data
                $session_data = array(
                    'created_open' =>$tickets_info['created_open'],
                    'created_inprogress' =>$tickets_info['created_inprogress'],
                    'created_closed' =>$tickets_info['created_closed'],
                    'assigned_open' =>$tickets_info['assigned_open'],
                    'assigned_inprogress' =>$tickets_info['assigned_inprogress'],
                    'assigned_closed' =>$tickets_info['assigned_closed'],
                    'updated' =>$tickets_info['updated']
                );
         $this->session->set_userdata($session_data);
         echo json_encode($session_data);
    }

}
