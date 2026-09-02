<?php
class Category {
    public static function all(mysqli $db): mysqli_result { return $db->query("SELECT * FROM categories ORDER BY category_name"); }
    public static function add(mysqli $db,string $name): bool { $s=$db->prepare("INSERT IGNORE INTO categories(category_name) VALUES(?)"); $s->bind_param("s",$name); return $s->execute(); }
}
