<?php

/**
 * wspNopasteEntry form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class wspNopasteEntryForm extends BasewspNopasteEntryForm
{
  /**
   * add sfPropelActAsSluggableBehaviorPlugin and sfEasySyntaxHighlighter
   * remove created at and created by
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
