<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * wspNopasteComment filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id$
 */
class BasewspNopasteCommentFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'nopaste_entries_id' => new sfWidgetFormPropelChoice(array('model' => 'wspNopasteEntry', 'add_empty' => true)),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'created_by'         => new sfWidgetFormFilterInput(),
      'nopaste_comment_id' => new sfWidgetFormPropelChoice(array('model' => 'wspNopasteComment', 'add_empty' => true)),
      'title'              => new sfWidgetFormFilterInput(),
      'body'               => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'nopaste_entries_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'wspNopasteEntry', 'column' => 'id')),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'created_by'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nopaste_comment_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'wspNopasteComment', 'column' => 'id')),
      'title'              => new sfValidatorPass(array('required' => false)),
      'body'               => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wsp_nopaste_comment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'wspNopasteComment';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'nopaste_entries_id' => 'ForeignKey',
      'created_at'         => 'Date',
      'created_by'         => 'Number',
      'nopaste_comment_id' => 'ForeignKey',
      'title'              => 'Text',
      'body'               => 'Text',
    );
  }
}
