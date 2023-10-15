<?php

namespace Drupal\sportsplus\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

class SportsplusController extends ControllerBase
{
  public function playerAddress($player_id): JsonResponse
  {
    $player = Node::load($player_id);
    $team = $player->get('field_team')->entity;

    $player_image = $player->get('field_player_image')->entity;
    $base_url = Url::fromRoute('<front>', [], ['absolute' => TRUE])->toString();
    $player_image_url = str_replace('public:/',$base_url.'sites/default/files', $player_image->getFileUri());
    $player_shirt_number = $player->get('field_shirt_number')->value;

    $address = new \stdClass();
    $address->street_address_one = $team->get('field_street_address_1')->value;
    $address->street_address_two = $team->get('field_street_address_2')->value;
    $address->country = $team->get('field_country')->value;
    $address->city = $team->get('field_city')->value;

    $content = Drupal::theme()->render('player_address_modal', ['address' => $address]);

    $modal_data = [
      'modal' => [
        'title' => $player->getTitle(),
        'player_image' => $player_image_url,
        'player_shirt_number' => $player_shirt_number,
        'content' => $content,
        'options' => [
          'width' => '700',
        ],
      ],
    ];

    return new JsonResponse($modal_data);
  }
}
