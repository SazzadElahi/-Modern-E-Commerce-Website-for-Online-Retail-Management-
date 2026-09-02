<?php
class Delivery
{
    public static function agent(mysqli $db, int $id): ?array
    {
        $s = $db->prepare("SELECT name FROM delivery_agents WHERE agent_id=?");
        $s->bind_param("i", $id);
        $s->execute();
        return $s->get_result()->fetch_assoc() ?: null;
    }
    public static function byEmail(mysqli $db, string $email): ?array
    {
        $s = $db->prepare("SELECT * FROM delivery_agents WHERE email=?");
        $s->bind_param("s", $email);
        $s->execute();
        return $s->get_result()->fetch_assoc() ?: null;
    }
    public static function all(mysqli $db): mysqli_result
    {
        return $db->query("SELECT delivery.*,orders.customer_name,orders.customer_address,orders.customer_phone,orders.total FROM delivery JOIN orders ON orders.id=delivery.order_id ORDER BY delivery.delivery_id DESC");
    }
    public static function update(mysqli $db, int $id, string $agent, string $status, ?string $date): void
    {
        $s = $db->prepare("UPDATE delivery SET agent_name=?,status=?,delivery_date=? WHERE delivery_id=?");
        $s->bind_param("sssi", $agent, $status, $date, $id);
        $s->execute();
        $order = $status === 'delivered' ? 'delivered' : ($status === 'shipped' ? 'shipped' : 'processing');
        $s = $db->prepare("UPDATE orders SET status=? WHERE id=(SELECT order_id FROM delivery WHERE delivery_id=?)");
        $s->bind_param("si", $order, $id);
        $s->execute();
    }
}
