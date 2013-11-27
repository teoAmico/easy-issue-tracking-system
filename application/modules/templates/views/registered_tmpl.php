<!DOCTYPE html>
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
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Jquery -->
        <script charset="utf-8" type="text/javascript"  src="<?php js_url('jquery-1.10.2.js'); ?>"></script>
        <script charset="utf-8" type="text/javascript"  src="<?php js_url("jquery-ui.min.js"); ?>"></script>
        <link href="<?php css_url("jquery-ui.css"); ?>" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="<?php css_url("bootstrap.css"); ?>" rel="stylesheet">
        <script charset="utf-8" type="text/javascript" src="<?php js_url("bootstrap.js"); ?>"></script>
        <!-- TinyMCE -->
        <script charset="utf-8" type="text/javascript" src="<?php js_url("tinymce/tinymce.min.js") ?>"></script>
        <!-- Tags plugin -->
        <link href="<?php css_url('tagmanager.css'); ?>" rel="stylesheet">
        <script charset="utf-8" type="text/javascript" src="<?php js_url('tagmanager.js') ?>"></script>
        <script charset="utf-8" type="text/javascript" src="<?php js_url('typeahead.js') ?>"></script>
        <link href="<?php css_url('typeahead-bootstrap.css'); ?>" rel="stylesheet">
        <!-- dataTable -->
        <link href="<?php css_url('jquery.dataTables.css'); ?>" rel="stylesheet">
        <script charset="utf-8" type="text/javascript" src="<?php js_url('jquery.dataTables.min.js') ?>"></script>
        <script charset="utf-8" type="text/javascript" src="<?php js_url('dataTables-fnreload.js') ?>"></script>
        <!-- My assets -->
        <link href="<?php css_url('mystyle.css'); ?>" rel="stylesheet">
        <script charset="utf-8" type="text/javascript" src="<?php js_url('myscript.js') ?>"></script>
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-top">
            <div class="container">
                <a class="navbar-brand"  href="<?php echo base_url('manage_tickets'); ?>"><strong>Tickets</strong>&nbsp;&nbsp;<em><span style="font-size: 12px">V</span><span style="font-size: 10px">&nbsp;<?php echo APPLICATION_VERSION ?></span></em></a>
                <ul class="nav navbar-nav navbar-right " >
                    <?php
                    if (isset($username)) {
                        echo '<li><a class="btn"  rel="popover" data-placement="bottom" data-original-title="User\'s info"  href="javascript:void(0)" id="pop_user_nav"><small id="username_navbar">' . $username . '</small>&nbsp;<img src="' . base_url('images/user.png') . '" width="15" height="15"/></a></li>';
                    }
                    if (isset($is_admin) && ($is_admin == true)) {
                        echo '<li><a class="btn" href="' . base_url('dashboard') . '" >Dashboard</a></li>';
                    }
                    if (isset($is_standard) && ($is_standard == true)) {
                        echo '<li><a class="btn" href="' . base_url('settings') . '" >Settings</a></li>';
                    }
                    ?>
                    <li><a class="btn" href="<?php echo base_url('logout'); ?>" >Logout</a></li>
                </ul> 
            </div>
            <!-- info about user-->
            <div style="display: none;visibility:hidden;" id="pop_user_content">
                <div style="min-width: 200px;">
                    <div><strong>Registered</strong></div>
                    <div class="text-center"><small>
                            <?php
                            if (isset($creation_date)) {
                                $creation_user_date = date_create($creation_date);
                                echo ' On ';
                                echo date_format($creation_user_date, 'd-m-Y');
                                ;
                                echo ' at ';
                                echo date_format($creation_user_date, 'H:i:s');
                            }
                            ?></small></div>
                    <div class="div_separator"></div>
                    <div><strong>Tickets created</strong></div>
                    <div>
                        <small><span style="color:#000;">Open:</span>
                            <div class="badge" style="float: right;background-color: green" id="tks_created_open">
                                <?php
                                if (isset($created_open)) {
                                    echo $created_open;
                                }
                                ?>
                            </div>
                        </small>
                    </div>
                    <div><small><span style="color:#000;">In Progress:</span>
                            <div class="badge" style="float: right;background-color: #0000ff" id="tks_created_inprogress">
                                <?php
                                if (isset($created_inprogress)) {
                                    echo $created_inprogress;
                                }
                                ?>
                            </div>
                        </small>
                    </div>
                    <div><small><span style="color:#000;">Closed:</span>
                            <div class="badge" style="float: right;background-color: #ff0000" id="tks_created_closed">
                                <?php
                                if (isset($created_closed)) {
                                    echo $created_closed;
                                }
                                ?>
                            </div>
                        </small>
                    </div>
                    <div class="div_separator"></div>
                    <div><strong>Tickets assigned</strong></div>
                    <div><small><span style="color:#000;">Open:</span>
                            <div class="badge" style="float: right;background-color: green" id="tks_assigned_open">
                                <?php
                                if (isset($assigned_open)) {
                                    echo $assigned_open;
                                }
                                ?>
                            </div>
                        </small>
                    </div>
                    <div><small><span style="color:#000;">In Progress:</span>
                            <div class="badge" style="float: right;background-color: #0000ff" id="tks_assigned_inprogress">
                                <?php
                                if (isset($assigned_inprogress)) {
                                    echo $assigned_inprogress;
                                }
                                ?>
                            </div>
                        </small>
                    </div>
                    <div><small><span style="color:#000;">Closed:</span>
                            <div class="badge" style="float: right;background-color: #ff0000" id="tks_assigned_closed">
                                <?php
                                if (isset($assigned_closed)) {
                                    echo $assigned_closed;
                                }
                                ?>
                            </div>
                        </small>
                    </div>
                    <div class="div_separator"></div>
                    <div><strong>Tickets updated</strong></div>
                    <div><small><span style="color:#000;">Total:</span>
                            <div class="badge" style="float: right" id="tks_updated">
                                <?php
                                if (isset($updated)) {
                                    echo $updated;
                                }
                                ?>
                            </div>
                        </small>
                    </div>
                </div>
            </div>
        </div>  
        <?php
        if (!isset($view_file)) {
            $view_file = "";
        }

        if (!isset($module)) {
            $module = $this->uri->segment(1);
        }
        if (($view_file != '') && ($module != '')) {
            $path = $module . '/' . $view_file;
            $this->load->view($path);
        }
        ?>
    </body>
</html>