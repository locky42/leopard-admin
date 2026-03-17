<div class="login-box">

    <div class="login-logo">
        <b>Admin</b>Panel
    </div>

    <div class="card">
        <div class="card-body login-card-body">

            <p class="login-box-msg">Sign in to start your session</p>

            <form method="post" action="/admin/login">

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ((array) $errors as $error): ?>
                                <li><?= htmlspecialchars($error, ENT_QUOTES) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="input-group mb-3">
                    <input 
                        type="text" 
                        name="username" 
                        class="form-control" 
                        placeholder="Username"
                        value="<?= htmlspecialchars($data['username'] ?? '', ENT_QUOTES) ?>"
                        required
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            👤
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Password"
                        required
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            *
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" <?= !empty($data['remember']) ? 'checked' : '' ?>>
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>

                    <div class="col-4">
                        <button 
                            type="submit" 
                            class="btn btn-primary btn-block"
                        >
                            Sign In
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

</div>
