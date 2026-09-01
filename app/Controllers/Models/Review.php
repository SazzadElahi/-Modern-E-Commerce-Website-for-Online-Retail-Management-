<?php
class Review {
    public static function forProduct(mysqli $db,int $product): mysqli_result { $s=$db->prepare("SELECT reviews.*,users.name FROM reviews JOIN users ON users.id=reviews.customer_id WHERE reviews.product_id=? ORDER BY reviews.review_id DESC");$s->bind_param("i",$product);$s->execute();return $s->get_result(); }
    public static function add(mysqli $db,int $product,int $customer,int $rating,string $comment): bool { $s=$db->prepare("INSERT INTO reviews(product_id,customer_id,rating,comment) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE rating=VALUES(rating),comment=VALUES(comment)");$s->bind_param("iiis",$product,$customer,$rating,$comment);return $s->execute(); }
}
