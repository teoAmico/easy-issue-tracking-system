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

class Homepage  extends MX_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->module('protect_webpage');
        if ($this->protect_webpage->is_logged_in()) {
            redirect(base_url('manage_tickets'));
        }
    }
    
    public function index($data = null){
        $data['view_file'] = 'login_form';
        $data['module'] = 'login';
        $this->load->module('templates');
        $this->templates->public_tmpl($data);
    }
}
