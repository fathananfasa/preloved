<?php

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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 15);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->unsignedInteger('weight')->nullable();
            $table->unsignedInteger('price_original');
            $table->unsignedInteger('bottom_price');
            $table->unsignedInteger('stock')->default(0);
            $table->enum('status', [
                'available',
                'sold'
            ])->default('available');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('negotiations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('offer_price');
            $table->enum('status', [
                'pending',
                'accepted',
                'rejected'
            ])->default('pending');
            $table->timestamps();
            $table->unsignedTinyInteger('attempt_count')->default(0);
            $table->boolean('is_blocked')->default(false);
            $table->decimal('counter_price', 12, 2)->nullable();
            $table->text('ai_message')->nullable();
            $table->decimal('final_price', 12, 2)->nullable();
            $table->unique(['product_id', 'user_id']);
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('quantity')->default(1);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('total')->nullable();
            $table->enum('status', [
                'waiting_payment',
                'paid',
                'expired'
            ])->default('waiting_payment');
            $table->string('snap_token')->nullable();
            $table->timestamp('expired_at');
            $table->string('receiver_name', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('c_name', 100)->nullable();
            $table->string('p_name', 100)->nullable();
            $table->string('k_name', 100)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('courier', 5)->nullable();
            $table->string('resi', 50)->nullable();
            $table->string('shipping_status')->default('dikemas');
            $table->json('tracking_history')->nullable();
            $table->json('last_tracking')->nullable();
            $table->timestamps();
        });

        Schema::create('transaction_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('transaction_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('qty');

            $table->unsignedInteger('price');

            $table->unsignedInteger('subtotal');

            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('receiver_name', 100);
            $table->string('phone', 20);
            $table->text('address');
            $table->unsignedInteger('city');
            $table->string('c_name', 100)->nullable();
            $table->unsignedInteger('province');
            $table->string('p_name', 100)->nullable();
            $table->unsignedInteger('kecamatan');
            $table->string('k_name', 100);
            $table->string('postal_code', 10);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('message');

            $table->unsignedTinyInteger('rating');

            $table->boolean('is_approved')
                ->default(true);

            $table->timestamps();
        });

        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->date('visit_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('negotiations');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};