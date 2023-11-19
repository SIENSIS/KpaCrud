<?php

/**
 * Language file from ES language. SPANISH
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
 */
return [
    'datatablesLangURL' => 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json',

    'btnSave'    => 'Guardar',
    'btnRecover'    => 'Recuperar',
    'btnEmpty'    => '<i class="fa-solid fa-triangle-exclamation"></i> Vaciar',
    'btnDelete'    => 'Eliminar',
    'btnUpdate'    => 'Actualizar',
    'btnCancel'    => 'Cancelar',
    'btnGoBack'    => 'Ir a lista',
    'btnGenerate' => 'Generar',
    'btnShowHide' => 'Mostrar/ocultar',

    'colHeadNitem'    => 'N.Item',
    'allItems'  => 'Todos los items',

    'modalRecoverConfirm' => 'Quieres recuperar los elementos seleccionados?',
    'modalRemovePermConfirm' => 'Quieres eliminar permanentemente los elementos seleccionados?',
    'modalRemoveConfirm' => 'Quieres eliminar los elementos seleccionados?',
    'modalEmptyConfirm' => '<i class=\"fa-solid fa-triangle-exclamation\"></i>Quieres vaciar la papelera?',

    'toolbars' => [
        'btnList'    => '<i class="fa-solid fa-table-list"></i> Lista',
        'btnAdd'    => '<i class="fa-solid fa-file-circle-plus"></i> Añadir',
        'btnRecycled'    => '<i class="fa-solid fa-trash-can"></i> Papelera',
        'btnShowRecycled'    => '<i class="fa-solid fa-trash"></i> Ver papelera',
        'btnExport'    => '<i class="fa-solid fa-file-excel"></i> Exportar',
        'btnPrint'    => '<i class="fa-solid fa-print"></i> Imprimir',
        'btnRecover'    => '<i class="fa-solid fa-trash-arrow-up"></i> Recuperar seleccionados',
        'btnRemove'    => '<i class="fa-solid fa-xmark"></i> Eliminar seleccionados',
        'btnRemovePermanently'    => '<i class="fa-solid fa-eraser"></i> Eliminar permanentemente',
        'btnEmpty'    => '<i class="fa-solid fa-recycle"></i> Vaciar papelera',
    ],
    'alerts' => [
        'addOk' => '<i class="fa-solid fa-check"></i> Item añadido correctamente. ID asignado {0, number}',
        'delOk' => '<i class="fa-solid fa-check"></i> Item {0, number} eliminado',
        'updatedOk' => '<i class="fa-solid fa-check"></i> Item {0, number} actualizado',
        'recoverOk' => '<i class="fa-solid fa-check"></i> Items recuperados correctamente',
        'removedOk' => '<i class="fa-solid fa-check"></i> Items eliminados correctamente',
        'emptyOk' => '<i class="fa-solid fa-check"></i> Se ha vaciado correctamente la papelera',
        'addErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error añadiendo item',
        'delErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error eliminando item {0, number}',
        'updatedErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error actualizando item {0, number}',
        'recoverErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error no se han recuperado los items',
        'removedErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error no se han eliminado los items',
        'notExistsErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error item {0, number} no existe',
        'callbackCancel'=>"<i class='fa-solid fa-triangle-exclamation'></i> Error callback ha cancelado la operación",
    ],
    'titles' => [
        'create' => 'Añadir item',
        'delete' => 'Eliminar item',
        'edit' => 'Actualizar item',
        'view' => 'Ficha item',
        'trash' => '<i class="fa-solid fa-trash-can"></i> Papelera',
        'modalRecoverConfirm' => 'Confirma recuperación',
        'modalRemovePermConfirm' => '<span class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Eliminación permanente</span>',
        'modalRemoveConfirm' => '<span class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Eliminar item</span>',
        'modalEmptyConfirm' => '<span class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Confirmación vaciar papelera</span>',
    ],

    'help' => [
        'btnAdd' => 'Añadir nuevo item',
        'btnList' => 'Mostrar lista items',
        'btnRecycled' => 'Mostrar elementos papelera',
        'btnExport' => 'Exportar los datos a un archivo Excel',
        'btnPrint' => 'Imprimir información',

        'btnShowItem' => 'Mostrar item',
        'btnEditItem' => 'Editar item',
        'btnDelItem' => 'Eliminar item',
    ],

    'exceptions' => [
        'tableNull' => 'El nombre de la tabla no puede estar vacio. Utiliza setTable per añadir el nombre de la tabla', 
        'tableNoExists' => 'La taubla {0} no existe. Revisa la base de datos y prueba de nuevo.', 
        'idNull' => 'La clave principal no puede ser NULL', 
        'fieldNoExists' => "El campo {0} no existe en la base de datos. Utiliza unicament nombre de campos existentes en la base de datos", 
        'fieldTypeUnknown' => "El tipo de campo {0} no existe. Revisa la documentación",
    ],
];
