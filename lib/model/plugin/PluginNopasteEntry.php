<?php

class PluginwspNopasteEntry extends BasewspNopasteEntry
{
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
   * if none is given, an anonymous dummy sfGuardUser is returned
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