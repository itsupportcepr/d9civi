<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace Drupal\sportsplus;

use Civi\API\Exception\UnauthorizedException;
use Civi\Api4\Address;
use Civi\Api4\Contact;
use CRM_Core_Exception;
use Drupal;
use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Service description.
 */
class TeamService {

  protected $civicrmService;
  /**
   * Constructs a new TeamService object.
   */
  public function __construct($civicrmService) {
    $this->civicrmService = $civicrmService;
    $this->civicrmService->initialize();
  }

  /**
   * Creates a Contact in CIVICRM.
   *
   * @throws CRM_Core_Exception
   */
  public function createCiviCRMContact(EntityInterface $team): void
  {

    $base_url = Url::fromRoute('<front>', [], ['absolute' => TRUE])->toString();
    $team_image_url = str_replace('public:/', 'sites/default/files/',
      $base_url.$team->get('field_team_image')->entity->getFileUri());

    try {
      $results = Contact::create(TRUE)
        ->addValue('contact_type', 'Organization')
        ->addValue('image_URL', $team_image_url)
        ->addValue('display_name',  $team->getTitle())
        ->addValue('organization_name', $team->getTitle())
        ->addValue('legal_name', $team->getTitle())
        ->execute();

      $contact_id = $results->first()['id'];
      $this->createCiviCRMAddress($team, $contact_id);
    } catch (\Exception $e) {
      Drupal::logger('sportsplus')->error($e->getMessage());
    }

  }

  /**
   * Creates an Address for the Team in CIVICRM.
   * @throws CRM_Core_Exception
   * @throws UnauthorizedException
   */
  public function createCiviCRMAddress(EntityInterface $team, Int $contact_id): void
  {
    try {
      Address::create(TRUE)
        ->addValue('contact_id', $contact_id)
        ->addValue('street_address', $team->get('field_street_address_1')->value)
        ->addValue('location_type_id', 3)
        ->addValue('supplemental_address_1', $team->get('field_street_address_2')->value)
        ->addValue('city', $team->get('field_city')->value)
        ->addValue('country_id.name', $team->get('field_country')->value)
        ->addValue('location_type_id', 3)
        ->addValue('name', $team->getTitle().t(' Team Main Address'))
        ->execute();
    } catch (\Exception $e) {
      Drupal::logger('sportsplus')->error($e->getMessage());
    }

  }

  public function create(ContainerInterface $container): TeamService
  {
    return new static(
      $container->get('civicrm')
    );
  }

}
