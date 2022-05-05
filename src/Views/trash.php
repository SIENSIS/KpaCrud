<?php
/**
 * Trash page view. Shows Datatable CRUD interface from trash (deleted records) if
 * useSoftDeletes flag is enabled, it shows controls configured as list view and table fields
 * and related fields if relations are defined.
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

renderCSS($css_files,$_hidden_head_links);
renderJS($js_files,$_hidden_head_links);


$alert = session()->getFlashdata('alert');
$alert_type = session()->getFlashdata('alert_type');

if (isset($alert)) {
  if ($alert_type == 'err')
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" id="my">';
  else
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert" id="my">';
  echo $alert;
  echo "\t<button type='button' class='btn-close' data-dismiss='alert' aria-label='Close' onclick=\"$('.alert').hide()\">";
  echo "\t</button>";
  echo "</div>";
}
?>
<div class="d-flex justify-content-center ">
  <h2 class="p-3 mb-2 bg-danger text-white rounded-pill"><?= lang('crud.titles.trash') ?></h2>
</div>


<?php
if ($config['exportXLS'] || $config['print']) { ?>


  <div class="d-flex justify-content-end">

    &nbsp;<a href="<?php echo base_url($_route) ?>" class="btn btn-info mb-2 rounded-pill" title="<?= lang('crud.help.btnList') ?>"><?= lang('crud.toolbars.btnList'); ?></a>


    <?php if ($config['exportXLS']) : ?>
      &nbsp;<a href="<?php echo base_url($_route . '?export=xls') ?>" class="btn btn-info mb-2 rounded-pill" title="<?= lang('crud.help.btnExport') ?>"><?= lang('crud.toolbars.btnExport'); ?></a>
    <?php endif; ?>

    <?php if ($config['print']) { ?>
      &nbsp;<a href="#" onclick="doPrint();" class="btn btn-info mb-2 rounded-pill" title="<?= lang('crud.help.btnPrint') ?>"><?= lang('crud.toolbars.btnPrint'); ?></a>
      <script>
        function doPrint() {
          var h = window.top.outerHeight,
            w = 800;

          const top = window.top.outerHeight / 2 + window.top.screenY - (h / 2);
          const left = window.top.outerWidth / 2 + window.top.screenX - (w / 2);

          var url = '<?php echo base_url($_route . '?export=print') ?>';
          var w = window.open(url, 'print_window', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

          w.onload = function() {
            w.focus();
            w.print();
            w.onafterprint = function() {
              w.close();
            }
          }
        }
      </script>
    <?php } ?>

  </div>

  <div id='content'></div>
<?php } //endif 
?>

<div class="mt-3">
  <button id="btnRecover" disabled><?= lang('crud.toolbars.btnRecover'); ?></button>
  <button id="btnRemove" disabled><?= lang('crud.toolbars.btnRemovePermanently'); ?></button>
  <button id="btnEmpty" <?= count($data) > 0 ? '' : 'disabled'; ?>><?= lang('crud.toolbars.btnEmpty'); ?></button>

</div>
<div class="mt-3">

  <form method="post" id="trash_recover" name="trash_recover" action="<?= base_url($_route . '?trash=list') ?>">
    <?= csrf_field(); ?>

  </form>


  <table class="table table-striped row-border" id="data-trash-<?= $_table ?>" style="width:100%">
    <thead>
      <tr>
        <?php
        if ($config['numerate']) echo "<th>" . lang('crud.colHeadNitem') . "</th>";

        foreach ($_data_header as $dbname) {
          $colname = $_data_columns[$dbname]['name'] ?? ucfirst($dbname);
          echo "<th>" . $colname . "</th>";
        }
        ?>
      </tr>
    </thead>
    <tbody>


      <?php if ($data) : ?>
        <?php
        $nRow = 1;
        foreach ($data as $row) :

          foreach ($primaryKey as $key) {
            $rowID[$key] = $row[$key];
          }
        ?>
          <tr id='item-<?= $nRow ?>' data-kpc-id='<?= json_encode($rowID) ?>'>
            <?php
            if ($config['numerate']) echo "<td>" . $nRow . "</td>";

            foreach ($_data_header as $dbname) {
              echo "<td>" . $row[$dbname] . "</td>";
            }

            $nRow++;
            ?>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>

    </tbody>
  </table>
</div>




<script>
  function doRemove() {

    var table = $('#data-trash-<?= $_table ?>').DataTable();

    $('<input>').attr({
      type: 'hidden',
      name: 'trashop',
      value: 'remove'
    }).appendTo('#trash_recover');

    let items = table.rows('.bg-secondary').data();
    for (let index = 0; index < items.length; index++) {
      let rowId = items[index]['DT_RowId'];

      let id = $('#' + rowId).attr('data-kpc-id');
      
      $('<input>').attr({
        type: 'hidden',
        name: 'remove[]',
        value: id
      }).appendTo('#trash_recover');
    }
    
    $("#trash_recover").submit();
  }

  function doEmpty() {
    $('<input>').attr({
      type: 'hidden',
      name: 'trashop',
      value: 'empty'
    }).appendTo('#trash_recover');

    $("#trash_recover").submit();
  }

  function doRecover() {

    var table = $('#data-trash-<?= $_table ?>').DataTable();

    $('<input>').attr({
      type: 'hidden',
      name: 'trashop',
      value: 'recover'
    }).appendTo('#trash_recover');

    let items = table.rows('.bg-secondary').data();
    for (let index = 0; index < items.length; index++) {
      let rowId = items[index]['DT_RowId'];

      let id = $('#' + rowId).attr('data-kpc-id');

      $('<input>').attr({
        type: 'hidden',
        name: 'recover[]',
        value: id
      }).appendTo('#trash_recover');
    }

    $("#trash_recover").submit();
  }

  $(document).ready(function() {
    $('#data-trash-<?= $_table ?>').DataTable({
      lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "<?=lang('crud.allItems');?>"]],
      stateSave: true,
      /*
      numbers - Page number buttons only
      simple - 'Previous' and 'Next' buttons only
      simple_numbers - 'Previous' and 'Next' buttons, plus page numbers
      full - 'First', 'Previous', 'Next' and 'Last' buttons
      full_numbers - 'First', 'Previous', 'Next' and 'Last' buttons, plus page numbers
      first_last_numbers - 'First' and 'Last' buttons, plus page numbers
      */
      pagingType: '<?= $config['pagingType'] ?>',
      language: {
        url: '<?= $config['langURL'] ?>'
      },
      paging: <?= $config['paging'] ? 'true' : 'false' ?>,
      filter: <?= $config['filterable'] ? 'true' : 'false' ?>,
      sort: <?= $config['sortable'] ? 'true' : 'false' ?>,
      columnDefs: [{
          targets: [-1],
          orderable: false,
        },
        <?php

        $i = 0;

        if ($config['numerate']) {
          echo "{targets:" . $i . ", orderable:true},";
          $i++;
        }

        foreach ($_data_header as $dbname => $desc) {

          if (isset($_sortable_columns[$dbname])) {
            $order = $_sortable_columns[$dbname] ? 'true' : 'false';
            echo "{targets:" . $i . ", orderable:" . $order . "},";
          }
          $i++;
        }
        ?>
      ],
    });

  });


  $(document).ready(function() {
    var table = $('#data-trash-<?= $_table ?>').DataTable();

    $('#data-trash-<?= $_table ?> tbody').on('click', 'tr', function() {
      $(this).toggleClass('bg-secondary');
      if (table.rows('.bg-secondary').data().length > 0) $('#btnRecover').prop('disabled', false);
      else $('#btnRecover').prop('disabled', true);

      if (table.rows('.bg-secondary').data().length > 0) $('#btnRemove').prop('disabled', false);
      else $('#btnRemove').prop('disabled', true);
    });

    $('#btnRecover').click(function() {
      $("#confirmRecover").modal("show");
    });

    $('#btnRemove').click(function() {
      $("#confirmRemove").modal("show");
    });

    $('#btnEmpty').click(function() {
      $("#confirmEmpty").modal("show");
    });
  });
