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
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name')->index('user_name');
                $table->string('email')->unique()->index('user_email');
                $table->timestamp('email_verified_at')->nullable();
               // $table->unsignedBigInteger('role_id'); // 
                //$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade'); 
                $table->string('department')->index('user_department')->nullable();
                $table->string('designation')->index('user_designation')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('users');
        }
    };
