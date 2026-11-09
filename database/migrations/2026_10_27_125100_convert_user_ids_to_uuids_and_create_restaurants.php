<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique();
            $table->boolean('system_admin')->default(false);
        });

        $ids = [];

        foreach (DB::table('users')->orderBy('id')->get() as $user) {
            $uuid = (string) Str::uuid7();
            $ids[$user->id] = $uuid;

            DB::table('users')->where('id', $user->id)->update([
                'uuid' => $uuid,
                'system_admin' => $user->email === 'tritz.kailar@gmail.com',
            ]);
        }

        Schema::table('sessions', function (Blueprint $table) {
            $table->uuid('user_uuid')->nullable();
        });

        foreach ($ids as $oldId => $uuid) {
            DB::table('sessions')->where('user_id', $oldId)->update([
                'user_uuid' => $uuid,
            ]);
        }

        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->renameColumn('user_uuid', 'user_id');
            $table->index('user_id');
        });

        Schema::create('users_uuid', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name')->default('');
            $table->string('last_name')->default('');
            $table->date('date_of_birth')->nullable();
            $table->char('state', 2)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('system_admin')->default(false);
            $table->timestamps();
            $table->index(['date_of_birth', 'state']);
        });

        foreach (DB::table('users')->orderBy('id')->get() as $user) {
            DB::table('users_uuid')->insert([
                'id' => $user->uuid,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'date_of_birth' => $user->date_of_birth,
                'state' => $user->state,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'remember_token' => $user->remember_token,
                'system_admin' => $user->system_admin,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        Schema::drop('users');
        Schema::rename('users_uuid', 'users');

        Schema::create('restaurants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('created_by_id')->constrained('users');
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->char('state', 2);
            $table->string('zip', 20);
            $table->string('logo_url')->nullable();
            $table->string('website_url')->nullable();
            $table->char('status', 1)->default('P');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('system_admin');
        });
    }
};
