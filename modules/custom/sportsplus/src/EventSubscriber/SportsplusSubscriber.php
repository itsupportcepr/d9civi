<?php

namespace Drupal\sportsplus\EventSubscriber;

use Drupal;
use Drupal\Core\Entity\EntityMalformedException;
use Drupal\Core\Link;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\sportsplus\Event\UserLoginEvent;
use Drupal\sportsplus\Event\TeamSavedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * SportsPlus event subscriber.
 */
class SportsplusSubscriber implements EventSubscriberInterface {

  /**
   * The messenger.
   *
   * @var MessengerInterface
   */
  protected MessengerInterface $messenger;

  /**
   * Constructs event subscriber.
   *
   * @param MessengerInterface $messenger
   *   The messenger.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * User login event handler.
   *
   * @param  $event
   *   Response event.
   * @throws EntityMalformedException
   */
  public function onUserLogin( $event ): void
  {
    $current_user_mail = $event->account->getEmail();
    $players = Drupal::service('sportsplus.player_service')->getAllPlayers(['entity_id','field_email_value']);

    foreach ($players as $player){
      if($player->field_email_value == $current_user_mail){
        $player_node = Node::load($player->entity_id);
        $player_node_url = $player_node->toUrl();
        $player_node_link = Link::fromTextAndUrl($player_node->getTitle(), $player_node_url )->toString();
        $this->messenger->addMessage(t('Welcome back! You can view your player profile at @link', ['@link' => $player_node_link]));
      }
    }
  }

  /**
   * Team saved event handler.
   *
   * @param  $event
   *   Response event.
   */
  public function onTeamSaved( $event ): void
  {
    $team = $event->team;
    Drupal::service('sportsplus.team_service')->createCiviCRMContact($team);
    $this->messenger->addMessage(t('Team @name \'s contact and address have been saved in CiviCRM.',
      ['@name' => $team->getTitle()]));
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array
  {
    return [
      UserLoginEvent::EVENT_NAME => 'onUserLogin',
      TeamSavedEvent::EVENT_NAME => 'onTeamSaved',
    ];
  }

}
