<div class="login-box">

	<div class="login-logo">
		Welcome to <b>Admin Panel</b>
	</div>

	<div class="card">
		<div class="card-body">

			<p class="login-box-msg">Create a new administrator account</p>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ((array) $errors as $error): ?>
                                <li><?= htmlspecialchars($error, ENT_QUOTES) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

			<form method="post" action="/admin/create" autocomplete="off">

                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group mb-3 w-100">
                        <div class="input-group-prepend">
                            <span class="input-group-text">👤</span>
                        </div>
                        <input 
                            type="text" 
                            name="username" 
                            class="form-control" 
                            placeholder="Username"
                            value="<?= htmlspecialchars($data['username'] ?? '', ENT_QUOTES) ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-group mb-3 w-100">
                        <div class="input-group-prepend">
                            <span class="input-group-text">✉️</span>
                        </div>
                        <input 
                            type="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="Email"
                            value="<?= htmlspecialchars($data['email'] ?? '', ENT_QUOTES) ?>"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="firstName">First name</label>
                    <div class="input-group mb-3 w-100">
                        <div class="input-group-prepend">
                            <span class="input-group-text">👤</span>
                        </div>
                        <input 
                            type="text" 
                            name="firstName" 
                            class="form-control" 
                            placeholder="First name"
                            value="<?= htmlspecialchars($data['firstName'] ?? '', ENT_QUOTES) ?>"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="lastName">Last name</label>
                    <div class="input-group mb-3 w-100">
                        <div class="input-group-prepend">
                            <span class="input-group-text">👤</span>
                        </div>
                        <input 
                            type="text" 
                            name="lastName" 
                            class="form-control" 
                            placeholder="Last name"
                            value="<?= htmlspecialchars($data['lastName'] ?? '', ENT_QUOTES) ?>"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group mb-3 w-100">
                        <div class="input-group-prepend">
                            <span class="input-group-text">🔒</span>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="Password"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirm Password</label>
                    <div class="input-group mb-3 w-100">
                        <div class="input-group-prepend">
                            <span class="input-group-text">🔒</span>
                        </div>
                        <input 
                            type="password" 
                            name="password_confirm" 
                            class="form-control" 
                            placeholder="Confirm password"
                            required
                        >
                    </div>
                </div>

				<div class="form-group mb-3">
					<label for="roles">Roles</label>
					<select name="roles[]" id="roles" class="form-control" disabled>
						<?php if (!empty($roles) && is_array($roles)): ?>
							<?php foreach ($roles as $role): ?>
								<option value="<?= htmlspecialchars($role['id'] ?? $role->getId(), ENT_QUOTES) ?>"
									<?= in_array($role['id'] ?? $role->getId(), $data['roles'] ?? []) ? 'selected' : '' ?>
								><?= htmlspecialchars($role['name'] ?? $role->getName(), ENT_QUOTES) ?></option>
							<?php endforeach; ?>
						<?php else: ?>
							<option value="admin">Administrator</option>
						<?php endif; ?>
					</select>
				</div>

				<div class="row">
					<div class="col-8">
						<a href="/admin" class="btn btn-secondary btn-block">Cancel</a>
					</div>
					<div class="col-4">
						<button 
							type="submit" 
							class="btn btn-primary btn-block"
						>
							Create
						</button>
					</div>
				</div>

			</form>

		</div>
	</div>

</div>
