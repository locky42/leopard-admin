<?php

namespace Leopard\Admin\Models;

use Doctrine\ORM\Mapping as ORM;
use Leopard\Admin\Contracts\Models\AdminUserInterface;
use Leopard\Admin\Traits\AdminUserModelTrait;

#[ORM\Entity]
#[ORM\Table(name: "admin_users")]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
class BaseAdminUser implements AdminUserInterface
{
    use AdminUserModelTrait;

    #[ORM\Column(type: "string", length: 255)]
    private string $username;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $lastName = null;

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
