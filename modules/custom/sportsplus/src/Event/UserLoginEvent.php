<?php

namespace Drupal\sportsplus\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\user\UserInterface;

class UserLoginEvent extends Event
{

  const EVENT_NAME = 'sportsplus.user_login';

  /**
   * The user account.
   *
   * @var UserInterface
   */
  public UserInterface $account;

  /**
   * Constructs the object.
   *
   * @param UserInterface $account
   *   The account of the user logged in.
   */
  public function __construct(UserInterface $account) {
    $this->account = $account;
  }

}
