<?php

/**
 * Delete page view. Shows delete form interface with configured table fields, it serves to confirm a single item remove.
 * 
 * @package KpaCrud\Views
 * 
 * @version 1.2
 * @author JMFXR <dev@siensis.com> 
 * @copyright 2022 SIENSIS Dev
 * @license MIT
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 * 
 * This code is provided for educational purposes
 * 
 * @ignore 
 */

use SIENSIS\KpaCrud\Libraries\KpaCrud;

renderCSS($css_files, $_hidden_head_links);
renderJS($js_files, $_hidden_head_links);


?>

<div style="margin-top:20px" class="border bg-light">
    <form method="post" id="delete_item" name="delete_item" action="<?= base_url($_route . '?del=item' . $id) ?>">
        <input type="hidden" name="op" value="del">
        <?= csrf_field(); ?>

        <div class="bg-secondary p-2 text-white">
            <h1><?= lang('crud.titles.delete'); ?></h1>
        </div>
        <div class="d-grid" style="margin-top:20px">
            <?php

            foreach ($tableFields as $dbfield) {

                if ($config['useTimestamps'] == true) {

                    // show all fields
                    // if !useSoftDeletes && field==delete
                    if ($dbfield->Field == $config['deletedField']) {
                        if ($config['useSoftDeletes'] == false)
                            ShowField($dbfield, $data, $_data_columns, $_relations);
                    } else {
                        //show if not created || updated
                        if ($dbfield->Field != $config['createdField'] && $dbfield->Field != $config['updatedField']) {
                            ShowField($dbfield, $data, $_data_columns, $_relations);
                        } else {
                            if ($config['showTimestamps'] == true) {   //useTimestamps && showTimestamps
                                ShowField($dbfield, $data, $_data_columns, $_relations);
                            }
                        }
                    }
                } else {
                    // show all fields 
                    if ($dbfield->Field == $config['deletedField']) {
                        if ($config['useSoftDeletes'] == false)
                            ShowField($dbfield, $data, $_data_columns, $_relations);
                    } else
                        ShowField($dbfield, $data, $_data_columns, $_relations);
                }
            } //end foreach field

            /** @ignore */
            function ShowField($dbfield, $data, $_data_columns, $_relations, $editable = false)
            {
                $colname = $_data_columns[$dbfield->Field]['name'] ?? ucfirst($dbfield->Field);
                $coltype = $_data_columns[$dbfield->Field]['type'] ?? KpaCrud::DEFAULT_FIELD_TYPE;

                if ($coltype != KpaCrud::INVISIBLE_FIELD_TYPE && $coltype != KpaCrud::PASSWORD_FIELD_TYPE) {
                    if (isset($_relations[$dbfield->Field])) {      // IF RELATED field
                        $displayAs = $_relations[$dbfield->Field]['relatedTable'] . KpaCrud::SQL_TABLE_SEPARATOR . $_relations[$dbfield->Field]['display_as'];

                        echo "<div class='p-2 '>";
                        echo "\t<label>" . $colname . "</label>";
                        echo "\t<div class='form-control bg-light' >" . $data[$displayAs] . "&nbsp;</div>";
                        echo "</div>";
                    } elseif ($dbfield->Extra != 'auto_increment') { //IF is normal column
                        echo "<div class='p-2 '>";
                        echo "\t<label>" . $colname . "</label>";
                        switch ($coltype) {
                            case strval(KpaCrud::DROPDOWN_FIELD_TYPE):
                                $coloptions = $_data_columns[$dbfield->Field]['options'] ?? [""];
                                $display = $data[$dbfield->Field];
                                echo "\t<div class='form-control bg-light' >" . $coloptions[$display] . "&nbsp;</div>";
                                break;
                            case strval(KpaCrud::CHECKBOX_FIELD_TYPE):
                                $chkvalue = $_data_columns[$dbfield->Field]['check_value'] ?? KpaCrud::DEFAULT_CHECK_VALUE;
                                if ($data[$dbfield->Field] == $chkvalue)
                                    echo "&nbsp;<input class='form-check-input' type='checkbox' checked disabled readonly>";
                                else
                                    echo "&nbsp;<input class='form-check-input' type='checkbox' disabled readonly>";
                                break;
                            default:
                                echo "\t<div class='form-control bg-light' >" . $data[$dbfield->Field] . "&nbsp;</div>";
                        }
                        echo "</div>";
                    } else {                                        // IF is primary key
                        echo "<div class='p-2 '>";
                        echo "\t<label>" . $colname . "</label>";
                        echo "\t<div class='form-control bg-light '>" . $data[$dbfield->Field] . "&nbsp;</div>";
                        echo "</div>";
                    }
                }
            }

            ?>


            <div class="p-3 bg-secondary mt-5">
                <button type="submit" class="btn btn-primary btn-block"><?= lang('crud.btnDelete'); ?></button>
                <a href="<?= base_url($_route) ?>" class="btn btn-light btn-block"><?= lang('crud.btnCancel'); ?></a>
            </div>
        </div>

    </form>
</div>