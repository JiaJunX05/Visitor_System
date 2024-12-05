<?php

include_once("../include/db.php");

class AdminController {
    private $conn;

    public function __construct() {
        $db = NEW Database(); //创建 Database 实例
        $this->conn = $db->getConnection(); //连接数据库
    }

    public function Login($name, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM `admins` WHERE `name` = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            //使用 password_verify() 函数验证密码是否正确
            if (password_verify($password, $row["password"])) {
                $_SESSION["admin_id"] = $row["id"];
                $_SESSION["name"] = $row["name"];
                //密码验证成功
                return true;
            } else {
                //密码错误
                return false;
            }
        } else {
            //用户名不存在
            return false;
        }
    }

    //使用 password_hash() 函数对密码进行加密
    public function Register($name, $password, $email) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO `admins` (`name`, `password`, `email`) VALUES (?,?,?)");
        $stmt->bind_param("sss", $name, $password_hash, $email);
        return $stmt->execute();
    }

    public function checkAccExist($username) {
        $stmt = $this->conn->prepare("SELECT * FROM `admins` WHERE `name` = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            //用户名已存在
            return true;
        } else {
            //用户名可使用
            return false;
        }
    }
}

?>