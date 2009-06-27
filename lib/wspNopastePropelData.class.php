<?php

/**
 * This class is based an sfPropelData for using database sources.
 * It provides the ability to cleanup after functional tests of wspNopastePlugin.
 *
 * @author Toni Uebernickel <tuebernickel@whitestarprogramming.de>
 * @package wspNopastePlugin
 * @subpackage test.functional
 */
class wspNopastePropelData extends sfPropelData
{
  /**
   * configuration of required fixture files
   *
   * @var array
   */
  protected $fixtureSettings;

  /**
   * set up the fixture configuration
   *
   */
  public function __construct()
  {
    // setup the fixture settings
    $this->fixtureSettings = array(
      sfConfig::get('sf_data_dir') . '/fixtures/wspNopastePlugin-sfGuardUserTest.yml' => 'mcx-users',
      sfConfig::get('sf_data_dir') . '/fixtures/wspNopastePlugin-EntriesCommentsTest.yml' => 'wsp-nopaste',
    );
  }

  /**
   * get a list of all used fixture files
   *
   * @return array of string
   */
  public function getFixtureFiles()
  {
    $files = array();

    foreach ($this->fixtureSettings as $eachFixtureFile => $eachDatabaseConnection)
    {
    	$files[] = $eachFixtureFile;
    }

    return $files;
  }

  /**
   * get a list of all used fixture files and their database connections
   *
   * @return array of file => database connection
   */
  public function getFixtureFilesWithConnection()
  {
    return $this->fixtureSettings;
  }

  /**
   * cleanup all a comment and its replies
   *
   * @param int $commentId
   */
  private function cleanupComments($commentId)
  {
    /* @var $comment wspNopasteComment */
    $comment = wspNopasteCommentPeer::retrieveByPK($commentId);
    if ($comment)
    {
      $replies = $comment->getwspNopasteCommentsRelatedByReplyId();
      if (!empty($replies))
      {
        /* @var $eachReplyComment wspNopasteComment */
        foreach ($replies as $eachReplyComment)
        {
        	$this->cleanupComments($eachReplyComment->getId());
        }
      }

      $comment->delete();
    }
  }

  /**
   * cleanup after the wspNopaste tests
   * removes wspNopasteComment and wspNopasteEntry database entries
   *
   */
  public function cleanup()
  {
    $data = $this->getData('all', 'wsp-nopaste');

    if (!empty($data))
    {
      if (isset($data['wspNopasteComment']))
      {
        foreach ($data['wspNopasteComment'] as $identifier => $eachNopasteComment)
        {
          $commentId = null;
          if (preg_match('/[a-zA-Z]*_([0-9]+)/', $identifier, $commentId))
          {
            $this->cleanupComments($commentId[1]);
          }
        }
      }

      if (isset($data['wspNopasteEntry']))
      {
        foreach ($data['wspNopasteEntry'] as $identifier => $eachNopasteEntry)
        {
          $entryId = null;
          if (preg_match('/[a-zA-Z]*_([0-9]+)/', $identifier, $entryId))
          {
            $entry = wspNopasteEntryPeer::retrieveByPK($entryId[1]);

            $entry->delete();
          }
        }
      }
    }
  }
}