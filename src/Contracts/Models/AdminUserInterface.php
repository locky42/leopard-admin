<?php

namespace Leopard\Admin\Contracts\Models;

use Leopard\User\Contracts\Models\UserInterface;

/**
 * Interface AdminUserInterface
 *
 * This interface extends the base UserInterface and can be used to define additional methods specific to admin users if needed.
 */
interface AdminUserInterface extends UserInterface
{
    /* Get the username of the admin user.
     *
     * @return string
     */
    public function getUserName(): string;

    /* Set the username of the admin user.
     *
     * @param string $username
     */
    public function setUserName(string $username): void;

    /* Get the email of the admin user.
     *
     * @return string|null
     */
    public function getEmail(): ?string;

    /* Set the email of the admin user.
     *
     * @param string|null $email
     */
    public function setEmail(?string $email): void;

    /* Get the first name of the admin user.
     *
     * @return string|null
     */
    public function getFirstName(): ?string;

    /* Set the first name of the admin user.
     *
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void;

    /* Get the last name of the admin user.
     *
     * @return string|null
     */
    public function getLastName(): ?string;

    /* Set the last name of the admin user.
     *
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void;
}
