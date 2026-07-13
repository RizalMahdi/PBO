<?php

namespace Database\Seeders;

use App\Models\Items;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $allItems = Items::all();

        if ($customers->isEmpty() || $allItems->isEmpty()) {
            $this->command->warn('Skip TransactionSeeder: no customers or items found.');
            return;
        }

        $statuses = ['completed', 'completed', 'completed', 'completed', 'completed', 'completed', 'completed',
                      'pending', 'pending',
                      'cancelled'];

        $transactions = [];

        for ($i = 0; $i < 15; $i++) {
            $customer = $customers->random();
            $createdAt = Carbon::now()->subMonths(6)->addDays(rand(0, 180));
            $status = $statuses[array_rand($statuses)];
            $itemCount = rand(1, 3);
            $totalAmount = 0;
            $items = [];

            $selectedItems = $allItems->random($itemCount);
            foreach ($selectedItems as $item) {
                $quantity = rand(1, 3);
                $subtotal = $item->harga_item * $quantity;
                $totalAmount += $subtotal;

                $items[] = [
                    'item_id' => $item->id,
                    'nama_item' => $item->nama_item,
                    'harga_item' => $item->harga_item,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
            }

            $transactions[] = [
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'username_roblox' => 'user_' . Str::random(8),
                'total_amount' => $totalAmount,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'items' => $items,
            ];
        }

        foreach ($transactions as $data) {
            $items = $data['items'];
            unset($data['items']);

            $transaction = Transaction::create($data);

            foreach ($items as $itemData) {
                $itemData['transaction_id'] = $transaction->id;
                TransactionItem::create($itemData);
            }
        }
    }
}
