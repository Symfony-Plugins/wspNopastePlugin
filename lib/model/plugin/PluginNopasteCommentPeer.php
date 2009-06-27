<?php

class PluginwspNopasteCommentPeer extends BasewspNopasteCommentPeer
{
  public static function doSelectJoinwspNopasteCommentRelatedByReplyId(Criteria $criteria = null, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
  {
  	return parent::doSelectJoinAllExceptwspNopasteEntry($criteria, $con, $join_behavior);
  }
}
