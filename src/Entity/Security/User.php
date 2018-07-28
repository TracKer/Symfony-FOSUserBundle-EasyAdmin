<?php

namespace App\Entity\Security;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements UserInterface {
  /**
   * @var int
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @var string
   * @ORM\Column(name="email", type="string", length=180)
   */
  protected $email;

  /**
   * @var string
   * @ORM\Column(name="email_canonical", type="string", length=180, unique=true)
   */
  protected $emailCanonical;

  /**
   * @var bool
   * @ORM\Column(name="enabled", type="boolean")
   */
  protected $enabled;

  /**
   * The salt to use for hashing.
   *
   * @var string
   * @ORM\Column(name="salt", type="string", nullable=true)
   */
  protected $salt;

  /**
   * Encrypted password. Must be persisted.
   *
   * @var string
   * @ORM\Column(name="password", type="string")
   */
  protected $password;

  /**
   * Plain password. Used for model validation. Must not be persisted.
   *
   * @var string
   */
  protected $plainPassword;

  /**
   * @var \DateTime|null
   * @ORM\Column(name="last_login", type="datetime", nullable=true)
   */
  protected $lastLogin;

  /**
   * Random string sent to the user email address in order to verify it.
   *
   * @var string|null
   * @ORM\Column(name="confirmation_token", type="string", length=180, unique=true, nullable=true)
   */
  protected $confirmationToken;

  /**
   * @var \DateTime|null
   * @ORM\Column(name="password_requested_at", type="datetime", nullable=true)
   */
  protected $passwordRequestedAt;

  /**
   * @var array
   * @ORM\Column(name="roles", type="json_array")
   */
  protected $roles;

  /**
   * User constructor.
   */
  public function __construct() {
    $this->enabled = false;
    $this->roles = array();
  }

  /**
   * @return string
   */
  public function __toString() {
    return (string)$this->getUsername();
  }

  /**
   * {@inheritdoc}
   */
  public function addRole($role) {
    $role = strtoupper($role);
    if ($role === static::ROLE_DEFAULT) {
      return $this;
    }

    if (!in_array($role, $this->roles, true)) {
      $this->roles[] = $role;
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function serialize() {
    return serialize(array(
      $this->password,
      $this->salt,
      $this->enabled,
      $this->id,
      $this->email,
      $this->emailCanonical,
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function unserialize($serialized) {
    $data = unserialize($serialized);

    list(
      $this->password,
      $this->salt,
      $this->enabled,
      $this->id,
      $this->email,
      $this->emailCanonical
      ) = $data;
  }

  /**
   * {@inheritdoc}
   */
  public function eraseCredentials() {
    $this->plainPassword = null;
  }

  /**
   * {@inheritdoc}
   */
  public function getId() {
    return $this->id;
  }

  /**
   * {@inheritdoc}
   */
  public function getUsername() {
    return $this->getEmail();
  }

  /**
   * {@inheritdoc}
   */
  public function getUsernameCanonical() {
    return $this->getEmail();
  }

  /**
   * {@inheritdoc}
   */
  public function getSalt() {
    return $this->salt;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmailCanonical() {
    return $this->emailCanonical;
  }

  /**
   * {@inheritdoc}
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * {@inheritdoc}
   */
  public function getPlainPassword() {
    return $this->plainPassword;
  }

  /**
   * Gets the last login time.
   *
   * @return \DateTime|null
   */
  public function getLastLogin() {
    return $this->lastLogin;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmationToken() {
    return $this->confirmationToken;
  }

  /**
   * {@inheritdoc}
   */
  public function getRoles() {
    $roles = $this->roles;

    // we need to make sure to have at least one role
    $roles[] = static::ROLE_DEFAULT;

    return array_unique($roles);
  }

  /**
   * {@inheritdoc}
   */
  public function hasRole($role) {
    return in_array(strtoupper($role), $this->getRoles(), true);
  }

  /**
   * {@inheritdoc}
   */
  public function isAccountNonExpired() {
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function isAccountNonLocked() {
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function isCredentialsNonExpired() {
    return true;
  }

  public function isEnabled() {
    return $this->enabled;
  }

  /**
   * {@inheritdoc}
   */
  public function isSuperAdmin() {
    return $this->hasRole(static::ROLE_SUPER_ADMIN);
  }

  /**
   * {@inheritdoc}
   */
  public function removeRole($role) {
    if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
      unset($this->roles[$key]);
      $this->roles = array_values($this->roles);
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setUsername($username) {
    // Do nothing.
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setUsernameCanonical($usernameCanonical) {
    // Do nothing.
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setSalt($salt) {
    $this->salt = $salt;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setEmail($email) {
    $this->email = $email;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setEmailCanonical($emailCanonical) {
    $this->emailCanonical = $emailCanonical;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setEnabled($boolean) {
    $this->enabled = (bool)$boolean;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setPassword($password) {
    $this->password = $password;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setSuperAdmin($boolean) {
    if (true === $boolean) {
      $this->addRole(static::ROLE_SUPER_ADMIN);
    } else {
      $this->removeRole(static::ROLE_SUPER_ADMIN);
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setPlainPassword($password) {
    $this->plainPassword = $password;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setLastLogin(\DateTime $time = null) {
    $this->lastLogin = $time;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfirmationToken($confirmationToken) {
    $this->confirmationToken = $confirmationToken;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setPasswordRequestedAt(\DateTime $date = null) {
    $this->passwordRequestedAt = $date;

    return $this;
  }

  /**
   * Gets the timestamp that the user requested a password reset.
   *
   * @return null|\DateTime
   */
  public function getPasswordRequestedAt() {
    return $this->passwordRequestedAt;
  }

  /**
   * {@inheritdoc}
   */
  public function isPasswordRequestNonExpired($ttl) {
    return $this->getPasswordRequestedAt() instanceof \DateTime &&
      $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
  }

  /**
   * {@inheritdoc}
   */
  public function setRoles(array $roles) {
    $this->roles = array();

    foreach ($roles as $role) {
      $this->addRole($role);
    }

    return $this;
  }
}
