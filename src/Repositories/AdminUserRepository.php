<?php

namespace Leopard\Admin\Repositories;

use Leopard\User\Contracts\Models\UserInterface;
use Leopard\Doctrine\Repositories\RepositoryAbstract;
use Leopard\Admin\Contracts\Models\AdminUserInterface;
use Leopard\Admin\Contracts\Repositories\AdminUserRepositoryInterface;

/**
 * Repository for managing admin users
 */
class AdminUserRepository extends RepositoryAbstract implements AdminUserRepositoryInterface
{
    /**
     * Find user by ID
     *
     * @param int $id User ID
     * @return AdminUserInterface|null Returns user or null if not found
     */
    public function findById(int $id): ?AdminUserInterface
    {
        return $this->entityManager->find(AdminUserInterface::class, $id);
    }

    /**
     * Find user by email
     *
     * @param string $email User email
     * @return AdminUserInterface|null Returns user or null if not found
     */
    public function findByEmail(string $email): ?AdminUserInterface
    {
        return $this->entityManager->getRepository(AdminUserInterface::class)->findOneBy(['email' => $email]);
    }

    /**
     * Find user by username
     *
     * @param string $username User username
     * @return AdminUserInterface|null Returns user or null if not found
     */
    public function findByUserName(string $username): ?AdminUserInterface
    {
        return $this->entityManager->getRepository(AdminUserInterface::class)->findOneBy(['username' => $username]);
    }

    /**
     * Save user to the database
     *
     * @param AdminUserInterface $user User to save
     * @return bool True on success, false on failure
     */
    public function save(UserInterface $admin): bool
    {
        if (! $admin instanceof AdminUserInterface) {
            // Ensure we operate on the admin implementation
            throw new \InvalidArgumentException('Expected instance of ' . AdminUserInterface::class);
        }

        $admin->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($admin);
        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
        
        return true;
    }
}
