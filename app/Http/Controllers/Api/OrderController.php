<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FirebaseToken;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $order = new Order();
            $order->order_id ='ORD' . uniqid();
            $order->status = 'Pending';
            $order->save();

            $orderItemsData = $request->all();

            foreach ($orderItemsData['order'] as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->save();
            }



            DB::commit();

            $device_tokens = FirebaseToken::where('user_type', 'admin')->where('is_active', "1")->get();
            foreach ($device_tokens as $device_token) {
                $token = $device_token['token'];
                $responsedata = self::sendNotification($token,$order,$orderItemsData['order']);
                //dd($responsedata);
            }



            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Order creation failed',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function getOrderProducts($orderId)
    {
        $order = Order::with('orderItems.product')->find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $orderDetails = [
            'order_id' => $order->id,
            'order_items' => [],
        ];

        foreach ($order->orderItems as $orderItem) {
            $orderDetails['order_items'][] = [
                'product_id' => $orderItem->product->id,
                'product_name' => $orderItem->product->name,
                'quantity' => $orderItem->quantity,
            ];
        }

        return response()->json([
            'message' => 'Order Details according to Id',
            'data' => $orderDetails,
        ]);
    }

    public function getAllOrdersWithProductDetails()
    {
        $order = Order::where('status', '!=', 'Done')->with('orderItems.product')->get();

        return response()->json([
            'message' => 'Get All Orders',
            'data' => $order,
        ]);
    }

    public function inprocessState($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->update(['status' => 'Inprocess']);
        }

        return response()->json([
            'message' => 'Order Status Updated Successfully',
            'data' => $order,
        ]);
    }

    public function doneState($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->update(['status' => 'Done']);
        }

        return response()->json([
            'message' => 'Order Status Updated Successfully',
            'data' => $order,
        ]);
    }

    public static function sendNotification($deviceToken,$order,$orderDetails)
    {
        //dd($orderDetails);
        $client = new Client();

        $orderItemsString = '';
        foreach ($orderDetails as $item) {
            $orderItemsString .= $item['product_id'] . ' - Quantity: ' . $item['quantity'] . "<br>";

        }

        // Remove the trailing comma and newline character
         // Remove the trailing <br> tag
        $orderItemsString = rtrim($orderItemsString, "<br>");
        //dd($orderItemsString);

        //$notificationBody = "This is your order ID: {$order->order_id}.\nItems:\n$orderItemsString";
        $notificationBody = "This is your order ID: {$order->order_id}.<br>Items:<br>$orderItemsString";

        //dd($notificationBody);

        $response = $client->post('https://fcm.googleapis.com/fcm/send', [
            'headers' => [
                'Authorization' => 'key=' . env('FIREBASE_TOKEN'),
                'Content-Type' => 'application/json',
                'Cache-Control' => 'no-cache',
            ],
            'json' => [
                'priority' => 'high',
                'dry_run' => false,
                'data' => [
                    'title' => 'Test Title - data',
                    'id' => '1',
                    'body' => 'test',
                ],
                'notification' => [
                    'title' => 'Order',
                    'sound' => 'siren.wav',
                    'body' => $notificationBody,
                    'short_content' => 'All The Order Items',
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'contentAvailable' => true,
                        ],
                    ],
                    'headers' => [
                        'apns-priority' => 5,
                    ],
                ],
                'registration_ids' => [$deviceToken],
            ],
        ]);

        return $response->getBody()->getContents();
    }
}
