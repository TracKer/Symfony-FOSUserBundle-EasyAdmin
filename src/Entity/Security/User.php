<?php

namespace App\Entity\Security;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser {
  /**
   * @var int
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Security\Group")
   * @ORM\JoinTable(
   *   name="fos_user_user_group",
   *   joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
   *   inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
   * )
   */
  protected $groups;
}
