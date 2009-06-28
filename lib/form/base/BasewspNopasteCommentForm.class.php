<?php

/**
 * wspNopasteComment form base class.
 *
 * @package    wspNopastePlugin
 * @subpackage form
 * @author     Toni Uebernickel <toni@uebernickel.info>
 * @version    SVN: $Id$
 */
class BasewspNopasteCommentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'nopaste_entries_id' => new sfWidgetFormPropelChoice(array('model' => 'wspNopasteEntry', 'add_empty' => true)),
      'created_at'         => new sfWidgetFormDateTime(),
      'created_by'         => new sfWidgetFormInput(),
      'nopaste_comment_id' => new sfWidgetFormPropelChoice(array('model' => 'wspNopasteComment', 'add_empty' => true)),
      'title'              => new sfWidgetFormInput(),
      'body'               => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorPropelChoice(array('model' => 'wspNopasteComment', 'column' => 'id', 'required' => false)),
      'nopaste_entries_id' => new sfValidatorPropelChoice(array('model' => 'wspNopasteEntry', 'column' => 'id', 'required' => false)),
      'created_at'         => new sfValidatorDateTime(array('required' => false)),
      'created_by'         => new sfValidatorInteger(),
      'nopaste_comment_id' => new sfValidatorPropelChoice(array('model' => 'wspNopasteComment', 'column' => 'id', 'required' => false)),
      'title'              => new sfValidatorString(array('max_length' => 120, 'required' => false)),
      'body'               => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wsp_nopaste_comment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'wspNopasteComment';
  }


}
