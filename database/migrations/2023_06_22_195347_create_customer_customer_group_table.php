<?php

use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_customer_group', function (Blueprint $table) {
            $table->foreignIdFor(Customer::class);
            $table->foreignIdFor(CustomerGroup::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_customer_group');
    }
};
