<?php

namespace Drupal\sportsplus;

use Drupal\Core\Database\Connection;

/**
 * All player services will be found in the class eg fetching players from the
 * database.
 */
class PlayerService {

  /**
   * The database connection.
   *
   * @var Connection
   */
  protected Connection $connection;

  /**
   * Constructs a PlayerService object.
   *
   * @param Connection $connection
   *   The database connection.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * Gets Players from the database.
   */
  public function getAllPlayers($fields = []): array
  {
    $query = $this->connection->select('node__field_email', 'n');
    $query->condition('n.bundle', 'player');
    if (empty($fields)) {
      $fields = [
        'title',
        'field_player_image',
        'field_email',
        'field_dob',
        'field_position',
        'field_team',
      ];

    }
    $query->fields('n', $fields);
    return $query->execute()->fetchAll();
  }

}
