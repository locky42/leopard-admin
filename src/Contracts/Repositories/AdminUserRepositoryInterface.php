<?php

namespace Leopard\Admin\Contracts\Repositories;

use Leopard\User\Contracts\Models\UserInterface;
use Leopard\User\Contracts\Repositories\UserRepositoryInterface;

/**
 * Interface AdminUserRepositoryInterface
 *
 * This interface extends the base UserRepositoryInterface and can be used to define additional methods specific to admin user repositories if needed.
 */
interface AdminUserRepositoryInterface extends UserRepositoryInterface
{
    /**
     * Find user by username
     */
    public function findByUserName(string $username): ?UserInterface;

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?UserInterface;
}
