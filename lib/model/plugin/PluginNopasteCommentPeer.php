<?php
/**
 * @author        Toni Uebernickel <toni@uebernickel.info>
 * @link          http://toni.uebernickel.info/
 *
 * @package       wspNopastePlugin
 * @subpackage    plugin.model
 * @version       $Id$
 * @link          $HeadURL$
 */

/**
 * wspNopastePlugin comment peer
 */
class PluginwspNopasteCommentPeer extends BasewspNopasteCommentPeer
{
  /**
   * get comment with related comments
   *
   * @param Criteria $criteria
   * @param PropelPDO $con
   * @param string $join_behavior
   *
   * @return array of wspNopasteComment
   */
  public static function doSelectJoinwspNopasteCommentRelatedByReplyId(Criteria $criteria = null, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
  {
  	return parent::doSelectJoinAllExceptwspNopasteEntry($criteria, $con, $join_behavior);
  }
}
