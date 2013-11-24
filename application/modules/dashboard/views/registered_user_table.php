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

echo form_fieldset();
echo '<div><legend>Manage users</legend></div>';
echo form_fieldset_close();
?>
<table class="table table-striped table-bordered table-hover table-condensed" id="dashboard_tbl">
    <thead>
        <tr>
            <th style="width:200px;">Name</th>
            <th style="width:180px;">Creation date</th>
            <th style="width:140px;">Level</th>
            <th class="text-center " style="width:100px;">State</th>
            <th class="text-center" style="width:80px;">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($users_info) && (sizeof($users_info) > 0)) {
            $open_tag_strike = '';
            $close_tag_strike = '';
            for ($i = 0; $i < sizeof($users_info); $i++) {
                if ($users_info[$i]['state'] == 0) {
                    $open_tag_strike = '<strike>';
                    $close_tag_strike = '</strike>';
                } else {
                    $open_tag_strike = '';
                    $close_tag_strike = '';
                }
                echo '<tr>';
                echo '<td id="name_' . $users_info[$i]['ID'] . '">' . $open_tag_strike . $users_info[$i]['name'] . $close_tag_strike . '</td>';
                echo '<td id="creation_date_' . $users_info[$i]['ID'] . '">' . $open_tag_strike . $users_info[$i]['creation_date'] . $close_tag_strike . '</td>';
                echo '<td id="level_' . $users_info[$i]['ID'] . '">' . $open_tag_strike;
                if ($users_info[$i]['level'] == 0) {
                    echo 'Administrator';
                } else {
                    echo 'Standard user';
                }
                echo $close_tag_strike . '</td>';
                echo '<td class="text-center" id="state_' . $users_info[$i]['ID'] . '">';
                if ($users_info[$i]['state'] == 0) {
                    echo '<span style="color:#ff0000" >Blocked</span>';
                } else {
                    echo '<span style="color:#00ff00" >Active</span>';
                }
                echo '</td>';
                echo '<td class="text-center"><a class="btn btn-primary btn-xs edit_user" href="' . base_url('edit_user/user/'. $users_info[$i]['ID'] ) . '">Edit</a></td>';
                echo '</tr>';
            }
        }
        ?>
    </tbody>
</table>    


