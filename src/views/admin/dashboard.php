<!-- Top Navbar -->
<?php

echo $this->renderBlock('sidebar-top', ['authenticationService' => $authenticationService]);
echo $this->renderBlock('sidebar-main', ['authenticationService' => $authenticationService]);

?>

<div class="content-wrapper">

	<!-- Content Header -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1><?= htmlspecialchars($title ?? 'Dashboard', ENT_QUOTES) ?></h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin">Home</a></li>
						<li class="breadcrumb-item active">Dashboard</li>
					</ol>
				</div>
			</div>
		</div>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-4 col-6">
					<div class="small-box bg-info">
						<div class="inner">
							<h3><?= $adminUserCount ?? '--' ?></h3>
							<p>Admin users</p>
						</div>
						<div class="icon">
							<i class="fas fa-users"></i>
						</div>
						<a href="/admin/admin-users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-4 col-6">
					<div class="small-box bg-success">
						<div class="inner">
							<h3><?= $adminRoleCount ?? '--' ?></h3>
							<p>Admin roles</p>
						</div>
						<div class="icon">
							<i class="fas fa-user-shield"></i>
						</div>
						<a href="/admin/admin-roles" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-4 col-6">
					<div class="small-box bg-warning">
						<div class="inner">
							<h3><?= $adminPermissionCount ?? '--' ?></h3>
							<p>Admin permissions</p>
						</div>
						<div class="icon">
							<i class="fas fa-key"></i>
						</div>
						<a href="/admin/admin-permissions" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4 col-6">
					<div class="small-box bg-info">
						<div class="inner">
							<h3><?= $userCount ?? '--' ?></h3>
							<p>Users</p>
						</div>
						<div class="icon">
							<i class="fas fa-users"></i>
						</div>
						<a href="/admin/users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-4 col-6">
					<div class="small-box bg-success">
						<div class="inner">
							<h3><?= $roleCount ?? '--' ?></h3>
							<p>Roles</p>
						</div>
						<div class="icon">
							<i class="fas fa-user-shield"></i>
						</div>
						<a href="/admin/user-roles" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-4 col-6">
					<div class="small-box bg-warning">
						<div class="inner">
							<h3><?= $permissionCount ?? '--' ?></h3>
							<p>Permissions</p>
						</div>
						<div class="icon">
							<i class="fas fa-key"></i>
						</div>
						<a href="/admin/user-permissions" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>

			<div class="row">
				<section class="col-lg-12 connectedSortable">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Overview</h3>
						</div>
						<div class="card-body">
							<?= $content ?? '<p>Welcome to the admin dashboard.</p>' ?>
						</div>
					</div>
				</section>
			</div>
		</div>
	</section>

</div>

<!---------------------- end AdminLTE Dashboard ---------------------->
