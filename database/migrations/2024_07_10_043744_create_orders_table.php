<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Oder;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // user id chỉ dùng để xác định nó là thằng người dùng nào trên hệ thống
            $table->foreignIdFor(\App\Models\User::class)->constrained();

            // Lưu lại toàn bộ thông tin của người đặt hàng
            $table->string('user_name');
            $table->string('user_email');
            $table->string('user_phone');
            $table->string('user_address');
            $table->string('user_note')->nullable();

            $table->boolean('is_ship_user_same_user')->default(true);

            // Lưu lại toàn bộ thông tin của người nhận hàng
            $table->string('ship_user_name')->nullable();
            $table->string('ship_user_email')->nullable();
            $table->string('ship_user_phone')->nullable();
            $table->string('ship_user_address')->nullable();
            $table->string('ship_user_note')->nullable();

            // Trạng thái
            $table->string('status_order')->default(\App\Models\Order::STATUS_ORDER_PENDING);
            $table->string('status_payment')->default(\App\Models\Order::STATUS_PAYMENT_UNPAID);

            $table->double('total_price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oders');
    }
};
