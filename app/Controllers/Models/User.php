<?php
class User {
    public static function find(mysqli $db, int $id): ?array {
        $s=$db->prepare("SELECT id,name,email,phone,address FROM users WHERE id=?"); $s->bind_param("i",$id); $s->execute(); return $s->get_result()->fetch_assoc() ?: null;
    }
    public static function byEmail(mysqli $db, string $email): ?array {
        $s=$db->prepare("SELECT * FROM users WHERE email=?"); $s->bind_param("s",$email); $s->execute(); return $s->get_result()->fetch_assoc() ?: null;
    }
    public static function create(mysqli $db,string $name,string $email,string $password,string $phone,string $address): int {
        $hash=password_hash($password,PASSWORD_DEFAULT); $s=$db->prepare("INSERT INTO users(name,email,password_hash,phone,address) VALUES(?,?,?,?,?)"); $s->bind_param("sssss",$name,$email,$hash,$phone,$address); $s->execute(); return $db->insert_id;
    }
    public static function emailTaken(mysqli $db,string $email, int $except=0): bool { $s=$db->prepare("SELECT id FROM users WHERE email=? AND id<>?"); $s->bind_param("si",$email,$except); $s->execute(); return $s->get_result()->num_rows>0; }
    public static function update(mysqli $db,int $id,string $name,string $email,string $phone,string $address): bool { $s=$db->prepare("UPDATE users SET name=?,email=?,phone=?,address=? WHERE id=?"); $s->bind_param("ssssi",$name,$email,$phone,$address,$id); return $s->execute(); }
    public static function all(mysqli $db): mysqli_result { return $db->query("SELECT id,name,email,phone,created_at FROM users ORDER BY id DESC"); }
    public static function delete(mysqli $db,int $id): bool { $s=$db->prepare("DELETE FROM users WHERE id=?"); $s->bind_param("i",$id); return $s->execute(); }
}
