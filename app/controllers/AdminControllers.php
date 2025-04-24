<?php
class AdminControllers extends Controller
{
    private $adminControllerModel;

    public function __construct()
    {
        if (!isAdmin()) {
            redirect('users/login');
        }
        $this->adminControllerModel = $this->model('AdminController');
    }

    public function index()
    {
        $users = $this->adminControllerModel->getAllUsers();
        $data = ['users' => $users];
        $this->view('admin/users/index', $data);
    }

    public function show($table, $id)
    {
        $user = $this->adminControllerModel->getUserDetailsById($table, $id);
        if (!$user) {
            die('User not found.');
        }

        $this->view('admin/users/show', ['user' => $user, 'role' => ucfirst($table)]);
    }

    public function changeStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $role = isset($_POST['role']) && !empty($_POST['role']) ? strtolower($_POST['role']) : null;
            $action = $_POST['action'];

            $newStatus = '';
            switch ($action) {
                case 'suspend':
                    $newStatus = 'suspended';
                    $suspendDate = $_POST['suspend_date'] ?? null;
                    break;
                case 'approve':
                    $newStatus = 'approved';
                    break;
                case 'deactivate':
                    $newStatus = 'deactivated';
                    break;
                default:
                    $newStatus = 'pending';
            }

            if ($this->adminControllerModel->updateUserStatus($role, $id, $newStatus, $suspendDate)) {
                flash('user_action', 'User status updated');
            } else {
                flash('user_action', 'Something went wrong', 'alert alert-danger');
            }

            redirect('adminControllers/show/' . $role . '/' . $id); // your route to user list
        }
    }

    public function filterUsers()
    {
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';

        $filteredUsers = $this->adminControllerModel->getFilteredUsers($role, $status);

        $data = [
            'users' => $filteredUsers,
            'selected_role' => $role,
            'selected_status' => $status
        ];

        $this->view('admin/users/index', $data);
    }
}
