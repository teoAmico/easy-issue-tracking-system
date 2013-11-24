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
            <h1>New ticket</h1>            
        </div>
        <div class="col-md-6 text-right">
            <h1><a class="btn btn-default" href="<?php echo base_url('manage_tickets') ?>" >Manage Tickets</a></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <p><small><em>All fields are required.</em></small></p>
        </div>
    </div>
    <?php
    $attributes = array('id' => 'new_ticket_form');
    echo form_open(base_url('new_ticket/create'), $attributes);
    ?>
    <div class="row">
        <div class="col-md-12">
            <?php
            $attr_lb_title = array('class' => 'control-label');
            echo form_label('Ticket title', 'new_title', $attr_lb_title);
            echo form_error('title', '<div style="color:#ff0000" id="title_error">', '</div>');
            echo '<div style="color:#ff0000" id="title_error"></div>';
            $title_attr = 'class="form-control" id="new_title"';
            echo form_input('title', set_value(), $title_attr);
            ?>
        </div>    
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <?php
            $attr_lb_description = array('class' => 'control-label');
            echo form_label('Ticket description', 'description', $attr_lb_description);
            echo form_error('description', '<div style="color:#ff0000" id="description_error">', '</div>');
            echo '<div style="color:#ff0000" id="description_error"></div>';
            $description_attr = 'class="form-control" id="description"';
            echo form_textarea('description', set_value(), $description_attr);
            ?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12" >
            <?php
            $attr_lb_tags = array('class' => 'control-label');
            echo form_label('Tags', 'new_tags', $attr_lb_tags);
            echo form_error('tags', '<div style="color:#ff0000" id="tags_error">', '</div>');
            echo '<div style="color:#ff0000" id="tags_error"></div>';
            ?>
            <div class="text-danger" id="tags_error"></div>
            <p><small><em>(Separate tags with comma, tab or press enter)</em></small></p>
            <input name="list_tags" autocomplete="off" id="tags" type="text" placeholder="Enter a tag"  class=" form-control tt-query" value="" style="width:280px" />

        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <div id="tags_container" id="tags_container"></div>
        </div> 
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-3">
            <?php
            $attr_lb_assigned = array('class' => 'control-label');
            echo form_label('Assigned to', 'assigned', $attr_lb_assigned);
            $options_assigned = array(0 => 'None');
            if (isset($list_names)) {
                $options_assigned = $list_names;
            }
            $extras_assigned = 'class="form-control" id="assigned" style="font-size:14px"';
            echo form_dropdown('assigned_to', $options_assigned, '', $extras_assigned);
            ?>
        </div>
        <div class="col-lg-9">
            <?php
            $attr_lb_priority = array('class' => 'control-label');
            echo form_label('Priority', 'priority', $attr_lb_priority);
            $options_priority = array('0' => 'Low', '1' => 'Normal', '2' => 'High');
            $extras_priority = 'class="form-control" id="priority" style="width:200px;font-size:14px"';
            echo form_dropdown('priority', $options_priority, 1, $extras_priority);
            ?> 
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <?php
            $attr_bt_create = 'class="btn btn-success"';
            echo form_submit('submit', 'Create ticket', $attr_bt_create);
            echo '&nbsp;&nbsp;';
            echo '<a class="btn btn-default" href="' . base_url('manage_tickets') . '" >Cancel</a>';
            ?>
        </div>
    </div>
    <?php
    echo form_close();
    ?>
    <div id="footer_new_ticket"></div>
</div>
