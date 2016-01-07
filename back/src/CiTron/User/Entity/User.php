<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\User\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class User
 *
 * @ORM\Entity(repositoryClass="CiTron\User\Repository\UserRepository")
 * @ORM\Table(name="user")
 *
 * @UniqueEntity(fields={"email"}, message="Email already in use.")
 * @UniqueEntity(fields={"username"}, message="Username already in use.")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * It should be clean while the user is retrieved
     * @var string
     *
     * @ORM\Column(name="password", type="string")
     * @Assert\Length(min = 4, minMessage="Password too short")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string")
     */
    private $salt;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Role", cascade={"persist"})
     */
    private $roles;

    public function __construct($salt = null)
    {
        if (is_null($salt)) {
            $this->salt = md5(uniqid() . 'key');
        } else {
            $this->salt = $salt;
        }
        $this->roles = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param  Role[] $roles
     * @return self
     */
    public function setRoles(array $roles)
    {
        $this->roles = new ArrayCollection($roles);
        return $this;
    }

    /**
     * @param Role[] $roles
     * @return self
     */
    public function addRoles(array $roles)
    {
        $this->roles->add($roles);
        return $this;
    }

    /**
     * @param Role $role
     * @return self
     */
    /*
    public function addRole(Role $role)
    {
        $this->roles->add($role);
        return $this;
    }
    */

    /**
     * @param  string $salt
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * @param  string $username
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param  string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object, he's serialized inside the session.
     */
    public function eraseCredentials()
    {
        // The goal of this method is basically to protect the password.
        // Our password is encoded and if it's set to a new password, it will be automatically encoded by a doctrine
        // listener, so let's let it empty for now.
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize([
            'id'       => $this->id,
            'username' => $this->username,
            'email'    => $this->email,
            'roles'    => $this->roles->toArray()
        ]);
    }

    /**
     * Constructs the object
     *
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($serialized)
    {
        $parameters = unserialize($serialized);
        $this->id       = $parameters['id'];
        $this->username = $parameters['username'];
        $this->email    = $parameters['email'];
        $this->roles    = new ArrayCollection($parameters['roles']);
    }
}
