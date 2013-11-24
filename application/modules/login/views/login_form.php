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
    <?php
    echo form_open(base_url('login/auth'));
    echo form_fieldset();
    ?>
    <div>
        <legend ><h1>Login</h1></legend>
    </div>
    <!-- msg custom -->
    <?php if (isset($msg)) echo '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong><br/>'.$msg.'</div>'; ?>
    <div class="row">
        <!-- username -->
        <div class="col-lg-4">
            <?php
            $attr_lb_user = array('class' => 'control-label');
            echo form_label('Username', 'username', $attr_lb_user);
            echo form_error('user', '<div style="color:#ff0000">', '</div>');
            $attr_username = 'id="username" class="form-control"';
            echo form_input('user', set_value('user'), $attr_username);
            ?> 
        </div>
    </div>
    <br/>
    <div class="row">
        <!-- password -->
        <div class="col-lg-4">
            <?php
            $attr_lb_pass = array('class' => 'control-label');
            echo form_label('Password', 'password', $attr_lb_pass);
            echo form_error('pass', '<div style="color:#ff0000">', '</div>');
            $attr_password = 'id="password" class="form-control"';
            echo form_password('pass', '', $attr_password);
            ?>      
        </div>
    </div>
    <br/>
    <div class="row">
        <!-- submit -->
        <div class="col-lg-12">
            <?php
            $attr_bt_submit = 'class="btn btn-success"';
            echo form_submit('submit', 'Login', $attr_bt_submit);
            ?>    
        </div>
    </div>
    <?php
    echo form_fieldset_close();
    echo form_close();
    ?>
</div>