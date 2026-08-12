<?php

namespace App\Support;

use App\Models\Buy;
use App\Models\BuyProduct;
use App\Models\Category;
use App\Models\customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\unit;
use Illuminate\Support\Facades\DB;

class DemoData
{
    public static function exists(): bool
    {
        return Supplier::withoutGlobalScopes()->where('is_demo', true)->exists();
    }

    public static function isVisible(): bool
    {
        return demo_data_visible();
    }

    public static function import(): array
    {
        if (self::exists()) {
            self::setVisible(true);

            return [
                'status' => 'shown',
                'message' => 'Demo data is now visible across the portal.',
            ];
        }

        DB::transaction(function () {
            \Illuminate\Database\Eloquent\Model::unguarded(function () {
                self::seed();
            });
            self::setVisible(true);
        });

        return [
            'status' => 'imported',
            'message' => 'Demo data imported successfully into the portal.',
        ];
    }

    public static function clearFromUi(): array
    {
        if (! self::exists()) {
            return [
                'status' => 'empty',
                'message' => 'No demo data found to hide.',
            ];
        }

        if (! self::isVisible()) {
            return [
                'status' => 'already_hidden',
                'message' => 'Demo data is already hidden from the UI (still safe in database).',
            ];
        }

        self::setVisible(false);

        return [
            'status' => 'hidden',
            'message' => 'Demo data hidden from UI. Database records were not deleted.',
        ];
    }

    public static function setVisible(bool $visible): void
    {
        $setting = Setting::current();
        $setting->update(['demo_data_visible' => $visible]);
        Setting::clearCache();
    }

    public static function seed(): void
    {
        $kg = unit::withoutGlobalScopes()->firstOrCreate(['name' => 'Kg'], ['is_demo' => true]);
        $piece = unit::withoutGlobalScopes()->firstOrCreate(['name' => 'Piece'], ['is_demo' => true]);
        $box = unit::withoutGlobalScopes()->firstOrCreate(['name' => 'Box'], ['is_demo' => true]);

        // Mark newly created demo units only when they were created as demo
        foreach ([$kg, $piece, $box] as $demoUnit) {
            if ($demoUnit->wasRecentlyCreated) {
                $demoUnit->forceFill(['is_demo' => true])->save();
            }
        }

        $suppliers = collect([
            ['name' => 'Al-Madina Traders', 'email' => 'demo.almadina@example.com', 'phone' => '0300-1112233', 'address' => 'Lahore, Pakistan'],
            ['name' => 'Pak Fresh Supplies', 'email' => 'demo.pakfresh@example.com', 'phone' => '0321-4455667', 'address' => 'Karachi, Pakistan'],
            ['name' => 'Green Valley Depot', 'email' => 'demo.greenvalley@example.com', 'phone' => '0333-7788990', 'address' => 'Islamabad, Pakistan'],
        ])->map(function (array $row) {
            return Supplier::withoutGlobalScopes()->updateOrCreate(
                ['email' => $row['email']],
                array_merge($row, ['is_demo' => true])
            );
        });

        $customers = collect([
            ['name' => 'Ali Retail Store', 'email' => 'demo.ali@example.com', 'address' => 'Gulberg, Lahore'],
            ['name' => 'City Mart', 'email' => 'demo.citymart@example.com', 'address' => 'Saddar, Karachi'],
            ['name' => 'Hussain Traders', 'email' => 'demo.hussain@example.com', 'address' => 'Faisal Town, Lahore'],
            ['name' => 'Noor Cash & Carry', 'email' => 'demo.noor@example.com', 'address' => 'Blue Area, Islamabad'],
        ])->map(function (array $row) {
            return customer::withoutGlobalScopes()->updateOrCreate(
                ['email' => $row['email']],
                array_merge($row, ['is_demo' => true, 'photo' => null])
            );
        });

        $categoryMap = [];
        $catalog = [
            0 => ['Dry Goods', 'Beverages'],
            1 => ['Dairy', 'Snacks'],
            2 => ['Produce', 'Household'],
        ];

        foreach ($catalog as $supplierIndex => $names) {
            foreach ($names as $name) {
                $category = Category::withoutGlobalScopes()->updateOrCreate(
                    [
                        'name' => $name,
                        'supplier_id' => $suppliers[$supplierIndex]->id,
                        'is_demo' => true,
                    ],
                    [
                        'name' => $name,
                        'supplier_id' => $suppliers[$supplierIndex]->id,
                        'is_demo' => true,
                    ]
                );
                $categoryMap[$supplierIndex][] = $category;
            }
        }

        $productDefs = [
            [0, 0, 'Basmati Rice 5kg', 1850, $kg->id],
            [0, 0, 'Cooking Oil 5L', 2200, $piece->id],
            [0, 1, 'Mineral Water Pack', 450, $box->id],
            [0, 1, 'Cola Can Pack', 980, $box->id],
            [1, 0, 'Fresh Milk 1L', 220, $piece->id],
            [1, 0, 'Yogurt Bucket', 650, $kg->id],
            [1, 1, 'Potato Chips Box', 1200, $box->id],
            [1, 1, 'Biscuits Carton', 1750, $box->id],
            [2, 0, 'Fresh Apples', 320, $kg->id],
            [2, 0, 'Bananas', 180, $kg->id],
            [2, 1, 'Detergent Pack', 890, $piece->id],
            [2, 1, 'Tissue Box Pack', 540, $box->id],
        ];

        $products = collect($productDefs)->map(function (array $def) use ($suppliers, $categoryMap) {
            [$supplierIndex, $categoryIndex, $name, $price, $unitId] = $def;
            $supplier = $suppliers[$supplierIndex];
            $category = $categoryMap[$supplierIndex][$categoryIndex];

            return Product::withoutGlobalScopes()->updateOrCreate(
                [
                    'name' => $name,
                    'supplier_id' => $supplier->id,
                    'is_demo' => true,
                ],
                [
                    'name' => $name,
                    'unit_price' => $price,
                    'supplier_id' => $supplier->id,
                    'unit_id' => $unitId,
                    'category_id' => $category->id,
                    'is_demo' => true,
                ]
            );
        })->values();

        // Purchases (approved)
        foreach ([0, 1, 2] as $i) {
            $supplier = $suppliers[$i];
            $category = $categoryMap[$i][0];
            $relatedProducts = $products->where('supplier_id', $supplier->id)->take(2)->values();

            $buy = Buy::withoutGlobalScopes()->updateOrCreate(
                ['purchase_no' => 'DEMO-PO-'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'purchase_no' => 'DEMO-PO-'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT),
                    'date' => now()->subDays(12 - ($i * 3))->toDateString(),
                    'supplier_id' => $supplier->id,
                    'category_id' => $category->id,
                    'sttaus' => 'approved',
                    'description' => 'Demo purchase order',
                    'is_demo' => true,
                ]
            );

            foreach ($relatedProducts as $product) {
                $qty = 20 + ($i * 5);
                $total = $qty * (float) $product->unit_price;

                BuyProduct::withoutGlobalScopes()->updateOrCreate(
                    [
                        'buy_id' => $buy->id,
                        'product_id' => $product->id,
                        'is_demo' => true,
                    ],
                    [
                        'buy_id' => $buy->id,
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'unit_price' => $product->unit_price,
                        'total_price' => $total,
                        'description' => 'Demo stock in',
                        'is_demo' => true,
                    ]
                );
            }
        }

