<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{


    /**
     * 
    "payments_pay_mode" => array:2 [▼
    0 => "Cash"
    1 => "Cheque"
  ]
  "payments_bank_id" => array:2 [▼
    0 => null
    1 => "1"
  ]
  "payments_branch_id" => array:2 [▼
    0 => null
    1 => "2"
  ]
  "payments_transaction_id" => array:2 [▼
    0 => null
    1 => "54d546564"
  ]
  "payments_date" => array:2 [▼
    0 => "2025-06-19"
    1 => "2025-06-19"
  ]
  "payments_attachments" => array:2 [▼
    0 => "/files/commons/6853ef5fde896-component-image.png"
    1 => "/files/commons/6853efd54ab33-component-image.png"
  ]
  "payments_verified" => array:2 [▼
    0 => "0"
    1 => "0"
  ]

     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_my_task_id')->constrained('service_my_tasks')->cascadeOnDelete();
            $table->string('pay_mode');
            $table->foreignId('bank_id')->nullable()->constrained('banks')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('bank_branches')->cascadeOnDelete();
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->string('attachments')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_payments');
    }
};
