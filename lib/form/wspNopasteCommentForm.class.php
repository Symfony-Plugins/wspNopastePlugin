<?php

/**
 * wspNopasteComment form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class wspNopasteCommentForm extends BasewspNopasteCommentForm
{
  public function setup()
  {
    parent::setup();

    unset(
      $this['created_at'],
      $this['created_by'],
      $this['nopaste_comment_id'],
      $this['nopaste_entries_id']
    );
  }
}
