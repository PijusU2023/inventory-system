<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Sukuriam roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $workerRole = Role::firstOrCreate(['name' => 'worker']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Sukuriam admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        // Sukuriam manager user
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole($managerRole);

        // Sukuriam worker user
        $worker = User::create([
            'name' => 'Worker User',
            'email' => 'worker@example.com',
            'password' => Hash::make('password'),
        ]);
        $worker->assignRole($workerRole);

        // Sukuriam customer user
        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
        ]);
        $customer->assignRole($customerRole);

        // Sinchronizuojame visus userius su role 'customer' su customers lentele
        $customerUsers = User::role('customer')->get();
        foreach ($customerUsers as $user) {
            Customer::updateOrCreate(
                ['email' => $user->email],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => '',      // Jei turi vartotojo telefono lauką, priskirk čia
                    'address' => '',
                    'company' => null,
                ]
            );
        }

        // Create categories
        $categories = [
            ['name' => 'Elektronika', 'description' => 'Elektroniniai prietaisai ir komponentai'],
            ['name' => 'Kompiuteriai', 'description' => 'Kompiuteriai ir jų dalys'],
            ['name' => 'Telefonai', 'description' => 'Mobilieji telefonai ir priedai'],
            ['name' => 'Biuro prekės', 'description' => 'Biuro reikmenys ir įranga'],
            ['name' => 'Programinė įranga', 'description' => 'Programos ir licencijos'],
        ];
        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create suppliers
        $suppliers = [
            [
                'name' => 'TechTiekimas UAB',
                'email' => 'info@techtiekimas.lt',
                'phone' => '+370 600 12345',
                'address' => 'Gedimino pr. 1, Vilnius'
            ],
            [
                'name' => 'Kompiuterių Pasaulis',
                'email' => 'uzsakymai@kompasaulis.lt',
                'phone' => '+370 600 54321',
                'address' => 'Laisvės al. 55, Kaunas'
            ],
            [
                'name' => 'Biuro Centras',
                'email' => 'info@biurocentras.lt',
                'phone' => '+370 600 11111',
                'address' => 'Taikos pr. 101, Klaipėda'
            ],
        ];
        foreach ($suppliers as $supplierData) {
            Supplier::create($supplierData);
        }

        // Create customers (neprisijungę arba kiti, kurie nėra vartotojai)
        $customers = [
            [
                'name' => 'Jonas Jonaitis',
                'email' => 'jonas@example.com',
                'phone' => '+370 600 99999',
                'address' => 'Vilniaus g. 15, Vilnius',
                'company' => 'Jonaičio įmonė'
            ],
            [
                'name' => 'Petras Petraitis',
                'email' => 'petras@example.com',
                'phone' => '+370 600 88888',
                'address' => 'Kauno g. 20, Kaunas',
                'company' => null
            ],
            [
                'name' => 'Ona Onaitė',
                'email' => 'ona@example.com',
                'phone' => '+370 600 77777',
                'address' => 'Klaipėdos g. 10, Klaipėda',
                'company' => 'Onos studija'
            ],
            [
                'name' => 'Marija Marijaitė',
                'email' => 'marija@example.com',
                'phone' => '+370 600 66666',
                'address' => 'Šiaulių g. 5, Šiauliai',
                'company' => 'MB Marijos verslas'
            ],
        ];
        foreach ($customers as $customerData) {
            Customer::updateOrCreate(['email' => $customerData['email']], $customerData);
        }

        // Create products with random suppliers
        $suppliers = Supplier::all();
        $products = [
            ['name' => 'Dell Monitor 24"', 'sku' => 'MON-DELL-24', 'description' => '24 colių Full HD monitorius', 'category_id' => 1, 'quantity' => 50, 'price' => 159.99, 'min_stock' => 10],
            ['name' => 'Logitech pelė MX Master', 'sku' => 'MOUSE-LOG-MX', 'description' => 'Belaidė profesionali pelė', 'category_id' => 1, 'quantity' => 75, 'price' => 89.99, 'min_stock' => 15],
            ['name' => 'Lenovo ThinkPad X1', 'sku' => 'LAP-LEN-X1', 'description' => 'Verslo klasės nešiojamas kompiuteris', 'category_id' => 2, 'quantity' => 20, 'price' => 1299.99, 'min_stock' => 5],
            ['name' => 'HP Desktop Pro', 'sku' => 'PC-HP-PRO', 'description' => 'Stacionarus kompiuteris biurui', 'category_id' => 2, 'quantity' => 30, 'price' => 699.99, 'min_stock' => 8],
            ['name' => 'Samsung Galaxy S23', 'sku' => 'PHN-SAM-S23', 'description' => 'Išmanusis telefonas', 'category_id' => 3, 'quantity' => 40, 'price' => 899.99, 'min_stock' => 10],
            ['name' => 'iPhone 15', 'sku' => 'PHN-APL-15', 'description' => 'Apple išmanusis telefonas', 'category_id' => 3, 'quantity' => 35, 'price' => 1199.99, 'min_stock' => 10],
            ['name' => 'Biuro kėdė Ergonomic', 'sku' => 'CHR-ERG-01', 'description' => 'Ergonominė biuro kėdė', 'category_id' => 4, 'quantity' => 25, 'price' => 249.99, 'min_stock' => 5],
            ['name' => 'Rašymo stalas 160x80', 'sku' => 'DSK-160-80', 'description' => 'Biuro stalas su reguliuojamu aukščiu', 'category_id' => 4, 'quantity' => 15, 'price' => 399.99, 'min_stock' => 3],
            ['name' => 'Microsoft Office 365', 'sku' => 'SW-MS-365', 'description' => 'Metinė Office 365 licencija', 'category_id' => 5, 'quantity' => 100, 'price' => 99.99, 'min_stock' => 20],
            ['name' => 'Antivirus Pro', 'sku' => 'SW-AV-PRO', 'description' => 'Antivirusinė programa (1 metai)', 'category_id' => 5, 'quantity' => 80, 'price' => 49.99, 'min_stock' => 15],
        ];

        foreach ($products as $productData) {
            $productData['supplier_id'] = $suppliers->random()->id;
            Product::create($productData);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin login: admin@example.com / password');
        $this->command->info('Manager login: manager@example.com / password');
        $this->command->info('Worker login: worker@example.com / password');
        $this->command->info('Customer login: customer@example.com / password');
    }
}
