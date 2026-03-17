<?php

echo $this->renderBlock('sidebar-top', ['authenticationService' => $authenticationService]);
echo $this->renderBlock('sidebar-main', ['authenticationService' => $authenticationService]);

?>
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= htmlspecialchars($title ?? 'Roles', ENT_QUOTES) ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="/admin/create-admin-role" class="btn btn-primary">Create Role</a>
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
                                <th>Name</th>
                                <th>Permissions</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($roles) && is_iterable($roles)): ?>
                                <?php foreach ($roles as $role):
                                    if (is_array($role)) {
                                        $id = $role['id'] ?? '';
                                        $name = $role['name'] ?? '';
                                        $perms = $role['permissions'] ?? [];
                                        $createdAt = $role['created_at'] ?? $role['createdAt'] ?? '';
                                        $updatedAt = $role['updated_at'] ?? $role['updatedAt'] ?? '';
                                    } else {
                                        $id = method_exists($role, 'getId') ? $role->getId() : '';
                                        $name = method_exists($role, 'getName') ? $role->getName() : '';
                                        $perms = [];
                                        if (method_exists($role, 'getPermissions')) {
                                            $p = $role->getPermissions();
                                            if (is_iterable($p)) {
                                                foreach ($p as $perm) {
                                                    if (is_array($perm)) {
                                                        $perms[] = $perm['name'] ?? ($perm['getName'] ?? '');
                                                    } elseif (method_exists($perm, 'getName')) {
                                                        $perms[] = $perm->getName();
                                                    }
                                                }
                                            }
                                        }
                                        $createdAt = '';
                                        if (method_exists($role, 'getCreatedAt')) {
                                            $createdAt = $role->getCreatedAt();
                                        } elseif (property_exists($role, 'createdAt')) {
                                            $createdAt = $role->createdAt;
                                        } elseif (property_exists($role, 'created_at')) {
                                            $createdAt = $role->created_at;
                                        }
                                        $updatedAt = '';
                                        if (method_exists($role, 'getUpdatedAt')) {
                                            $updatedAt = $role->getUpdatedAt();
                                        } elseif (property_exists($role, 'updatedAt')) {
                                            $updatedAt = $role->updatedAt;
                                        } elseif (property_exists($role, 'updated_at')) {
                                            $updatedAt = $role->updated_at;
                                        }
                                    }
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars((string)$id, ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars((string)$name, ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars(implode(', ', is_array($perms) ? $perms : (is_iterable($perms) ? iterator_to_array($perms) : [])), ENT_QUOTES) ?></td>
                                        <?php
                                        if ($createdAt instanceof DateTimeInterface) { $createdAt = $createdAt->format('Y-m-d H:i:s'); }
                                        if ($updatedAt instanceof DateTimeInterface) { $updatedAt = $updatedAt->format('Y-m-d H:i:s'); }
                                        ?>
                                        <td><?= htmlspecialchars((string)$createdAt, ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars((string)$updatedAt, ENT_QUOTES) ?></td>
                                        <td>
                                            <a href="/admin/edit-admin-role?id=<?= urlencode((string)$id) ?>" class="btn btn-sm btn-secondary">Edit</a>
                                            <form method="post" action="/admin/delete-admin-role?id=<?= urlencode((string)$id) ?>" style="display:inline-block; margin-left:6px;">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this role?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No roles found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>