        // Invoices
        $invoicePlans = [
            [0, 0, 'full_paid', null],
            [1, 1, 'partial_paid', 2500],
            [2, 2, 'full_due', null],
            [3, 0, 'full_paid', null],
        ];

        foreach ($invoicePlans as $index => $plan) {
            [$customerIndex, $supplierIndex, $payStatus, $partial] = $plan;
            $supplier = $suppliers[$supplierIndex];
            $customer = $customers[$customerIndex];
            $lines = $products->where('supplier_id', $supplier->id)->take(2)->values();

            $subtotal = 0;
            $lineData = [];
            foreach ($lines as $lineIndex => $product) {
                $qty = 3 + $lineIndex;
                $lineTotal = $qty * (float) $product->unit_price;
                $subtotal += $lineTotal;
                $lineData[] = compact('product', 'qty', 'lineTotal');
            }

            $invoice = Invoice::withoutGlobalScopes()->updateOrCreate(
                [
                    'customer_id' => $customer->id,
                    'supplier_id' => $supplier->id,
                    'date' => now()->subDays(8 - $index)->toDateString(),
                    'is_demo' => true,
                    'total_price' => $subtotal,
                ],
                [
                    'date' => now()->subDays(8 - $index)->toDateString(),
                    'customer_id' => $customer->id,
                    'supplier_id' => $supplier->id,
                    'payment_status' => $payStatus,
                    'partial_amount' => $partial,
                    'total_price' => $subtotal,
                    'status' => 'approved',
                    'is_demo' => true,
                ]
            );

            foreach ($lineData as $line) {
                Item::withoutGlobalScopes()->updateOrCreate(
                    [
                        'invoice_id' => $invoice->id,
                        'product_id' => $line['product']->id,
                        'is_demo' => true,
                    ],
                    [
                        'invoice_id' => $invoice->id,
                        'category_id' => $line['product']->category_id,
                        'product_id' => $line['product']->id,
                        'quantity' => $line['qty'],
                        'description' => 'Demo sale line',
                        'price' => $line['product']->unit_price,
                        'discount' => 0,
                        'total_price' => $line['lineTotal'],
                        'is_demo' => true,
                    ]
                );
            }

            if ($payStatus === 'full_paid') {
                Payment::withoutGlobalScopes()->updateOrCreate(
                    [
                        'invoice_id' => $invoice->id,
                        'is_demo' => true,
                    ],
                    [
                        'invoice_id' => $invoice->id,
                        'amount' => $subtotal,
                        'payment_date' => now()->subDays(7 - $index)->toDateString(),
                        'is_demo' => true,
                    ]
                );
            } elseif ($payStatus === 'partial_paid' && $partial) {
                Payment::withoutGlobalScopes()->updateOrCreate(
                    [
                        'invoice_id' => $invoice->id,
                        'is_demo' => true,
                    ],
                    [
                        'invoice_id' => $invoice->id,
                        'amount' => $partial,
                        'payment_date' => now()->subDays(6 - $index)->toDateString(),
                        'is_demo' => true,
                    ]
                );
            }
        }
    }
}
