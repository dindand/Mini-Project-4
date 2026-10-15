<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Mengintegrasikan data master P4 dengan Factory Laravel (Slide 30).
     *
     * @return void
     */
    public function run()
    {
        // 1. Seed Users (Admin & Staff) - password: 'password123'
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Utama',
                'email' => 'admin@inventory.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        UserProfile::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'full_name' => 'Administrator Utama',
                'phone' => '081234567890',
                'address' => 'Gedung IT Lantai 3, Jakarta',
            ]
        );

        $staff = User::firstOrCreate(
            ['username' => 'staff'],
            [
                'name' => 'Budi Santoso',
                'email' => 'staff@inventory.com',
                'password' => Hash::make('password123'),
                'role' => 'staff',
            ]
        );

        UserProfile::firstOrCreate(
            ['user_id' => $staff->id],
            [
                'full_name' => 'Budi Santoso (Staff Gudang)',
                'phone' => '089876543210',
                'address' => 'Gudang Logistik Blok B, Bekasi',
            ]
        );

        // 2. Seed Categories dari P4
        $catElektronik = Category::firstOrCreate(
            ['name' => 'Elektronik & Komputer'],
            ['description' => 'Perangkat komputer, laptop, monitor, dan aksesoris utama']
        );

        $catAksesoris = Category::firstOrCreate(
            ['name' => 'Aksesoris & Periferal'],
            ['description' => 'Keyboard, mouse, flashdisk, dan perlengkapan input/output']
        );

        $catAtk = Category::firstOrCreate(
            ['name' => 'Alat Tulis Kantor'],
            ['description' => 'Kertas, pena, map, dan peralatan operasional kantor harian']
        );

        $catJaringan = Category::firstOrCreate(
            ['name' => 'Peralatan Jaringan'],
            ['description' => 'Router, switch, kabel LAN, crimping tool, dan konektor RJ45']
        );

        // 3. Seed Suppliers dari P4
        $sup1 = Supplier::firstOrCreate(
            ['name' => 'PT Sumber Jaya Abadi'],
            [
                'contact_person' => 'Hendra Setiawan',
                'phone' => '021-5551234',
                'email' => 'info@sumberjaya.co.id',
                'address' => 'Jl. Industri Raya No. 45, Jakarta Pusat',
            ]
        );

        $sup2 = Supplier::firstOrCreate(
            ['name' => 'CV Maju Makmur Sentosa'],
            [
                'contact_person' => 'Rina Kartika',
                'phone' => '022-7778899',
                'email' => 'sales@majumakmur.com',
                'address' => 'Jl. Sukajadi No. 12, Bandung',
            ]
        );

        $sup3 = Supplier::firstOrCreate(
            ['name' => 'PT Global Tech Distribusi'],
            [
                'contact_person' => 'Andi Pratama',
                'phone' => '031-8889900',
                'email' => 'contact@globaltech.id',
                'address' => 'Jl. Pemuda No. 88, Surabaya',
            ]
        );

        // 4. Seed Products dari P4
        $productsData = [
            [
                'category_id' => $catElektronik->id,
                'sku' => 'PRD-ELEC-001',
                'name' => 'Laptop Lenovo ThinkPad T480 Core i5',
                'price' => 6500000.00,
                'stock' => 12,
                'minimum_stock' => 5,
                'suppliers' => [
                    $sup1->id => ['supplier_price' => 5800000.00],
                    $sup3->id => ['supplier_price' => 5950000.00],
                ],
                'movements' => [
                    ['user_id' => $admin->id, 'type' => 'IN', 'quantity' => 15, 'notes' => 'Stok awal pengadaan laptop divisi IT'],
                    ['user_id' => $staff->id, 'type' => 'OUT', 'quantity' => 3, 'notes' => 'Distribusi laptop karyawan baru tim dev'],
                ],
            ],
            [
                'category_id' => $catElektronik->id,
                'sku' => 'PRD-ELEC-002',
                'name' => 'Monitor Dell 24 Inch IPS Full HD',
                'price' => 1850000.00,
                'stock' => 4,
                'minimum_stock' => 5, // Stok Kritis!
                'suppliers' => [
                    $sup3->id => ['supplier_price' => 1600000.00],
                ],
                'movements' => [
                    ['user_id' => $admin->id, 'type' => 'IN', 'quantity' => 10, 'notes' => 'Pengadaan monitor kantor cabang'],
                    ['user_id' => $staff->id, 'type' => 'OUT', 'quantity' => 6, 'notes' => 'Pemasangan display ruang meeting dan workstation'],
                ],
            ],
            [
                'category_id' => $catAksesoris->id,
                'sku' => 'PRD-ACC-001',
                'name' => 'Mechanical Keyboard RGB Red Switch',
                'price' => 450000.00,
                'stock' => 15,
                'minimum_stock' => 5,
                'suppliers' => [
                    $sup2->id => ['supplier_price' => 380000.00],
                ],
                'movements' => [
                    ['user_id' => $admin->id, 'type' => 'IN', 'quantity' => 20, 'notes' => 'Restock keyboard dari CV Maju Makmur'],
                    ['user_id' => $staff->id, 'type' => 'OUT', 'quantity' => 5, 'notes' => 'Penggantian keyboard lab programming'],
                ],
            ],
            [
                'category_id' => $catAksesoris->id,
                'sku' => 'PRD-ACC-002',
                'name' => 'Wireless Mouse Ergonomic Silent Click',
                'price' => 175000.00,
                'stock' => 2,
                'minimum_stock' => 5, // Stok Kritis!
                'suppliers' => [
                    $sup2->id => ['supplier_price' => 140000.00],
                ],
                'movements' => [
                    ['user_id' => $admin->id, 'type' => 'IN', 'quantity' => 10, 'notes' => 'Pengadaan perlengkapan peripheral'],
                    ['user_id' => $staff->id, 'type' => 'OUT', 'quantity' => 8, 'notes' => 'Permintaan divisi marketing dan finance'],
                ],
            ],
            [
                'category_id' => $catJaringan->id,
                'sku' => 'PRD-NET-001',
                'name' => 'Router WiFi Gigabit Dual-Band AC1200',
                'price' => 380000.00,
                'stock' => 8,
                'minimum_stock' => 5,
                'suppliers' => [
                    $sup3->id => ['supplier_price' => 310000.00],
                ],
                'movements' => [
                    ['user_id' => $admin->id, 'type' => 'IN', 'quantity' => 12, 'notes' => 'Penerimaan perangkat jaringan baru'],
                    ['user_id' => $staff->id, 'type' => 'OUT', 'quantity' => 4, 'notes' => 'Instalasi access point lantai 2 dan 3'],
                ],
            ],
            [
                'category_id' => $catAtk->id,
                'sku' => 'PRD-ATK-001',
                'name' => 'Kertas HVS PaperOne A4 80gr 1 Rim',
                'price' => 55000.00,
                'stock' => 25,
                'minimum_stock' => 10,
                'suppliers' => [
                    $sup1->id => ['supplier_price' => 48000.00],
                ],
                'movements' => [
                    ['user_id' => $admin->id, 'type' => 'IN', 'quantity' => 30, 'notes' => 'Stok bulanan kebutuhan ATK'],
                    ['user_id' => $staff->id, 'type' => 'OUT', 'quantity' => 5, 'notes' => 'Kebutuhan cetak laporan bulanan direksi'],
                ],
            ],
            [
                'category_id' => $catAtk->id,
                'sku' => 'PRD-ATK-002',
                'name' => 'Ballpoint Gel Hitam 0.5mm (Pak 12 pcs)',
                'price' => 36000.00,
                'stock' => 40,
                'minimum_stock' => 10,
                'suppliers' => [
                    $sup1->id => ['supplier_price' => 30000.00],
                ],
                'movements' => [
                    ['user_id' => $admin->id, 'type' => 'IN', 'quantity' => 50, 'notes' => 'Restock alat tulis'],
                    ['user_id' => $staff->id, 'type' => 'OUT', 'quantity' => 10, 'notes' => 'Distribusi ATK seluruh divisi'],
                ],
            ],
            [
                'category_id' => $catAksesoris->id,
                'sku' => 'PRD-ACC-003',
                'name' => 'Flashdisk SanDisk Ultra USB 3.0 64GB',
                'price' => 85000.00,
                'stock' => 3,
                'minimum_stock' => 5, // Stok Kritis!
                'suppliers' => [
                    $sup2->id => ['supplier_price' => 70000.00],
                ],
                'movements' => [
                    ['user_id' => $admin->id, 'type' => 'IN', 'quantity' => 15, 'notes' => 'Pengadaan media penyimpanan eksternal'],
                    ['user_id' => $staff->id, 'type' => 'OUT', 'quantity' => 12, 'notes' => 'Pembagian USB backup untuk koordinator unit'],
                ],
            ],
        ];

        foreach ($productsData as $data) {
            $suppliers = $data['suppliers'];
            $movements = $data['movements'];
            unset($data['suppliers'], $data['movements']);

            $product = Product::firstOrCreate(
                ['sku' => $data['sku']],
                $data
            );

            // Sync suppliers junction
            $product->suppliers()->syncWithoutDetaching($suppliers);

            // Seed stock movements
            foreach ($movements as $m) {
                StockMovement::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'user_id' => $m['user_id'],
                        'notes' => $m['notes'],
                    ],
                    [
                        'type' => $m['type'],
                        'quantity' => $m['quantity'],
                    ]
                );
            }
        }

        // 5. Demonstrasi Factory (Slide 30): Tambahkan dummy Category & Product
        Category::factory(2)->create()->each(function ($cat) {
            Product::factory(3)->create(['category_id' => $cat->id]);
        });
    }
}
