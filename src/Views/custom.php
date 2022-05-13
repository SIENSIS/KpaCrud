<?php

/**
 * Custom function page view. 
 * 
 * @package KpaCrud\Views
 * 
 * @version 1.4.3a
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
?>
<div class="d-flex justify-content-end">

  <a href="<?php echo base_url($_route) ?>" class="btn btn-info" title="<?= lang('crud.help.btnList') ?>"><?= lang('crud.toolbars.btnList'); ?></a>
  <?php
  if ($config['add_button'] || $config['exportXLS'] || $config['recycled_button'] || $config['print']) {
  ?>

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


  <?php } //endif 
  ?>
</div>
<div id='content'>
  <?= $content ?>
</div>