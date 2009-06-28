<?php
/**
 * @author        Toni Uebernickel <toni@uebernickel.info>
 * @link          http://toni.uebernickel.info/
 *
 * @package       wspNopastePlugin
 * @subpackage    actions.wspNopaste.modules
 * @version       $Id$
 * @link          $HeadURL$
 */

/**
 * wspNopaste actions
 */
class wspNopasteActions extends sfActions
{
  /**
   * The default limit of lists, if none is given or configured.
   *
   * @var int
   */
  const LIST_LIMIT_DEFAULT = 25;

  /**
   * get the limit for the latest entries list
   *
   * @return int
   */
  private function getLatestEntriesLimit()
  {
    if (is_numeric(sfConfig::get('app_wsp_nopaste_plugin_list_limit')))
    {
      return sfConfig::get('app_wsp_nopaste_plugin_list_limit');
    }
    else
    {
      return self::LIST_LIMIT_DEFAULT;
    }
  }

  /**
   * show a list of all entries
   *
   * @param sfRequest $request A request object
   *
   * @return string
   */
  public function executeShowAll(sfWebRequest $request)
  {
    $this->nopasteTableHeading = sfConfig::get('app_wsp_nopaste_plugin_table_heading_all', 'wspNopaste - All Entries');
    $this->pastes = wspNopasteEntryPeer::retrieveOrderedByCreatedAt();
    $this->setTemplate('showTable');

    return sfView::SUCCESS;
  }

  /**
   * show a list of the latest entries
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeShowLatest(sfWebRequest $request)
  {
    $this->nopasteTableHeading = sfConfig::get('app_wsp_nopaste_plugin_table_heading_latest', 'wspNopaste - Latest Entries');
    $this->pastes = wspNopasteEntryPeer::retrieveLatestEntries($this->getLatestEntriesLimit());
    $this->setTemplate('showTable');

    return sfView::SUCCESS;
  }

  /**
   * show a single paste
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeShowEntry(sfWebRequest $request)
  {
    /* @var $paste wspNopasteEntry */
    $paste = $this->paste = $this->getRoute()->getObject();

    return sfView::SUCCESS;
  }

  // functions for the "new entry" form (wspNopasteEntry)

  /**
   * show the form to enter a new paste
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeNew(sfWebRequest $request)
  {
    $this->form = new wspNopasteEntryForm();

    return sfView::SUCCESS;
  }

  /**
   * process the form after it has been sent from executeNew()
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new wspNopasteEntryForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');

    return sfView::SUCCESS;
  }

  /**
   * show the form to edit a given entry
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeEdit(sfWebRequest $request)
  {
    $this->form = new wspNopasteEntryForm($this->getRoute()->getObject());

    return sfView::SUCCESS;
  }

  /**
   * process the form after it has been sent from executeEdit()
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeUpdate(sfWebRequest $request)
  {
    $this->form = new wspNopasteEntryForm($this->getRoute()->getObject());
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');

    return sfView::SUCCESS;
  }

  /**
   * process the form after it has been sent to delete
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeDelete(sfWebRequest $request)
  {
    /* @var $entry wspNopasteEntry */
    $entry = $this->getRoute()->getObject();

    if ($this->getUser()->isAuthenticated() and ($this->getUser()->getGuardUser()->getId() == $entry->getCreatedBy()))
    {
      $entry->delete();
      $this->getUser()->setFlash(
        sfConfig::get('app_wsp_nopaste_plugin_flash_notice', 'form_notice'),
        sfConfig::get('app_wsp_nopaste_plugin_flash_delete_notice', 'This entry has been deleted.')
      );
    }
    else
    {
      $this->getUser()->setFlash(
        sfConfig::get('app_wsp_nopaste_plugin_flash_forbidden', 'form_error'),
        sfConfig::get('app_wsp_nopaste_plugin_flash_delete_forbidden', 'The requested action is forbidden.')
      );
    }

    $this->redirect('wspNopaste/showLatest');
  }

  /**
   * process the form
   *
   * @param sfWebRequest $request
   * @param sfFormPropel $form
   *
   * @return void
   */
  protected function processForm(sfWebRequest $request, sfFormPropel $form)
  {
    $form->bind(
      $request->getParameter($form->getName()),
      $request->getFiles($form->getName())
    );

    if ($form->isValid())
    {
      if ($this->getUser()->getGuardUser())
      {
        $userId = $this->getUser()->getGuardUser()->getId();
      }
      else
      {
        $userId = 0;
      }
      $form->getObject()->setCreatedBy($userId);
      $form->save();

      $this->redirect($this->generateUrl('wsp_nopaste_entry_permalink', $form->getObject()));
    }
    else
    {
      $this->getUser()->setFlash(
        sfConfig::get('sf_validation_error_class', 'form_error'),
        sfConfig::get('app_wsp_nopaste_plugin_form_error', 'The form is invalid.'),
        false
      );
    }
  }
}
