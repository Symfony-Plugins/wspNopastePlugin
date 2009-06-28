<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * wspNopasteEntry filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id$
 */
class BasewspNopasteEntryFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'created_by' => new sfWidgetFormFilterInput(),
      'password'   => new sfWidgetFormFilterInput(),
      'language'   => new sfWidgetFormFilterInput(),
      'title'      => new sfWidgetFormFilterInput(),
      'slug'       => new sfWidgetFormFilterInput(),
      'body'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'created_by' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'password'   => new sfValidatorPass(array('required' => false)),
      'language'   => new sfValidatorPass(array('required' => false)),
      'title'      => new sfValidatorPass(array('required' => false)),
      'slug'       => new sfValidatorPass(array('required' => false)),
      'body'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wsp_nopaste_entry_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'wspNopasteEntry';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'created_at' => 'Date',
      'created_by' => 'Number',
      'password'   => 'Text',
      'language'   => 'Text',
      'title'      => 'Text',
      'slug'       => 'Text',
      'body'       => 'Text',
    );
  }
}
