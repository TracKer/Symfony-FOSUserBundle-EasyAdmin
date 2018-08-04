<?php

namespace App\Entity\Security;

use FOS\UserBundle\Doctrine\UserManager as BaseUserManager;

class UserManager extends BaseUserManager {
  public function findUserByUsername($username) {
    return $this->findUserByEmail($username);
  }

  public function findUserByUsernameOrEmail($usernameOrEmail) {
    return $this->findUserByEmail($usernameOrEmail);
  }
}
