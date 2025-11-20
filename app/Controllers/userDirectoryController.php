<?php
class UserDirectoryController extends Controller
{
    protected $userRoleMap = ['Admin' => 0, 'Manager' => 1, 'Teacher' => 2, 'Student' => 3, 'Parent' => 4];

    public function getRecentUsers($limit = 5)
    {
        $model = $this->model('UserModel');
        $users = $model->getRecentUsers($limit);
        foreach ($users as &$user) {
            $user['role'] = array_search($user['role'], $this->userRoleMap);
        }
        return $users;
    }

    public function getAllUsers()
    {
        $model = $this->model('UserModel');
        $users = $model->getAllUsers();
        foreach ($users as &$user) {
            $user['role'] = array_search($user['role'], $this->userRoleMap);
        }
        return $users;
    }

    public function getUserById($userId)
    {
        $model = $this->model('UserModel');
        $user = $model->getUserDetailsById($userId);
        if ($user) {
            $user['role'] = array_search($user['role'], $this->userRoleMap);
        }
        return $user;
    }

    public function searchUsers($query)
    {
        $model = $this->model('UserModel');
        $users = $model->searchUsers($query);
        foreach ($users as &$user) {
            $user['role'] = array_search($user['role'], $this->userRoleMap);
        }
        return $users;
    }
}