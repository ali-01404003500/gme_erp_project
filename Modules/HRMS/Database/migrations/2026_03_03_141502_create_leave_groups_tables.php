<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('leave_group_details');
        Schema::dropIfExists('leave_groups');

        Schema::create('leave_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('leave_group_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_group_id')->constrained('leave_groups')->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained('leave_types')->onDelete('cascade');
            $table->decimal('allowed_balance', 8, 2)->default(0);
            $table->decimal('max_leave_balance_in_year', 8, 2)->default(0);
            $table->integer('continuous_sanction')->default(0);
            $table->decimal('max_forward_from_previous_year', 8, 2)->default(0);
            $table->integer('max_sanction_in_service_life')->default(0);
            $table->integer('interval_days_in_same_leave')->default(0);
            $table->integer('min_day_count_for_attachment')->default(0);
            $table->integer('max_limit_for_past_leave')->default(0);
            $table->integer('apply_future_leave_after_days')->default(0);
            $table->decimal('max_balance_for_encashment', 8, 2)->nullable();
            $table->boolean('is_balance_forward')->default(false);
            $table->boolean('allow_leave_encashment')->default(false);
            $table->boolean('balance_forwarding_on_group_change')->default(false);
            $table->boolean('leave_allow_between_multiple_years')->default(false);
            $table->boolean('negative_balance')->default(false);
            $table->boolean('is_half_day')->default(false);
            $table->boolean('continuous_days_allow')->default(false);
            $table->boolean('is_prefix_allowed')->default(false);
            $table->boolean('is_suffix_allowed')->default(false);
            $table->boolean('requires_leave_attachment')->default(false);
            $table->boolean('allow_earn_leave')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_group_details');
        Schema::dropIfExists('leave_groups');
    }
};
