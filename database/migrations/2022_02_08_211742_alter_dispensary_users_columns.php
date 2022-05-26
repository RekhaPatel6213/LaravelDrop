<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Dispensary\DispensaryUser;

class AlterDispensaryUsersColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dispensary_users', function (Blueprint $table) {
            $table->string('staff_role', 255)->nullable();
            $table->json('territory_ids')->nullable();
            $table->enum('role', [DispensaryUser::ALL, DispensaryUser::HUB, DispensaryUser::DISPATCH])->default(DispensaryUser::ALL);
            $table->enum('is_owner', [DispensaryUser::YES, DispensaryUser::NO])->default(DispensaryUser::NO);
            $table->enum('status', [DispensaryUser::ACTIVE, DispensaryUser::INACTIVE])->default(DispensaryUser::ACTIVE);
            $table->timestamp('last_login')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dispensary_users', function (Blueprint $table) {
            $table->dropColumn([
                'staff_role',
                'territory_ids',
                'role',
                'is_owner',
                'status',
                'last_login'
            ]);
        });
    }
}
