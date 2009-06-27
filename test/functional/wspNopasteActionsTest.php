<?php

require_once(dirname(__FILE__) . '/../../bootstrap/functional.php');

// create a new test browser
$testBrowser = new sfTestBrowser();
$testBrowser->initialize();
$testBrowser->info('Cleaning up ..');
// cleanup the database
$testData = new wspNopastePropelData();
// we do not know, whether this succeeded the last time, so clean up first
$testData->cleanup();

$testBrowser->info('Testing empty database ..');
// check module and action
$testBrowser->getAndCheck('wspNopaste', 'showAll', '/wspNopaste/showAll', 200);
// right now there should not be any entry
$testBrowser->checkResponseElement('#nopaste-entry-list', null);

// check module and action
$testBrowser->getAndCheck('wspNopaste', 'showLatest', '/wspNopaste/showLatest', 200);
// right now there should not be any entry
$testBrowser->checkResponseElement('#nopaste-entry-list', null);

$testBrowser->info('Loading fixtures files ..');
$fixtureFiles = $testData->getFixtureFilesWithConnection();
foreach ($fixtureFiles as $eachFixtureFile => $eachConnection)
{
  // load each file with its corresponding connection
  $testData->loadData($eachFixtureFile, $eachConnection);
}

$testBrowser->info('Check for inserted entries and comments ..');
$testBrowser->get('/wspNopaste/showLatest');
$testBrowser->isStatusCode(200);
// check entry
$testBrowser->checkResponseElement('#nopaste-entry-list', '/Test Paste 1/');
$testBrowser->checkResponseElement('#nopaste-entry-list tbody tr', '/.*anonymous\W+Test Paste 1.*/');

$testBrowser->getAndCheck('wspNopaste', 'showEntry', '/test-paste-1', 200);
$testBrowser->checkResponseElement('.nopaste-entry > h3', '/.*by anonymous.*/');
// check first comment
$testBrowser->checkResponseElement('.nopaste-comment h4', '/A test comment/');
$testBrowser->checkResponseElement('.nopaste-comment p', '/This is just a test comment on a paste./');
// check reply comment
$testBrowser->checkResponseElement('.nopaste-comment .nopaste-comment h4', '/Yet another test comment/');
$testBrowser->checkResponseElement('.nopaste-comment .nopaste-comment p', '/This is a reply comment!/');

$testBrowser->info('Testing wspNopasteEntryForm ..');
$testBrowser->getAndCheck('wspNopaste', 'new', '/wspNopaste/new', 200);
// check for the form elements
$testBrowser->responseContains('wsp_nopaste_entry_password');
$testBrowser->responseContains('wsp_nopaste_entry_language');
$testBrowser->responseContains('wsp_nopaste_entry_title');
$testBrowser->responseContains('wsp_nopaste_entry_body');
$testBrowser->responseContains('value="text" selected="selected"');
// these attributes should not be visible to an user
$testBrowser->checkResponseElement('wsp_nopaste_entry_created_at', null);
$testBrowser->checkResponseElement('wsp_nopaste_entry_created_by', null);
$testBrowser->checkResponseElement('wsp_nopaste_entry_slug', null);

$testBrowser->info('Setting up a test entry ..');
$testEntry = new wspNopasteEntry();
$testEntry->setTitle('Automated Test Entry');
$testEntry->setLanguage('text');
$testEntry->setBody('This is an automated entry.');
// the expected slug
$testEntry->setSlug('automated-test-entry');

$testBrowser->post('/wspNopaste/create', array('wsp_nopaste_entry' => array('language' => $testEntry->getLanguage(), 'title' => $testEntry->getTitle(), 'body' => $testEntry->getBody())));
$testBrowser->isRedirected(true);
// HTTP 302: Found
$testBrowser->isStatusCode(302);

$testBrowser->info('Check the submitted data ..');
$testBrowser->getAndCheck('wspNopaste', 'showEntry', '/' . $testEntry->getSlug(), 200);
$testBrowser->checkResponseElement('.nopaste-entry h2', $testEntry->getTitle());
$testBrowser->checkResponseElement('.nopaste-entry pre', $testEntry->getBody());
$testBrowser->checkResponseElement('#no-comments', 'No comments so far!');

$testBrowser->info('Sending invalid form data ..');
$testBrowser->post('/wspNopaste/create', array('wsp_nopaste_entry' => array('password' => 'passwd')));
$testBrowser->isRedirected(false);
$testBrowser->isStatusCode(200);
$testBrowser->checkResponseElement('.flashlist .form_error', 'The form data is invalid!');
$testBrowser->checkResponseElement('.error_list li', 'Required.');
// check whether the sent data is shown
$testBrowser->responseContains('value="passwd" id="wsp_nopaste_entry_password"');

$testBrowser->info('Checking RSS Feed ..');
$testBrowser->getAndCheck('wspNopasteFeed', 'latestEntries', '/wspNopasteFeed/latestEntries', 200);
$testBrowser->isResponseHeader('Content-Type', '/.*xml.*/');
$testBrowser->responseContains($testEntry->getTitle());
$testBrowser->responseContains($testEntry->getSlug());

$testBrowser->info('Cleaning up ..');
$testData->cleanup();