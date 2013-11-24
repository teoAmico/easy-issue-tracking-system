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


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('css_url')) {

    function css_url($name_file = '') {
        echo base_url('css/'.$name_file);
        
    }
}
if (!function_exists('js_url')) {
    function js_url($name_file = '') {
        echo base_url('js/'.$name_file);
        
    }
}
if (!function_exists('img_url')) {
    function img_url($name_file = '') {
        echo  base_url('images/'.$name_file);
        
    }

}