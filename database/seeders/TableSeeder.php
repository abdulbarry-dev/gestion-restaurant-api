<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            ['table_number' => 'T1', 'capacity' => 2, 'status' => 'available'],
            ['table_number' => 'T2', 'capacity' => 4, 'status' => 'available'],
            ['table_number' => 'T3', 'capacity' => 4, 'status' => 'available'],
            ['table_number' => 'T4', 'capacity' => 6, 'status' => 'available'],
            ['table_number' => 'T5', 'capacity' => 2, 'status' => 'available'],
            ['table_number' => 'T6', 'capacity' => 4, 'status' => 'available'],
            ['table_number' => 'T7', 'capacity' => 8, 'status' => 'available'],
            ['table_number' => 'T8', 'capacity' => 2, 'status' => 'available'],
            ['table_number' => 'T9', 'capacity' => 4, 'status' => 'available'],
            ['table_number' => 'T10', 'capacity' => 6, 'status' => 'available'],
        ];

        foreach ($tables as $table) {
            Table::create($table);
        }
    }
}