</script>



<!-- Modal Empty-->
<div class="modal fade" id="confirmEmpty" tabindex="-1" aria-labelledby="headModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header     ">
        <h5 class="modal-title" id="headModalLabel"><?= lang('crud.titles.modalEmptyConfirm'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?= lang('crud.modalEmptyConfirm'); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('crud.btnCancel'); ?></button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick='doEmpty()'><?= lang('crud.btnEmpty'); ?></button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Remove-->
<div class="modal fade" id="confirmRemove" tabindex="-1" aria-labelledby="headModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header     ">
        <h5 class="modal-title" id="headModalLabel"><?= lang('crud.titles.modalRemovePermConfirm'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?= lang('crud.modalRemovePermConfirm'); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('crud.btnCancel'); ?></button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick='doRemove()'><?= lang('crud.btnDelete'); ?></button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Recover-->
<div class="modal fade" id="confirmRecover" tabindex="-1" aria-labelledby="headModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header     ">
        <h5 class="modal-title" id="headModalLabel"><?= lang('crud.titles.modalRecoverConfirm'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?= lang('crud.modalRecoverConfirm'); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('crud.btnCancel'); ?></button>
        <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick='doRecover()'><?= lang('crud.btnRecover'); ?></button>
      </div>
    </div>
  </div>
</div>