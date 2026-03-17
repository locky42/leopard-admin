<?php

echo $this->renderBlock('sidebar-top', ['authenticationService' => $authenticationService]);
echo $this->renderBlock('sidebar-main', ['authenticationService' => $authenticationService]);

?>
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= htmlspecialchars($title ?? 'Permissions', ENT_QUOTES) ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="/admin/create-admin-permission" class="btn btn-primary">Create Permission</a>
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
                                <th>Description</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($permissions) && is_iterable($permissions)): ?>
                                <?php foreach ($permissions as $perm):
                                    if (is_array($perm)) {
                                        $id = $perm['id'] ?? '';
                                        $name = $perm['name'] ?? '';
                                        $desc = $perm['description'] ?? '';
                                        $createdAt = $perm['created_at'] ?? $perm['createdAt'] ?? '';
                                        $updatedAt = $perm['updated_at'] ?? $perm['updatedAt'] ?? '';
                                    } else {
                                        $id = method_exists($perm, 'getId') ? $perm->getId() : '';
                                        $name = method_exists($perm, 'getName') ? $perm->getName() : '';
                                        $desc = method_exists($perm, 'getDescription') ? $perm->getDescription() : '';
                                        $createdAt = '';
                                        if (method_exists($perm, 'getCreatedAt')) {
                                            $createdAt = $perm->getCreatedAt();
                                        } elseif (property_exists($perm, 'createdAt')) {
                                            $createdAt = $perm->createdAt;
                                        } elseif (property_exists($perm, 'created_at')) {
                                            $createdAt = $perm->created_at;
                                        }
                                        $updatedAt = '';
                                        if (method_exists($perm, 'getUpdatedAt')) {
                                            $updatedAt = $perm->getUpdatedAt();
                                        } elseif (property_exists($perm, 'updatedAt')) {
                                            $updatedAt = $perm->updatedAt;
                                        } elseif (property_exists($perm, 'updated_at')) {
                                            $updatedAt = $perm->updated_at;
                                        }
                                    }
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars((string)$id, ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars((string)$name, ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars((string)$desc, ENT_QUOTES) ?></td>
                                        <?php
                                        if ($createdAt instanceof DateTimeInterface) { $createdAt = $createdAt->format('Y-m-d H:i:s'); }
                                        if ($updatedAt instanceof DateTimeInterface) { $updatedAt = $updatedAt->format('Y-m-d H:i:s'); }
                                        ?>
                                        <td><?= htmlspecialchars((string)$createdAt, ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars((string)$updatedAt, ENT_QUOTES) ?></td>
                                        <td>
                                            <a href="/admin/edit-admin-permission?id=<?= urlencode((string)$id) ?>" class="btn btn-sm btn-secondary">Edit</a>
                                            <form method="post" action="/admin/delete-admin-permission?id=<?= urlencode((string)$id) ?>" style="display:inline-block; margin-left:6px;">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this permission?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No permissions found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>
