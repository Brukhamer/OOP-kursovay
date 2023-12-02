<?php

namespace app\models;

use app\core\BaseModel;

class UserModel extends BaseModel
{
    public function addNewUser($username, $login, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        return $this->insert(
            'INSERT INTO users (username, login, password) values (:username, :login, :password)',
            [
                'username' => $username,
                'login' => $login,
                'password' => $password
            ]
        );
    }

    public function autByLogin($login, $password)
    {
        $result = false;
        $error_message = '';

        if (empty($login)){
            $error_message .= "Введите ваш логин!";
        }
        if (empty($password)) {
            $error_message .= "Введите ваш пароль!";
        }
        if (empty($error_message)) {
            $user = $this->select("SELECT * from users where login = :login", ['login' => $login]);
            if (!empty($user[0])) {
                $passwordCorrect = password_verify($password, $user[0]['password']);
                if ($passwordCorrect) {
                    $_SESSION['user']['id'] = $user[0]['id'];
                    $_SESSION['user']['login'] = $user[0]['login'];
                    $_SESSION['user']['name'] = $user[0]['name'];
                    $_SESSION['user']['is_admin'] = ($user[0]['is_admin'] == '1');

                    $result = true;
                } else {
                    $error_message .= "Не верный логин или пароль!";
                }
            } else {
                $error_message .= "Пользователь не найден!";

            }

            return [
                'result' => $result,
                'error_message' => $error_message

            ];
        }
    }
    public function changePassword($current_password, $new_password, $confirm_new_password)
    {
        $result = false;
        $error_message ='';
        if (empty($current_password)){
            $error_message .= "Введите текущий пароль!<br>";
        }
        if (empty($new_password)){
            $error_message .= "Введите новый пароль!<br>";
        }
        if (empty($confirm_new_password)){
            $error_message .= "Повторите новый пароль!<br>";
        }
        if ($new_password != $confirm_new_password){
            $error_message .= "Пароли не совпадают!<br>";
        }

        if (empty($error_message)){
            $user = $this->select("select * from users where login = :login ", ['login' => $_SESSION['user']['login']]);

            if (!empty($user[0])){
                $passwordCorrect = password_verify($current_password, $user[0]['password']);

                if ($passwordCorrect){
                    $new_password = password_hash($new_password, PASSWORD_DEFAULT);

                    $updatePassword = $this->update("update users set password = :password where login = :login", [
                        'password' => $new_password,
                        'login' => $_SESSION['user']['login']
                    ]);

                    $result = $updatePassword;
                }else{
                    $error_message .= "Неверный пароль!<br>";
                }
            }else{
                $error_message .= "Произошла ошибка при смене пароля!<br>";
            }
        }

        return [
            'result' => $result,
            'error_message' => $error_message
        ];

    }

    public function getListUsers()
    {
        $result = null;

        $users = $this->select("select id, username, login, is_admin from users");
        if (!empty($users)){
            $result = $users;
        }

        return $result;
    }
    public function deleteByUser($id)
    {
        $result = false;
        $error_message = '';

        if (empty($id)){
            $error_message .= "Отсутствует пользователь!<br>";
        }
        if (empty($error_message)){
            $result = $this->delete("delete from users where id = :id", ['id'=>$id]);
        }

        return [
            'result' => $result,
            'error_message' => $error_message
        ];
    }

}
