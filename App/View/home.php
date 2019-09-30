<?php
/**
 * Created by PhpStorm.
 * User: димон
 * Date: 27.09.2019
 * Time: 13:46
 */
session_start();
?>
<div class="container">
    <div class="row ">
        <div class="col-6 admin">
            <?php
            echo $_SESSION['admin'];
            if (!isset($_SESSION['admin'])):
                ?>
                <form method="post">
                    <h4>Вход для Администратора</h4>
                    <input type="text" value="" placeholder="Логин" name="login" id="login" required><br>
                    <input type="password" value="" placeholder="Пароль" name="pass" id="pass" required><br>

                </form>
                <input type="button" value="LOGIN" onclick="login()">
            <?php
            else:
                ?>
                <form method="post" action="home/logout">
                    <input type="submit" value="LOGOUT">
                </form>
            <?php
            endif;
            ?>
        </div>
        <div class="col-6 admin">
            <form id="new-task" method="post">
                <h4>Создать новую задачу</h4>
                <input type="text" placeholder="Имя пользователя" name="username" required><br>
                <input type="email" placeholder="Email" name="email" class="email" required><span id="valid"></span><br>
                <textarea placeholder="Текст задачи" name="task" required></textarea><br>
            </form>
            <input type="button" value="SAVE" id="submit" onclick="addTask()">
        </div>
    </div>
    <div class="row header">
        <div class="col-3">Имя пользователя &nbsp;<a href="/?username=desc"> &#8593;</a> &nbsp;
            <a href="/?username=asc">&#8595;</a></div>
        <div class="col-3">email &nbsp;<a href="/?email=desc"> &#8593;</a> &nbsp;
            <a href="/?email=asc">&#8595;</a></div>
        <div class="col-3">Задача</div>
        <div class="col-3">Статус &nbsp;<a href="/?status=desc"> &#8593;</a> &nbsp;
            <a href="/?status=asc">&#8595;</a></div>
    </div>
    <?php
    foreach ($data['tasks'] as $task):
        ?>
        <div class="row task-body">
            <div class="col-3"><?= htmlspecialchars($task['username']); ?></div>
            <div class="col-3"><?= htmlspecialchars($task['email']); ?></div>
            <div class="col-3 task-style task<?= $task['id']; ?>"><?= htmlspecialchars($task['task']); ?></div>
            <div class="col-3 check<?= $task['id']; ?>"><?= $task['status']; ?>
                <?php
                if ($_SESSION['admin'] == true) :
                    ?>
                    <br>
                    <?php
                    if ($task['status'] != 'Выполнено' && $task['admin_edit'] != 1):
                        ?>
                        <a href="javascript:void(0)" onclick="checkTask(<?= $task['id']; ?>)">Отметка о выполнении</a>
                        <br>
                        <a href="javascript:void(0)" onclick="editTask(<?= $task['id']; ?>)">Редактировать задачу</a>

                    <?php
                    elseif ($task['status'] != 'Выполнено' && $task['admin_edit'] == 1):
                        ?>
                        <span>Отредактировано администратором</span><br>
                        <a href="javascript:void(0)" onclick="checkTask(<?= $task['id']; ?>)">Отметка о выполнении</a>
                        <br>
                        <a href="javascript:void(0)" onclick="editTask(<?= $task['id']; ?>)">Редактировать задачу</a>

                    <?php
                    elseif ($task['status'] == 'Выполнено' && $task['admin_edit'] == 1):
                        ?>
                        <span>Отредактировано администратором</span><br>

                    <?php
                    endif;
                else:
                    if ($task['status'] == 'Выполнено' && $task['admin_edit'] == 1):
                        ?>
                        <br><span>Отредактировано администратором</span><br>

                    <?php
                    endif;
                endif;
                ?>
            </div>
        </div>
    <?php
    endforeach;
    ?>
    <div class="col-12 paginator">
        <?php
        while ($data['page']++ < $data['num_pages']):
            if ($data['page'] == $cur_page):
                ?>
                <b><?= $data['page'] ?></b>
            <?php
            else:
                if (!$_GET):
                    ?>
                    <a href="?page=<?= $data['page'] ?>"><?= $data['page'] ?></a>
                <?php
                else:
                    ?>
                    <a href="?page=<?= $data['page'] ?>&<?= key($_GET)?>=<?= $_GET[key($_GET)] ?>"><?= $data['page'] ?></a>
                <?php
                endif;
            endif;
        endwhile;
        ?>
    </div>
</div>
