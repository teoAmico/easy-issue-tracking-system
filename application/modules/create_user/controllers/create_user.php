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

class Create_user  extends MX_Controller {
    
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
    
    public function new_user(){
        echo $this->load->view('create_user_form','',true); 
    }
    
    public function registered_user(){
        $this->form_validation->set_rules('user', 'username', 'required');
        $this->form_validation->set_rules('pass', 'password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->module('dashboard');
            $this->homepage->index();
        } else {
            $user = $this->input->post('user',TRUE);
            $pass = $this->input->post('pass',TRUE);
            $level = $this->input->post('level',TRUE);
            $this->load->module('users');
            if($this->users->name_available($user) == 0){
                $this->users->registered_user($user, $pass, $level);
                $new_user = $this->users->show_user($user, $pass);
                $result = array('check'=> 1, 'ID' => $new_user['ID'] ,'name' => $new_user['name'],'date' => $new_user['creation_date'],'level' => $new_user['level']);
                echo json_encode($result);
            }else{
                //name already exits into database
                $result = array('check'=> 0);
                echo json_encode($result);
            }

            
        }        
        
    }
    
}
