<?php
/** @var array $old */
?>
<?php echo $this->renderBlock('sidebar-top', ['authenticationService' => $authenticationService]); ?>
<?php echo $this->renderBlock('sidebar-main', ['authenticationService' => $authenticationService]); ?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= htmlspecialchars($title ?? 'Create User Permission', ENT_QUOTES) ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="/admin/user-permissions" class="btn btn-secondary">Back</a>
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

                    <form method="post" action="/admin/save-user-permission">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($old['name'] ?? '', ENT_QUOTES) ?>" />
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control"><?= htmlspecialchars($old['description'] ?? '', ENT_QUOTES) ?></textarea>
                        </div>

                        <button class="btn btn-primary">Save</button>
                        <a class="btn btn-secondary" href="/admin/user-permissions">Cancel</a>
                    </form>

                </div>
            </div>
        </div>
    </section>

</div>
