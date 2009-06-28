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
 * wspNopastePlugin comment
 */
class PluginwspNopasteComment extends BasewspNopasteComment
{
  /**
   * The string representation of a comment is its title.
   *
   * @return string
   */
  public function __toString()
  {
    return $this->getTitle();
  }

  /**
   * get the sfGuardUser of the creator
   * if none is given, an anonymous dummy sfGuardUser is returned (userid: 0)
   *
   * @return sfGuardUser
   */
  public function getsfGuardUser()
  {
    if ($sfGuardUser = sfGuardUserPeer::retrieveByPK($this->getCreatedBy()))
    {
      return $sfGuardUser;
    }
    else
    {
      // anonymous
      $sfGuardUser = new sfGuardUser();
      $sfGuardUser->setId(0);
      $sfGuardUser->setUsername('anonymous');

      return $sfGuardUser;
    }
  }
}
