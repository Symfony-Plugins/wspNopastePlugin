<?php

class PluginwspNopasteComment extends BasewspNopasteComment
{
  public function __toString()
  {
    return $this->getTitle();
  }

  /**
   * get the sfGuardUser of the creator
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
