<?php

namespace Drupal\sportsplus\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

class SportsplusController extends ControllerBase
{
  public function playerAddress($player_id): JsonResponse
  {
    $player = Node::load($player_id);
    $team = $player->get('field_team')->entity;

    $address = new \stdClass();
    $address->street_address_one = $team->get('field_street_address_1')->value;
    $address->street_address_two = $team->get('field_street_address_2')->value;
    $address->country = $team->get('field_country')->value;
    $address->city = $team->get('field_city')->value;

    $content = Drupal::theme()->render('player_address_modal', ['address' => $address]);

    $data = [
      'modal' => [
        'title' => $player->getTitle(),
        'content' => $content,
        'options' => [
          'width' => '700',
        ],
      ],
    ];
    return new JsonResponse($data);
  }
}
