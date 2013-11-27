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
<div class="container" >
    <div class="row">
        <div class="col-md-6">
            <h1>Settings</h1>            
        </div>
        <div class="col-md-6 text-right">
            <h1><a class="btn btn-default" href="<?php echo base_url('manage_tickets') ?>" >Manage Tickets</a></h1>
        </div>
    </div>
    <?php
    $attributes = array('id' => 'settings_user_form');
    echo form_open(base_url('settings/update_user'), $attributes);
    echo form_fieldset();
    ?>
    <div id="edit_legend">
        <legend >Change your username and password</legend>
    </div>
    <?php 
    if (isset($msg_success)) {
        echo '<div class="alert alert-dismissable alert-success msg"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong><br/>' . $msg_success . '</div>';
    }
    if (isset($msg_error)) {
        echo '<div class="alert alert-dismissable alert-danger msg"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong><br/>' . $msg_error . '</div>';
    }
    ?>
    <!-- msg custom -->
    <div id="msg_error"></div>
    <!-- loading-->
    <div class="progress progress-striped active " id="loading" >
        <div class="progress-bar" id="edit_progress_bar" style="width: 100%"></div>
    </div>


    <div class="row">
        <div class="col-lg-3">
            <!-- username -->
            <?php
            $attr_lb_user = array('class' => 'control-label');
            echo form_label('Username', 'username', $attr_lb_user);
            echo form_error('user', '<div style="color:#ff0000">', '</div>');
            echo '<div style="color:#ff0000" id="name_error"></div>';
            $attr_username = 'id="username" class="form-control"';
            $user = '';
            if(isset($username)){
                $user = $username;
            }
            echo form_input('user', $user  , $attr_username);
            ?>    
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-3">
            <!-- password -->
            <?php
            $attr_lb_pass = array('class' => 'control-label');
            echo form_label('Password', 'password', $attr_lb_pass);
            echo form_error('pass', '<div style="color:#ff0000">', '</div>');
            $attr_password = 'id="password" class="form-control" placeholder="New password"';
            echo form_password('pass', '', $attr_password);
            ?>    
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <!-- hidden and submit button-->
            <?php
            echo form_hidden('ID', '');
            $attr_bt_save = 'class="btn btn-primary"';
            echo form_submit('submit', 'Save', $attr_bt_save);
            ?>  
        </div>

    </div>
    <?php
    echo form_fieldset_close();
    echo form_close();
    ?>
</div>
