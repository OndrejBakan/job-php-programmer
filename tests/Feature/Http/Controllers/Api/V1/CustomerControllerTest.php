<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_customers(): void
    {
        $customers = Customer::factory(10)->create();

        $response = $this->getJson(route('api.v1.customers.index'));

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->has(10)
                ->first(
                    fn (AssertableJson $json) => $json
                        ->whereAll([
                            'id' => $customers[0]->id,
                            'name' => $customers[0]->name,
                        ])
                        ->etc()
                )
        );
    }

    public function test_customer_does_not_return_customer_groups_by_default(): void
    {
        $customer = Customer::factory()->create();
        $customerGroups = CustomerGroup::factory(3)->create();
        $customer->customerGroups()->sync($customerGroups);

        $response = $this->getJson(route('api.v1.customers.show', ['customer' => $customer->id]));

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'created_at', 'updated_at'])
                ->missing('customer_groups')
        );
    }

    public function test_customer_does_return_customer_groups_when_requested(): void
    {
        $customer = Customer::factory()->create();
        $customerGroups = CustomerGroup::factory(3)->create();
        $customer->customerGroups()->sync($customerGroups);

        $response = $this->getJson(route('api.v1.customers.show', ['customer' => 1, 'include' => 'customerGroups']));

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'created_at', 'updated_at'])
                ->has('customer_groups', 3)
        );
    }

    public function test_customer_can_be_created(): void
    {
        $name = fake()->name;

        $response = $this->postJson(route('api.v1.customers.store', ['name' => $name]));

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'created_at', 'updated_at'])
                ->where('name', $name)
        );
    }

    public function test_customer_cannot_be_created_with_invalid_data(): void
    {
        $name = Str::random(256);

        $response = $this->postJson(route('api.v1.customers.store', ['name' => $name]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('name');
    }

    public function test_customer_can_be_updated(): void
    {
        $name = 'New Name';
        $customer = Customer::factory()->create(['name' => 'Old Name']);

        $response = $this->patchJson(route('api.v1.customers.update', ['customer' => $customer->id, 'name' => $name]));

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'created_at', 'updated_at'])
                ->where('name', $name)
        );
    }

    public function test_customer_cannot_be_updated_with_invalid_data(): void
    {
        $name = Str::random(256);
        $customer = Customer::factory()->create(['name' => 'Old Name']);

        $response = $this->patchJson(
            route('api.v1.customers.update', ['customer' => $customer->id, 'name' => $name])
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('name');
    }

    public function test_customer_can_be_deleted(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->deleteJson(route('api.v1.customers.update', ['customer' => $customer->id]));

        $response->assertStatus(200);
    }

    public function test_customer_can_be_attached_to_a_customer_group(): void
    {
        $customer = Customer::factory()->create();
        $customerGroup = CustomerGroup::factory()->create();

        $response = $this->postJson(
            route('api.v1.customers.customer-groups.create', ['customer' => $customer->id]),
            [
                'customer_groups' => [
                    ['id' => $customerGroup->id],
                ],
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'created_at', 'updated_at'])
                ->has('customer_groups', 1)
        );
    }

    public function test_customer_can_be_attached_to_many_customer_groups_at_once(): void
    {
        $customer = Customer::factory()->create();
        $customerGroups = CustomerGroup::factory(3)->create();

        $response = $this->postJson(
            route('api.v1.customers.customer-groups.create', ['customer' => $customer->id]),
            [
                'customer_groups' => $customerGroups->map(function ($item) {
                    return ['id' => $item->id];
                })->toArray(),
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'created_at', 'updated_at'])
                ->has('customer_groups', 3)
        );
    }

    public function test_customer_can_be_detached_from_a_customer_group(): void
    {
        $customer = Customer::factory()->create();
        $customerGroups = CustomerGroup::factory(3)->create();
        $customer->customerGroups()->attach($customerGroups);

        $response = $this->deleteJson(
            route('api.v1.customers.customer-groups.destroy', ['customer' => $customer->id]),
            [
                'customer_groups' => [
                    ['id' => $customerGroups->first()->id],
                ],
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'created_at', 'updated_at'])
                ->has('customer_groups', 2)
        );
    }

    public function test_customer_can_be_detached_from_many_customer_groups_at_once(): void
    {
        $customer = Customer::factory()->create();
        $customerGroups = CustomerGroup::factory(3)->create();
        $customer->customerGroups()->attach($customerGroups);

        $response = $this->deleteJson(
            route('api.v1.customers.customer-groups.destroy', ['customer' => $customer->id]),
            [
                'customer_groups' => $customerGroups->map(function ($item) {
                    return ['id' => $item->id];
                })->toArray(),
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'created_at', 'updated_at'])
                ->has('customer_groups', 0)
        );
    }

    public function test_customer_can_be_attached_and_detached_from_customer_groups_via_sync_method(): void
    {
        $customer = Customer::factory()->create();
        $customerGroupsOld = CustomerGroup::factory(3)->create();
        $customerGroupsNew = CustomerGroup::factory(3)->create();
        $customer->customerGroups()->attach($customerGroupsOld);
        $customerOld = $customer->load('customerGroups');

        $response = $this->patchJson(
            route('api.v1.customers.customer-groups.update', ['customer' => $customer->id]),
            [
                'customer_groups' => $customerGroupsNew->map(function ($item) {
                    return ['id' => $item->id];
                })->toArray(),
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name', 'created_at', 'updated_at'])
                ->has(
                    'customer_groups',
                    3,
                    fn (AssertableJson $json) => $json
                        ->where('id', $customerGroupsNew->first()->id)
                        ->etc()
                )
        );
        $response->assertJsonMissing(
            ['customer_groups' => $customerOld->customerGroups->toArray()],
        );
    }
}
