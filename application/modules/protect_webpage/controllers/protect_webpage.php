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

class Protect_webpage  extends MX_Controller {
    
    public function __construct() {
        parent::__construct();
        
    }

    public function is_logged_in(){
        $is_logged_in = $this->session->userdata('logged_in');
        $user_id = $this->session->userdata('user_id');
        $user_level = $this->session->userdata('user_level');
        if(($is_logged_in == 1) && (is_numeric($user_id)) && (($user_level ==0) || ($user_level== 1)) ){
            return true;
        }else{
            return false;
        }

    }
    public function is_admin(){
        $user_level = $this->session->userdata('user_level');
        if($user_level == 0){
            return true;
        }else{
            return false;
        }
    }
}
