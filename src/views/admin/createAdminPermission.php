<?php

echo $this->renderBlock('sidebar-top', ['authenticationService' => $authenticationService]);
echo $this->renderBlock('sidebar-main', ['authenticationService' => $authenticationService]);

?>
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= htmlspecialchars($title ?? 'Create Permission', ENT_QUOTES) ?></h1>
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

                    <form method="post" action="/admin/save-admin-permission">
                        <div class="form-group">
                            <label for="name">Permission name</label>
                            <input id="name" name="name" class="form-control" value="<?= htmlspecialchars($old['name'] ?? '', ENT_QUOTES) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3"><?= htmlspecialchars($old['description'] ?? '', ENT_QUOTES) ?></textarea>
                        </div>

                        <div class="form-group text-right">
                            <a href="/admin/permissions" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

</div>
