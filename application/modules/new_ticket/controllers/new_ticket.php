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

class New_ticket extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->module('protect_webpage');
        if (!$this->protect_webpage->is_logged_in()) {
            redirect('homepage');
        }
    }

    public function index($data = null) {
        $data['title_page'] = 'New ticket - Tickets v'.APPLICATION_VERSION;
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
        //if user is admin show dashboard button
        if ($this->protect_webpage->is_admin()) {
            $data['is_admin'] = TRUE;
            $data['is_standard'] = FALSE;
        }else{
            $data['is_standard'] = TRUE;
        }
        $this->load->module('manage_tickets');
        $data['list_names'] = $this->manage_tickets->list_users_not_blocked_ordered();

        /* load module layout of the page */
        $data['username'] = $sess['name'];
        $data['view_file'] = 'new_ticket_layout';
        $data['module'] = 'new_ticket';
        $this->load->module('templates');
        $this->templates->registered_tmpl($data);
    }

    public function create() {
        $sess = $this->session->all_userdata();
        $this->form_validation->set_rules('title', 'Ticket title', 'required');
        $this->form_validation->set_rules('description', 'Ticket description', 'required');
        $this->form_validation->set_rules('tags', 'Tags', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $title = $this->input->post('title',TRUE);
            $description = $this->input->post('description',TRUE);
            $tags = $this->input->post('tags',TRUE);
            $created_by = $sess['user_id'];
            $assigned_to = $this->input->post('assigned_to',TRUE);
            $priority = $this->input->post('priority',TRUE);
            $this->load->model('new_ticket_model');
            $this->new_ticket_model->insert_new_ticket($title, $description, $tags, $created_by, $assigned_to, $priority);
            $this->index();
        }
    }
    
    public function tags(){
        $this->load->module('manage_tickets');
        $result = $this->manage_tickets->tags();
        echo json_encode($result);        
    }

}
