<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('make_payment_details', function (Blueprint $table) {
        $table->unsignedBigInteger('approved_by')->nullable()->after('verified_date');
        $table->timestamp('approved_at')->nullable()->after('approved_by');

        // Optional foreign key
        $table->foreign('approved_by')
              ->references('id')
              ->on('users')
              ->onDelete('set null');
    });
}

public function down()
{
    Schema::table('make_payment_details', function (Blueprint $table) {
        $table->dropForeign(['approved_by']);
        $table->dropColumn(['approved_by', 'approved_at']);
    });
}
};
