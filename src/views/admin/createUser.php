<?php

echo $this->renderBlock('sidebar-top', ['authenticationService' => $authenticationService]);
echo $this->renderBlock('sidebar-main', ['authenticationService' => $authenticationService]);

?>
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= htmlspecialchars($title ?? 'Create User', ENT_QUOTES) ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="/admin/users" class="btn btn-secondary">Back to list</a>
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

                    <form method="post" action="/admin/save-user">

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" name="password" type="password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Roles</label>
                            <div>
                                <?php if (!empty($roles) && is_iterable($roles)): ?>
                                    <?php foreach ($roles as $role):
                                        $roleId = is_array($role) ? ($role['id'] ?? $role['name'] ?? '') : (method_exists($role, 'getId') ? $role->getId() : (method_exists($role, 'getName') ? $role->getName() : ''));
                                        $roleName = is_array($role) ? ($role['name'] ?? ($role['getName'] ?? '')) : (method_exists($role, 'getName') ? $role->getName() : '');
                                    ?>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="role-<?= htmlspecialchars((string)$roleId, ENT_QUOTES) ?>" name="roles[]" value="<?= htmlspecialchars((string)$roleId, ENT_QUOTES) ?>" <?= in_array($roleId, $old['roles'] ?? []) ? 'checked' : '' ?> />
                                            <label class="form-check-label" for="role-<?= htmlspecialchars((string)$roleId, ENT_QUOTES) ?>"><?= htmlspecialchars((string)$roleName, ENT_QUOTES) ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-muted">No roles available.</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <a href="/admin/users" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

</div>
