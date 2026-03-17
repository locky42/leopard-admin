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
                    <h1><?= htmlspecialchars($title ?? 'Create User Role', ENT_QUOTES) ?></h1>
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

                    <form method="post" action="/admin/save-user-role">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($old['name'] ?? '', ENT_QUOTES) ?>" />
                        </div>

                        <div class="form-group">
                            <label>Permissions</label>
                            <?php if (!empty($permissions)): ?>
                                <?php foreach ($permissions as $perm): $pid = method_exists($perm, 'getId') ? $perm->getId() : (method_exists($perm, 'getName') ? $perm->getName(): null); ?>
                                    <div><label><input type="checkbox" name="permissions[]" value="<?= htmlspecialchars((string)$pid, ENT_QUOTES) ?>" /> <?= htmlspecialchars(method_exists($perm, 'getName') ? $perm->getName() : '', ENT_QUOTES) ?></label></div>
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
