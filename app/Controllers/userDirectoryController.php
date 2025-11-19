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
}