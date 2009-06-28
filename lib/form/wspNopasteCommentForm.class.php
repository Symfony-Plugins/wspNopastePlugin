<?php
/**
 * @author        Toni Uebernickel <toni@uebernickel.info>
 * @link          http://toni.uebernickel.info/
 *
 * @package       wspNopastePlugin
 * @subpackage    forms
 * @version       $Id$
 * @link          $HeadURL$
 */

/**
 * wspNopasteComment form
 */
class wspNopasteCommentForm extends BasewspNopasteCommentForm
{
  /**
   * remove created_at, created_by and references from form
   *
   * @return void
   */
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
