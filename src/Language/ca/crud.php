<?php

/**
 * Language file from CA language. Catalan 
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
    'datatablesLangURL' => '//cdn.datatables.net/plug-ins/1.11.5/i18n/ca.json',

    'btnSave'    => 'Desar',
    'btnRecover'    => 'Recuperar',
    'btnEmpty'    => '<i class="fa-solid fa-triangle-exclamation"></i> Buidar',
    'btnDelete'    => 'Eliminar',
    'btnUpdate'    => 'Actualitzar',
    'btnCancel'    => 'Cancela',
    'btnGoBack'    => 'Anar llista',
    'btnGenerate' => 'Generar',
    'btnShowHide' => 'Mostrar/ocultar',

    'colHeadNitem'    => 'N.Item',
    'allItems'  => 'All items',

    'modalRecoverConfirm' => 'Vols recuperar els elements seleccionats?',
    'modalRemovePermConfirm' => 'Vols eliminar permanentment els elements seleccionats?',
    'modalRemoveConfirm' => 'Vols eliminar els elements seleccionats?',
    'modalEmptyConfirm' => '<i class=\"fa-solid fa-triangle-exclamation\"></i>Vols buidar la paperera?',

    'toolbars' => [
        'btnList'    => '<i class="fa-solid fa-table-list"></i> Llista',
        'btnAdd'    => '<i class="fa-solid fa-file-circle-plus"></i> Afegir',
        'btnRecycled'    => '<i class="fa-solid fa-trash-can"></i> Paperera',
        'btnShowRecycled'    => '<i class="fa-solid fa-trash"></i> Veure paperera',
        'btnExport'    => '<i class="fa-solid fa-file-excel"></i> Exportar',
        'btnPrint'    => '<i class="fa-solid fa-print"></i> Imprimir',
        'btnRecover'    => '<i class="fa-solid fa-trash-arrow-up"></i> Reccuperar seleccionats',
        'btnRemove'    => '<i class="fa-solid fa-xmark"></i> Eliminar seleccionats',
        'btnRemovePermanently'    => '<i class="fa-solid fa-eraser"></i> Eliminar permanentment',
        'btnEmpty'    => '<i class="fa-solid fa-recycle"></i> Buidar paperera',
    ],
    'alerts' => [
        'addOk' => '<i class="fa-solid fa-check"></i> Item afegit correctament. ID assignat {0, number}',
        'delOk' => '<i class="fa-solid fa-check"></i> Item {0, number} eliminat',
        'updatedOk' => '<i class="fa-solid fa-check"></i> Item {0, number} actualitzat',
        'recoverOk' => '<i class="fa-solid fa-check"></i> Items recuperats correctament',
        'removedOk' => '<i class="fa-solid fa-check"></i> Items eliminats correctament',
        'emptyOk' => '<i class="fa-solid fa-check"></i> S\'ha buidat correctament la paperera',
        'addErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error afegint item',
        'delErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error eliminant item {0, number}',
        'updatedErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error actualitzant item {0, number}',
        'recoverErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error no s\'han recuperat els items',
        'removedErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error no s\'han eliminats els items',
        'notExistsErr' => '<i class="fa-solid fa-triangle-exclamation"></i> Error item {0, number} no existeix',
        'callbackCancel'=>"<i class='fa-solid fa-triangle-exclamation'></i> Error callback ha cancel.lat l'operació",
    ],
    'titles' => [
        'create' => 'Afegir item',
        'delete' => 'Eliminar item',
        'edit' => 'Actualitzar item',
        'view' => 'Fitxa item',
        'trash' => '<i class="fa-solid fa-trash-can"></i> Paperera',
        'modalRecoverConfirm' => 'Confirma recuperació',
        'modalRemovePermConfirm' => '<span class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Eliminació permanent</span>',
        'modalRemoveConfirm' => '<span class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Eliminar item</span>',
        'modalEmptyConfirm' => '<span class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Confirmació buidar paperera</span>',
    ],

    'help' => [
        'btnAdd' => 'Afegir nou item',
        'btnList' => 'Mostrar llista items',
        'btnRecycled' => 'Mostrar elements paperera',
        'btnExport' => 'Exporta les dades a un arxiu Excel',
        'btnPrint' => 'Imprimir informació',

        'btnShowItem' => 'Mostrar item',
        'btnEditItem' => 'Editar item',
        'btnDelItem' => 'Eliminar item',
    ],

    'exceptions' => [
        'tableNull' => 'El nom de la taula no pot estar buit. Utilitza setTable per afegir el nom de la taula', 
        'tableNoExists' => 'La taula {0} no existeix. Revisa la base de dades i prova-ho de nou.', 
        'idNull' => 'La clau primaria no pot ser NULL', 
        'fieldNoExists' => "El camp {0} no existeix a la base de dades. Utilitza unicament noms de camp que existeixn a la base de dades", 
        'fieldTypeUnknown' => "El tipus de camp {0} no existeix. Revisa la documentació",
    ],
];
