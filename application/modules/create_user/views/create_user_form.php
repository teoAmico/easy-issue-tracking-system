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
<div id="db_wrap_first_column">
    <?php
    $attributes = array('id' => 'create_user_form');
    echo form_open(base_url('create_user/registered_user'), $attributes);
    echo form_fieldset();
    ?>
    <div>
        <legend>Create user</legend>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!-- leading-->
            <div class="progress progress-striped active" id="loading">
                <div class="progress-bar" id="edit_progress_bar" style="width: 100%;"></div>
                <br/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!-- username -->
            <?php
            $attr_lb_user = array('class' => 'control-label');
            echo form_label('Username', 'username', $attr_lb_user);
            echo form_error('user', '<div style="color:#ff0000" id="name_error">', '</div>');
            echo '<div style="color:#ff0000" id="name_error"></div>';
            $attr_username = 'id="username" class="form-control"';
            echo form_input('user', set_value('user'), $attr_username);
            ?>    
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <!-- password -->
            <?php
            $attr_lb_pass = array('class' => 'control-label');
            echo form_label('Password', 'password', $attr_lb_pass);
            echo form_error('pass', '<div style="color:#ff0000" >', '</div>');
            echo '<div style="color:#ff0000" id="pass_error"></div>';
            $attr_password = 'id="password" class="form-control"';
            echo form_password('pass', '', $attr_password);
            ?>    
        </div>
    </div>   
    <br/>
    <div class="row ">
        <div class="col-lg-12">
            <!-- Level -->
            <?php
            $attr_lb_level = array('class' => 'control-label');
            echo form_label('Select level', 'level', $attr_lb_level);
            $options = array('0' => 'Administrator', '1' => 'Standard user');
            $extras = 'class="form-control" id="level" style="width:200px"';
            echo form_dropdown('level', $options, '', $extras);
            ?>                
        </div>
    </div>
    <br/>
    <div class="row ">
        <div class="col-lg-12">
            <!-- submit --> 
            <?php
            $attr_bt_submit = 'class="btn btn-success"';
            echo form_submit('submit', 'Create', $attr_bt_submit);
            ?>   
        </div>
    </div>
    <?php
    echo form_fieldset_close();
    echo form_close();
    ?>
</div>
