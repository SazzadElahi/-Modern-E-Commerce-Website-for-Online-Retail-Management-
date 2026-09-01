<?php
class ReviewController extends BaseController
{
    public function create(mysqli $db): void
    {
        require_customer();
        $pid = (int)($_GET['product_id'] ?? $_POST['product_id'] ?? 0);
        $product = Product::find($db, $pid);
        if (!$product) {
            header('Location:index.php');
            exit;
        }
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = (int)($_POST['rating'] ?? 0);
            $comment = trim($_POST['comment'] ?? '');
            if ($rating < 1 || $rating > 5) $error = 'Please select a rating from 1 to 5.';
            elseif (Review::add($db, $pid, (int)$_SESSION['user_id'], $rating, $comment)) {
                header('Location: product.php?id=' . $pid);
                exit;
            }
        }
        $d = $this->data($db);
        $d['product'] = $product;
        $d['error'] = $error;
        render('customer/review', $d);
    }
}
