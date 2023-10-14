<?php

namespace Drupal\sportsplus\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Entity\EntityInterface;
use Drupal\node\Entity\Node;

class TeamSavedEvent extends Event
{

  const EVENT_NAME = 'sportsplus.team_saved';

  /**
   * The node entity
   *
   * @var Node
   */
  public EntityInterface $team;

  /**
   * Constructs the object.
   *
   * @param  EntityInterface $team
   *   The team node entity that was saved.
   */
  public function __construct( EntityInterface $team ) {
    $this->team = $team;
  }

}
