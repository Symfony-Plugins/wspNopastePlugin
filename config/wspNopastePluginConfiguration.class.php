<?php
/**
 * @author        Toni Uebernickel <toni@uebernickel.info>
 * @link          http://toni.uebernickel.info/
 *
 * @package       wspNopastePlugin
 * @subpackage    config
 * @version       $Id$
 * @link          $HeadURL$
 */

/**
 * The configuration of the wspNopastePlugin.
 * Includes all dependencies.
 */
class wspNopastePluginConfiguration extends sfPluginConfiguration
{
  static protected $DEPENDENCIES = array(
    'sfGuardPlugin' => 'user integration',
    'sfEasySyntaxHighlighterPlugin' => 'syntax highlighting',
    'sfFeed2Plugin' => 'RSS feeds',
    'sfPropelActAsSluggableBehaviorPlugin' => 'URL slugs',
  );

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    $enabledPlugins = $this->configuration->getPlugins();

    foreach (self::$DEPENDENCIES as $pluginName => $whatFor)
    {
      if (!in_array($pluginName, $enabledPlugins))
      {
        throw new sfConfigurationException(sprintf('You must install and enable plugin "%s" which provides %s.', $pluginName, $whatFor));
      }
    }

    /* required for symfony 1.1 compatibility */
    require dirname(__FILE__).'/config.php';
  }
}
