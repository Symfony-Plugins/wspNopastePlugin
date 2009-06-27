<?php

class PluginwspNopasteEntryPeer extends BasewspNopasteEntryPeer
{
  /**
   * get the latest entries that are not password protected
   *
   * @param int $limit
   * @param PropelPDO $con
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
    $criteria->add($criteria->getNewCriterion(self::PASSWORD, null, Criteria::EQUAL)->addOr($criteria->getNewCriterion(self::PASSWORD, '', Criteria::EQUAL)));
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
   * @param int $limit
   * @param PropelPDO $con
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
    $criteria->addDescendingOrderByColumn(self::CREATED_AT);
    $criteria->addDescendingOrderByColumn(self::ID);

    if ($limit)
    {
      $criteria->setLimit($limit);
    }

    return self::doSelect($criteria);
  }
}
