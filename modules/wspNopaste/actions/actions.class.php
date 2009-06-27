<?php

/**
 * wspNopaste actions.
 *
 * @package    wspNopastePlugin
 * @subpackage wspNopaste
 * @author     Toni Uebernickel <tuebernickel@whitestarprogramming.de>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class wspNopasteActions extends sfActions
{
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
   */
  public function executeShowAll(sfWebRequest $request)
  {
    $this->nopasteTableHeading = sfConfig::get('app_wsp_nopaste_plugin_table_heading_all', 'wspNopaste - All Entries');
    $this->pastes = wspNopasteEntryPeer::retrieveOrderedByCreatedAt();
    $this->setTemplate('showTable');
  }

  /**
   * show a list of the latest entries
   *
   * @param sfWebRequest $request
   */
  public function executeShowLatest(sfWebRequest $request)
  {
    $this->nopasteTableHeading = sfConfig::get('app_wsp_nopaste_plugin_table_heading_latest', 'wspNopaste - Latest Entries');
    $this->pastes = wspNopasteEntryPeer::retrieveLatestEntries($this->getLatestEntriesLimit());
    $this->setTemplate('showTable');
  }

  /**
   * show a single paste
   *
   * @param sfWebRequest $request
   */
  public function executeShowEntry(sfWebRequest $request)
  {
    /* @var $paste wspNopasteEntry */
    $paste = $this->paste = $this->getRoute()->getObject();
  }

  // functions for the "new entry" form (wspNopasteEntry)
  public function executeNew(sfWebRequest $request)
  {
    $this->form = new wspNopasteEntryForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new wspNopasteEntryForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->form = new wspNopasteEntryForm($this->getRoute()->getObject());
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->form = new wspNopasteEntryForm($this->getRoute()->getObject());
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    /* @var $entry wspNopasteEntry */
    $entry = $this->getRoute()->getObject();

    if ($this->getUser()->isAuthenticated() and ($this->getUser()->getGuardUser()->getId() == $entry->getCreatedBy()))
    {
      $entry->delete();
      $this->getUser()->setFlash(sfConfig::get('app_wsp_nopaste_plugin_flash_notice'), sfConfig::get('app_wsp_nopaste_plugin_flash_delete_notice'));
    }
    else
    {
      $this->getUser()->setFlash(sfConfig::get('app_wsp_nopaste_plugin_flash_forbidden'), sfConfig::get('app_wsp_nopaste_plugin_flash_delete_forbidden'));
    }

    $this->redirect('wspNopaste/showLatest');
  }

  /**
   * process the form
   *
   * @param sfWebRequest $request
   * @param sfFormPropel $form
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
      $this->getUser()->setFlash(sfConfig::get('sf_validation_error_class'), sfConfig::get('app_wsp_nopaste_plugin_form_error', 'The form is invalid.'), false);
    }
  }
}
