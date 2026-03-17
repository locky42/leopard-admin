<?php

echo $this->renderBlock('sidebar-top', ['authenticationService' => $authenticationService]);
echo $this->renderBlock('sidebar-main', ['authenticationService' => $authenticationService]);

?>
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= htmlspecialchars($title ?? 'Users', ENT_QUOTES) ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="/admin/create-user" class="btn btn-primary">Create User</a>
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
                                <th>Roles</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users) && is_iterable($users)): ?>
                                <?php foreach ($users as $user):
                                    if (is_array($user)) {
                                        $id = $user['id'] ?? '';
                                        $roles = $user['roles'] ?? [];
                                        $createdAt = $user['created_at'] ?? $user['createdAt'] ?? '';
                                        $updatedAt = $user['updated_at'] ?? $user['updatedAt'] ?? '';
                                    } else {
                                        $id = method_exists($user, 'getId') ? $user->getId() : '';
                                        $roles = [];
                                        if (method_exists($user, 'getRoles')) {
                                            $r = $user->getRoles();
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
                                        if (method_exists($user, 'getCreatedAt')) {
                                            $createdAt = $user->getCreatedAt();
                                        } elseif (property_exists($user, 'createdAt')) {
                                            $createdAt = $user->createdAt;
                                        } elseif (property_exists($user, 'created_at')) {
                                            $createdAt = $user->created_at;
                                        }
                                        $updatedAt = '';
                                        if (method_exists($user, 'getUpdatedAt')) {
                                            $updatedAt = $user->getUpdatedAt();
                                        } elseif (property_exists($user, 'updatedAt')) {
                                            $updatedAt = $user->updatedAt;
                                        } elseif (property_exists($user, 'updated_at')) {
                                            $updatedAt = $user->updated_at;
                                        }
                                    }
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars((string)$id, ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars(implode(', ', $roles), ENT_QUOTES) ?></td>
                                        <?php
                                        if ($createdAt instanceof DateTimeInterface) { $createdAt = $createdAt->format('Y-m-d H:i:s'); }
                                        if ($updatedAt instanceof DateTimeInterface) { $updatedAt = $updatedAt->format('Y-m-d H:i:s'); }
                                        ?>
                                        <td><?= htmlspecialchars((string)$createdAt, ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars((string)$updatedAt, ENT_QUOTES) ?></td>
                                        <td>
                                            <a href="/admin/edit-user?id=<?= urlencode((string)$id) ?>" class="btn btn-sm btn-secondary">Edit</a>
                                            <form method="post" action="/admin/delete-user?id=<?= urlencode((string)$id) ?>" style="display:inline-block; margin-left:6px;">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>
