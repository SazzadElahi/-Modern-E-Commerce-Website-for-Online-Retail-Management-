<?php
class OrderController extends BaseController
{
    public function index(mysqli $db): void
    {
        require_customer();
        $d = $this->data($db);
        $d['orders'] = Order::customerOrders($db, (int)$_SESSION['user_id']);
        render('customer/orders', $d);
    }
    public function checkout(mysqli $db): void
    {
        require_customer();
        $id = (int)$_SESSION['user_id'];
        $user = User::find($db, $id);
        $result = Cart::items($db, $id);
        $items = [];
        $total = 0;
        while ($r = $result->fetch_assoc()) {
            $items[] = $r;
            $total += $r['price'] * $r['quantity'];
        }
        $success = false;
        $error = '';
        $orderId = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $total > 0) {
            $name = trim($_POST['name'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $method = trim($_POST['method'] ?? 'Cash on Delivery');
            if ($name === '' || $address === '' || $phone === '') $error = 'Please fill in all fields.';
            elseif (!in_array($method, ['Cash on Delivery', 'Card', 'Mobile Banking'])) $error = 'Please select a valid payment method.';
            else {
                try {
                    foreach ($items as $i) if ($i['quantity'] > $i['stock']) throw new Exception('Not enough stock for ' . $i['name']);
                    User::update($db, $id, $name, $user['email'], $phone, $address);
                    $orderId = Order::create($db, $id, ['name' => $name, 'address' => $address, 'phone' => $phone], $total, $items, $method);
                    Cart::clear($db, $id);
                    $success = true;
                } catch (Throwable $e) {
                    $error = $e->getMessage();
                }
            }
        }
        $d = $this->data($db);
        $d['user'] = User::find($db, $id);
        $d['total'] = $total;
        $d['success'] = $success;
        $d['error'] = $error;
        $d['orderId'] = $orderId;
        render('customer/checkout', $d);
    }
}
