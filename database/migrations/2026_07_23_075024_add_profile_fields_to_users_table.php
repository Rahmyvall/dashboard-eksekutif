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

        Schema::table('users', function (Blueprint $table) {

            if (! Schema::hasColumn('users', 'username')) {

                $table->string('username', 100)
                    ->nullable()
                    ->unique()
                    ->after('name');

            }

            if (! Schema::hasColumn('users', 'phone')) {

                $table->string('phone', 20)
                    ->nullable()
                    ->after('email');

            }

            if (! Schema::hasColumn('users', 'photo')) {

                $table->string('photo')
                    ->nullable()
                    ->after('role');

            }

            if (! Schema::hasColumn('users', 'last_login_ip')) {

                $table->string('last_login_ip')
                    ->nullable()
                    ->after('last_login_at');

            }

            if (! Schema::hasColumn('users', 'deleted_at')) {

                $table->softDeletes();

            }

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('users', function (Blueprint $table) {

            $columns = [];

            if (Schema::hasColumn('users', 'username')) {

                $columns[] = 'username';

            }

            if (Schema::hasColumn('users', 'phone')) {

                $columns[] = 'phone';

            }

            if (Schema::hasColumn('users', 'photo')) {

                $columns[] = 'photo';

            }

            if (Schema::hasColumn('users', 'last_login_ip')) {

                $columns[] = 'last_login_ip';

            }

            if (Schema::hasColumn('users', 'deleted_at')) {

                $columns[] = 'deleted_at';

            }

            if (! empty($columns)) {

                $table->dropColumn($columns);

            }

        });

    }

};
