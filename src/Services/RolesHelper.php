<?php

namespace App\Services;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 * Class RolesHelper
 * @package App\Services
 */
class RolesHelper {
  /**
   * @var TokenStorageInterface
   */
  private $tokenStorage;

  /**
   * @var RoleHierarchyInterface
   */
  private $roleHierarchy;

  /**
   * @var string[]
   */
  private $roles = [];

  public function __construct(TokenStorageInterface $tokenStorage, RoleHierarchyInterface $roleHierarchy) {
    $this->tokenStorage = $tokenStorage;
    $this->roleHierarchy = $roleHierarchy;
  }

  public function getRoles() {
    if ($this->roles) {
      return $this->roles;
    }

    $user = $this->tokenStorage->getToken()->getUser();
    $userRoles = $user->getRoles();

    foreach ($userRoles as &$role) {
      $role = new Role($role);
    }

    $roles = $this->roleHierarchy->getReachableRoles($userRoles);
    foreach ($roles as &$role) {
      $role = $role->getRole();
    }
    return $roles;
  }
}
