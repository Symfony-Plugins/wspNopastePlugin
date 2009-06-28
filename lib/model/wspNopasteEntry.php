<?php
/**
 * @author        Toni Uebernickel <toni@uebernickel.info>
 * @link          http://toni.uebernickel.info/
 *
 * @package       wspNopastePlugin
 * @subpackage    model
 * @version       $Id$
 * @link          $HeadURL$
 */


class wspNopasteEntry extends PluginwspNopasteEntry
{
}

// slug the title
$propelSlugOptions = array(
    'columns' => array('from' => wspNopasteEntryPeer::TITLE, 'to' => wspNopasteEntryPeer::SLUG),
    // SEO directive to separate words
    'separator' => '-',

    // make the generated slug permanent, to keep the link permanent as well
    'permanent' => true,
  );

sfPropelBehavior::add('wspNopasteEntry', array('sfPropelActAsSluggableBehavior' => $propelSlugOptions));