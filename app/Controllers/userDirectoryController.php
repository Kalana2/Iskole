<?php
class UserDirectoryController extends Controller
{
    protected $userRoleMap = ['Admin' => 0, 'Manager' => 1, 'Teacher' => 2, 'Student' => 3, 'Parent' => 4];
    public function getAllUsers()
    {
        $model = $this->model('UserModel');
        $users = $model->getAllUsers();
        foreach ($users as &$user) {
            $user['role'] = array_search($user['role'], $this->userRoleMap);
        }
        return $users;
    }
}