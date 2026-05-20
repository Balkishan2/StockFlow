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
        // First modify the invoices table
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('draft', 'unpaid', 'paid', 'overdue') DEFAULT 'draft'");
        
        Schema::table('invoices', function (Blueprint $table) {
            // Add summary fields if they don't exist
            $table->decimal('subtotal', 10, 2)->default(0.00)->after('status');
            $table->decimal('total_tax', 10, 2)->default(0.00)->after('subtotal');
            $table->decimal('total_discount', 10, 2)->default(0.00)->after('total_tax');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('tax', 10, 2)->default(0.00)->after('unit_price');
            $table->decimal('discount', 10, 2)->default(0.00)->after('tax');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('unpaid', 'paid', 'overdue') DEFAULT 'unpaid'");
        
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'total_tax', 'total_discount']);
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['tax', 'discount']);
        });
    }
};
