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
use Nekland\Tools\EqualableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class User
 *
 * @ORM\Entity(repositoryClass="CiTron\User\Repository\UserRepository")
 * @ORM\Table(name="user")
 *
 * @UniqueEntity(fields={"email"}, message="Email already in use.")
 * @UniqueEntity(fields={"username"}, message="Username already in use.")
 *
 * @JMS\ExclusionPolicy("all")
 */
class User implements UserInterface, EqualableInterface
{
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    /**
     * The default key is not so awesome. To avoid salt to be guessable it could be nice to redefined the salt.
     * (but that's not an obligation as one is randomly generated)
     *
     * @var string
     */
    private static $saltKey = 'key';

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
     * @JMS\Groups({"standard", "current"})
     * @JMS\Expose
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
     * @ORM\Column
     * @Gedmo\Slug(fields={"username"})
     *
     * @JMS\Groups({"standard", "current"})
     * @JMS\Expose
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", unique=true)
     * @Assert\Email()
     *
     * @JMS\Groups({"current"})
     * @JMS\Expose
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string")
     */
    private $salt;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CiTron\Project\Entity\Project", mappedBy="user")
     */
    private $projects;

    /**
     * User constructor.
     *
     * @param string $salt
     */
    public function __construct(string $salt = null)
    {
        if (is_null($salt)) {
            $this->salt = md5(uniqid() . User::$saltKey);
        } else {
            $this->salt = $salt;
        }

        $this->roles = [self::ROLE_USER];
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
    public function setPassword(string $password) : User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param  string[] $roles
     * @return self
     */
    public function setRoles(array $roles) : User
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string[] $roles
     * @return self
     */
    public function addRoles(array $roles) : User
    {
        $this->roles = array_merge($this->roles, $roles);

        return $this;
    }

    /**
     * @param string $role
     * @return self
     */
    public function addRole(string $role) : User
    {
        $this->roles[] = $role;
        return $this;
    }

    /**
     * @param  string $salt
     * @return self
     */
    public function setSalt(string $salt) : User
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @param  string $username
     * @return self
     */
    public function setUsername(string $username) : User
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
     * @return string[] The user roles
     */
    public function getRoles() : array
    {
        return $this->roles;
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
    public function setEmail(string $email)
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
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return User
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @param string $saltKey
     */
    public static function setSaltKey(string $saltKey)
    {
        User::$saltKey = $saltKey;
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

    /**
     * {@inheritdoc}
     */
    public function equals($item)
    {
        if (!$item instanceof User) {
            return false;
        }

        return $this->id === $item->getId();
    }
}
