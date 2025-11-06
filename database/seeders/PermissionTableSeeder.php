<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           
           'user-list',
           'user-create',
           'user-edit',
           'user-delete',

           'part-list',
           'part-create',
           'part-edit',
           'part-delete',

           'product-list',
           'product-create',
           'product-edit',
           'product-delete',

           'finished-product-list',
           'finished-product-create',
           'finished-product-edit',
           'finished-product-delete',

           'customer-list',
           'customer-create',
           'customer-edit',
           'customer-delete',

           'purchaseOrder-list',
           'purchaseOrder-create',
           'purchaseOrder-edit',
           'purchaseOrder-delete',

           'internalRejections-create',
           'internalRejections-edit',
           'internalRejections-delete',

           'customerRejections-list',
           'customerRejections-create',

           'sales-list',
           'sales-create',
           'sales-edit',
           'sales-delete',

           'deliveryChallans-list',
           'deliveryChallans-create',
           'deliveryChallans-edit',
           'deliveryChallans-delete',

           'settings-list'
        ];

        foreach ($permissions as $permission) {
            // Check if the permission already exists before creating
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }
    }
}