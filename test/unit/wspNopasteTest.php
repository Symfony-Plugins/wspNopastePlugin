<?php
/**
 * @author        Toni Uebernickel <toni@uebernickel.info>
 * @link          http://toni.uebernickel.info/
 *
 * @package       wspNopastePlugin
 * @subpackage    unit.test
 * @version       $Id$
 * @link          $HeadURL$
 */

require_once(dirname(__FILE__) . '/../bootstrap/unit.php');

$limeTest = new lime_test(20, new lime_output_color());

$limeTest->info('Setting up sfContext ..');
sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration('nopaste', 'test', true));

$limeTest->info('Cleaning up ..');
// we do not know, whether this succeeded the last time, so clean up first
$testData = new wspNopastePropelData();
$testData->cleanup();

$limeTest->is(count(wspNopasteEntryPeer::doSelect(new Criteria())), 0, 'no entries given');
$limeTest->is(count(wspNopasteCommentPeer::doSelect(new Criteria())), 0, 'no comments given');

$testEntry = new wspNopasteEntry();
$testEntry->setTitle('Automated Test Entry');
$testEntry->setLanguage('text');
$testEntry->setBody('This is an automated entry.');
$expectedSlug = 'automated-test-entry';

$limeTest->is($testEntry->save(), 1, 'inserted test entry');

$dbEntry = wspNopasteEntryPeer::doSelectOne(new Criteria());
$limeTest->isa_ok($dbEntry, 'wspNopasteEntry', 'found test entry');
$limeTest->info('Comparing testEntry with dbEntry ..');
$limeTest->is($dbEntry->getTitle(), $testEntry->getTitle(), 'title matches');
$limeTest->is($dbEntry->getBody(), $testEntry->getBody(), 'body matches');
$limeTest->is($dbEntry->getLanguage(), $testEntry->getLanguage(), 'language matches');
$limeTest->is($dbEntry->getSlug(), $expectedSlug, 'slug as expected');

$limeTest->info('Testing permanent slug ..');
$testEntry->setTitle('Automated Test Entry second edition');
$dbEntry->setTitle($testEntry->getTitle());
$dbEntry->save();

// reload from database
$dbEntry = wspNopasteEntryPeer::doSelectOne(new Criteria());
$limeTest->is($dbEntry->getTitle(), $testEntry->getTitle(), 'changed title matches');
$limeTest->is($dbEntry->getSlug(), $expectedSlug, 'slug is permanent');
$dbEntry->setSlug('automated-test-entry-2');
$dbEntry->save();
$limeTest->isnt($dbEntry->getSlug(), $expectedSlug, 'slug overwritten manually');

// insert a comment
$limeTest->info('Testing comments and replies ..');
$testComment = new wspNopasteComment();
$testComment->setTitle('A test comment');
$testComment->setBody('This is a test comment on an entry.');
$testComment->setwspNopasteEntry($dbEntry);
$limeTest->is($testComment->save(), 1, 'inserted test comment');
// reload from database
$dbComment = wspNopasteCommentPeer::doSelectOne(new Criteria());
$limeTest->isa_ok($dbComment, 'wspNopasteComment', 'found test comment');
$limeTest->is($dbComment->getNopasteEntryId(), $dbEntry->getId(), 'comment is linked correctly');

// insert a reply
$testReply = new wspNopasteComment();
$testReply->setTitle('A test reply comment');
$testReply->setBody('This is a test comment on a comment (reply).');
$testReply->setwspNopasteCommentRelatedByReplyId($testComment);
$limeTest->is($testReply->save(), 1, 'inserted test reply comment');
// reload from database using db comment
$tmp = $dbComment->getwspNopasteCommentsRelatedByReplyId();
/* @var $dbReply wspNopasteComment */
$dbReply = $tmp[0];
unset($tmp);
$limeTest->isa_ok($dbReply, 'wspNopasteComment', 'found test reply comment');
$limeTest->is($dbComment->getId(), $dbReply->getReplyId(), 'reply is linked correctly');
$testCommentTree = $dbEntry->getwspNopasteCommentsJoinwspNopasteCommentRelatedByReplyId();
$limeTest->is(count($testCommentTree), 1, 'found test comment tree');
$limeTest->info('Testing delete function of wspNopasteEntry ..');
$dbEntry->delete();
$limeTest->is(count(wspNopasteEntryPeer::doSelect(new Criteria())), 0, 'no entries given');
$limeTest->is(count(wspNopasteCommentPeer::doSelect(new Criteria())), 0, 'no comments given');

// cleanup
$testData->cleanup();