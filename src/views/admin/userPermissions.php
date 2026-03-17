<?php
/** @var array $permissions */
?>
<?php echo $this->renderBlock('sidebar-top', ['authenticationService' => $authenticationService]); ?>
<?php echo $this->renderBlock('sidebar-main', ['authenticationService' => $authenticationService]); ?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= htmlspecialchars($title ?? 'User Permissions', ENT_QUOTES) ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="/admin/create-user-permission" class="btn btn-primary">Create Permission</a>
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
                                <?php foreach ($permissions as $p):
                                    if (is_array($p)) {
                                        $id = $p['id'] ?? '';
                                        $name = $p['name'] ?? '';
                                        $desc = $p['description'] ?? '';
                                        $createdAt = $p['created_at'] ?? $p['createdAt'] ?? '';
                                        $updatedAt = $p['updated_at'] ?? $p['updatedAt'] ?? '';
                                    } else {
                                        $id = method_exists($p,'getId') ? $p->getId() : '';
                                        $name = method_exists($p,'getName') ? $p->getName() : '';
                                        $desc = method_exists($p,'getDescription') ? $p->getDescription() : '';
                                        $createdAt = '';
                                        if (method_exists($p, 'getCreatedAt')) { $createdAt = $p->getCreatedAt(); }
                                        elseif (property_exists($p, 'createdAt')) { $createdAt = $p->createdAt; }
                                        elseif (property_exists($p, 'created_at')) { $createdAt = $p->created_at; }
                                        $updatedAt = '';
                                        if (method_exists($p, 'getUpdatedAt')) { $updatedAt = $p->getUpdatedAt(); }
                                        elseif (property_exists($p, 'updatedAt')) { $updatedAt = $p->updatedAt; }
                                        elseif (property_exists($p, 'updated_at')) { $updatedAt = $p->updated_at; }
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
                                            <a href="/admin/edit-user-permission?id=<?= urlencode((string)$id) ?>" class="btn btn-sm btn-secondary">Edit</a>
                                            <form method="post" action="/admin/delete-user-permission?id=<?= urlencode((string)$id) ?>" style="display:inline-block; margin-left:6px;">
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
