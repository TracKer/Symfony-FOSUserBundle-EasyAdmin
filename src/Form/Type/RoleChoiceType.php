<?php

namespace App\Form\Type;

use App\Services\RolesHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleChoiceType extends AbstractType {
  /**
   * @var RolesHelper
   */
  private $rolesHelper;

  public function __construct(RolesHelper $rolesHelper) {
    $this->rolesHelper = $rolesHelper;
  }

  public function configureOptions(OptionsResolver $resolver) {
    $roles = $this->rolesHelper->getRoles();
    $roles = array_combine($roles, $roles);

    $resolver->setDefaults([
      'choices' => $roles,
    ]);
  }

  public function getParent() {
    return ChoiceType::class;
  }

  public function getBlockPrefix() {
    return 'role_choice';
  }
}
