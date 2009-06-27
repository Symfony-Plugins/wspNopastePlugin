<?php

/**
 * wspNopasteFeed actions.
 *
 * @package    wspNopastePlugin
 * @subpackage wspNopasteFeed
 * @author     Toni Uebernickel <tuebernickel@whitestarprogramming.de>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class wspNopasteFeedActions extends sfActions
{
  /**
   * Default of RSS limit.
   *
   * @var int
   */
  const RSS_LIMIT_DEFAULT = 5;

  /**
   * A sfFeed instance
   *
   * @var sfFeed
   */
  private $feed;

  /**
   * A list of wspNopasteEntry
   *
   * @var array
   */
  private $itemList = array();

  /**
   * set the template for all feeds to simply output the feed
   */
  public function postExecute()
  {
    $this->addFeedItemsByList($this->getItemList());
    $this->setVar('feed', $this->getFeed());
    $this->setTemplate('feed');
  }

  /**
   * get the current sfFeed
   *
   * @return sfFeed
   */
  private function getFeed()
  {
    if (!$this->feed)
    {
      $this->feed = $this->getFeedInstance();
    }

    return $this->feed;
  }

  /**
   * get a new sfFeed instance
   *
   * @return sfFeed
   */
  private function getFeedInstance()
  {
    $feed = sfFeed::newInstance('rss201rev2');

    $feed->setTitle($this->getFeedTitle());
    $feed->setLink($this->getFeedLink());
    $feed->setDescription($this->getFeedDescription());
    $feed->setAuthorEmail($this->getFeedAuthorEmail());
    $feed->setAuthorName($this->getFeedAuthorName());

    return $feed;
  }

  /**
   * get a sfFeeditem by wspNopasteEntry
   *
   * @param wspNopasteEntry $entry
   * @return sfFeedItem
   */
  private function getFeedItemByEntry(wspNopasteEntry $entry)
  {
      $item = new sfFeedItem();

      if ($entry->getsfGuardUser())
      {
        $item->setAuthorName($entry->getsfGuardUser()->getUsername());

        if (class_exists(sfConfig::get('app_sf_guard_plugin_profile_class', 'sfGuardUserProfile')) and $entry->getsfGuardUser()->getProfile() and method_exists($entry->getsfGuardUser()->getProfile(), 'getEmail'))
        {
          $item->setAuthorEmail($entry->getsfGuardUser()->getProfile()->getEmail());
        }
      }

      $item->setPubdate($entry->getCreatedAt('U'));
      $item->setTitle($entry->getTitle());
      $item->setLink('@wsp_nopaste_entry_permalink?slug=' . $entry->getSlug());
      $item->setDescription($this->getFeedItemDescription($entry->getBody()));

      return $item;
  }

  /**
   * add a list of wspNopasteEntry as feed items
   *
   * @param array $entryList
   */
  private function addFeedItemsByList($entryList)
  {
    /* @var $eachEntry wspNopasteEntry */
    foreach ($entryList as $eachEntry)
    {
      if ($eachEntry instanceof wspNopasteEntry)
      {
        $this->getFeed()->addItem($this->getFeedItemByEntry($eachEntry));
      }
      else
      {
        throw new InvalidArgumentException('The given entry is invalid.', 3);
      }
    }
  }

  /**
   * set up the item list
   *
   * @param array $list
   */
  private function setItemList($list)
  {
    if (is_array($list))
    {
      $this->itemList = $list;
    }
    else
    {
      throw new InvalidArgumentException('The given item list is invalid.', 1);
    }
  }

  /**
   * return the item list
   *
   * @return array
   */
  private function getItemList()
  {
    return $this->itemList;
  }

  /**
   *
   * @param sfWebRequest $request
   * @return
   */
  public function executeLatestEntries(sfWebRequest $request)
  {
    $this->setItemList(wspNopasteEntryPeer::retrieveLatestEntries($this->getLatestEntriesLimit()));
  }

  private function getFeedTitle()
  {
    if (sfConfig::get('app_wsp_nopaste_plugin_feed_title'))
    {
      return sfConfig::get('app_wsp_nopaste_plugin_feed_title');
    }
    else
    {
      return 'wspNopastePlugin Feed';
    }
  }

  private function getFeedLink()
  {
    if (sfConfig::get('app_wsp_nopaste_plugin_feed_link'))
    {
      return sfConfig::get('app_wsp_nopaste_plugin_feed_link');
    }
    else
    {
      return $_SERVER['REQUEST_URI'];
    }
  }

  private function getFeedDescription()
  {
    if (sfConfig::get('app_wsp_nopaste_plugin_feed_description'))
    {
      return sfConfig::get('app_wsp_nopaste_plugin_feed_description');
    }
    else
    {
      return 'This feed does not have any description, yet.';
    }
  }

  private function getFeedAuthorEmail()
  {
    if (sfConfig::get('app_wsp_nopaste_plugin_feed_author_email'))
    {
      return sfConfig::get('app_wsp_nopaste_plugin_feed_author_email');
    }
    else
    {
      return '';
    }
  }

  private function getFeedAuthorName()
  {
    if (sfConfig::get('app_wsp_nopaste_plugin_feed_author_name'))
    {
      return sfConfig::get('app_wsp_nopaste_plugin_feed_author_name');
    }
    else
    {
      return 'wspNopastePlugin - Various Authors';
    }
  }

  private function getLatestEntriesLimit()
  {
    if (is_numeric(sfConfig::get('app_wsp_nopaste_plugin_feed_limit')))
    {
      return sfConfig::get('app_wsp_nopaste_plugin_feed_limit');
    }
    else
    {
      return self::RSS_LIMIT_DEFAULT;
    }
  }

  private function getFeedItemDescription($content)
  {
    if (is_numeric(sfConfig::get('app_wsp_nopaste_plugin_feed_max_description_length')))
    {
      $length = sfConfig::get('app_wsp_nopaste_plugin_feed_max_description_length');
      if (strlen($content) > $length)
      {
        return substr($content, 0, $length-2) . '..';
      }
      else
      {
        return $content;
      }
    }
    else
    {
      return $content;
    }
  }
}
