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
?>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1>Manage tickets</h1>
        </div>
        <div class="col-md-6 text-right">
            <h1><a class="btn btn-default" href="<?php site_url() ?>new_ticket" >New Ticket</a></h1>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12" id="first-row">
            <?php
            //I manage filters by its module
            if (!isset($view_file_filters)) {
                $view_file_filters = "";
            }

            if (!isset($module_filters)) {
                $module_filters = $this->uri->segment(1);
            }
            if (($view_file_filters != '') && ($module_filters != '')) {
                $path = $module_filters . '/' . $view_file_filters;
                $this->load->view($path);
            }
            ?>
        </div>

    </div>
    <br/>
    <?php echo form_fieldset();?>
            <div id="legend">
                <legend class=""></legend>
            </div>
    <?php echo form_fieldset_close();?>
    <div class="row">
        <div class="col-md-12" id="main_table">
            <?php $this->load->view('manage_tickets_tbl'); ?>
        </div>
    </div>
</div>
