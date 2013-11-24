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

class Manage_tickets_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select_names_tags() {
        $table_name = TABLE_PREFIX . 'tags';
        $string_query = "SELECT name FROM $table_name";
        $rows = $this->db->query($string_query);
        /* created array bidimensional with all users */
        $result = array();
        $i = 0;
        while ($arr = $rows->_fetch_assoc()) {
            $result[] = $arr['name'];
            $i++;
        }
        return $result;
    }

    public function select_ticket($ID) {
        $table_ticket = TABLE_PREFIX . 'tickets';
        $table_user = TABLE_PREFIX . 'users';
        $string_query = "SELECT ticket.ID, created.name AS created, ticket.assigned_to AS assigned , updated.name AS updated , ticket.creation_date, ticket.update_date, ticket.title, ticket.description , ticket.state, ticket.priority
	FROM $table_ticket  AS ticket 
	LEFT JOIN $table_user AS created ON ticket.created_by = created.ID
  	LEFT JOIN $table_user AS assigned ON ticket.assigned_to = assigned.ID 
	LEFT JOIN $table_user AS updated ON ticket.updated_by = updated.ID 
	WHERE ticket.ID='$ID'";
        $row = $this->db->query($string_query);
        $result = $row->_fetch_assoc();
        return $result;
    }

    public function select_tags_ticket($ID) {
        $table_ticket = TABLE_PREFIX . 'tickets';
        $table_link_tags_tickets = TABLE_PREFIX . 'link_tags_tickets';
        $table_tags = TABLE_PREFIX . 'tags';
        $string_query = "SELECT tags.name 
            FROM $table_ticket AS ticket
            JOIN $table_link_tags_tickets AS link  ON link.ticket_id = ticket.ID 
            JOIN $table_tags AS tags ON tags.ID = link.tag_id     
            WHERE ticket.ID='$ID' AND NOT link.trash = '1'";
        $rows = $this->db->query($string_query);

        /* created array bidimensional with all tags for the ticket */
        $result = array();
        $i = 0;
        while ($arr = $rows->_fetch_assoc()) {
            $result[] = $arr['name'];
            $i++;
        }
        return $result;
    }


    public function select_data_table_tickets($sLimit, $sOrder, $sWhere, $ID) {
        $table_filters = TABLE_PREFIX . 'filters';
        $table_tags_filters = TABLE_PREFIX . 'tags_filters';
        $table_link_tags_tickets = TABLE_PREFIX . 'link_tags_tickets';
        $table_users = TABLE_PREFIX . 'users';
        $table_tickets = TABLE_PREFIX . 'tickets';
        $table_tags = TABLE_PREFIX . 'tags';
        $string_query_filters = "SELECT * FROM $table_filters WHERE user_id='$ID'";
        $row = $this->db->query($string_query_filters);
        $filters = $row->_fetch_assoc();
        $string_query_tags_filters = "SELECT tag_id FROM $table_tags_filters WHERE user_id='$ID' AND selected='1'";
        $tags_rows = $this->db->query($string_query_tags_filters);
        $tags_filters = array();
        while ($arr = $tags_rows->_fetch_assoc()) {
            $tags_filters[] = $arr['tag_id'];
        }
        $tags_list = "'";
        if (sizeof($tags_filters) != 0) {
            for ($i = 0; $i < sizeof($tags_filters); $i++) {
                if (($i + 1) == sizeof($tags_filters)) {
                    $tags_list = $tags_list . $tags_filters[$i] . "'"; //is last element 
                } else {
                    $tags_list = $tags_list . $tags_filters[$i] . '|'; // | is OR for tags
                }
            }
        } else {
            $tags_list = 'NULL';
        }


        $state_open = '';
        if ($filters['open'] == 1) {
            $state_open = " ( tickets.state='0'";
        }
        $state_inprogress = '';
        if ($filters['inprogress'] == 1) {
            if ($state_open != '') {
                $state_inprogress = " OR tickets.state='1' ";
            } else {
                $state_inprogress = " ( tickets.state='1' ";
            }
        }
        $state_closed = '';
        if ($filters['closed'] == 1) {
            if ($state_inprogress != '' || $state_open != '') {
                $state_closed = " OR  tickets.state='2') AND ";
            } else {
                $state_closed = " ( tickets.state='2') AND ";
            }
        }
        if ($state_open != '' && $state_inprogress != '' && $state_closed == '') {
            $state_inprogress = $state_inprogress . ' ) AND ';
        }
        if ($state_open == '' && $state_inprogress != '' && $state_closed == '') {
            $state_inprogress = $state_inprogress . ' ) AND ';
        }
        if ($state_open != '' && $state_closed == '' && $state_inprogress == '') {
            $state_open = $state_open . ' ) AND ';
        }


        $priority_low = '';
        if ($filters['low'] == 1) {
            $priority_low = " ( tickets.priority='0' ";
        }
        $priority_normal = '';
        if ($filters['normal'] == 1) {
            if ($priority_low != '') {
                $priority_normal = " OR tickets.priority='1' ";
            } else {
                $priority_normal = " ( tickets.priority='1' ";
            }
        }
        $priority_high = '';
        if ($filters['high'] == 1) {
            if ($priority_normal != '' || $priority_low != '') {
                $priority_high = " OR  tickets.priority='2') AND ";
            } else {
                $priority_high = " ( tickets.priority='2') AND ";
            }
        }
        if ($priority_low != '' && $priority_high == '' && $priority_normal == '') {
            $priority_low = $priority_low . ' ) AND ';
        }
        if ($priority_low != '' && $priority_normal != '' && $priority_high == '') {
            $priority_normal = $priority_normal . ' ) AND ';
        }
        if ($priority_low == '' && $priority_normal != '' && $priority_high == '') {
            $priority_normal = $priority_normal . ' ) AND ';
        }



        $created = '';
        if ($filters['created_by'] != 0) {
            $created = " tickets.created_by ='" . $filters['created_by'] . "' AND ";
        }
        $assigned = '';
        if ($filters['assigned_to'] != 0) {
            $assigned = " tickets.assigned_to ='" . $filters['assigned_to'] . "' AND ";
        }
        $updated = '';
        if ($filters['updated_by'] != 0) {
            $updated = " tickets.updated_by = '" . $filters['updated_by'] . "'  AND ";
        }


        $string_query_select_tickets_by_filters = "SELECT tickets.ID, tickets.title, 
            tickets.state, tickets.priority, tickets.created_by as created_id , created.name as created_name,
            tickets.assigned_to as assigned_id , assigned.name as assigned_name, 
            tickets.updated_by as updated_id, updated.name as updated_name, 
            tickets.creation_date, tickets.update_date, GROUP_CONCAT(tags.name) AS tags 
            FROM $table_tickets AS tickets
            LEFT JOIN $table_users AS created ON tickets.created_by = created.ID
            LEFT JOIN $table_users AS assigned ON tickets.assigned_to = assigned.ID 
            LEFT JOIN $table_users AS updated ON tickets.updated_by = updated.ID
            LEFT JOIN $table_link_tags_tickets AS link ON tickets.ID = link.ticket_id
            LEFT JOIN $table_tags AS tags ON link.tag_id = tags.ID 
            WHERE 
            $state_open $state_inprogress $state_closed $priority_low $priority_normal $priority_high
            $created $assigned $updated $sWhere tags.ID REGEXP $tags_list  
            GROUP BY tickets.ID $sOrder $sLimit";
        

        $rows = $this->db->query($string_query_select_tickets_by_filters);
        
        
        $result = array();
        $i = 0;
        while ($arr = $rows->_fetch_assoc()) {
            $result[$i] = $arr;
            $ticket_ID = $result[$i]['ID'];
            //I've to rewrite index tags od result array with all tags of the ticekt
            $string_query_select_tags_for_tickets = "SELECT GROUP_CONCAT(tags.name) AS tags_names
                FROM $table_tickets AS tickets
                LEFT JOIN $table_link_tags_tickets AS link ON tickets.ID = link.ticket_id
                LEFT JOIN $table_tags AS tags ON link.tag_id = tags.ID 
                WHERE link.ticket_id='$ticket_ID' AND link.trash='0'
                GROUP BY tickets.ID";
            $row = $this->db->query($string_query_select_tags_for_tickets);
            $result[$i]['tags'] = implode(",", $row->_fetch_assoc()); //I do this so you don't have $result[][tags][tags_names] but $result[][tags]
            $i++;
        }

        /* Total Data set length after filtering */
        $query_stirng_count = "SELECT tickets.ID
            FROM $table_tickets AS tickets
            LEFT JOIN $table_users AS created ON tickets.created_by = created.ID
            LEFT JOIN $table_users AS assigned ON tickets.assigned_to = assigned.ID 
            LEFT JOIN $table_users AS updated ON tickets.updated_by = updated.ID
            LEFT JOIN $table_link_tags_tickets AS link ON tickets.ID = link.ticket_id
            LEFT JOIN $table_tags AS tags ON link.tag_id = tags.ID 
            WHERE 
            $state_open $state_inprogress $state_closed $priority_low $priority_normal $priority_high
            $created $assigned $updated $sWhere tags.ID REGEXP $tags_list  
            GROUP BY tickets.ID ";
        $count = $this->db->query($query_stirng_count);
        $iFilteredTotal = $count->num_rows();
        

       

        /* Total data set length without filters */
        $string_query_total_count_tickets = "SELECT 'ID' FROM   $table_tickets";
        $rResultTotal = $this->db->query($string_query_total_count_tickets);
        $iTotal = $rResultTotal->num_rows();

        
        /*
         * Output
         */

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array(),
            "aoColumns" => array(
                'ID', //id
                'Title', //title
                'State', //state
                'Priority', //priority
                'Tags', //tags
                'Created by', //created by
                'Creation date', //creation date 
                'Assigned to', //assigned to
                'Update by', //updated by
                'Update date', //update date
                'Edit' //edit
            )
        );
        for($i=0;$i<  sizeof($result);$i++){
            $row = array(
                $result[$i]['ID'],
                $result[$i]['title'],
                $result[$i]['state'],
                $result[$i]['priority'],
                str_replace(',', ', ', $result[$i]['tags']),
                $result[$i]['created_name'],
                $result[$i]['creation_date'],
                $result[$i]['assigned_name'],
                $result[$i]['updated_name'],
                $result[$i]['update_date'],
                $result[$i]['ID']);
            array_push($output["aaData"],$row);
        }
        return $output;
    }

}