<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'name' => 'owner', 'label' => 'Owner / Pemilik', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'admin', 'label' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'cashier', 'label' => 'Kasir', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Optional: Assign role to first user if exists
        $firstUser = DB::table('users')->orderBy('id')->first();
        if ($firstUser) {
            DB::table('users')->where('id', $firstUser->id)->update(['role_id' => 1]); // Make first user Owner
        }
    }
}
