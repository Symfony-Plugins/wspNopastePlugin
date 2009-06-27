<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
<?php use_helper('Form') ?>
<?php if (!isset($submitLabel)): $submitLabel = 'Submit'; endif; ?>
<?php if (!isset($resetLabel)): $resetLabel = 'Reset'; endif; ?>
<?php /*@var $form sfForm*/
echo $form->renderFormTag($formURL) ?>
<?php include_partial('wspNopaste/flash', array('flashType' => array('form_notice', sfConfig::get('sf_validation_error_class')))) ?>
  <table id="nopaste-form">
    <tfoot>
      <tr><td colspan="2"><?php echo reset_tag($resetLabel), submit_tag($submitLabel) ?></td></tr>
    </tfoot>
    <tbody>
     <?php echo $form ?>
    </tbody>
  </table>
</form>
