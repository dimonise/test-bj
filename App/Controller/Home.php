<?php
/**
 * Created by PhpStorm.
 * User: димон
 * Date: 27.09.2019
 * Time: 13:44
 */


class Home extends Controller
{

    function __construct()
    {
        session_start();
    }

    public function index()
    {

        $cur_page = 1;
        $per_page = 3;

        if (isset($_GET['page']) && $_GET['page'] > 0) {
            $cur_page = $_GET['page'];
        }

        $page = 0;
        $start = ($cur_page - 1) * $per_page;
        $tasks = $this->model('Model');
        if ($_GET['username']) {
            $order = $_GET['username'];

            switch ($order) {
                case 'desc':
                    $order = 'order by `username` DESC';
                    break;
                case 'asc':
                    $order = 'order by `username` ASC';
                    break;
            }
        } elseif ($_GET['email']) {
            $order = $_GET['email'];

            switch ($order) {
                case 'desc':
                    $order = 'order by `email` DESC';
                    break;
                case 'asc':
                    $order = 'order by `email` ASC';
                    break;
            }
        }
            elseif ($_GET['status']) {
            $order = $_GET['status'];

            switch ($order) {
                case 'desc':
                    $order = 'order by `status` DESC';
                    break;
                case 'asc':
                    $order = 'order by `status` ASC';
                    break;
            }
        } else {
            $order = null;
        }
        $task = $tasks->getLimitRows($start, $per_page, $order);
        $total_rows = $tasks->getAllRows();

        $num_pages = ceil($total_rows / $per_page);

        $this->view('layout/header');
        $this->view('home', ['tasks' => $task, 'num_pages' => $num_pages, 'per_page' => $per_page, 'page' => $page]);
        $this->view('layout/footer');
    }

    public function login()
    {

        $login = htmlspecialchars($_POST['login']);
        $pass = md5(htmlspecialchars($_POST['pass']));

        $checkLogin = $this->model('Model')->login($login, $pass);
        if ($checkLogin == false) {
            echo false;

        } else {
            header('Location: /');
        }
    }

    public function logout()
    {
        unset($_SESSION['admin']);
        session_destroy();
        header('Location: /');
    }

    public function addTask()
    {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $task = htmlspecialchars($_POST['task']);

        $this->model('Model')->addTask($username, $email, $task);
        header('Location: /');
    }

    public function updTask()
    {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $task = htmlspecialchars($_POST['task']);
        $id = htmlspecialchars($_POST['id']);

        $this->model('Model')->updTask($username, $email, $task, $id);

    }

    public function checkTask()
    {
        $id = htmlspecialchars($_POST['id']);
        $this->model('Model')->checkTask($id);
        echo 'Выполнено';
    }

    public function editTask()
    {
        $id = htmlspecialchars($_POST['id']);
        $result = $this->model('Model')->editTask($id);

        echo json_encode($result);
    }

    public function saveTask()
    {
        $id = htmlspecialchars($_POST['id']);
        $task = htmlspecialchars($_POST['task']);
        $this->model('Model')->updTask($task, $id);
        echo 'Отредактировано администратором';

    }
}