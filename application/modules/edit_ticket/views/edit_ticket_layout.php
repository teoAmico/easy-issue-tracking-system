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
        <div class="col-md-10">
            <h1>Edit ticket</h1>
        </div>
        <div class="col-md-2 text-right">
            <h1><a class="btn btn-default" href="<?php echo base_url('manage_tickets') ?>" >Manage Tickets</a></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php
            if (isset($ticket_data)) {
                $created_date = date_create($ticket_data['creation_date']);
                echo '<p><small><em>Created by ';
                echo $ticket_data['created'];
                echo ' on ';
                echo date_format($created_date, 'd-m-Y');
                ;
                echo ' at ';
                echo date_format($created_date, 'H:i:s');
                ;
                if ($ticket_data['updated'] !== null) {
                    $update_date = date_create($ticket_data['update_date']);
                    echo ', Updated by ';
                    echo $ticket_data['updated'];
                    echo ' on ';
                    echo date_format($update_date, 'd-m-Y');
                    ;
                    echo ' at ';
                    echo date_format($update_date, 'H:i:s');
                } else {
                    echo ', not yet updated by anyone.';
                }
                echo '</em></small></p>';
            }
            ?> 
        </div>
    </div>
    <?php
    $attributes = array('id' => 'edit_ticket_form');
    echo form_open(base_url('edit_ticket/update'), $attributes);
    ?>
    <div class="row">
        <div class="col-md-12">
            <?php
            $attr_lb_title = array('class' => 'control-label');
            echo form_label('Ticket title', 'title', $attr_lb_title);
            echo form_error('title', '<div style="color:#ff0000" id="title_error">', '</div>');
            echo '<div style="color:#ff0000" id="title_error"></div>';
            $title_attr = 'class="form-control" id="edit_title"';
            $title = '';
            if (isset($ticket_data)) {
                $title = $ticket_data['title'];
            }
            echo form_input('title', $title, $title_attr);
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
            $description = '';
            if (isset($ticket_data)) {
                $description = $ticket_data['description'];
            }
            echo form_textarea('description', $description, $description_attr);
            ?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <?php
            $attr_lb_tags = array('class' => 'control-label');
            echo form_label('Tags', 'new_tags', $attr_lb_tags);
            echo form_error('tags', '<div style="color:#ff0000" id="tags_error">', '</div>');
            echo '<div style="color:#ff0000" id="tags_error"></div>';
            ?>
            <div class="text-danger" id="tags_error"></div>
            <p><small><em>(Separate tags with comma, tab or press enter)</em></small></p>
            <input name="list_tags" autocomplete="off" id="edit_tags" type="text" placeholder="Enter a tag"  class=" form-control tt-query" value="" style="width:280px" />
            <?php
            $prefilled_tags = '';
            if(isset($list_tags)){
                $prefilled_tags = '';
                for($i=0;$i < sizeof($list_tags);$i++){
                    if($i ==0){
                        $prefilled_tags = $list_tags[$i];
                    }else{
                        $prefilled_tags = $prefilled_tags.','.$list_tags[$i];
                    }
                    
                }
            }
            echo '<input type="hidden" id="HiddenPrefilledTags"  value="'.$prefilled_tags.'" />';
            ?>
            
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
            if (isset($list_names_full) && isset($list_names_not_blocked) && isset($ticket_data)) {
                echo '<select name="assigned_to" class="form-control bk_color_blocked_user" id="assigned" style="font-size:14px">';
                $count = 0;
                foreach ($list_names_full as $key1 => $value1) {
                    foreach ($list_names_not_blocked as $key2 => $value2) {
                        if ($key1 == $key2) {
                            $count = 1;
                            break;
                        }
                    }
                    if ($count == 0) {
                        if ($key1 == $ticket_data['assigned']) {
                            echo '<option selected="selected" value="' . $key1 . '" style="background:#FF0000;color:#ffffff;font-size:14px" class="option_selected_blocked_user">' . $value1 . ' - Blocked</option>';
                        } else {
                            echo '<option value="' . $key1 . '" style="background:#FF0000;color:#ffffff;font-size:14px">' . $value1 . ' - Blocked</option>';
                        }
                    } else {
                        if ($key1 == $ticket_data['assigned']) {
                            echo '<option selected="selected"  value="' . $key1 . '" style="background:#ffffff;color:#000000;font-size:14px">' . $value1 . '</option>';
                            $count = 0;
                        } else {
                            echo '<option value="' . $key1 . '" style="background:#ffffff;color:#000000;font-size:14px">' . $value1 . '</option>';
                            $count = 0;
                        }
                    }
                }
                echo '</select>';
            } else {
                echo '<select class="form-control" id="assigned" style="font-size:14px"><option value="0">None</option></select>';
            }
            ?>
        </div>
        <div class="col-lg-2">
            <?php
            $attr_lb_priority = array('class' => 'control-label');
            echo form_label('Priority', 'priority', $attr_lb_priority);
            $options_priority = array('0' => 'Low', '1' => 'Normal', '2' => 'High');
            $extras_priority = 'class="form-control " id="priority" style="width:180px;font-size:14px"';
            $priority_value = 1;
            if (isset($ticket_data)) {
                $priority_value = $ticket_data['priority'];
            }
            echo form_dropdown('priority', $options_priority, $priority_value, $extras_priority);
            ?>

        </div>
        <div class="col-lg-2">
            <?php
            $attr_lb_state = array('class' => 'control-label');
            echo form_label('State', 'state', $attr_lb_state);
            echo '<select name="state" class="form-control" id="edit_state" style="width:200px;font-size:14px">';
            echo '<option value="0" style="font-size:14px" ';
            if (isset($ticket_data) && $ticket_data['state'] == 0) {
                echo 'selected="selected"';
            }
            echo '>Open</option>';
            echo '<option value="1" style="font-size:14px" ';
            if (isset($ticket_data) && $ticket_data['state'] == 1) {
                echo 'selected="selected" ';
            }
            echo '>In progress</option>';
            echo '<option value="2" style="font-size:14px" ';
            if (isset($ticket_data) && $ticket_data['state'] == 2) {
                echo 'selected="selected" ';
            }
            echo '>Closed</option>';
            echo '</select>';
            ?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <?php
            $attr_bt_create = 'class="btn btn-success"';
            if(isset($ticket_data)){
                echo '<input type="hidden" name="ID" value="'.$ticket_data['ID'].'" />';
            }
            
            echo form_submit('submit', 'Update ticket', $attr_bt_create);
            echo '&nbsp;&nbsp;';
            echo '<a class="btn btn-default" href="'.  base_url('manage_tickets').'">Cancel</a>';
            ?>
        </div>
    </div>
    <?php
    echo form_close();
    ?>
    <div id="footer_new_ticket"></div>
</div>
