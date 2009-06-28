<?php

/**
 * wspNopasteEntry form base class.
 *
 * @package    wspNopastePlugin
 * @subpackage form
 * @author     Toni Uebernickel <toni@uebernickel.info>
 * @version    SVN: $Id$
 */
class BasewspNopasteEntryForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'created_at' => new sfWidgetFormDateTime(),
      'created_by' => new sfWidgetFormInput(),
      'password'   => new sfWidgetFormInput(),
      'language'   => new sfWidgetFormInput(),
      'title'      => new sfWidgetFormInput(),
      'slug'       => new sfWidgetFormInput(),
      'body'       => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'wspNopasteEntry', 'column' => 'id', 'required' => false)),
      'created_at' => new sfValidatorDateTime(array('required' => false)),
      'created_by' => new sfValidatorInteger(),
      'password'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'language'   => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'title'      => new sfValidatorString(array('max_length' => 120, 'required' => false)),
      'slug'       => new sfValidatorString(array('max_length' => 255)),
      'body'       => new sfValidatorString(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'wspNopasteEntry', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('wsp_nopaste_entry[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'wspNopasteEntry';
  }


}
