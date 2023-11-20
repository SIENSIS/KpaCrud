<?php

/**
 * List page view. Shows Datatable CRUD interface with controls configured, shows table fields
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

renderCSS($css_files, $_hidden_head_links);
renderJS($js_files, $_hidden_head_links);

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

if ($config['add_button'] || $config['exportXLS'] || $config['recycled_button'] || $config['print']) {
?>
  <div class="d-flex justify-content-end">

    <?php if ($config['add_button'] == true) : ?>
      &nbsp;<a href="<?php echo base_url($_route . '?add=item') ?>" class="btn btn-info" title="<?= lang('crud.help.btnAdd') ?>"><?= lang('crud.toolbars.btnAdd'); ?></a>
    <?php endif; ?>

    <?php if ($config['recycled_button'] && $config['useSoftDeletes']) : ?>

      <div class="dropdown show">

        &nbsp;<a class="btn btn-info dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= lang('crud.toolbars.btnRecycled'); ?></a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
          <a href="<?php echo base_url($_route . '?trash=list') ?>" class="dropdown-item" title="<?= lang('crud.help.btnRecycled') ?>"><?= lang('crud.toolbars.btnShowRecycled'); ?></a>
          <a class="dropdown-item" href="#" id="btnEmpty"><?= lang('crud.toolbars.btnEmpty'); ?></a>
        </div>
      </div>

    <?php endif; ?>

    <?php if ($config['exportXLS'] == true) : ?>
      &nbsp;<a href="<?php echo base_url($_route . '?export=xls') ?>" class="btn btn-info" title="<?= lang('crud.help.btnExport') ?>"><?= lang('crud.toolbars.btnExport'); ?></a>
    <?php endif; ?>

    <?php if ($config['print']) { ?>
      &nbsp;<a href="#" onclick="doPrint();" class="btn btn-info" title="<?= lang('crud.help.btnPrint') ?>"><?= lang('crud.toolbars.btnPrint'); ?></a>
      <script>
        function doPrint() {
          var h = 700,
            /*window.top.outerHeight,*/
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
  <?php if ($config['multidelete']) : ?>
    <button id="btnRemove" disabled><?= lang('crud.toolbars.btnRemove'); ?></button>
  <?php endif; ?>
  <?php if ($config['deletepermanent'] && $config['useSoftDeletes']) : ?>
    <button id="btnRemovePerm" disabled><?= lang('crud.toolbars.btnRemovePermanently'); ?></button>
  <?php endif; ?>


</div>

<div class="mt-3">
  <?php if ($config['multidelete'] || ($config['deletepermanent'] && $config['useSoftDeletes'])) : ?>
    <form method="post" id="list_form" name="list_form" action="<?= base_url($_route) ?>">
      <?= csrf_field(); ?>

    </form>
  <?php endif; ?>


  <table class="table table-striped row-border" id="data-list-<?= $_table ?>" style="width:100%">
    <thead>
      <tr>
        <?php
        if ($config['numerate']) echo "<th>" . lang('crud.colHeadNitem') . "</th>";

        foreach ($_data_header as $dbname) {
          $colname = $_data_columns[$dbname]['name'] ?? ucfirst($dbname);
          echo "<th>" . $colname . "</th>";
        }
        if ($config['show_button'] || $config['editable'] || $config['removable'] || count($_arrItemFunctions) > 0)
          echo '<th>&nbsp;</th>';
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
              if (($_data_columns[$dbname]['type'] ?? '') == 'checkbox') {
                if ($row[$dbname] == ($_data_columns[$dbname]['check_value'] ?? ''))
                  echo "<td><span class='fas fa-check-square'></span></td>";
                else
                  echo "<td><span class='far fa-square'></span></td>";
              } else {
                echo "<td>" . $row[$dbname] . "</td>";
              }
            }
            $idQuery = "";
            foreach ($primaryKey as $key) {
              $idQuery .= "&" . str_rot13($key) . "=" . str_rot13($row[$key]);
            }

            if ($config['show_button'] || $config['editable'] || $config['removable'] || count($_arrItemFunctions) > 0) echo "<td>";

            if ($config['show_button']) {
              echo "<a href='" . base_url($_route . '?view=item' . $idQuery) . "' class='btn btn-sm text-info' title='" . lang('crud.help.btnShowItem') . "'><i class='fa-solid fa-eye'></i></a>" . PHP_EOL;
            }

            if ($config['editable'] || $config['removable']) {
              if ($config['editable'])
                echo "<a href='" . base_url($_route . '?edit=item' . $idQuery) . "' class='btn btn-sm text-primary' title='" . lang('crud.help.btnEditItem') . "'><i class='fa-solid fa-pen'></i></a>" . PHP_EOL;
              if ($config['removable'])
                echo "<a href='" . base_url($_route . '?del=item' . $idQuery) . "' class='btn  btn-sm text-danger' title='" . lang('crud.help.btnDelItem') . "'><i class='fa-solid fa-trash'></i></a>" . PHP_EOL;
            }

            if (count($_arrItemFunctions) > 0) {

              foreach ($_arrItemFunctions as $name => $itemFunc) {

                if ($itemFunc['visible']) {
                  if ($itemFunc['type'] == 'callback') {
                    echo "<a href='" . base_url($_route . '?customf=' . $name . $idQuery);
                    echo "' class='btn  btn-sm text-primary' title='" . $itemFunc['description'];
                    echo "'><i class='fa-solid " . $itemFunc['icon'] . "'></i></a>" . PHP_EOL;
                  } else { //type == 'link
                    $urlID = "";
                    foreach ($primaryKey as $key) {
                      if (is_array($itemFunc['func'])) {
                        if ($itemFunc['func'][1] == 'hash')   //FIXED: hash with multiple keys
                          $urlID .= "/" . md5($row[$key]);
                        else
                          $urlID .= "/" . $row[$key];
                      } else
                        $urlID .= "/" . $row[$key];
                    }
                    if (is_array($itemFunc['func']))
                      echo "<a href='" . $itemFunc['func'][0] . $urlID;
                    else
                      echo "<a href='" . $itemFunc['func'] . $urlID;

                    echo "' class='btn  btn-sm text-primary' title='" . $itemFunc['description'];
                    echo "'><i class='fa-solid " . $itemFunc['icon'] . "'></i></a>" . PHP_EOL;
                  }
                }
              }
            }

            if ($config['show_button'] || $config['editable'] || $config['removable'] || count($_arrItemFunctions) > 0) echo "</td>";
            $nRow++;
            ?>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>

    </tbody>
  </table>
