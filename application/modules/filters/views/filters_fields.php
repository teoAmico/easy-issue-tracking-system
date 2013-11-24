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

$attributes = array('id' => 'filters_form');
echo form_open(base_url('filters/apply_filters'),$attributes);
?>
<div class="row">
    <div class="col-lg-4" >
        <?php
        $attr_lb_created = array('class' => 'control-label');
        echo form_label('Created by', 'created', $attr_lb_created);
        if (isset($list_names_full) && isset($list_names_not_blocked) && isset($user_filters)) {
            echo '<select name="created" class="form-control bk_color_blocked_created bk_color_blocked_user" id="created_filters">';
            echo '<option value="0" style="background:#ffffff;color:#000000">All</option>';
            $count = 0;
            foreach ($list_names_full as $key1 => $value1) {
                $selected = '';
                if($user_filters['created_by'] == $key1){
                    $selected = ' selected="selected" ';
                }else{
                    $selected = '';
                }
                foreach ($list_names_not_blocked as $key2 => $value2) {
                    if ($key1 == $key2) {
                        $count = 1;
                        break;
                    }
                }
                if ($count == 0) {
                    echo '<option value="' . $key1 . '" style="background:#FF0000;color:#ffffff"'.$selected.' class="option_selected_blocked_created">' . $value1 . ' - Blocked</option>';
                } else {
                    echo '<option value="' . $key1 . '" style="background:#ffffff;color:#000000"'.$selected.'>' . $value1 . '</option>';
                    $count = 0;
                }
            }
            echo '</select>';
        } else {
            echo '<select class="form-control" id="created"><option value="0">None</option></select>';
        }
        ?>   
    </div>
    <div class="col-lg-4">
        <?php
        $attr_lb_assigned = array('class' => 'control-label');
        echo form_label('Assigned to', 'assigned', $attr_lb_assigned);
        if (isset($list_names_full) && isset($list_names_not_blocked)  && isset($user_filters)) {
            echo '<select name="assigned" class="form-control bk_color_blocked_assigned bk_color_blocked_user" id="assigned_filters">';
            echo '<option value="0" style="background:#ffffff;color:#000000">All</option>';
            $count = 0;
            foreach ($list_names_full as $key1 => $value1) {
                $selected = '';
                if($user_filters['assigned_to'] == $key1){
                    $selected = ' selected="selected" ';
                }else{
                    $selected = '';
                }
                foreach ($list_names_not_blocked as $key2 => $value2) {
                    if ($key1 == $key2) {
                        $count = 1;
                        break;
                    }
                }
                if ($count == 0) {
                    echo '<option value="' . $key1 . '" style="background:#FF0000;color:#ffffff" class="option_selected_blocked_assigned" '.$selected.'>' . $value1 . ' - Blocked</option>';
                } else {
                    echo '<option value="' . $key1 . '" style="background:#ffffff;color:#000000" '.$selected.'>' . $value1 . '</option>';
                    $count = 0;
                }
            }
            echo '</select>';
        } else {
            echo '<select class="form-control" id="assigned"><option value="0">None</option></select>';
        }
        ?>
    </div>
    <div class="col-lg-4">
        <?php
        $attr_lb_updated_by = array('class' => 'control-label');
        echo form_label('Updated by', 'updated', $attr_lb_updated_by);
        if (isset($list_names_full) && isset($list_names_not_blocked)  && isset($user_filters)) {
            echo '<select name="updated" class="form-control bk_color_blocked_updated bk_color_blocked_user" id="updated_filters">';
            echo '<option value="0" style="background:#ffffff;color:#000000">All</option>';
            $count = 0;
            foreach ($list_names_full as $key1 => $value1) {
                $selected = '';
                if($user_filters['updated_by'] == $key1){
                    $selected = ' selected="selected" ';
                }else{
                    $selected = '';
                }
                foreach ($list_names_not_blocked as $key2 => $value2) {
                    if ($key1 == $key2) {
                        $count = 1;
                        break;
                    }
                }
                if ($count == 0) {
                    echo '<option value="' . $key1 . '" style="background:#FF0000;color:#ffffff" class="option_selected_blocked_updated"'.$selected.'>' . $value1 . ' - Blocked</option>';
                } else {
                    echo '<option value="' . $key1 . '" style="background:#ffffff;color:#000000" '.$selected.'>' . $value1 . '</option>';
                    $count = 0;
                }
            }
            echo '</select>';
        } else {
            echo '<select class="form-control" id="updated"><option value="0">None</option></select>';
        }
        ?>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-lg-3">

        <p ><strong>Priority</strong>&nbsp;</p>
        <?php
        $attr_ck_low = 'id="priority_low"';
        $low_flag = true;
        if (isset($user_filters) && $user_filters['low'] == 0) {
            $low_flag = false;
        }
        echo form_checkbox('priority[]', 0, $low_flag, $attr_ck_low);
        echo '&nbsp;';
        echo '<span>Low</span>';

        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
        $attr_ck_normal = 'id="priority_normal"';
        $normal_flag = true;
        if (isset($user_filters) && $user_filters['normal'] == 0) {
            $normal_flag = false;
        }
        echo form_checkbox('priority[]', 1, $normal_flag, $attr_ck_normal);
        echo '&nbsp;';
        echo '<span>Normal</span>';

        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
        $attr_ck_high = 'id="priority_high"';
        $high_flag = true;
        if (isset($user_filters) && $user_filters['high'] == 0) {
            $high_flag = false;
        }
        echo form_checkbox('priority[]', 2, $high_flag, $attr_ck_high);
        echo '&nbsp;';
        echo '<span>High</span>';
        ?>

    </div>
    <div class="col-lg-9">
        <p ><strong>State</strong>&nbsp;</p>
        <?php
        $attr_ck_open = 'id="state_open"';
        $open_flag = true;
        if (isset($user_filters) && $user_filters['open'] == 0) {
            $open_flag = false;
        }
        echo form_checkbox('state[]', 0, $open_flag, $attr_ck_open);
        echo '&nbsp;';
        echo '<span>Open</span>';

        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
        $attr_ck_inprogress = 'id="state_inprogress"';
        $inprogress_flag = true;
        if (isset($user_filters) && $user_filters['inprogress'] == 0) {
            $inprogress_flag = false;
        }
        echo form_checkbox('state[]', 1, $inprogress_flag, $attr_ck_inprogress);
        echo '&nbsp;';
        echo '<span>In progress</span>';

        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
        $attr_ck_closed = 'id="state_closed"';
        $closed_flag = true;
        if (isset($user_filters) && $user_filters['closed'] == 0) {
            $closed_flag = false;
        }
        echo form_checkbox('state[]', 2, $closed_flag, $attr_ck_closed);
        echo '&nbsp;';
        echo '<span>Closed</span>';
        ?>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-lg-12 ">
        <?php
        //I manage filter tags by its module 
        if (!isset($view_file_filter_tags)) {
            $view_file_filter_tags = "";
        }

        if (!isset($module_filter_tags)) {
            $module_filter_tags = $this->uri->segment(1);
        }
        if (($view_file_filter_tags != '') && ($module_filter_tags != '')) {
            $path = $module_filter_tags . '/' . $view_file_filter_tags;
            $this->load->view($path);
        }
        ?>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-lg-12 text-right">
        <?php
        echo form_hidden('filters_id', $user_filters['ID']);
        $extra_apply_btn = "class='btn btn-success'";
        echo form_submit('submit', 'Apply filters', $extra_apply_btn);
        ?>
    </div>
</div>
<?php echo form_close(); ?>