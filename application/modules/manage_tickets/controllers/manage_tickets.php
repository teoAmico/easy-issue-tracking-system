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

class Manage_tickets extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->module('protect_webpage');
        if (!$this->protect_webpage->is_logged_in()) {
            redirect('homepage');
        }
        $this->load->model('manage_tickets_model');
    }

    public function list_users_not_blocked_ordered() {
        $sess = $this->session->all_userdata();
        //to put login user in top of the list of users
        $current_user[0] = array('ID' => $sess['user_id'], 'name' => $sess['name']);
        $this->load->module('users');
        $unsorted_list_names = $this->users->all_users_not_blocked($sess['user_id']);
        $array_merge = array_merge($current_user, $unsorted_list_names);
        $sorted_list_names = array();
        for ($i = 0; $i < sizeof($array_merge); $i++) {
            $sorted_list_names[$array_merge[$i]['ID']] = $array_merge[$i]['name'];
        }
        return $sorted_list_names;
    }

    public function list_users_ordered() {
        $sess = $this->session->all_userdata();
        //to put login user in top of the list of users
        $current_user[0] = array('ID' => $sess['user_id'], 'name' => $sess['name']);
        $this->load->module('users');
        $unsorted_list_names = $this->users->all_users($sess['user_id']);
        $array_merge = array_merge($current_user, $unsorted_list_names);
        $sorted_list_names = array();
        for ($i = 0; $i < sizeof($array_merge); $i++) {
            $sorted_list_names[$array_merge[$i]['ID']] = $array_merge[$i]['name'];
        }
        return $sorted_list_names;
    }

    public function index($data = null) {
        $sess = $this->session->all_userdata();
        $data['list_names_full'] = $this->list_users_ordered();
        $data['list_names_not_blocked'] = $this->list_users_not_blocked_ordered();
        $data['creation_date'] = $sess['creation_date'];
        $data['created_open'] = $sess['created_open'];
        $data['created_inprogress'] = $sess['created_inprogress'];
        $data['created_closed'] = $sess['created_closed'];
        $data['assigned_open'] = $sess['assigned_open'];
        $data['assigned_inprogress'] = $sess['assigned_inprogress'];
        $data['assigned_closed'] = $sess['assigned_closed'];
        $data['updated'] = $sess['updated'];


        // first row layout
        $data['view_file_filters'] = 'filters_fields';
        $data['module_filters'] = 'filters';
        $this->load->module('filters');
        $data['user_filters'] = $this->filters->show_user_filters();
        $data['view_file_filter_tags'] = 'tags_view';
        $data['module_filter_tags'] = 'filter_tags';
        $this->load->module('filter_tags');
        $data['list_tags'] = $this->filter_tags->full_list_tags();


        //if user is admin show dashboard button
        if ($this->protect_webpage->is_admin()) {
            $data['is_admin'] = TRUE;
            $data['is_standard'] = FALSE;
        }else{
            $data['is_standard'] = TRUE;
        }
        /* load module layout of the page */
        $data['username'] = $sess['name'];
        $data['view_file'] = 'manage_tickets_layout';
        $data['module'] = 'manage_tickets';
        $this->load->module('templates');
        $this->templates->registered_tmpl($data);
    }

    public function tags() {
        $result = $this->manage_tickets_model->select_names_tags();
        return $result;
    }

    public function select_ticket($ID = NULL) {
        $result = $this->manage_tickets_model->select_ticket($ID);
        return $result;
    }

    public function select_tags_ticket($ID = NULL) {
        $result = $this->manage_tickets_model->select_tags_ticket($ID);
        return $result;
    }


    public function select_data_tickets_ajax() {
        $sess = $this->session->all_userdata();
        /*
         * Paging
         */
        //Sortable columns
        $aColumns = array(
            'tickets.ID', 
            'tickets.title', 
            'tickets.state',
            'tickets.priority',
            '',
            'tickets.created_by',
            '',
            'assigned.name',
            'updated.name' ,
            
        );
        $bColumns = array(
            'tickets.title', 
            'tickets.description'
            
        );
        $sLimit = "";
        if ($this->input->get('iDisplayStart') && ($this->input->get('iDisplayLength') != '-1')) {
            $sLimit = " LIMIT " . $this->input->get('iDisplayStart',TRUE) . ", " .
                    $this->input->get('iDisplayLength',TRUE);
        }
        /*
         * Ordering
         */
        $sOrder = "";
        if ($this->input->get('iSortCol_0')) {
            $sOrder = " ORDER BY  ";
            for ($i = 0; $i < intval($this->input->get('iSortingCols')); $i++) {
                if ($this->input->get('bSortable_' . intval($this->input->get('iSortCol_' . $i))) == "true") {
                    $sOrder .= $aColumns[intval($this->input->get('iSortCol_' . $i))] . "
				 	" . $this->input->get('sSortDir_' . $i,TRUE) . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == " ORDER BY ") {
                $sOrder = "";
            }
        }
        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if ($this->input->get('sSearch') != "") {
            $sWhere = " ";
            for ($i = 0; $i < count($bColumns); $i++) {
                $sWhere .= $bColumns[$i] . " LIKE '%" . $this->input->get('sSearch',TRUE) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= " AND ";
        }

        //Individual column filtering 

        for ($i = 0; $i < count($bColumns); $i++) {
            if ($this->input->get('bSearchable_' . $i) == "true" && $this->input->get('sSearch_' . $i) != '') {
                    $sWhere .= " ";
                $sWhere .= $bColumns[$i] . " LIKE '%" . $this->input->get('sSearch_' . $i,TRUE) . "%' ";
                $sWhere .= " AND ";
            }
        }

        $table_data = $this->manage_tickets_model->select_data_table_tickets($sLimit, $sOrder, $sWhere,$sess['user_id']);
        echo json_encode($table_data);

    }

}
