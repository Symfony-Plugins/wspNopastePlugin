<?php /* @var $paste wspNopasteEntry */ ?>
<div class="nopaste-entry">
<h2><?php echo $paste->getTitle() ?></h2>
<h3>created at <?php echo $paste->getCreatedAt('D, M j Y'); ?> by <?php echo $paste->getsfGuardUser()->getUsername() ?></h3>
<?php echo $paste->getParsedCode() ?>
<h3 id="comments">Comments</h3>
<?php
if (count($paste->getwspNopasteComments())):
  /* @var $eachComment wspNopasteComment */
  foreach ($paste->getwspNopasteComments() as $eachComment):
    if (!$eachComment->getReplyId()):
     include_partial('wspNopaste/showComment', array('comment' => $eachComment));
    endif;
  endforeach;
else:
?>
<p id="no-comments">No comments so far!</p>
<?php endif; ?>
</div>