</div>

<script>
  <?php if ($config['recycled_button'] && $config['useSoftDeletes']) : ?>

    function doEmpty() {
      $('<input>').attr({
        type: 'hidden',
        name: 'trashop',
        value: 'empty'
      }).appendTo('#list_form');

      // <?= base_url($_route . '?trash=list') ?>

      $('#list_form').attr('action', '<?= base_url($_route) ?>?trash=list');
      $("#list_form").submit();
    }
  <?php endif; ?>
  <?php if ($config['multidelete'] || ($config['deletepermanent'] && $config['useSoftDeletes'])) : ?>

    function doRemove(type) {
      $('#list_form').attr('action', '<?= base_url($_route) ?>?list=' + type);

      var table = $('#data-list-<?= $_table ?>').DataTable();

      $('<input>').attr({
        type: 'hidden',
        name: 'listop',
        value: 'remove' + type
      }).appendTo('#list_form');

      let items = table.rows('.bg-secondary').data();
      for (let index = 0; index < items.length; index++) {
        let rowId = items[index]['DT_RowId'];

        let id = $('#' + rowId).attr('data-kpc-id');

        $('<input>').attr({
          type: 'hidden',
          name: 'remove[]',
          value: id
        }).appendTo('#list_form');
      }

      $("#list_form").submit();
    }
  <?php endif; ?>

  $(document).ready(function() {
    $('#data-list-<?= $_table ?>').DataTable({
      lengthMenu: [
        [5, 10, 25, 50, 100, -1],
        [5, 10, 25, 50, 100, "<?= lang('crud.allItems'); ?>"]
      ],
      iDisplayLength: '<?= $config['defaultPageSize'] ?>',
      stateSave: '<?= $config['rememberState'] ?>',
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
          echo "{targets:" . $i . ", orderable:true}," . PHP_EOL;
          $i++;
        }

        foreach ($_data_header as $key => $dbname) {

          if (isset($_sortable_columns[$dbname])) {
            $order = $_sortable_columns[$dbname] ? 'true' : 'false';
            echo "\t\t{targets:" . $i . ", orderable:" . $order . "}," . PHP_EOL;
          }
          $i++;
        }
        ?>


      ],
    });

    <?php if ($config['multidelete'] || ($config['deletepermanent'] && $config['useSoftDeletes'])) : ?>

      var table = $('#data-list-<?= $_table ?>').DataTable();
      $('#data-list-<?= $_table ?> tbody').on('click', 'tr', function() {
        $(this).toggleClass('bg-secondary');

        <?php if ($config['deletepermanent'] && $config['useSoftDeletes']) : ?>
          if (table.rows('.bg-secondary').data().length > 0) $('#btnRemovePerm').prop('disabled', false);
          else $('#btnRemovePerm').prop('disabled', true);
        <?php endif; ?>

        <?php if ($config['multidelete']) : ?>
          if (table.rows('.bg-secondary').data().length > 0) $('#btnRemove').prop('disabled', false);
          else $('#btnRemove').prop('disabled', true);
        <?php endif; ?>
      });

      <?php if ($config['multidelete']) : ?>
        $('#btnRemove').click(function() {
          $("#confirmRemove").modal("show");
        });
      <?php endif; ?>

      <?php if ($config['deletepermanent'] && $config['useSoftDeletes']) : ?>
        $('#btnRemovePerm').click(function() {
          $("#confirmRemovePerm").modal("show");
        });
      <?php endif; ?>
    <?php endif; ?>

    <?php if ($config['deletepermanent'] && $config['useSoftDeletes']) : ?>
      $('#btnEmpty').click(function() {
        $("#confirmEmpty").modal("show");
      });
    <?php endif; ?>
  });
</script>

<?php if ($config['multidelete']) : ?>
  <!-- Modal Remove-->
  <div class="modal fade" id="confirmRemove" tabindex="-1" aria-labelledby="headModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header     ">
          <h5 class="modal-title" id="headModalLabel"><?= lang('crud.titles.modalRemoveConfirm'); ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?= lang('crud.modalRemoveConfirm'); ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('crud.btnCancel'); ?></button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick='doRemove("trash")'><?= lang('crud.btnDelete'); ?></button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php if ($config['deletepermanent'] && $config['useSoftDeletes']) : ?>
  <!-- Modal Remove-->
  <div class="modal fade" id="confirmRemovePerm" tabindex="-1" aria-labelledby="headModalLabel" aria-hidden="true">
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
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick='doRemove("perm")'><?= lang('crud.btnDelete'); ?></button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php if ($config['recycled_button'] && $config['useSoftDeletes']) : ?>
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
<?php endif; ?>
