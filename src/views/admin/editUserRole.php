<?php
/** @var object|array $role */
/** @var array $permissions */
?>
<?php echo $this->renderBlock('sidebar-top', ['authenticationService' => $authenticationService]); ?>
<?php echo $this->renderBlock('sidebar-main', ['authenticationService' => $authenticationService]); ?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= htmlspecialchars($title ?? 'Edit User Role', ENT_QUOTES) ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="/admin/user-roles" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger"><ul><?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e, ENT_QUOTES).'</li>'; ?></ul></div>
                    <?php endif; ?>

                    <?php $roleId = is_object($role) && method_exists($role,'getId') ? $role->getId() : (is_array($role) ? ($role['id'] ?? null) : null); ?>
                    <?php $roleName = is_object($role) && method_exists($role,'getName') ? $role->getName() : (is_array($role) ? ($role['name'] ?? '') : ''); ?>

                    <form method="post" action="/admin/save-user-role">
                        <input type="hidden" name="id" value="<?= htmlspecialchars((string)$roleId, ENT_QUOTES) ?>" />
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars((string)$roleName, ENT_QUOTES) ?>" />
                        </div>

                        <div class="form-group">
                            <label>Permissions</label>
                            <?php if (!empty($permissions)): ?>
                                <?php foreach ($permissions as $perm): $pid = method_exists($perm, 'getId') ? $perm->getId() : (method_exists($perm, 'getName') ? $perm->getName() : null); $has = false; ?>
                                    <?php if (is_object($role) && method_exists($role,'getPermissions')): foreach ($role->getPermissions() as $rp) { if (method_exists($rp,'getId') && $rp->getId() == $pid) { $has = true; break; } } endif; ?>
                                    <div><label><input type="checkbox" name="permissions[]" value="<?= htmlspecialchars((string)$pid, ENT_QUOTES) ?>" <?= $has ? 'checked' : '' ?> /> <?= htmlspecialchars(method_exists($perm, 'getName') ? $perm->getName() : '', ENT_QUOTES) ?></label></div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No permissions available.</p>
                            <?php endif; ?>
                        </div>

                        <button class="btn btn-primary">Save</button>
                        <a class="btn btn-secondary" href="/admin/user-roles">Cancel</a>
                    </form>

                </div>
            </div>
        </div>
    </section>

</div>
