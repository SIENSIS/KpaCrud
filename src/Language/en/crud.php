<?php
/**
 * Language file from EN language. English 
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
 */
return [
    'datatablesLangURL' => '', // default language: English

    'btnSave'    => 'Save',
    'btnRecover'    => 'Recover',
    'btnEmpty'    => '<i class="fa-solid fa-triangle-exclamation"></i> Empty',
    'btnDelete'    => 'Delete',
    'btnUpdate'    => 'Update',
    'btnCancel'    => 'Cancel',
    'btnGoBack'    => 'Go list',
    'btnGenerate' => 'Generate',
    'btnShowHide' => 'Show/hide',
    
    'colHeadNitem'    => 'N.Item',
    'allItems'  => 'All items',

    'modalRecoverConfirm' => 'Whould you like to recover selected items?',
    'modalRemovePermConfirm' => 'Whould you like to remove permanently selected items?',
    'modalRemoveConfirm' => 'Whould you like to remove selected items?',
    'modalEmptyConfirm' => '<i class=\"fa-solid fa-triangle-exclamation\"></i>Whould you like to empty trash?',

    'toolbars' => [
        'btnList'    => '<i class="fa-solid fa-table-list"></i> List',
        'btnAdd'    => '<i class="fa-solid fa-file-circle-plus"></i> Add',
        'btnRecycled'    => '<i class="fa-solid fa-trash-can"></i> Trash',
        'btnShowRecycled'    => '<i class="fa-solid fa-trash"></i> Show trash',
        'btnExport'    => '<i class="fa-solid fa-file-excel"></i> Export',
        'btnPrint'    => '<i class="fa-solid fa-print"></i> Print',
        'btnRecover'    => '<i class="fa-solid fa-trash-arrow-up"></i> Recover selected',
        'btnRemove'    => '<i class="fa-solid fa-xmark"></i> Remove selected',
        'btnRemovePermanently'    => '<i class="fa-solid fa-eraser"></i> Remove permanently',
        'btnEmpty'    => '<i class="fa-solid fa-recycle"></i> Empty trash',
    ],
    'alerts' => [
        'addOk' => '<i class="fa-solid fa-check"></i> New item added correctly. {0, number} assigned as ID',
        'delOk' => '<i class="fa-solid fa-check"></i> Item {0, number} removed',
        'updatedOk' => '<i class="fa-solid fa-check"></i> Item {0, number} updated',
        'recoverOk' => '<i class="fa-solid fa-check"></i> Items recovered correctly',
        'removedOk' => '<i class="fa-solid fa-check"></i> Items removed correctly',
        'emptyOk' => '<i class="fa-solid fa-check"></i> Trash was empty correctly',
        'addErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error adding item',
        'delErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error removing item {0, number}',
        'updatedErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error updating item {0, number}',
        'recoverErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error no items recovered',
        'removedErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error no items removed',
        'notExistsErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error item {0, number} not exists',
        'callbackCancel'=>'<i class="fa-solid fa-triangle-exclamation"></i> Error callback function cancel operation',
    ],
    'titles' => [
        'create' => 'Add item',
        'delete' => 'Delete item',
        'edit' => 'Update item',
        'view' => 'View item',
        'trash' => '<i class="fa-solid fa-trash-can"></i> Trash',
        'modalRecoverConfirm' => 'Recover confirm',
        'modalRemovePermConfirm' => '<span class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Remove permanently</span>',
        'modalRemoveConfirm' => '<span class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Remove item</span>',
        'modalEmptyConfirm' => '<span class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Empty trash confirm</span>',
    ],

    'help' => [
        'btnAdd' => 'Add new item',
        'btnList' => 'Show list items',
        'btnRecycled' => 'Show recycled items',
        'btnExport' => 'Export data to Excel file',
        'btnPrint' => 'Print info',

        'btnShowItem' => 'Show item',
        'btnEditItem' => 'Edit item',
        'btnDelItem' => 'Delete item',
    ],

    'exceptions' => [
        'tableNull' => 'The table name can\'t be empty. Please use setTable to add a basic table name',
        'tableNoExists' => 'The table name {0} does not exist. Please check you database and try again.',
        'idNull' => 'Primary key cannot be null',
        'fieldNoExists' => "The field name {0} doesn't exist in the database. Please use the unique fields only for fields that exist in the database",
        'fieldTypeUnknown' => "The field type {0} doesn't exists. Please check library documentation",
    ],


];
