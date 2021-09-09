<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
             $table->string('username')->unique();
             $table->integer('status')->nullable();
             $table->integer('email_verify')->nullable();
             $table->dateTime('last_active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('paid');
            $table->dropColumn('status');
            $table->dropColumn('email_verify');
            $table->dropColumn('last_active');
        });
    }
}
