<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerCustomerGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customerIds = Customer::pluck('id', 'id')->toArray();
        $customerGroupIds = CustomerGroup::pluck('id', 'id')->toArray();

        for($i = 1; $i <= 500; $i++) {
            DB::table('customer_customer_group')->insert([
                'customer_id'       => array_rand(Customer::pluck('id', 'id')->toArray(), 1),
                'customer_group_id' => array_rand(CustomerGroup::pluck('id', 'id')->toArray(), 1),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
        
    }
}
