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

class Filters extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->module('protect_webpage');
        if (!$this->protect_webpage->is_logged_in()) {
            redirect('homepage');
        }
        $this->load->model('filters_model');
    }

    public function index($data = NULL) {
        return $this->load->view('filters_fields', $data, TRUE);
    }

    public function show_user_filters() {
        $sess = $this->session->all_userdata();
        $result = $this->filters_model->select_filters_by_user($sess['user_id']);
        return $result;
    }

    public function apply_filters() {
        $sess = $this->session->all_userdata();
        $input_post = array();
        $input_post[0] = $this->input->post('created',TRUE);
        $input_post[1] = $this->input->post('assigned',TRUE);
        $input_post[2] = $this->input->post('updated',TRUE);
        $input_post[3] = array();
        if (is_array($this->input->post('priority',TRUE))) {
            foreach ($this->input->post('priority',TRUE) as $value) {
                $input_post[3][] = $value;
            }
        }
        
        $input_post[4] = array();
        if (is_array($this->input->post('state',TRUE))) {
            foreach ($this->input->post('state',TRUE) as $value) {
                $input_post[4][] = $value;
            }
        } 
        
        $input_post[5] = array();
        if (is_array($this->input->post('tags',TRUE))) {
            foreach ($this->input->post('tags',TRUE) as $value) {
                $input_post[5][] = $value;
            }
        }
        $this->filters_model->update_filters($sess['user_id'],$input_post);
        echo json_encode(array('update'=>TRUE));

    }

}
