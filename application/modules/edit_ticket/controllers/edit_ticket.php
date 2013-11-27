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

class Edit_ticket extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->module('protect_webpage');
        if (!$this->protect_webpage->is_logged_in()) {
            redirect(base_url('homepage'));
        }
    }

    public function edit($ticket_id = null, $data = null) {
        $sess = $this->session->all_userdata();
        $data['creation_date'] = $sess['creation_date'];
        $data['created_open'] = $sess['created_open'];
        $data['created_inprogress'] = $sess['created_inprogress'];
        $data['created_closed'] = $sess['created_closed'];
        $data['assigned_open'] = $sess['assigned_open'];
        $data['assigned_inprogress'] = $sess['assigned_inprogress'];
        $data['assigned_closed'] = $sess['assigned_closed'];
        $data['updated'] = $sess['updated'];
        $this->load->module('manage_tickets');
        //if user is admin show dashboard button
        if ($this->protect_webpage->is_admin()) {
            $data['is_admin'] = TRUE;
            $data['is_standard'] = FALSE;
        }else{
            $data['is_standard'] = TRUE;
        }
        $ID = null;
        if (is_numeric($ticket_id)) {
            $ID = $ticket_id;
        }

        $data['ticket_data'] = $this->manage_tickets->select_ticket($ID);
        if (!isset($data['ticket_data'])) {
            redirect(base_url('manage_tickets'));
        }
        $data['list_names_full'] = $this->manage_tickets->list_users_ordered();
        $data['list_names_not_blocked'] = $this->manage_tickets->list_users_not_blocked_ordered();

        $data['list_tags'] = $this->manage_tickets->select_tags_ticket($ID);

        /* load module layout of the page */
        $data['username'] = $sess['name'];
        $data['view_file'] = 'edit_ticket_layout';
        $data['module'] = 'edit_ticket';
        $this->load->module('templates');
        $this->templates->registered_tmpl($data);
    }

    public function update() {
        $sess = $this->session->all_userdata();
        $this->form_validation->set_rules('title', 'Ticket title', 'required');
        $this->form_validation->set_rules('description', 'Ticket description', 'required');
        $this->form_validation->set_rules('tags', 'Tags', 'required');
        $ID = $this->input->post('ID',TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->edit($ID);
        } else {
            $title = $this->input->post('title',TRUE);
            $description = $this->input->post('description',TRUE);
            $tags = $this->input->post('tags',TRUE);
            $updated_by = $sess['user_id'];
            $assigned_to = $this->input->post('assigned_to',TRUE);
            $priority = $this->input->post('priority',TRUE);
            $state = $this->input->post('state',TRUE);
            $this->load->model('edit_ticket_model');
            $this->edit_ticket_model->update_ticket($ID,$title, $description, $tags, $updated_by, $assigned_to, $priority, $state);
            echo 'done, implement redirect';
            redirect('manage_tickets'); 
        }
    }

    public function tags() {
        $this->load->module('manage_tickets');
        $result = $this->manage_tickets->tags();
        echo json_encode($result);
    }

}
