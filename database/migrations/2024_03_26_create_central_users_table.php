<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('central_users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('tenant_id');
            $table->timestamps();
            
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');
                
            // Ensure a user can only be registered once per tenant
            $table->unique(['email', 'tenant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('central_users');
    }
}; 