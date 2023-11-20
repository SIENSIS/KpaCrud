<?php

/**
 * Add page view. Shows add form interface with configured table fields.
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

if (!isset($oldForm))
    $oldForm = null;

if (isset($newID)) {

    if ($newID < 0) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" id="my">';
        echo lang('crud.alerts.addErr');
        echo "\t<button type='button' class='btn-close' data-dismiss='alert' aria-label='Close' onclick=\"$('.alert').hide()\">";
        echo "\t</button>";
        echo "</div>";
    } else {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert" id="my">';
        echo lang('crud.alerts.addOk', [$newID]);
        echo "\t<button type='button' class='btn-close' data-dismiss='alert' aria-label='Close' onclick=\"$('.alert').hide()\">";
        echo "\t</button>";
        echo "</div>";
    }
}

?>

<script>
    function checkInput(obj) {
        if (obj.value == '')
            obj.classList.remove('is-invalid', 'is-valid');
        else if (!obj.checkValidity()) {
            obj.classList.add('is-invalid');
            obj.classList.remove('is-valid');
        } else {
            obj.classList.remove('is-invalid');
            obj.classList.add('is-valid');
        }
    }
    function togglePassword(obj) {
        if (obj.type === "password") {
            obj.type = "text";
            obj.nextElementSibling.childNodes[0].classList.remove('fa-eye-slash');
            obj.nextElementSibling.childNodes[0].classList.add('fa-eye');
        } else {
            obj.type = "password";
            obj.nextElementSibling.childNodes[0].classList.remove('fa-eye');
            obj.nextElementSibling.childNodes[0].classList.add('fa-eye-slash');
        }
    }
    function generatePassword(obj) {
        var length = 8,
            charset = "abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789@#$*+~.",
            retVal = "";
        if (obj.minLength>0) length = parseInt(obj.minLength);

        for (var i = 0, n = charset.length; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * n));
            obj.value = retVal;
        }
    }
</script>

<div style="margin-top:20px" class="border bg-light">
    <form method="post" id="add_create" name="add_create" action="<?= base_url($_route . '?add=item') ?>">
        <input type="hidden" name="op" value="add">
        <?= csrf_field(); ?>

        <div class="bg-secondary p-2 text-white">
            <h1><?= lang('crud.titles.create'); ?></h1>
        </div>
        <div class="d-grid" style="margin-top:20px">
            <?php
            foreach ($tableFields as $dbfield) {

                if ($config['useTimestamps'] == true) {

                    // show all fields
                    // if !useSoftDeletes && field==delete
                    if ($dbfield->Field == $config['deletedField']) {
                        if ($config['useSoftDeletes'] == false)
                            ShowField($dbfield, null, $_data_columns, $_relations, $oldForm);
                    } else {
                        //show if not created || updated
                        if ($dbfield->Field != $config['createdField'] && $dbfield->Field != $config['updatedField']) {
                            ShowField($dbfield, null, $_data_columns, $_relations, $oldForm);
                        } else {
                            if ($config['showTimestamps'] == true) {   //useTimestamps && showTimestamps
                                ShowField($dbfield, null, $_data_columns, $_relations, $oldForm, false);
                            }
                        }
                    }
                } else {
                    // show all fields 
                    if ($dbfield->Field == $config['deletedField']) {
                        if ($config['useSoftDeletes'] == false)
                            ShowField($dbfield, null, $_data_columns, $_relations, $oldForm);
                    } else
                        ShowField($dbfield, null, $_data_columns, $_relations, $oldForm);
                }
            } //end foreach field

            /** @ignore */
            function ShowField($dbfield, $data, $_data_columns, $_relations, $oldForm, $editable = true)
            {
                $colname = $_data_columns[$dbfield->Field]['name'] ?? ucfirst($dbfield->Field);
                $coltype = $_data_columns[$dbfield->Field]['type'] ?? KpaCrud::DEFAULT_FIELD_TYPE;
                $colhtmlatts = $_data_columns[$dbfield->Field]['html_atts'] ?? [""];
                $coldefault = $oldForm["data_" . $dbfield->Field] ?? $_data_columns[$dbfield->Field]['default'] ?? "";

                if ($coltype != KpaCrud::INVISIBLE_FIELD_TYPE) {
                    if (isset($_relations[$dbfield->Field])) {      // IF RELATED field
                        echo "<div class='p-2 form-group'>";
                        echo "\t<label for='data_" . $dbfield->Field . "'>" . $colname . "</label>";

                        $options = $_relations[$dbfield->Field]['options'];
                        $atts = [
                            'class'       => 'form-select',
                            'id'    => "data_" . $dbfield->Field,
                        ];

                        if (isset($_data_columns[$dbfield->Field]['excludes']))
                        {
                            foreach ($_data_columns[$dbfield->Field]['excludes'] as $exclude)
                            {
                                unset($options[$exclude]);
                            }
                        }
                        echo form_dropdown("data_" . $dbfield->Field, $options, null, $atts);
                        echo "</div>";
                    } elseif ($dbfield->Extra != 'auto_increment') { //IF is normal column
                        if ($editable) {
                            echo "<div class='p-2 '>";
                            echo "\t<label>" . $colname . "</label>";
                            switch ($coltype) {
                                case strval(KpaCrud::DROPDOWN_FIELD_TYPE):
                                    $coloptions = $_data_columns[$dbfield->Field]['options'] ?? [""];
                                    $atts = "class='form-select' id='data_" . $dbfield->Field . "' onchange='checkInput(this)' ";

                                    $atts = $atts . implode(" ", $colhtmlatts);

                                    echo form_dropdown("data_" . $dbfield->Field, $coloptions, $coldefault, $atts);
                                    break;
                                case strval(KpaCrud::RANGE_FIELD_TYPE):
                                    echo "\t<input type='range' name='data_" . $dbfield->Field . "' ";
                                    echo " " .  implode(" ", $colhtmlatts), " ";
                                    echo " onchange='checkInput(this)' ";
                                    echo " value='" . $coldefault . "' ";
                                    echo " oninput='this.nextElementSibling.innerHTML = this.value' ";
                                    echo " class='form-range' style='width:90%'>";
                                    echo " <span class='align-text-bottom fs-4'>" . $coldefault . "</span>";
                                    break;
                                case strval(KpaCrud::NUMBER_FIELD_TYPE):
                                    echo "\t<input type='number' name='data_" . $dbfield->Field . "' ";
                                    echo " " .  implode(" ", $colhtmlatts), " ";
                                    echo " onchange='checkInput(this)' ";
                                    echo " value='" . $coldefault . "' ";
                                    echo " class='form-control' >";
                                    break;
                                case strval(KpaCrud::CHECKBOX_FIELD_TYPE):
                                    echo "\t<input type='checkbox' name='data_" . $dbfield->Field . "' class='form-check-input' ";
                                    echo " " .  implode(" ", $colhtmlatts), " ";
                                    echo " value='" . KpaCrud::DEFAULT_CHECK_VALUE . "' ";
                                    if ($coldefault == KpaCrud::DEFAULT_CHECK_VALUE) echo " checked ";
                                    echo ">";
                                    break;
                                case strval(KpaCrud::DATE_FIELD_TYPE):
                                    echo "\t<input type='date' ";
                                    echo " " .  implode(" ", $colhtmlatts), " ";
                                    echo " onchange='checkInput(this)' ";
                                    echo " value='" . date('Y-m-d', strtotime($coldefault)) . "' ";
                                    echo " name='data_" . $dbfield->Field . "' class='form-control' >";
                                    break;
                                case strval(KpaCrud::DATETIME_FIELD_TYPE):
                                    echo "\t<input type='datetime-local' ";
                                    echo " " .  implode(" ", $colhtmlatts), " ";
                                    echo " onchange='checkInput(this)' ";
                                    echo " value='" . date('Y-m-d\TH:i', strtotime($coldefault)) . "' ";
                                    echo " name='data_" . $dbfield->Field . "' class='form-control' >";
                                    break;
                                case strval(KpaCrud::PASSWORD_FIELD_TYPE):
                                    echo '<div class="input-group">';

                                    echo "\t<input type='password' ";
                                    echo " " .  implode(" ", $colhtmlatts), " ";
                                    echo " onchange='checkInput(this)' ";
                                    echo " value='" . $coldefault . "' ";
                                    echo " name='data_" . $dbfield->Field . "' class='form-control' >";
                                    
                                    echo "<div class='btn' onclick='togglePassword(this.previousElementSibling)'>";
                                    echo "<span class='fa fa-eye-slash' ></span> ".lang('crud.btnShowHide')."</div>";
                                    
                                    echo "<div class='btn' onclick='generatePassword(this.previousElementSibling.previousElementSibling)'>";
                                    echo "<span class='fa fa-random'></span> ".lang('crud.btnGenerate')."</div>";
                                    
                                    echo '</div>';
                                    break;
                                case strval(KpaCrud::EMAIL_FIELD_TYPE):
                                    echo "\t<input type='email' pattern='[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$'";
                                    echo " " .  implode(" ", $colhtmlatts), " ";
                                    echo " onchange='checkInput(this)' ";
                                    echo " value='" . $coldefault . "' ";
                                    echo " name='data_" . $dbfield->Field . "' class='form-control' >";
                                    break;
                                case strval(KpaCrud::TEXTAREA_FIELD_TYPE):
                                    echo "\t<textarea name='data_" . $dbfield->Field . "' ";
                                    echo " " .  implode(" ", $colhtmlatts), " ";
                                    echo " onchange='checkInput(this)' ";
                                    echo " class='form-control' >";
                                    echo  $coldefault;
                                    echo "</textarea>";
                                    break;
                                default:
                                    echo "\t<input type='text' name='data_" . $dbfield->Field . "' ";
                                    echo " " .  implode(" ", $colhtmlatts), " ";
                                    echo " onchange='checkInput(this)' ";
                                    echo " value='" . $coldefault . "' ";
                                    echo " class='form-control' >";
                            }
                            echo "</div>";
                        }
                    }
                }
            }

            ?>

        </div>

        <div class="d-grid p-3 bg-secondary mt-5">
            <div class="row">
                <div class="col-sm">
                    <button type="submit" class="btn btn-primary btn-block"><?= lang('crud.btnSave'); ?></button>
                    <a href="<?= base_url($_route) ?>" class="btn btn-light btn-block"><?= lang('crud.btnCancel'); ?></a>
                </div>
                <div class="col-sm-2">
                    <a href="<?= base_url($_route) ?>" class="btn btn-light btn-block "><?= lang('crud.btnGoBack'); ?></a>
                </div>
            </div>
        </div>

    </form>
</div>
