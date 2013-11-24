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
        <div class="col-lg-4">
            <h1>Dashboard</h1>
        </div>
        <div class="col-lg-8 text-right">
            <h1 id="bt_nav_dashboard"><a class="btn btn-default" href="<?php echo base_url('manage_tickets'); ?>" >Manage Tickets</a></h1> 
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-5" id="first-col">
            <?php
            if (!isset($view_file_first_col)) {
                $view_file_first_col = "";
            }

            if (!isset($module_first_col)) {
                $module_first_col = $this->uri->segment(1);
            }
            if (($view_file_first_col != '') && ($module_first_col != '')) {
                $path = $module_first_col . '/' . $view_file_first_col;
                $this->load->view($path);
            }
            ?>
        </div>
        <div class="col-md-7">
            <?php $this->load->view('registered_user_table'); ?>
        </div>
    </div>
    <div id="footer_dashboard"></div>
</div>

