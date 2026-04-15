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
            $table->string('name', 100);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->unsignedInteger('weight')->nullable();
            $table->unsignedInteger('price_original');
            $table->unsignedInteger('stock')->default(0);
            $table->enum('status', [
                'available',
                'waiting_payment',
                'sold'
            ])->default('available');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('negotiations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('offer_price');
            $table->enum('status', [
                'pending',
                'accepted',
                'rejected',
                'waiting'
            ])->default('pending');
            $table->timestamps();

            $table->unique(['product_id', 'buyer_id']);
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });


        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->integer('total')->nullable();   
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
            $table->string('c_name')->nullable();
            $table->string('p_name')->nullable();
            $table->string('k_name')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('courier', 5)->nullable();
            $table->string('resi')->nullable();
            $table->enum('shipping_status',[
            'dikemas',
            'dikirim',
            'selesai'
            ])->default('dikemas');
            $table->integer('qty');
            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('receiver_name', 100);
            $table->string('phone', 20);
            $table->text('address');
            $table->unsignedInteger('city');
            $table->string('c_name')->nullable();
            $table->unsignedInteger('province');
            $table->string('p_name')->nullable();
            $table->unsignedInteger('kecamatan');
            $table->string('k_name');
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('negotiations');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
