<?php

namespace Leopard\Admin\Traits;

use Doctrine\ORM\Mapping as ORM;
use Leopard\User\Traits\UserModelTrait;
use Leopard\Admin\Contracts\Models\AdminRoleInterface;

/**
 * Trait AdminUserModelTrait
 *
 * This trait can be used in the AdminUser model to establish a many-to-many relationship with AdminRole.
 */
trait AdminUserModelTrait
{
    use UserModelTrait;

    #[ORM\ManyToMany(targetEntity: AdminRoleInterface::class, inversedBy: "users")]
    #[ORM\JoinTable(name: "admin_user_roles")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", onDelete: "CASCADE")]
    #[ORM\InverseJoinColumn(name: "role_id", referencedColumnName: "id", onDelete: "CASCADE")]
    protected $roles;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    protected string $username;

    #[ORM\Column(type: "string", length: 255, nullable: true, unique: true)]
    protected ?string $email = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    protected ?string $firstName = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    protected ?string $lastName = null;

    /**
     * Get the username of the admin user.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the username of the admin user.
     *
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Get the email of the admin user.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the email of the admin user.
     *
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get the first name of the admin user.
     *
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set the first name of the admin user.
     *
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Get the last name of the admin user.
     *
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set the last name of the admin user.
     *
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
