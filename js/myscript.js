$(document).ready(function() {
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

    /*
     * TinyMce Editor
     */
    var base_url = window.location.protocol + "//" + window.location.host + "/";
    var url = base_url + "tickets/css/myTinyMCE.css"
    tinymce.init({
        selector: "#description",
        theme: "modern",
        menubar: false,
        statusbar: true,
        content_css: url,
        plugins: [
            "advlist autolink link image lists charmap hr anchor pagebreak ",
            "searchreplace visualblocks visualchars code insertdatetime nonbreaking ",
            "textcolor"
        ],
        toolbar: "insertfile undo redo | searchreplace | styleselect | fontselect | fontsizeselect |  bold italic underline | forecolor backcolor |  bullist numlist outdent indent | link image "

    });
    /*
     * Link Edit user
     */
    $('#loading').hide();
    $('body').on('click', '.edit_user', function(e) {
        e.preventDefault();
        //IE9 & Other Browsers
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        //IE8 and Lower
        else {
            e.cancelBubble = true;
        }
        var url = $(this).attr('href');
        $.ajax({
            'url': url,
            'type': 'POST',
            'data': '',
            'success': function(data) {
                var result = JSON.parse(data);
                $('#bt_nav_dashboard').html(result['bt_create_user']);
                $('#first-col').html(result['edit_view']);
                $('#username').val(result['name']);
                $('#password').val('');
                $('#level').val(result['level']);
                $('#user_state').val(result['state']);
                if (result['state'] === '0') {
                    $('#level').attr('disabled', 'disabled');
                    $('#username').attr('readOnly', true);
                    $('#password').attr('readOnly', true);
                }
                $('input[name=ID]').val(result['ID'])
                var edit_title = '<legend>Edit user - ' + result['name'] + '</legend>';
                $('#edit_legend').html(edit_title);
                $('#loading').hide();
                return false;
            }
        });
        return false;
    });
    /*
     * Button Create user
     */
    $('body').on('click', '#bt_create_user', function(e) {
        e.preventDefault();
        //IE9 & Other Browsers
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        //IE8 and Lower
        else {
            e.cancelBubble = true;
        }
        var url = $(this).attr('href');
        $.ajax({
            'url': url,
            'type': 'GET',
            'success': function(data) {
                $("#loading").hide();
                $('#first-col').html(data);
                $('#bt_create_user').hide();
                $('#loading').hide();
                return false;
            }
        });
        return false;
    });
    /*
     * Create user form submit
     */

    $('body').on('submit', '#create_user_form', function(e) {

        e.preventDefault();
        //IE9 & Other Browsers
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        //IE8 and Lower
        else {
            e.cancelBubble = true;
        }
        var username = $('#username').val().length;
        if (username === 0) {
            $('#name_error').html('<p>The username field is required.</p>');
        } else {
            $('#name_error').html('');
        }
        var password = $('#password').val().length;
        if (password === 0) {
            $('#pass_error').html('<p>The password field is required.</p>');
        } else {
            $('#pass_error').html('');
        }
        if ((username !== 0) & (password !== 0)) {

            $("#loading").show();
            var string_data = $('#create_user_form').serialize();
            var url = $('#create_user_form').attr('action');
            $.ajax({
                'url': url,
                'type': 'POST',
                'data': string_data,
                'success': function(data) {
                    var result = JSON.parse(data);
                    if (result['check']) {
                        //update users table 
                        var level = 'Administrator';
                        if (result['level'] === '1') {
                            level = 'Standard user';
                        }
                        var state = 'Active';
                        if (result['state'] === 0) {
                            state = 'Blocked'; //never call this because when you create a user is always active just check for be sure, <td> has the style always green
                        }
                        var base_url = url.replace('create_user/registered_user', '');
                        var link_path = base_url + 'edit_user/user/' + result['ID'];
                        var link_edit = '<a class="btn btn-primary btn-xs edit_user" href="' + link_path + '">Edit</a>';
                        var row_tbl = '<tr>\n\
                                    <td id="name_' + result['ID'] + '">' + result['name'] + '</td>\n\
                                    <td id="creation_date_' + result['ID'] + '">' + result['date'] + '</td>\n\
                                    <td id="level_' + result['ID'] + '">' + level + '</td>\n\\n\
                                    <td class="text-center" style="color:#00ff00" id="state_' + result['ID'] + '">' + state + '</td>\n\\n\
                                    <td class="text-center">' + link_edit + '</td></tr>';
                        $('#dashboard_tbl tbody').append(row_tbl);
                        //reset form values
                        $('#username').val('');
                        $('#password').val('');
                        $('#level').val('0');
                    } else {
                        //user already exists
                        $('#name_error').html('<p>This username already exists.</p>');
                    }
                    $("#loading").hide();
                    return false;
                }
            });
        }
        return false;
    });
    $('body').on('submit', '#edit_user_form', function(e) {

        e.preventDefault();
        //IE9 & Other Browsers
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        //IE8 and Lower
        else {
            e.cancelBubble = true;
        }

        var username = $('#username').val().length;
        if (username === 0) {
            $('#name_error').html('<p>The username field is required.</p>');
        } else {
            $('#name_error').html('');
        }
        if (username !== 0) {
            $("#loading").show();
            $('#level').removeAttr('disabled'); // enable for submit option selected
            var string_data = $('#edit_user_form').serialize();
            var url = $('#edit_user_form').attr('action');
            $.ajax({
                'url': url,
                'type': 'POST',
                'data': string_data,
                'success': function(data) {
                    var result = JSON.parse(data);
                    if (result['check'] === 0) {
                        $('#msg_error').html('<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong><br/>At least one administrator must exist on this server before you can change this user level.</div>');
                        if (result['state'] === '0') {
                            $('#level').attr('disabled', 'disabled');
                        }
                    }
                    if (result['check'] === 1) {
                        $('#msg_error').html('<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong><br/>You can\'t blocked the only administrator on this server.</div>');
                        if (result['state'] === '0') {
                            $('#level').attr('disabled', 'disabled');
                        }
                    }
                    if (result['check'] === 2) {
                        var open_tag_strike = '<span>';
                        var close_tag_strike = '</span>';
                        if (result['state'] === '0') {
                            open_tag_strike = '<strike>';
                            close_tag_strike = '</strike>';
                        }
                        var id_name = '#name_' + result['ID'];
                        var id_level = '#level_' + result['ID'];
                        var id_state = '#state_' + result['ID'];
                        var id_creation_date = '#creation_date_' + result['ID'];
                        var creation_date = $(id_creation_date).text();
                        $(id_creation_date).html(open_tag_strike + creation_date + close_tag_strike);
                        $(id_name).html(open_tag_strike + result['name'] + close_tag_strike);
                        if (result['level'] === '0') {
                            $(id_level).html(open_tag_strike + 'Administrator' + close_tag_strike);
                        } else {
                            $(id_level).html(open_tag_strike + 'Standard user' + close_tag_strike);
                        }
                        if (result['state'] === '0') {
                            $(id_state).html('<span style="color:#ff0000" >Blocked</span>');
                            $('#level').attr('disabled', 'disabled');
                        } else {
                            $(id_state).html('<span style="color:#00ff00" >Active</span>');
                        }
                        if (result['change_name']) {
                            $('#username_navbar').text(result['name']);
                        }
                        var edit_title = '<legend>Edit user - ' + result['name'] + '</legend>';
                        $('#edit_legend').html(edit_title);
                        //reset form value
                        $('#password').val('');
                        $('#msg_error').html('');
                        $('#name_error').html('');
                    }
                    if (result['check'] === 3) {
                        $('#name_error').html('<p>This username already exists.</p>');
                        if (result['state'] === '0') {
                            $('#level').attr('disabled', 'disabled');
                        }
                    }


                    $("#loading").hide();
                    return false;
                }
            });
        }
        return false;
    });
    /*
     * New ticket check before submit
     * 
     */

    $('body').on('submit', '#new_ticket_form', function(e) {
        var title = $('#new_title').val().length;
        if (title === 0) {
            $('#title_error').html('<p>The Ticket title field is required.</p>');
        } else {
            $('#title_error').html('');
        }
        var description = $('#description').val().length;
        if (description === 0) {
            $('#description_error').html('<p>The Ticket description field is required.</p>');
        } else {
            $('#description_error').html('');
        }
        var tags = $('input[name="tags"]').val().length;
        if (tags === 0) {
            $('#tags_error').html('<p>The Tags field is required.</p>');
        } else {
            $('#tags_error').html('');
        }
        if ((title !== 0) && (description !== 0) && (tags !== 0)) {
            return true;
        } else {
            return false;
        }

    });
    /*
     * 
     */
    $('body').on('change', '#user_state', function(e) {
        e.preventDefault();
        //IE9 & Other Browsers
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        //IE8 and Lower
        else {
            e.cancelBubble = true;
        }

        var state = $("#user_state option:selected").val();
        if (state === '1') {
            $('#level').removeAttr('disabled');
            $('#username').attr('readOnly', false);
            $('#password').attr('readOnly', false);
        } else {
            $('#level').attr('disabled', 'disabled');
            $('#username').attr('readOnly', true);
            $('#password').attr('readOnly', true);
        }


    });
    /*
     * Change color backgorund dropdown
     */


    $('body').on('change', '.bk_color_blocked_user', function(e) {
        e.preventDefault();
        //IE9 & Other Browsers
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        //IE8 and Lower
        else {
            e.cancelBubble = true;
        }

        var style = $(this).children(":selected").attr("style");
        $(this).attr("style", style);
    });
    $('body').on('change', '.bk_color_state', function(e) {
        e.preventDefault();
        //IE9 & Other Browsers
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        //IE8 and Lower
        else {
            e.cancelBubble = true;
        }

        var style = $(this).children(":selected").attr("style");
        $(this).attr("style", style);
    });
    /*
     * Tag new-ticket
     */
    if (('#tags').length) {
        var tagApi = $('#tags').tagsManager({
            prefilled: [],
            tagsContainer: '#tags_container',
            tagClass: 'tm-tag tm-tag-success tm-tag-large',
            hiddenTagListName: 'tags',
            delimiters: [9, 13, 44], //use code ascii tab - enter - comma
            backspace: [] //don't delete insert tag 

        });
    }
    if ($('#new_ticket_form').length) {
        var url = $('#new_ticket_form').attr('action');
        var right_path = url.replace("create", "tags");
        $('#tags').typeahead({
            prefetch: right_path
        }).keydown(function(e) { //make sure that suggest box close after selection of item
            if ((e.which === 9) || (e.which === 13) || (e.which === 44)) {
                $('#tags').typeahead("setQuery", "");
            }

        });
    }



    //hack for bootstrap and typeahead in case you want use small or large input style
    $('.typeahead.input-sm').siblings('input.tt-hint').addClass('hint-small');
    $('.typeahead.input-lg').siblings('input.tt-hint').addClass('hint-large');
    /*
     * Assigned to in edit_user 
     */
    if ($('#assigned').length) {
        var element = $('#assigned').find("option:selected").attr("class");
        switch (element) {
            case "option_selected_blocked_user":
                var style = $('.bk_color_blocked_user').children(":selected").attr("style");
                $('.bk_color_blocked_user').attr("style", style);
                break;
        }
    }


    /*
     * Tag new-ticket
     */
    if (('#edit_tags').length && $("#HiddenPrefilledTags").length) {
        var prefilled_tags = $("#HiddenPrefilledTags").val();
        var prefilled_string = prefilled_tags.split(",");
        $('#edit_tags').tagsManager({
            prefilled: prefilled_string, //without [ ] 
            tagsContainer: '#tags_container',
            tagClass: 'tm-tag tm-tag-success tm-tag-large',
            hiddenTagListName: 'tags',
            delimiters: [9, 13, 44], //use code ascii tab - enter - comma
            backspace: [] //don't delete insert tag 

        });
    }


    if ($('#edit_ticket_form').length) {
        var url = $('#edit_ticket_form').attr('action');
        var right_path = url.replace("update", "tags");
        $('#edit_tags').typeahead({
            prefetch: right_path
        }).keydown(function(e) { //make sure that suggest box close after you select an item
            if ((e.which === 9) || (e.which === 13) || (e.which === 44)) {
                $('#edit_tags').typeahead("setQuery", "");
            }

        });
    }

    /*
     * Edit ticket check before submit
     * 
     */

    $('body').on('submit', '#edit_ticket_form', function(e) {
        var title = $('#edit_title').val().length;
        if (title === 0) {
            $('#title_error').html('<p>The Ticket title field is required.</p>');
        } else {
            $('#title_error').html('');
        }
        var description = $('#description').val().length;
        if (description === 0) {
            $('#description_error').html('<p>The Ticket description field is required.</p>');
        } else {
            $('#description_error').html('');
        }
        var tags = $('input[name="tags"]').val().length;
        if (tags === 0) {
            $('#tags_error').html('<p>The Tags field is required.</p>');
        } else {
            $('#tags_error').html('');
        }
        if ((title !== 0) && (description !== 0) && (tags !== 0)) {
            return true;
        } else {
            return false;
        }

    });
    $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
    {
        return {
            "iStart": oSettings._iDisplayStart,
            "iEnd": oSettings.fnDisplayEnd(),
            "iLength": oSettings._iDisplayLength,
            "iTotal": oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage": oSettings._iDisplayLength === -1 ?
                    0 : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            "iTotalPages": oSettings._iDisplayLength === -1 ?
                    0 : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
        };
    };
    /* Bootstrap style pagination control */
    $.extend($.fn.dataTableExt.oPagination, {
        "bootstrap": {
            "fnInit": function(oSettings, nPaging, fnDraw) {
                var oLang = oSettings.oLanguage.oPaginate;
                var fnClickHandler = function(e) {
                    e.preventDefault();
                    if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
                        fnDraw(oSettings);
                    }
                };
                $(nPaging).addClass('pagination').append(
                        '<ul class="pagination pagination-sm pager">' +
                        '<li class="prev disabled"><a href="#">' + oLang.sPrevious + '</a></li>' +
                        '<li class="next disabled"><a href="#">' + oLang.sNext + ' </a></li>' +
                        '</ul>'
                        );
                var els = $('a', nPaging);
                $(els[0]).bind('click.DT', {action: "previous"}, fnClickHandler);
                $(els[1]).bind('click.DT', {action: "next"}, fnClickHandler);
            },
            "fnUpdate": function(oSettings, fnDraw) {
                var iListLength = 5;
                var oPaging = oSettings.oInstance.fnPagingInfo();
                var an = oSettings.aanFeatures.p;
                var i, j, sClass, iStart, iEnd, iHalf = Math.floor(iListLength / 2);
                if (oPaging.iTotalPages < iListLength) {
                    iStart = 1;
                    iEnd = oPaging.iTotalPages;
                }
                else if (oPaging.iPage <= iHalf) {
                    iStart = 1;
                    iEnd = iListLength;
                } else if (oPaging.iPage >= (oPaging.iTotalPages - iHalf)) {
                    iStart = oPaging.iTotalPages - iListLength + 1;
                    iEnd = oPaging.iTotalPages;
                } else {
                    iStart = oPaging.iPage - iHalf + 1;
                    iEnd = iStart + iListLength - 1;
                }

                for (i = 0, iLen = an.length; i < iLen; i++) {
                    // Remove the middle elements
                    $('li:gt(0)', an[i]).filter(':not(:last)').remove();
                    // Add the new list items and their event handlers
                    for (j = iStart; j <= iEnd; j++) {
                        sClass = (j === oPaging.iPage + 1) ? 'class="active"' : '';
                        $('<li ' + sClass + '><a href="#">' + j + '</a></li>')
                                .insertBefore($('li:last', an[i])[0])
                                .bind('click', function(e) {
                            e.preventDefault();
                            oSettings._iDisplayStart = (parseInt($('a', this).text(), 10) - 1) * oPaging.iLength;
                            fnDraw(oSettings);
                        });
                    }

                    // Add / remove disabled classes from the static elements
                    if (oPaging.iPage === 0) {
                        $('li:first', an[i]).addClass('disabled');
                    } else {
                        $('li:first', an[i]).removeClass('disabled');
                    }

                    if (oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0) {
                        $('li:last', an[i]).addClass('disabled');
                    } else {
                        $('li:last', an[i]).removeClass('disabled');
                    }
                }
            }
        }
    });
    if ($('#table_tickets').length) {
        var oTable = $('#table_tickets').dataTable({
            "sProcessing": false, //you can see the phrase "precessing..." because I delete children of tbody tag for not see old content before the update of new content has clompleted
            "bServerSide": true,
            "sAjaxSource": './manage_tickets/select_data_tickets_ajax',
            "bJQueryUI": false,
            "iDisplayStart": 0,
            "sPaginationType": "bootstrap",
            "bFilter": true,
            "bInfo": true,
            "iDisplayLength": 50, //default
            "bPaginate": true,
            "oLanguage": {
                "sLengthMenu": '<small><strong>Show per page&nbsp;&nbsp;</strong></small><select name="per_page">' +
                        '<option value="50">50</option>' +
                        '<option value="75">75</option>' +
                        '<option value="100">100</option>' +
                        '<option value="200">200</option>' +
                        '</select>'
            },
            "aoColumns": [
                null, //id
                null, //title
                null, //state
                null, //priority
                {"bSortable": false}, //tags
                null, //created by
                null, //creation date 
                null, //assigned to
                null, //updated by
                null, //update date
                {"bSortable": false} //edit
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                $('td', nRow).each(function(i, v) {
                    $('td:eq(0)', nRow).addClass('text-center');
                    $('td:eq(1)', nRow).css({width: '350px'});
                    if (aData[2] === '0') {
                        $('td:eq(2)', nRow).html('Open');
                        $('td:eq(2)', nRow).addClass('text-center');
                        $('td:eq(2)', nRow).css({color: '#fff', background: 'green', width: '125px'});
                    }
                    if (aData[2] === '1') {
                        $('td:eq(2)', nRow).html('In progress');
                        $('td:eq(2)', nRow).addClass('text-center');
                        $('td:eq(2)', nRow).css({color: '#fff', background: '#0000ff', width: '125px'});
                    }

                    if (aData[2] === '2') {
                        $('td:eq(2)', nRow).html('Closed');
                        $('td:eq(2)', nRow).addClass('text-center');
                        $('td:eq(2)', nRow).css({color: '#fff', background: '#ff0000', width: '125px'});
                    }
                    if (aData[3] === '0') {
                        $('td:eq(3)', nRow).addClass('text-center');
                        $('td:eq(3)', nRow).html('Low');
                        $('td:eq(3)', nRow).css({width: '80px'});
                    }
                    if (aData[3] === '1') {
                        $('td:eq(3)', nRow).addClass('text-center');
                        $('td:eq(3)', nRow).html('Normal');
                        $('td:eq(3)', nRow).css({width: '80px'});
                    }

                    if (aData[3] === '2') {
                        $('td:eq(3)', nRow).addClass('text-center');
                        $('td:eq(3)', nRow).html('High');
                        $('td:eq(3)', nRow).css({width: '80px'});
                    }
                    $('td:eq(4)', nRow).css({width: '150px'});

                    var a_creation_date = '<a class="popoverCreated" id="trigger_pop_created_' + aData[0] + '" href="javascript:void(0)" rel="popover" data-placement="bottom" data-original-title="Creation date" >' + aData[5] + '</a>';
                    $('td:eq(5)', nRow).html(a_creation_date);
                    $('td:eq(5)', nRow).addClass('text-center');
                    $('td:eq(6)', nRow).attr('id', 'content_pop_created_' + aData[0]);
                    $('td:eq(6)', nRow).addClass('text-center');
                    $('td:eq(6)', nRow).css({display: 'none', visibility: 'hidden'});
                    $('td:eq(7)', nRow).addClass('text-center');
                    var a_update_date = ''
                    if (aData[8] !== null) {
                        a_update_date = '<a class="popoverUpdated" id="trigger_pop_update_' + aData[0] + '" href="javascript:void(0)" rel="popover" data-placement="bottom" data-original-title="Update date" >' + aData[8] + '</a>';
                    }

                    $('td:eq(8)', nRow).html(a_update_date);
                    $('td:eq(8)', nRow).addClass('text-center');

                    $('td:eq(9)', nRow).addClass('text-center');
                    $('td:eq(9)', nRow).attr('id', 'content_pop_update_' + aData[0]);
                    $('td:eq(9)', nRow).css({display: 'none', visibility: 'hidden'});
                    var edit_btn = '<a class="btn btn-primary btn-xs" href="edit_ticket/edit/' + aData[0] + '">Edit</a>';
                    $('td:eq(10)', nRow).html(edit_btn);
                    $('td:eq(10)', nRow).addClass('text-center');

                    return nRow;
                });
            }


        });

        $('.dataTables_filter input').addClass('search');
    }

    /*
     * Filter
     */
    $("#manage_table_loading").hide();
    $('body').on('submit', '#filters_form', function(e) {

        e.preventDefault();
        //IE9 & Other Browsers
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        //IE8 and Lower
        else {
            e.cancelBubble = true;
        }
        $('#div_table_manage_tickets').hide();
        $("#manage_table_loading").show();
        var string_data = $('#filters_form').serialize();
        var url = $('#filters_form').attr('action');
        $.ajax({
            'url': url,
            'type': 'POST',
            'data': string_data,
            'success': function(data) {
                var result = JSON.parse(data);
                $("#manage_table_loading").hide();
                $("#table_tickets_info").html("");
                $("#table_tickets > tbody").html(""); //I delete all children for not to see the old rows untill the new updated is completed
                $('#div_table_manage_tickets').show();
                //reload the table width  new data

                oTable.fnReloadAjax();
                return false;
            }

        });
        return false;
    });
    /*
     * Popover in the table manage tickets
     * 
     */
    $('body').on('mouseenter', '.popoverCreated', function(e) {
        var trigger_id = e.target.id;
        var pop_id = '#' + trigger_id;
        $(pop_id).popover({
            'trigger': "click",
            'html': true,
            'content': function() {
                var content_id = '#' + trigger_id.replace("trigger", "content");
                var string_content = $(content_id).text();
                return '<div>' + string_content + '</div>';
            }
        });

    });

    $('body').on('mouseenter', '.popoverUpdated', function(e) {
        var trigger_id = e.target.id;
        var pop_id = '#' + trigger_id;
        $(pop_id).popover({
            'trigger': "click",
            'html': true,
            'content': function() {
                var content_id = '#' + trigger_id.replace("trigger", "content");
                var string_content = $(content_id).text();
                return '<div>' + string_content + '</div>';
            }
        });
    });
    /*
     * Popover in nav bar
     */

    if ($('#pop_user_nav').length) {
        $('#pop_user_nav').popover({
            'trigger': "click",
            'html': true,
            'content': function() {
                //ajax call for update content
                var base_url = window.location.protocol + "//" + window.location.host + "/";
                var url = base_url + "tickets/templates/user_info_ajax";
                $.ajax({
                    'url': url,
                    'type': 'POST',
                    'data': '',
                    'success': function(data) {
                        var result = JSON.parse(data);
                        $('#tks_created_open').html(result['created_open']);
                        $('#tks_created_inprogress').html(result['created_inprogress']);
                        $('#tks_created_closed').html(result['created_closed']);

                        $('#tks_assigned_open').html(result['assigned_open']);
                        $('#tks_assigned_inprogress').html(result['assigned_inprogress']);
                        $('#tks_assigned_closed').html(result['assigned_closed']);

                        $('#tks_updated').html(result['updated']);
                    }
                });
                var content = $('#pop_user_content').html();
                return content;
            }
        });
    }

    $('body').on('mouseup', function(e) {
        $('.popover').hide();
        $('#pop_user_nav').popover('hide');
    });
    /*
     * Style Crated by - Assigned to - Updated by in Manage Tickets 
     */
    if ($('#created_filters').length) {
        var element = $('#created_filters').find("option:selected").attr("class");
        switch (element) {
            case "option_selected_blocked_created":
                var style = $('.bk_color_blocked_created').children(":selected").attr("style");
                $('.bk_color_blocked_created').attr("style", style);
                break;
        }
    }
    if ($('#assigned_filters').length) {
        var element = $('#assigned_filters').find("option:selected").attr("class");
        switch (element) {
            case "option_selected_blocked_assigned":
                var style = $('.bk_color_blocked_assigned').children(":selected").attr("style");
                $('.bk_color_blocked_assigned').attr("style", style);
                break;
        }
    }
    if ($('#updated_filters').length) {
        var element = $('#updated_filters').find("option:selected").attr("class");
        switch (element) {
            case "option_selected_blocked_updated":
                var style = $('.bk_color_blocked_updated').children(":selected").attr("style");
                $('.bk_color_blocked_updated').attr("style", style);
                break;
        }
    }
});