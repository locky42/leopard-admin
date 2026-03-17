<?php

namespace Leopard\Admin\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use Leopard\Doctrine\EntityManager;
use Leopard\Doctrine\ResolveTargetEntityRegistry;
use Leopard\User\Services\AuthorizationService;
use Leopard\User\Services\AuthenticationService;
use Leopard\Admin\Repositories\AdminUserRepository;
use Leopard\Admin\Contracts\Models\AdminUserInterface;

class AdminController
{
    protected AuthorizationService $authorizationService;
    protected AuthenticationService $authenticationService;
    private EntityManagerInterface $entityManager;

    protected string $viewsPath = __DIR__ . '/../views';
    protected string $layout = 'admin/layouts/main';
    protected string $blocksPath = 'admin/blocks';

    public function __construct()
    {
        $this->authorizationService = new AuthorizationService();
        $this->authenticationService = new AuthenticationService(
            fn($username) => (new AdminUserRepository())->findByUserName($username),
            fn($id) => (new AdminUserRepository())->findById($id)
        );
        $this->entityManager = EntityManager::getEntityManager();
    }

    public function indexAction()
    {
        $this->beforeAction(__FUNCTION__);
        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(AdminUserInterface::class)['implementation'] ?? null;
        $count = $this->entityManager->getRepository($modelClass)->count([]);
        if ($count === 0) {
            return $this->render('admin/adminCreate', ['title' => 'Create Admin User', 'authenticationService' => $this->authenticationService]);
        } elseif ($this->authenticationService->isAuthenticated()) {
            $user = $this->authenticationService->getCurrentUser();
            if ($this->authorizationService->hasRole($user, 'admin')) {
                $adminUserCount = $this->entityManager->getRepository($modelClass)->count([]);
                $modelRoleClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminRoleInterface::class)['implementation'] ?? null;
                $adminRoleCount = $this->entityManager->getRepository($modelRoleClass)->count([]);
                $modelPermissionClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminPermissionInterface::class)['implementation'] ?? null;
                $adminPermissionCount = $this->entityManager->getRepository($modelPermissionClass)->count([]);
                $userModelClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\UserInterface::class)['implementation'] ?? null;
                $userCount = $this->entityManager->getRepository($userModelClass)->count([]);
                $userRoleClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\RoleInterface::class)['implementation'] ?? null;
                $roleCount = $this->entityManager->getRepository($userRoleClass)->count([]);
                $userPermissionClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\PermissionInterface::class)['implementation'] ?? null;
                $permissionCount = $this->entityManager->getRepository($userPermissionClass)->count([]);

                return $this->render('admin/dashboard', [
                    'title' => 'Admin Dashboard',
                    'authenticationService' => $this->authenticationService,
                    'adminUserCount' => $adminUserCount,
                    'adminRoleCount' => $adminRoleCount,
                    'adminPermissionCount' => $adminPermissionCount,
                    'userCount' => $userCount,
                    'roleCount' => $roleCount,
                    'permissionCount' => $permissionCount
                ]);
            } else {
                return $this->render('admin/access_denied', [
                    'title' => 'Access Denied',
                    'authenticationService' => $this->authenticationService
                ]);
            }
        } else {
            return $this->render('admin/login', [
                'title' => 'Admin Login',
                'authenticationService' => $this->authenticationService
            ]);
        }
    }

    public function postLoginAction()
    {
        $data = $_POST;
        $errors = [];

        if (empty($data['username'])) {
            $errors[] = 'Username is required.';
        }
        if (empty($data['password'])) {
            $errors[] = 'Password is required.';
        }

        if (!empty($errors)) {
            return $this->render('admin/login', [
                'title' => 'Admin Login',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        }

        $username = $data['username'];
        $password = $data['password'];

        try {
            $user = $this->authenticationService->attempt($username, $password);
        } catch (\Exception $e) {
            $errors[] = 'Authentication error: ' . $e->getMessage();
            return $this->render('admin/login', [
                'title' => 'Admin Login',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        }

        if ($user === null) {
            $errors[] = 'Invalid credentials.';
            return $this->render('admin/login', [
                'title' => 'Admin Login',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        }

        // Remember me: extend session cookie lifetime if requested
        if (!empty($data['remember'])) {
            $lifetime = 30 * 24 * 60 * 60; // 30 days
            if (!headers_sent()) {
                setcookie(session_name(), session_id(), time() + $lifetime, '/');
            }
        }

        // Redirect back to current URL without the /login suffix
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $targetPath = preg_replace('#/login/?$#', '', $path);
        if ($targetPath === '') {
            $targetPath = '/';
        }
        $query = parse_url($uri, PHP_URL_QUERY);
        $target = $targetPath . ($query ? '?' . $query : '');
        header('Location: ' . $target, true, 302);
        exit;
    }

    public function postCreateAction()
    {
        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(AdminUserInterface::class)['implementation'] ?? null;
        $count = $this->entityManager->getRepository($modelClass)->count([]);
        if ($count > 0) {
            $uri = $_SERVER['REQUEST_URI'] ?? '/';
            $path = parse_url($uri, PHP_URL_PATH) ?: '/';
            $targetPath = preg_replace('#/create/?$#', '', $path);
            if ($targetPath === '') {
                $targetPath = '/';
            }
            $query = parse_url($uri, PHP_URL_QUERY);
            $target = $targetPath . ($query ? '?' . $query : '');
            header('Location: ' . $target, true, 302);
            exit;
        }
        $data = $_POST;
        $errors = [];

        if (empty($data['username'])) {
            $errors[] = 'Username is required.';
        }
        if (empty($data['password'])) {
            $errors[] = 'Password is required.';
        }
        if (($data['password'] ?? '') !== ($data['password_confirm'] ?? '')) {
            $errors[] = 'Password confirmation does not match.';
        }

        if (!empty($errors)) {
            return $this->render('admin/adminCreate', [
                'title' => 'Create Admin User',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
             ]);
        }

        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(AdminUserInterface::class)['implementation'] ?? null;
        if (!$modelClass || !class_exists($modelClass)) {
            $errors[] = 'User model not configured.';
            return $this->render('admin/adminCreate', [
                'title' => 'Create Admin User',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        }

        $user = new $modelClass();
        if (method_exists($user, 'setUsername')) {
            $user->setUsername($data['username']);
        }
        if (method_exists($user, 'setEmail')) {
            $user->setEmail($data['email'] ?? null);
        }
        if (method_exists($user, 'setFirstName')) {
            $user->setFirstName($data['firstName'] ?? null);
        }
        if (method_exists($user, 'setLastName')) {
            $user->setLastName($data['lastName'] ?? null);
        }
        if (method_exists($user, 'setPassword')) {
            $user->setPassword($data['password']);
        }

        try {
            // Persist user
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            $errors[] = 'Username or email already exists.';
            return $this->render('admin/adminCreate', [
                'title' => 'Create Admin User',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        } catch (\Exception $e) {
            $errors[] = $e::class . ' Error creating user: ' . $e->getMessage();
            return $this->render('admin/adminCreate', [
                'title' => 'Create Admin User',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        }

        // Ensure there is an 'admin' role and assign it
        $roleMapping = \Leopard\Admin\Contracts\Models\AdminRoleInterface::class;
        $roleClass = ResolveTargetEntityRegistry::getMappingForInterface($roleMapping)['implementation'] ?? null;
        if ($roleClass && class_exists($roleClass)) {
            $roleRepo = $this->entityManager->getRepository($roleClass);
            $role = $roleRepo->findOneBy(['name' => 'admin']);
            if (!$role) {
                $role = new $roleClass();
                if (method_exists($role, 'setName')) {
                    $role->setName('admin');
                }
                $this->entityManager->persist($role);
                $this->entityManager->flush();
            }
            if (method_exists($role, 'addUser')) {
                // some role implementations expect addUser on role, otherwise addRole on user
                $role->addUser($user);
                $this->entityManager->flush();
            } elseif (method_exists($user, 'addRole')) {
                $user->addRole($role);
                $this->entityManager->flush();
            }
        }

        $this->redirect('create');
    }

    public function postLogoutAction()
    {
        $this->authenticationService->logout();
        $this->redirect('admin');
    }

    public function adminsAction()
    {
        $this->beforeAction(__FUNCTION__);
        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(AdminUserInterface::class)['implementation'] ?? null;
        $admins = $this->entityManager->getRepository($modelClass)->findAll();
        return $this->render('admin/admins', [
            'title' => 'Admin Users',
            'authenticationService' => $this->authenticationService,
            'admins' => $admins
         ]);
    }

    public function createAdminAction()
    {
        $this->beforeAction(__FUNCTION__);
        $modelRolesClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminRoleInterface::class)['implementation'] ?? null;
        $roles = $this->entityManager->getRepository($modelRolesClass)->findAll();

        return $this->render('admin/createAdmin', [
            'title' => 'Create Admin User',
            'roles' => $roles,
            'authenticationService' => $this->authenticationService
        ]);
    }

    public function editAdminAction($id)
    {
        $this->beforeAction(__FUNCTION__);
        $id = $id ?? $_GET['id'] ?? null;
        $modelAdminClass = ResolveTargetEntityRegistry::getMappingForInterface(AdminUserInterface::class)['implementation'] ?? null;
        $admin = $this->entityManager->getRepository($modelAdminClass)->find($id);

        $modelRolesClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminRoleInterface::class)['implementation'] ?? null;
        $roles = $this->entityManager->getRepository($modelRolesClass)->findAll();

        if (!$admin) {
            header('HTTP/1.0 404 Not Found');
            echo 'Admin user not found';
            exit;
        }
        return $this->render('admin/adminEdit', [
            'title' => 'Edit Admin User',
            'admin' => $admin,
            'roles' => $roles,
            'authenticationService' => $this->authenticationService
        ]);
    }

    public function postSaveAdminAction()
    {
        $this->beforeAction(__FUNCTION__);
        $data = $_POST;
        $id = $data['id'] ?? null;
        $modelAdminClass = ResolveTargetEntityRegistry::getMappingForInterface(AdminUserInterface::class)['implementation'] ?? null;
        $admin = $id ? $this->entityManager->getRepository($modelAdminClass)->find($id) : new $modelAdminClass();
       
        if (!$admin) {
            header('HTTP/1.0 404 Not Found');
            echo 'Admin user not found';
            exit;
        }

        if (method_exists($admin, 'setUsername')) {
            $admin->setUsername($data['username']);
        }
        if (method_exists($admin, 'setEmail')) {
            $admin->setEmail($data['email'] ?? null);
        }
        if (method_exists($admin, 'setFirstName')) {
            $admin->setFirstName($data['firstName'] ?? null);
        }
        if (method_exists($admin, 'setLastName')) {
            $admin->setLastName($data['lastName'] ?? null);
        }
        if (!empty($data['password']) && method_exists($admin, 'setPassword')) {
            $admin->setPassword($data['password']);
        }

        $modelRolesClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminRoleInterface::class)['implementation'] ?? null;
        $roleRepo = $this->entityManager->getRepository($modelRolesClass);
        $roles = $roleRepo->findAll();

        // Handle roles assignment
        if (isset($data['roles']) && is_array($data['roles'])) {
            foreach ($roles as $role) {
                $hasRole = in_array(method_exists($role, 'getId') ? $role->getId() : (method_exists($role, 'getName') ? $role->getName() : ''), $data['roles']);
                if ($hasRole && method_exists($admin, 'addRole')) {
                    $admin->addRole($role);
                } elseif (!$hasRole && method_exists($admin, 'removeRole')) {
                    $admin->removeRole($role);
                }
            }
        }

        $this->entityManager->persist($admin);

        try {
            $this->entityManager->flush();
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            $errors[] = 'Username or email already exists.';
            return $this->render('admin/createAdmin', [
                'title' => 'Edit Admin User',
                'old' => $data,
                'roles' => $roles,
                'authenticationService' => $this->authenticationService,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            $errors[] = 'An error occurred while saving the admin user.';
            return $this->render('admin/createAdmin', [
                'title' => 'Edit Admin User',
                'old' => $data,
                'roles' => $roles,
                'authenticationService' => $this->authenticationService,
                'errors' => $errors
            ]);
            exit;
        }

        $this->redirect('save-admin');
    }

    public function postDeleteAdminAction($id)
    {
        $this->beforeAction(__FUNCTION__);
        $id = $id ?? $_GET['id'] ?? null;
        $modelAdminClass = ResolveTargetEntityRegistry::getMappingForInterface(AdminUserInterface::class)['implementation'] ?? null;
        $admin = $this->entityManager->getRepository($modelAdminClass)->find($id);
        if (!$admin) {
            header('HTTP/1.0 404 Not Found');
            echo 'Admin user not found';
            exit;
        }
        $this->entityManager->remove($admin);
        $this->entityManager->flush();
        $this->redirect('delete-admin');
    }

    public function adminRolesAction()
    {
        $this->beforeAction(__FUNCTION__);
        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminRoleInterface::class)['implementation'] ?? null;
        $roles = $this->entityManager->getRepository($modelClass)->findAll();
        return $this->render('admin/adminRoles', [
            'title' => 'Admin Roles',
            'authenticationService' => $this->authenticationService,
            'roles' => $roles
        ]);
    }

    public function createAdminRoleAction()
    {
        $this->beforeAction(__FUNCTION__);
        return $this->render('admin/createAdminRole', [
            'title' => 'Create Admin Role',
            'authenticationService' => $this->authenticationService
        ]);
    }

    public function editAdminRoleAction($id)
    {
        $this->beforeAction(__FUNCTION__);
        $id = $id ?? $_GET['id'] ?? null;
        $modelRoleClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminRoleInterface::class)['implementation'] ?? null;
        $role = $this->entityManager->getRepository($modelRoleClass)->find($id);

        $modelPermissionClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminPermissionInterface::class)['implementation'] ?? null;
        $permissions = $this->entityManager->getRepository($modelPermissionClass)->findAll();

        if (!$role) {
            header('HTTP/1.0 404 Not Found');
            echo 'Admin role not found';
            exit;
        }
        return $this->render('admin/editAdminRole', [
            'title' => 'Edit Admin Role',
            'authenticationService' => $this->authenticationService,
            'role' => $role,
            'permissions' => $permissions
         ]);
    }

    public function postSaveAdminRoleAction()
    {
        $this->beforeAction(__FUNCTION__);
        $data = $_POST;
        $errors = [];

        $data = $_POST;
        $id = $data['id'] ?? null;

        if (empty($data['name'])) {
            $errors[] = 'Role name is required.';
        }

        if (!empty($errors)) {
            return $this->render('admin/createAdminRole', [
                'title' => $id ? 'Edit Admin Role' : 'Create Admin Role',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        }

        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminRoleInterface::class)['implementation'] ?? null;
        if (!$modelClass || !class_exists($modelClass)) {
            $errors[] = 'Role model not configured.';
            return $this->render('admin/createAdminRole', [
                'title' => $id ? 'Edit Admin Role' : 'Create Admin Role',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        }

        $role = $id ? $this->entityManager->getRepository($modelClass)->find($id) : new $modelClass();
        if (method_exists($role, 'setName')) {
            $role->setName($data['name']);
        }

        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $modelPermissionClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminPermissionInterface::class)['implementation'] ?? null;
            $permissionRepo = $this->entityManager->getRepository($modelPermissionClass);
            $allPermissions = $permissionRepo->findAll();
            foreach ($allPermissions as $perm) {
                $permId = method_exists($perm, 'getId') ? $perm->getId() : (method_exists($perm, 'getName') ? $perm->getName() : null);
                if ($permId === null) {
                    continue;
                }
                $hasPerm = in_array($permId, $data['permissions']);
                if ($hasPerm && method_exists($role, 'addPermission')) {
                    $role->addPermission($perm);
                } elseif (!$hasPerm && method_exists($role, 'removePermission')) {
                    $role->removePermission($perm);
                }
            }
        }

        try {
            // Persist role
            $this->entityManager->persist($role);
            $this->entityManager->flush();
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            $errors[] = 'Role name already exists.';
            return $this->render('admin/createAdminRole', [
                'title' => $id ? 'Edit Admin Role' : 'Create Admin Role',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        } catch (\Exception $e) {
            $errors[] = 'An error occurred while saving the role.';
            return $this->render('admin/createAdminRole', [
                'title' => $id ? 'Edit Admin Role' : 'Create Admin Role',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
            exit;
        }

        $this->redirect('save-admin-role');
    }

    public function postDeleteAdminRoleAction($id)
    {
        $this->beforeAction(__FUNCTION__);
        $id = $id ?? $_GET['id'] ?? null;
        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminRoleInterface::class)['implementation'] ?? null;
        $role = $this->entityManager->getRepository($modelClass)->find($id);
        if (!$role) {
            header('HTTP/1.0 404 Not Found');
            echo 'Admin role not found';
            exit;
        }
        $this->entityManager->remove($role);
        $this->entityManager->flush();
        $this->redirect('delete-admin-role');
    }

    public function adminPermissionsAction()
    {
        $this->beforeAction(__FUNCTION__);
        $modelAdminClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminPermissionInterface::class)['implementation'] ?? null;
        $permissions = $this->entityManager->getRepository($modelAdminClass)->findAll();
        return $this->render('admin/adminPermissions', [
            'title' => 'Admin Permissions',
            'authenticationService' => $this->authenticationService,
            'permissions' => $permissions
        ]);
    }

    public function createAdminPermissionAction()
    {
        $this->beforeAction(__FUNCTION__);
        return $this->render('admin/createAdminPermission', [
            'title' => 'Create Admin Permission',
            'authenticationService' => $this->authenticationService
        ]);
    }

    public function editAdminPermissionAction($id)
    {
        $this->beforeAction(__FUNCTION__);
        $id = $id ?? $_GET['id'] ?? null;
        $modelAdminClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminPermissionInterface::class)['implementation'] ?? null;
        $permission = $this->entityManager->getRepository($modelAdminClass)->find($id);

        if (!$permission) {
            header('HTTP/1.0 404 Not Found');
            echo 'Admin permission not found';
            exit;
        }
        return $this->render('admin/editAdminPermission', [
            'title' => 'Edit Admin Permission',
            'authenticationService' => $this->authenticationService,
            'permission' => $permission
         ]);
    }

    public function postSaveAdminPermissionAction()
    {
        $this->beforeAction(__FUNCTION__);
        $data = $_POST;
        $errors = [];

        $id = $data['id'] ?? null;
        if (empty($data['name'])) {
            $errors[] = 'Permission name is required.';
        }

        $modelAdminClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminPermissionInterface::class)['implementation'] ?? null;
        $permission = $id ? $this->entityManager->getRepository($modelAdminClass)->find($id) : new $modelAdminClass();
        if (method_exists($permission, 'setName')) {
            $permission->setName($data['name']);
        }
        if (method_exists($permission, 'setDescription')) {
            $permission->setDescription($data['description'] ?? null);
        }

        $this->entityManager->persist($permission);

        try {
            $this->entityManager->flush();
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            $errors[] = 'Permission name already exists.';
            return $this->render('admin/createAdminPermission', [
                'title' => $id ? 'Edit Admin Permission' : 'Create Admin Permission',
                'old' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        } catch (\Exception $e) {
            $errors[] = 'An error occurred while saving the permission.';
            return $this->render('admin/createAdminPermission', [
                'title' => $id ? 'Edit Admin Permission' : 'Create Admin Permission',
                'old' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
            exit;
        }

        $this->redirect('save-admin-permission');
    }

    public function postDeleteAdminPermissionAction($id)
    {
        $this->beforeAction(__FUNCTION__);
        $id = $id ?? $_GET['id'] ?? null;
        $modelAdminClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\Admin\Contracts\Models\AdminPermissionInterface::class)['implementation'] ?? null;
        $permission = $this->entityManager->getRepository($modelAdminClass)->find($id);
        if (!$permission) {
            header('HTTP/1.0 404 Not Found');
            echo 'Admin permission not found';
            exit;
        }
        $this->entityManager->remove($permission);
        $this->entityManager->flush();
        $this->redirect('delete-admin-permission');
    }

    public function usersAction()
    {
        $this->beforeAction(__FUNCTION__);
        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\UserInterface::class)['implementation'] ?? null;
        $users = $this->entityManager->getRepository($modelClass)->findAll();
        return $this->render('admin/users', [
            'title' => 'Users',
            'authenticationService' => $this->authenticationService,
            'users' => $users
        ]);
    }

    public function createUserAction()
    {
        $this->beforeAction(__FUNCTION__);
        $modelRolesClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\RoleInterface::class)['implementation'] ?? null;
        $roles = $this->entityManager->getRepository($modelRolesClass)->findAll();

        return $this->render('admin/createUser', [
            'title' => 'Create User',
            'roles' => $roles,
            'authenticationService' => $this->authenticationService
        ]);
    }

    public function editUserAction($id)
    {
        $this->beforeAction(__FUNCTION__);
        $id = $id ?? $_GET['id'] ?? null;
        $modelUserClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\UserInterface::class)['implementation'] ?? null;
        $user = $this->entityManager->getRepository($modelUserClass)->find($id);

        $modelRolesClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\RoleInterface::class)['implementation'] ?? null;
        $roles = $this->entityManager->getRepository($modelRolesClass)->findAll();

        if (!$user) {
            header('HTTP/1.0 404 Not Found');
            echo 'User not found';
            exit;
        }
        return $this->render('admin/editUser', [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => $roles,
            'authenticationService' => $this->authenticationService
         ]);
    }

    public function postSaveUserAction()
    {
        $this->beforeAction(__FUNCTION__);
        $data = $_POST;
        $id = $data['id'] ?? null;
        $modelUserClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\UserInterface::class)['implementation'] ?? null;
        $user = $id ? $this->entityManager->getRepository($modelUserClass)->find($id) : new $modelUserClass();

        if (!$user) {
            header('HTTP/1.0 404 Not Found');
            echo 'User not found';
            exit;
        }

        if (method_exists($user, 'setUsername')) {
            $user->setUsername($data['username']);
        }
        if (method_exists($user, 'setEmail')) {
            $user->setEmail($data['email'] ?? null);
        }
        if (method_exists($user, 'setFirstName')) {
            $user->setFirstName($data['firstName'] ?? null);
        }
        if (method_exists($user, 'setLastName')) {
            $user->setLastName($data['lastName'] ?? null);
        }
        if (!empty($data['password']) && method_exists($user, 'setPassword')) {
            $user->setPassword($data['password']);
        }

        $modelRolesClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\RoleInterface::class)['implementation'] ?? null;
        $roleRepo = $this->entityManager->getRepository($modelRolesClass);
        $roles = $roleRepo->findAll();

        if (isset($data['roles']) && is_array($data['roles'])) {
            foreach ($roles as $role) {
                $roleId = method_exists($role, 'getId') ? $role->getId() : (method_exists($role, 'getName') ? $role->getName() : '');
                $hasRole = in_array($roleId, $data['roles']);
                if ($hasRole && method_exists($user, 'addRole')) {
                    $user->addRole($role);
                } elseif (!$hasRole && method_exists($user, 'removeRole')) {
                    $user->removeRole($role);
                }
            }
        }

        $this->entityManager->persist($user);

        try {
            $this->entityManager->flush();
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            $errors[] = 'Username or email already exists.';
            return $this->render('admin/createUser', [
                'title' => 'Edit User',
                'old' => $data,
                'roles' => $roles,
                'authenticationService' => $this->authenticationService,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            $errors[] = 'An error occurred while saving the user.';
            return $this->render('admin/createUser', [
                'title' => 'Edit User',
                'old' => $data,
                'roles' => $roles,
                'authenticationService' => $this->authenticationService,
                'errors' => $errors
            ]);
            exit;
        }

        $this->redirect('save-user');
    }

    public function postDeleteUserAction($id)
    {
        $this->beforeAction(__FUNCTION__);
        $id = $id ?? $_GET['id'] ?? null;
        $modelUserClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\UserInterface::class)['implementation'] ?? null;
        $user = $this->entityManager->getRepository($modelUserClass)->find($id);
        if (!$user) {
            header('HTTP/1.0 404 Not Found');
            echo 'User not found';
            exit;
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->redirect('delete-user');
    }

    // --- User Roles ---
    public function userRolesAction()
    {
        $this->beforeAction(__FUNCTION__);
        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\RoleInterface::class)['implementation'] ?? null;
        $roles = $this->entityManager->getRepository($modelClass)->findAll();
        return $this->render('admin/userRoles', [
            'title' => 'User Roles',
            'authenticationService' => $this->authenticationService,
            'roles' => $roles
        ]);
    }

    public function createUserRoleAction()
    {
        $this->beforeAction(__FUNCTION__);
        $modelPermClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\PermissionInterface::class)['implementation'] ?? null;
        $permissions = $this->entityManager->getRepository($modelPermClass)->findAll();
        return $this->render('admin/createUserRole', [
            'title' => 'Create User Role',
            'permissions' => $permissions,
            'authenticationService' => $this->authenticationService
        ]);
    }

    public function editUserRoleAction($id)
    {
        $this->beforeAction(__FUNCTION__);
        $id = $id ?? $_GET['id'] ?? null;
        $modelRoleClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\RoleInterface::class)['implementation'] ?? null;
        $role = $this->entityManager->getRepository($modelRoleClass)->find($id);

        $modelPermClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\PermissionInterface::class)['implementation'] ?? null;
        $permissions = $this->entityManager->getRepository($modelPermClass)->findAll();

        if (!$role) {
            header('HTTP/1.0 404 Not Found');
            echo 'User role not found';
            exit;
        }
        return $this->render('admin/editUserRole', [
            'title' => 'Edit User Role',
            'authenticationService' => $this->authenticationService,
            'role' => $role,
            'permissions' => $permissions
         ]);
    }

    public function postSaveUserRoleAction()
    {
        $this->beforeAction(__FUNCTION__);
        $data = $_POST;
        $errors = [];
        $id = $data['id'] ?? null;

        if (empty($data['name'])) {
            $errors[] = 'Role name is required.';
        }

        if (!empty($errors)) {
            return $this->render('admin/createUserRole', [
                'title' => $id ? 'Edit User Role' : 'Create User Role',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        }

        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\RoleInterface::class)['implementation'] ?? null;
        if (!$modelClass || !class_exists($modelClass)) {
            $errors[] = 'Role model not configured.';
            return $this->render('admin/createUserRole', [
                'title' => $id ? 'Edit User Role' : 'Create User Role',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        }

        $role = $id ? $this->entityManager->getRepository($modelClass)->find($id) : new $modelClass();
        if (method_exists($role, 'setName')) {
            $role->setName($data['name']);
        }

        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $modelPermissionClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\PermissionInterface::class)['implementation'] ?? null;
            $permissionRepo = $this->entityManager->getRepository($modelPermissionClass);
            $allPermissions = $permissionRepo->findAll();
            foreach ($allPermissions as $perm) {
                $permId = method_exists($perm, 'getId') ? $perm->getId() : (method_exists($perm, 'getName') ? $perm->getName() : null);
                if ($permId === null) {
                    continue;
                }
                $hasPerm = in_array($permId, $data['permissions']);
                if ($hasPerm && method_exists($role, 'addPermission')) {
                    $role->addPermission($perm);
                } elseif (!$hasPerm && method_exists($role, 'removePermission')) {
                    $role->removePermission($perm);
                }
            }
        }

        try {
            $this->entityManager->persist($role);
            $this->entityManager->flush();
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            $errors[] = 'Role name already exists.';
            return $this->render('admin/createUserRole', [
                'title' => $id ? 'Edit User Role' : 'Create User Role',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        } catch (\Exception $e) {
            $errors[] = 'An error occurred while saving the role.';
            return $this->render('admin/createUserRole', [
                'title' => $id ? 'Edit User Role' : 'Create User Role',
                'data' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
            exit;
        }

        $this->redirect('save-user-role');
    }

    public function postDeleteUserRoleAction($id)
    {
        $this->beforeAction(__FUNCTION__);
        $id = $id ?? $_GET['id'] ?? null;
        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\RoleInterface::class)['implementation'] ?? null;
        $role = $this->entityManager->getRepository($modelClass)->find($id);
        if (!$role) {
            header('HTTP/1.0 404 Not Found');
            echo 'User role not found';
            exit;
        }
        $this->entityManager->remove($role);
        $this->entityManager->flush();
        $this->redirect('delete-user-role');
    }

    // --- User Permissions ---
    public function userPermissionsAction()
    {
        $this->beforeAction(__FUNCTION__);
        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\PermissionInterface::class)['implementation'] ?? null;
        $permissions = $this->entityManager->getRepository($modelClass)->findAll();
        return $this->render('admin/userPermissions', [
            'title' => 'User Permissions',
            'authenticationService' => $this->authenticationService,
            'permissions' => $permissions
        ]);
    }

    public function createUserPermissionAction()
    {
        $this->beforeAction(__FUNCTION__);
        return $this->render('admin/createUserPermission', [
            'title' => 'Create User Permission',
            'authenticationService' => $this->authenticationService
        ]);
    }

    public function editUserPermissionAction($id)
    {
        $this->beforeAction(__FUNCTION__);
        $id = $id ?? $_GET['id'] ?? null;
        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\PermissionInterface::class)['implementation'] ?? null;
        $permission = $this->entityManager->getRepository($modelClass)->find($id);

        if (!$permission) {
            header('HTTP/1.0 404 Not Found');
            echo 'User permission not found';
            exit;
        }
        return $this->render('admin/editUserPermission', [
            'title' => 'Edit User Permission',
            'permission' => $permission,
            'authenticationService' => $this->authenticationService
        ]);
    }

    public function postSaveUserPermissionAction()
    {
        $this->beforeAction(__FUNCTION__);
        $data = $_POST;
        $errors = [];

        $id = $data['id'] ?? null;
        if (empty($data['name'])) {
            $errors[] = 'Permission name is required.';
        }

        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\PermissionInterface::class)['implementation'] ?? null;
        $permission = $id ? $this->entityManager->getRepository($modelClass)->find($id) : new $modelClass();
        if (method_exists($permission, 'setName')) {
            $permission->setName($data['name']);
        }
        if (method_exists($permission, 'setDescription')) {
            $permission->setDescription($data['description'] ?? null);
        }

        $this->entityManager->persist($permission);

        try {
            $this->entityManager->flush();
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            $errors[] = 'Permission name already exists.';
            return $this->render('admin/createUserPermission', [
                'title' => $id ? 'Edit User Permission' : 'Create User Permission',
                'old' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
        } catch (\Exception $e) {
            $errors[] = 'An error occurred while saving the permission.';
            return $this->render('admin/createUserPermission', [
                'title' => $id ? 'Edit User Permission' : 'Create User Permission',
                'old' => $data,
                'errors' => $errors,
                'authenticationService' => $this->authenticationService
            ]);
            exit;
        }

        $this->redirect('save-user-permission');
    }

    public function postDeleteUserPermissionAction($id)
    {
        $this->beforeAction(__FUNCTION__);
        $id = $id ?? $_GET['id'] ?? null;
        $modelClass = ResolveTargetEntityRegistry::getMappingForInterface(\Leopard\User\Contracts\Models\PermissionInterface::class)['implementation'] ?? null;
        $permission = $this->entityManager->getRepository($modelClass)->find($id);
        if (!$permission) {
            header('HTTP/1.0 404 Not Found');
            echo 'User permission not found';
            exit;
        }
        $this->entityManager->remove($permission);
        $this->entityManager->flush();
        $this->redirect('delete-user-permission');
    }

    protected function redirect($pathPart)
    {
        // Redirect back to current URL without the /create suffix
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $targetPath = preg_replace('#/' . $pathPart .'/?$#', '', $path);
        if ($targetPath === '') {
            $targetPath = '/';
        }
        $query = parse_url($uri, PHP_URL_QUERY);
        $target = $targetPath . ($query ? '?' . $query : '');
        header('Location: ' . $target, true, 302);
        exit;
    }

    protected function render(string $template, array $data = []): string
    {
        $templatePath = $this->viewsPath . '/' . $template . '.php';

        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template not found: $templatePath");
        }

        // Extract data variables for use in the template
        extract($data);

        // Start output buffering for the content
        ob_start();
        include $templatePath;
        $content = ob_get_clean();

        // Render the layout with the content
        $layoutPath = $this->viewsPath . '/' . $this->layout . '.php';
        if (!file_exists($layoutPath)) {
            throw new \RuntimeException("Layout not found: $layoutPath");
        }

        ob_start();
        include $layoutPath;
        return ob_get_clean();
    }

    public function renderBlock(string $block, array $data = []): string
    {
        // Check if the block exists in the specific layout folder
        $layoutSpecificBlockPath = $this->viewsPath . '/' . $this->layout . '/' . $block . '.php';
        $defaultBlockPath = $this->viewsPath . '/' . $this->blocksPath . '/' . $block . '.php';

        $blockPath = file_exists($layoutSpecificBlockPath) ? $layoutSpecificBlockPath : $defaultBlockPath;

        if (!file_exists($blockPath)) {
            throw new \RuntimeException("Block not found: $blockPath");
        }

        extract($data);

        ob_start();
        include $blockPath;
        return ob_get_clean();
    }

    protected function renderLayout($layout, $content, $data = [])
    {
        extract($data);
        include $this->viewsPath . "/layouts/{$layout}.php";
    }

    protected function beforeAction($action)
    {
        // Check if user is authenticated for all actions except login
        if (!in_array($action, ['indexAction', 'loginAction']) && !$this->authenticationService->isAuthenticated()) {
            header('Location: /admin');
            exit;
        }
    }
}
