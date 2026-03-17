<?php

echo $this->renderBlock('sidebar-top', ['authenticationService' => $authenticationService]);
echo $this->renderBlock('sidebar-main', ['authenticationService' => $authenticationService]);

?>
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= htmlspecialchars($title ?? 'Edit Permission', ENT_QUOTES) ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="/admin/permissions" class="btn btn-secondary">Back to list</a>
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

                    <?php
                    // normalize permission data (object or array) and old input
                    $id = $old['id'] ?? '';
                    $name = $old['name'] ?? '';
                    $description = $old['description'] ?? '';

                    if (isset($permission)) {
                        if (is_array($permission)) {
                            $id = $permission['id'] ?? $id;
                            $name = $permission['name'] ?? $name;
                            $description = $permission['description'] ?? $description;
                        } else {
                            if (method_exists($permission, 'getId')) {
                                $id = $permission->getId();
                            }
                            if (method_exists($permission, 'getName')) {
                                $name = $permission->getName();
                            }
                            if (method_exists($permission, 'getDescription')) {
                                $description = $permission->getDescription();
                            }
                        }
                    }
                    ?>

                    <form method="post" action="/admin/save-admin-permission">
                        <input type="hidden" name="id" value="<?= htmlspecialchars((string)$id, ENT_QUOTES) ?>">

                        <div class="form-group">
                            <label for="name">Permission name</label>
                            <input id="name" name="name" class="form-control" value="<?= htmlspecialchars((string)$name, ENT_QUOTES) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3"><?= htmlspecialchars((string)$description, ENT_QUOTES) ?></textarea>
                        </div>

                        <div class="form-group text-right">
                            <a href="/admin/permissions" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

</div>
