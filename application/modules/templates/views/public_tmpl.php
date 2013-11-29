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
        <title><?php if(isset($title_page)) echo $title_page;?></title>
        <!-- JQuery -->
        <script src="<?php js_url("jquery-1.10.2.js"); ?>"></script>
        <!-- Bootstrap core CSS -->
        <link href="<?php css_url("bootstrap.css"); ?>" rel="stylesheet">
        <script src="<?php js_url("bootstrap.js"); ?>"></script>
        
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand"  href="<?php base_url() ?>"><strong>Tickets</strong>&nbsp;&nbsp;<em><span style="font-size: 12px">V</span><span style="font-size: 10px">&nbsp;<?php echo APPLICATION_VERSION?></span></em></a>
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

