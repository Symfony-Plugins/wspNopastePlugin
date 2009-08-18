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
 * wspNopastePlugin entry peer
 */
class PluginwspNopasteEntryPeer extends BasewspNopasteEntryPeer
{
  /**
   * Get a Criterion to identify an Entry having no password set.
   *
   * @param Criteria $criteria
   *
   * @return Criterion
   */
  protected static final function getNoPasswordCriterion($criteria)
  {
    return $criteria->getNewCriterion(self::PASSWORD, null, Criteria::EQUAL)->addOr($criteria->getNewCriterion(self::PASSWORD, '', Criteria::EQUAL));
  }

  /**
   * get the latest entries that are not password protected
   *
   * @param int $limit defaults to unlimited
   * @param PropelPDO $con
   *
   * @return array of wspNopasteEntry
   */
  public static function retrieveLatestEntries($limit = 0, PropelPDO $con = null)
  {
    if (!is_numeric($limit))
    {
      throw new InvalidArgumentException('The given limit argument is invalid.', 1);
    }

    if ($con === null)
    {
      $con = Propel::getConnection(self::DATABASE_NAME, Propel::CONNECTION_READ);
    }

    $criteria = new Criteria(self::DATABASE_NAME);
    $criteria->add(self::getNoPasswordCriterion($criteria));
    $criteria->addDescendingOrderByColumn(self::CREATED_AT);
    $criteria->addDescendingOrderByColumn(self::ID);

    if ($limit)
    {
      $criteria->setLimit($limit);
    }

    return self::doSelect($criteria);
  }

  /**
   * retrieve entries ordered by creation date
   *
   * @param int $limit defaults to unlimited
   * @param PropelPDO $con
   *
   * @return array of wspNopasteEntry
   */
  public static function retrieveOrderedByCreatedAt($limit = 0, PropelPDO $con = null)
  {
    if (!is_numeric($limit))
    {
      throw new InvalidArgumentException('The given limit argument is invalid.', 2);
    }

    if ($con === null)
    {
      $con = Propel::getConnection(self::DATABASE_NAME, Propel::CONNECTION_READ);
    }

    $criteria = new Criteria(self::DATABASE_NAME);
    $criteria->add(self::getNoPasswordCriterion($criteria));
    $criteria->addDescendingOrderByColumn(self::CREATED_AT);
    $criteria->addDescendingOrderByColumn(self::ID);

    if ($limit)
    {
      $criteria->setLimit($limit);
    }

    return self::doSelect($criteria);
  }
}
