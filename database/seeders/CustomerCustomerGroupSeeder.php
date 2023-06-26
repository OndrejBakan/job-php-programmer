<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Database\Seeder;

class CustomerCustomerGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $customerGroups = CustomerGroup::all();

        foreach ($customers as $customer) {
            $customer->customerGroups()->sync(
                $customerGroups->random(rand(0, 5))
            );
        }
    }
}
