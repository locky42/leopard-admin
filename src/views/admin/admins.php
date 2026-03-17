<?php

echo $this->renderBlock('sidebar-top', ['authenticationService' => $authenticationService]);
echo $this->renderBlock('sidebar-main', ['authenticationService' => $authenticationService]);

?>
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= htmlspecialchars($title ?? 'Admin Users', ENT_QUOTES) ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="/admin/create-admin" class="btn btn-primary">Create Admin</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($admins) && is_iterable($admins)): ?>
                                <?php foreach ($admins as $admin):
                                    if (is_array($admin)) {
                                        $id = $admin['id'] ?? '';
                                        $username = $admin['username'] ?? ($admin['userName'] ?? '');
                                        $email = $admin['email'] ?? '';
                                        $roles = $admin['roles'] ?? [];
                                        $createdAt = $admin['created_at'] ?? $admin['createdAt'] ?? '';
                                        $updatedAt = $admin['updated_at'] ?? $admin['updatedAt'] ?? '';
                                    } else {
                                        $id = method_exists($admin, 'getId') ? $admin->getId() : '';
                                        $username = method_exists($admin, 'getUsername') ? $admin->getUsername() : (method_exists($admin, 'getUserName') ? $admin->getUserName() : '');
                                        $email = method_exists($admin, 'getEmail') ? $admin->getEmail() : '';
                                        $roles = [];
                                        if (method_exists($admin, 'getRoles')) {
                                            $r = $admin->getRoles();
                                            if (is_iterable($r)) {
                                                foreach ($r as $role) {
                                                    if (is_array($role)) {
                                                        $roles[] = $role['name'] ?? ($role['getName'] ?? '');
                                                    } elseif (method_exists($role, 'getName')) {
                                                        $roles[] = $role->getName();
                                                    }
                                                }
                                            }
                                        }
                                        $createdAt = '';
                                        if (method_exists($admin, 'getCreatedAt')) {
                                            $createdAt = $admin->getCreatedAt();
                                        } elseif (method_exists($admin, 'getCreated_at')) {
                                            $createdAt = $admin->getCreated_at();
                                        } elseif (property_exists($admin, 'createdAt')) {
                                            $createdAt = $admin->createdAt;
                                        } elseif (property_exists($admin, 'created_at')) {
                                            $createdAt = $admin->created_at;
                                        }

                                        $updatedAt = '';
                                        if (method_exists($admin, 'getUpdatedAt')) {
                                            $updatedAt = $admin->getUpdatedAt();
                                        } elseif (method_exists($admin, 'getUpdated_at')) {
                                            $updatedAt = $admin->getUpdated_at();
                                        } elseif (property_exists($admin, 'updatedAt')) {
                                            $updatedAt = $admin->updatedAt;
                                        } elseif (property_exists($admin, 'updated_at')) {
                                            $updatedAt = $admin->updated_at;
                                        }
                                    }
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars((string)$id, ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars((string)$username, ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars((string)$email, ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars(implode(', ', $roles), ENT_QUOTES) ?></td>
                                        <?php
                                        if ($createdAt instanceof DateTimeInterface) {
                                            $createdAt = $createdAt->format('Y-m-d H:i:s');
                                        }
                                        if ($updatedAt instanceof DateTimeInterface) {
                                            $updatedAt = $updatedAt->format('Y-m-d H:i:s');
                                        }
                                        ?>
                                        <td><?= htmlspecialchars((string)$createdAt, ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars((string)$updatedAt, ENT_QUOTES) ?></td>
                                        <td>
                                            <a href="/admin/edit-admin?id=<?= urlencode((string)$id) ?>" class="btn btn-sm btn-secondary">Edit</a>
                                            <form method="post" action="/admin/delete-admin?id=<?= urlencode((string)$id) ?>" style="display:inline-block; margin-left:6px;">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this admin?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No admins found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>
