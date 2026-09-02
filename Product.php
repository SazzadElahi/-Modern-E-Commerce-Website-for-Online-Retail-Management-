<?php
class Product {
    public static function browse(mysqli $db,string $search='',int $category=0,string $sort=''): mysqli_result {
        $sql="SELECT products.*,categories.category_name,sellers.shop_name,COALESCE(AVG(reviews.rating),0) rating FROM products LEFT JOIN categories ON categories.category_id=products.category_id LEFT JOIN sellers ON sellers.seller_id=products.seller_id LEFT JOIN reviews ON reviews.product_id=products.id WHERE 1=1";
        if($search!=='') $sql.=" AND (products.name LIKE ? OR products.description LIKE ?)";
        if($category>0) $sql.=" AND products.category_id=?";
        $sql.=" GROUP BY products.id ORDER BY ".($sort==='low'?"products.price ASC":"products.id DESC");
        $s=$db->prepare($sql);
        if($search!==''&&$category>0){$like="%$search%";$s->bind_param("ssi",$like,$like,$category);} elseif($search!==''){$like="%$search%";$s->bind_param("ss",$like,$like);} elseif($category>0){$s->bind_param("i",$category);} $s->execute(); return $s->get_result();
    }
    public static function find(mysqli $db,int $id): ?array { $s=$db->prepare("SELECT products.*,categories.category_name,sellers.shop_name FROM products LEFT JOIN categories ON categories.category_id=products.category_id LEFT JOIN sellers ON sellers.seller_id=products.seller_id WHERE products.id=?"); $s->bind_param("i",$id); $s->execute(); return $s->get_result()->fetch_assoc() ?: null; }
    public static function all(mysqli $db): mysqli_result { return $db->query("SELECT products.*,categories.category_name,sellers.shop_name FROM products LEFT JOIN categories ON categories.category_id=products.category_id LEFT JOIN sellers ON sellers.seller_id=products.seller_id ORDER BY products.id DESC"); }
    public static function add(mysqli $db,int $seller,int $category,string $name,float $price,int $stock,string $description): bool {
        $s=$db->prepare("INSERT INTO products(seller_id,category_id,name,price,stock,description) VALUES(?,?,?,?,?,?)");
        $s->bind_param("iisdis",$seller,$category,$name,$price,$stock,$description);
        return $s->execute();
    }
    public static function updateStock(mysqli $db,int $id,int $stock,int $seller): bool { $s=$db->prepare("UPDATE products SET stock=? WHERE id=? AND seller_id=?"); $s->bind_param("iii",$stock,$id,$seller); return $s->execute(); }
    public static function delete(mysqli $db,int $id): bool { $s=$db->prepare("DELETE FROM products WHERE id=?"); $s->bind_param("i",$id); return $s->execute(); }
    public static function sellerProducts(mysqli $db,int $seller): mysqli_result { $s=$db->prepare("SELECT products.*,categories.category_name FROM products LEFT JOIN categories ON categories.category_id=products.category_id WHERE seller_id=? ORDER BY products.id DESC"); $s->bind_param("i",$seller); $s->execute(); return $s->get_result(); }
    public static function count(mysqli $db): int { return (int)$db->query("SELECT COUNT(*) n FROM products")->fetch_assoc()['n']; }
}
