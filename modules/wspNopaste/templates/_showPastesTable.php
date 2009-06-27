<?php if (count($pastes)): ?>
<table id="nopaste-entry-list">
  <thead>
    <tr><th>Created At</th><th>Created By</th><th>Title</th></tr>
  </thead>
  <tbody>
  <?php
  /* @var $eachPaste wspNopasteEntry */
  foreach ($pastes as $eachPaste): ?>
  <tr>
    <td><?php echo $eachPaste->getCreatedAt('D, M j Y') ?></td>
    <td><?php echo $eachPaste->getsfGuardUser()->getUsername() ?></td>
    <td><?php echo link_to($eachPaste->getTitle(), '@wsp_nopaste_entry_permalink?slug=' . $eachPaste->getSlug()) ?></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>