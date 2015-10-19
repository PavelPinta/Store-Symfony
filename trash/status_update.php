<?php

/**
 * @api {post} /json/status_update Update order status
 * @apiName Status update
 * @apiGroup Content
 * @apiVersion 0.0.1
 *
 * @apiParam {string} token md5 today's date.
 * @apiParam {int} order_id Order ID.
 * @apiParam {int} status_id Status ID.
 * 
 * @apiSuccessExample {json} Success-Response:
 * 
 *  if name orders updated
 *  { status: "success", msg: "order status updated" }
 * 
 * @apiErrorExample {json} Error-Response:
 * 
 *  if token is invalid
 *  { status: "error", msg: "token is invalid" }
 * 
 *  if order is not found
 *  { status: "error", msg: "order is not found" }
 * 
 *  if ID status is not found
 *  { status: "error", msg: "status_id is not found" } 
 * 
 */
$status = array(
    '1' => 'Новый',
    '2' => 'Приянт к оплате',
    '3' => 'Отправлен',
    '4' => 'Выполнен',
    '5' => 'Отменен',
    '6' => 'Оплата получена');

$token = $_POST['token'];
$orderID = $_POST['order_id'];
$statusID = $_POST['status_id'];
$today = md5(date("m.d.y"));

$token = $today;
$orderID = 1545;
$statusID = 3;

if ($today != $token) {
    header('HTTP/1.x 404 Bad token');
    header('Content-type: application/json');
    echo json_encode(array("status" => "error", "msg" => "Token is invalid"));
} else {
    if ($statusID > 6 || $statusID < 1) {
        header('HTTP/1.x 404 Bad token');
        header('Content-type: application/json');
        echo json_encode(array("status" => "error", "msg" => "Status_id is not found"));
    } else {
        $name = $status[$statusID];
        $res = $modx->db->select("short_txt", $modx->getFullTableName('manager_shopkeeper'), "id = $orderID");
        $row = $modx->db->getRow($res);
        if ($row) {
            $order = (unserialize($row['short_txt']));
            $order['subject'] = $name;
            $orderSerail = serialize($order);
            $result = $modx->db->update(
                    array(
                'short_txt' => $orderSerail,
                'status' => $statusID), $modx->getFullTableName('manager_shopkeeper'), 'id="' . $orderID . '"'
            );
            if ($result) {
                
            } else {
                
            }
        } else {
            header('HTTP/1.x 404 Order is not found');
            header('Content-type: application/json');
            echo json_encode(array("status" => "error", "msg" => "Order is not found"));
        }
    }
}
?>