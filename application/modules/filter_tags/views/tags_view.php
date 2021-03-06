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
<p ><strong>Tags</strong>&nbsp;</p>
<div class="checkBoxListTags">

    <?php
//load tags from db
    if (isset($list_tags)) {
        for ($i = 0; $i < sizeof($list_tags); $i++) {
            echo '<div style="float:left;min-width:50px;height:25px;">';
            $info = array(
                'name' => 'tags[]',
                'id' => 'tag_id_'.$list_tags[$i]['tag_id'],
                'value' => $list_tags[$i]['tag_id'],
                'checked' => $list_tags[$i]['selected'],

            );
            echo form_checkbox($info);
            $id_tag = $list_tags[$i]['tag_id'];
            echo '&nbsp;<label for="tag_id_'.$id_tag .'">';
            echo $list_tags[$i]['name'] . '</label>';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;</div>';
        }
        if (sizeof($list_tags) == 0) {
            echo '<small><em>No tags yet.</em></small>';
        }
    }
    ?>

</div>
