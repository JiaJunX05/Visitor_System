<?php

include_once("../include/db.php");


class OwnerController {
    private $conn;

    public function __construct() {
        $db = new Database(); //创建 Database 实例
        $this->conn = $db->getConnection(); //连接数据库
    }

    public function Login($name, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM `users` WHERE `name` = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            //使用 password_verify() 函数验证密码是否正确
            if (password_verify($password, $row["password"])) {
                $_SESSION["owner_id"] = $row["id"];
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

    //使用 password_hash() 函数加密密码
    public function Register($name, $password, $email, $phone, $unit) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT); 
        $stmt = $this->conn->prepare("INSERT INTO `users` (`name`, `password`, `email`, `phone`, `unit`) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $name, $password_hash, $email, $phone, $unit);
        return $stmt->execute();
    }

    public function checkUnit($unit) {
        $stmt = $this->conn->prepare("SELECT * FROM `users` WHERE `unit` =?");
        $stmt->bind_param("s", $unit);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            //单位已存在
            return true;
        } else {
            //单位可注册
            return false;
        }
    }

    public function OwnerList($limit = 10, $offset = 0) {
        $stmt = $this->conn->prepare("SELECT * FROM users LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $owners = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $owners;
    }

    public function SearchOwner($name, $unit, $limit = 10, $offset = 0) {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];
        $types = "";
        
        if (!empty($name)) {
            $sql .= " AND name LIKE ?";
            $params[] = "%$name%";
            $types .= "s";
        }
        if (!empty($unit)) {
            $sql .= " AND unit LIKE ?";
            $params[] = "$unit%";
            $types .= "s";
        }
        
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
        
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $owners = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $owners;
    }

    public function CountOwners($name, $unit) {
        $sql = "SELECT COUNT(*) AS count FROM users WHERE 1=1";
        $params = [];
        $types = "";
        
        if (!empty($name)) {
            $sql .= " AND name LIKE ?";
            $params[] = "%$name%";
            $types .= "s";
        }
        if (!empty($unit)) {
            $sql .= " AND unit LIKE ?";
            $params[] = "%$unit%";
            $types .= "s";
        }
        
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'];
    }

    public function OwenrModify($name, $email, $password, $id) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
        $stmt->bind_param('sssi', $name, $email, $password_hash, $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function OwnerDelete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }
}

?>