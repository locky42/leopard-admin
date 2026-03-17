<?php

echo $this->renderBlock('sidebar-top', ['authenticationService' => $authenticationService]);
echo $this->renderBlock('sidebar-main', ['authenticationService' => $authenticationService]);

?>
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= htmlspecialchars($title ?? 'Create Role', ENT_QUOTES) ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="/admin/roles" class="btn btn-secondary">Back to list</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">

                    <?php if (!empty($errors) && is_iterable($errors)): ?>
                        <div class="alert alert-danger">
                            <ul style="margin:0; padding-left:1.25rem;">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= htmlspecialchars((string)$err, ENT_QUOTES) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/admin/save-admin-role">
                        <div class="form-group">
                            <label for="name">Role name</label>
                            <input id="name" name="name" class="form-control" value="<?= htmlspecialchars($old['name'] ?? ($role['name'] ?? ''), ENT_QUOTES) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Permissions</label>
                            <div>
                                <?php if (!empty($permissions) && is_iterable($permissions)): ?>
                                    <?php foreach ($permissions as $perm):
                                        $permId = is_array($perm) ? ($perm['id'] ?? $perm['name'] ?? '') : (method_exists($perm, 'getId') ? $perm->getId() : (method_exists($perm, 'getName') ? $perm->getName() : ''));
                                        $permName = is_array($perm) ? ($perm['name'] ?? ($perm['getName'] ?? '')) : (method_exists($perm, 'getName') ? $perm->getName() : '');
                                        $checked = in_array($permId, $old['permissions'] ?? ($role['permissions'] ?? [])) ? 'checked' : '';
                                    ?>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="perm-<?= htmlspecialchars((string)$permId, ENT_QUOTES) ?>" name="permissions[]" value="<?= htmlspecialchars((string)$permId, ENT_QUOTES) ?>" <?= $checked ?> />
                                            <label class="form-check-label" for="perm-<?= htmlspecialchars((string)$permId, ENT_QUOTES) ?>"><?= htmlspecialchars((string)$permName, ENT_QUOTES) ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-muted">No permissions available.</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <a href="/admin/roles" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

</div>
