<?php
/**
 * Created by PhpStorm.
 * User: димон
 * Date: 27.09.2019
 * Time: 13:45
 */

class Model
{

    public $pdo;

    public function __construct()
    {
        session_start();
        $settings = $this->getPDOSettings();
        $this->pdo = new \PDO($settings['dsn'], $settings['user'], $settings['pass'], null);

    }

    protected function getPDOSettings()
    {

        $config = include 'App/Config/DB.php';
        $result['dsn'] = "{$config['type']}:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $result['user'] = $config['user'];
        $result['pass'] = $config['pass'];
        return $result;
    }

    public function executes($query, array $params = null)
    {

        if (is_null($params)) {
            $stmt = $this->pdo->query($query);
            return $stmt->fetchAll();
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();

    }

    public function getAllRows()
    {
        $query = 'select * from `tasks` ';
        $result = $this->executes($query);
        return count($result);
    }

    public function getLimitRows($start, $per_page, $order=null)
    {
        if($order == null){
            $order = 'order by `id` DESC';
        }
        $query = 'select * from `tasks` '.$order.' limit ' . $start . ',' . $per_page;
        $result = $this->executes($query);
        return $result;
    }

    public function login($login, $pass)
    {

        $query = "select * from `admin` where `login`='$login' and `pass` = '$pass'";
        $result = $this->executes($query);

        if (count($result) > 0):
            $_SESSION['admin'] = $login;
            return true;
        else:
            return false;
        endif;
    }

    function pdoSet($allowed, &$values, $source = array())
    {

        $set = '';
        $values = array();
        if (!$source) $source = &$_POST;
        foreach ($allowed as $field) {
            if (isset($source[$field])) {
                $set .= "`" . str_replace("`", "``", $field) . "`" . "=:$field, ";
                $values[$field] = $source[$field];
            }
        }
        return substr($set, 0, -2);
    }

    public function addTask($username, $email, $task)
    {
        $values = [$username, $email, $task];
        $allowed = ['username', 'email', 'task'];
        $sql = "insert into `tasks` set " . $this->pdoSet($allowed, $values);
        $stm = $this->pdo->prepare($sql);
        $stm->execute($values);
    }

    public function checkTask($id)
    {
        $val = 'Выполнено';
        $sql = "update `tasks` set `status`='$val' where id = $id";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
    }

    public function editTask($id)
    {
        $query = "select * from `tasks` where id = $id";
        $result = $this->executes($query);
        return $result[0]['task'];
    }

    public function updTask($task,  $id)
    {
        if($_SESSION['admin']):
        $sql = "update `tasks` set `task`='$task', `admin_edit`= '1' where id = $id";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        endif;
    }


}