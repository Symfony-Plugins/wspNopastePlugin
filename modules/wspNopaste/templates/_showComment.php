<?php /* @var $comment wspNopasteComment */ ?>
<div class="nopaste-comment" id="comment-<?php echo $comment->getId() ?>">
  <h4><?php echo $comment->getTitle() ?></h4>
  <p><?php echo $comment->getBody() ?></p>
  <h5>created at <?php echo $comment->getCreatedAt('D, M j Y') ?> by <?php echo $comment->getsfGuardUser()->getUsername() ?></h5>
  <?php
  if (count($comment->getwspNopasteCommentsRelatedByReplyId())):
    foreach ($comment->getwspNopasteCommentsRelatedByReplyId() as $eachReply):
      include_partial('wspNopaste/showComment', array('comment' => $eachReply));
    endforeach;
  endif;
  ?>
</div>