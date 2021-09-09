<?php

namespace Database\Seeders;

use App\Models\Other\Order;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $orders = [
            [
                'status' => Order::STATUS_NEW,
                'client_email' => 'bestfatorv@gmail.com',
                'partner_id' => 1,
                'delivery_dt' => Carbon::now()
            ],
            [
                'status' => Order::STATUS_NEW,
                'client_email' => 'bestfatorv@gmail.com',
                'partner_id' => 2,
                'delivery_dt' => Carbon::now()->addDays(3)
            ],
            [
                'status' => Order::STATUS_CONFIRM,
                'client_email' => 'bestfatorv@gmail.com',
                'partner_id' => 3,
                'delivery_dt' => Carbon::now()->addDay(1)
            ],
            [
                'status' => Order::STATUS_CONFIRM,
                'client_email' => 'bestfatorv@gmail.com',
                'partner_id' => 4,
                'delivery_dt' => Carbon::now()->addDays(2)
            ],
            [
                'status' => Order::STATUS_COMPLETED,
                'client_email' => 'bestfatorv@gmail.com',
                'partner_id' => 5,
                'delivery_dt' => Carbon::now()->subDays(2)
            ],
        ];

        $orderProducts = [
            [
                'product_id' => 1,
                'quantity' => 10,
                'price' => 200, //цена продукта 20
            ],
            [
                'product_id' => 2,
                'quantity' => 5,
                'price' => 1000, //цена продукта 200
            ],
            [
                'product_id' => 3,
                'quantity' => 100,
                'price' => 70000, //цена продукта 700
            ],
            [
                'product_id' => 4,
                'quantity' => 3,
                'price' => 450, //цена продукта 150
            ],
            [
                'product_id' => 5,
                'quantity' => 7,
                'price' => 6300, //цена продукта 900
            ],
        ];

        $products = [
            [
                'name' => 'Mercedes-Benz A-Класс хэтчбэк',
                'price' => 20,
                'vendor_id' => 1
            ],
            [
                'name' => 'Mercedes-Benz GLA',
                'price' => 200,
                'vendor_id' => 2
            ],
            [
                'name' => 'Mercedes-Benz GLA 250 4MATIC комплектация Sport двигатель 2 литра (211 л.с.)',
                'price' => 700,
                'vendor_id' => 3
            ],
            [
                'name' => 'GLB 200d 4MATIC Progressive',
                'price' => 150,
                'vendor_id' => 4
            ],
            [
                'name' => 'AMG GLE 63 S 4MATIC+ купе',
                'price' => 900,
                'vendor_id' => 5
            ],
        ];
        $partners = [
            [
                'email' => 'partner1@test.com',
                'name' => 'ООО ПАРТНЁР 1',
            ],
            [
                'email' => 'partner2@test.com',
                'name' => 'ООО ПАРТНЁР 2',
            ],
            [
                'email' => 'partner3@test.com',
                'name' => 'ООО ПАРТНЁР 3',
            ],
            [
                'email' => 'partner4@test.com',
                'name' => 'ООО ПАРТНЁР 4',
            ],
            [
                'email' => 'partner5@test.com',
                'name' => 'ООО ПАРТНЁР 5',
            ],
        ];
        $vendors = [
            [
                'email' => 'vendor1@test.com',
                'name' => 'ООО ПОСТАВЩИК 1',
            ],
            [
                'email' => 'vendor2@test.com',
                'name' => 'ООО ПОСТАВЩИК 2',
            ],
            [
                'email' => 'vendor3@test.com',
                'name' => 'ООО ПОСТАВЩИК 3',
            ],
            [
                'email' => 'vendor4@test.com',
                'name' => 'ООО ПОСТАВЩИК 4',
            ],
            [
                'email' => 'vendor5@test.com',
                'name' => 'ООО ПОСТАВЩИК 5',
            ],
        ];

        DB::transaction(function () use ($orders, $orderProducts, $products, $partners, $vendors) {

            foreach ($partners as $item) {
                \App\Models\Other\Partner::create([
                    'email' => $item['email'],
                    'name' => $item['name'],
                ]);
            }

            foreach ($vendors as $item) {
                \App\Models\Other\Vendor::create([
                    'email' => $item['email'],
                    'name' => $item['name'],
                ]);
            }
            foreach ($products as $item) {
                \App\Models\Other\Product::create([
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'vendor_id' => $item['vendor_id']
                ]);
            }

            foreach ($orders as $item) {
                $order = \App\Models\Other\Order::create([
                    'status' => $item['status'],
                    'client_email' => $item['client_email'],
                    'partner_id' => $item['partner_id'],
                    'delivery_dt' => $item['delivery_dt'],
                ]);
                foreach ($orderProducts as $product) {
                    \App\Models\Other\OrderProduct::create([
                        'order_id' => $order->id,
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity'],
                        'price' => $product['price'],
                    ]);
                }
            }
        });
    }
}
