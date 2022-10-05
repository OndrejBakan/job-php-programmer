<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CustomerTest extends TestCase
{

    use RefreshDatabase;

    public function test_specific_customer_should_not_include_customer_group_by_default()
    {
        $customer = Customer::factory(1)->create();
        $customerGroup = CustomerGroup::factory(1)->create();

        $rel = DB::table('customer_customer_group')->insert([
            'customer_id'       => $customer->first()->id,
            'customer_group_id' => $customerGroup->first()->id,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $response = $this->getJson('/api/v1/customers/' . $customer->first()->id);

        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->where('id', $customer->first()->id)
                    ->where('name', $customer->first()->name)
                    ->etc()
            );
    }

    public function test_specific_customer_should_include_customer_group_on_demand()
    {
        $customer = Customer::factory(1)->create();
        $customerGroup = CustomerGroup::factory(1)->create();

        $rel = DB::table('customer_customer_group')->insert([
            'customer_id'       => $customer->first()->id,
            'customer_group_id' => $customerGroup->first()->id,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $response = $this->getJson('/api/v1/customers/' . $customer->first()->id . '?include=customer-groups');

        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->where('id', $customer->first()->id)
                    ->where('name', $customer->first()->name)
                    ->has('customer_groups')
                    ->etc()
            );
    }
}
