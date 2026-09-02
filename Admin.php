<?php
class Admin { public static function byEmail(mysqli $db,string $email): ?array { $s=$db->prepare("SELECT * FROM admins WHERE email=?");$s->bind_param("s",$email);$s->execute();return $s->get_result()->fetch_assoc() ?: null; } }
