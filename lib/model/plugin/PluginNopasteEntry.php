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
 * wspNopastePlugin entry
 */
class PluginwspNopasteEntry extends BasewspNopasteEntry
{
  /**
   * The string representation of an entry is its title.
   *
   * @return string
   */
  public function __toString()
  {
    return $this->getTitle();
  }

  /**
   * get the geshi parsed code
   *
   * @uses sfEasySyntaxHighlighterHelper
   *
   * @param bool $keywordLinks
   *
   * @return string
   */
  public function getParsedCode($keywordLinks = false)
  {
    $geshi = new sfEasySyntaxHighlighterHelper($this->getBody(), $this->getLanguage());
    $geshi->enable_keyword_links($keywordLinks);

    return $geshi->parse_code();
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

  public function save(PropelPDO $con = null)
  {
    if (sfContext::getInstance()->getUser()->getGuardUser() and $userId = sfContext::getInstance()->getUser()->getGuardUser()->getId())
    {
      $this->setCreatedBy($userId);
    }

    return parent::save($con);
  }
}