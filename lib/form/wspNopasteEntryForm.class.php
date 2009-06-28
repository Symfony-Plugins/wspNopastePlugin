<?php
/**
 * @author        Toni Uebernickel <toni@uebernickel.info>
 * @link          http://toni.uebernickel.info/
 *
 * @package       wspNopastePlugin
 * @subpackage    forms
 * @version       $Id$
 * @link          $HeadURL$
 */

/**
 * wspNopasteEntry form
 */
class wspNopasteEntryForm extends BasewspNopasteEntryForm
{
  /**
   * add sfEasySyntaxHighlighter
   * removes created_at, created_by and slug widgets
   *
   */
  public function setup()
  {
    parent::setup();

    // geshi language
    $this->setWidget('language', new sfEasySyntaxHighlighterWidgetLanguageChoice(array('default' => 'text')));
    $this->setValidator('language', new sfEasySyntaxHighlighterValidatorLanguageChoice());

    // title and body are required and non empty
    $this->setValidator('title', new sfValidatorString(array('max_length' => 120, 'required' => true)));
    $this->setValidator('body', new sfValidatorString(array('required' => true)));

    unset($this['created_by'], $this['created_at'], $this['slug']);

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
}
