<?php /** @noinspection PhpParamsInspection */

namespace Drupal\sportsplus\Plugin\Block;

use CRM_Core_Exception;
use Drupal;
use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\sportsplus\TeamService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a civicrm teams block.
 *
 * @Block(
 *   id = "sportsplus_civicrm_teams",
 *   admin_label = @Translation("CiviCRM Teams"),
 *   category = @Translation("Custom")
 * )
 */
class CivicrmTeamsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The sportsplus.team_service service.
   *
   * @var TeamService
   */
  protected TeamService $teamService;

  /**
   * Constructs a new CivicrmTeamsBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param TeamService $team_service
   *   The sportsplus.team_service service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, TeamService $team_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->teamService = $team_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): CivicrmTeamsBlock|ContainerFactoryPluginInterface|static
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('sportsplus.team_service')
    );
  }

  /**
   * {@inheritdoc}
   * @throws CRM_Core_Exception
   */
  public function build(): array|TranslatableMarkup
  {
    $teams = $this->teamService->getTeams();

    Drupal::service('plugin.manager.block')->clearCachedDefinitions();

    $build['content'] = [];

    if (empty($teams)) {
      $build['content']['#markup'] = $this->t('There are no team in registered in CiviCRM yet.');
    }
    else{
      $build['content']['#markup'] = Drupal::theme()->render('civicrm_teams_block',
        ['teams' => $teams]);
    }

    return $build;
  }

}
