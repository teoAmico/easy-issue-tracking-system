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
<div class="row">
    <!-- loading-->
    <div class="col-lg-12" id="manage_table_loading" >
        <div class="progress progress-striped active" >
            <div class="progress-bar" id="edit_progress_bar" style="width: 100%"></div>
        </div>
    </div>

    <div class="col-lg-12" id="div_table_manage_tickets">
        

        <table id="table_tickets" class="table table-striped table-bordered table-hover table-condensed data-table">
            <thead>
                <tr>
                    <th class="text-center" style="vertical-align:middle;">ID</th>
                    <th style="vertical-align:middle;min-width: 250px">Ticket title</th>
                    <th class="text-center" style="vertical-align:middle;min-width:100px;">&nbsp;State&nbsp;</th>
                    <th class="text-center" style="vertical-align:middle;min-width:80px;">&nbsp;Priority&nbsp;</th>
                    <th class="text-center" style="vertical-align:middle;min-width:100px;">&nbsp;Tags&nbsp;</th>
                    <th class="text-center" style="vertical-align:middle;">&nbsp;Created by&nbsp;</th>
                    <th style="vertical-align:middle;display: none;visibility: hidden">Creation date</th>
                    <th class="text-center" style="vertical-align:middle;width: 100px ">&nbsp;Assigned to&nbsp;</th>
                    <th class="text-center" style="vertical-align:middle;">&nbsp;Updated by&nbsp;</th>
                    <th  class="text-center" style="vertical-align:middle;display: none;visibility: hidden" >&nbsp;Updated date&nbsp;</th>
                    <th class="text-center" style="vertical-align:middle;width: 50px;">Action</th>
                </tr>
            </thead>
            <tbody> 
            </tbody>
        </table>
    </div>   
</div>
<div id="footer_new_ticket" ></div>