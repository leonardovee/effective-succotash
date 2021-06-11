<?php

use App\Models\UserType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->unique('unique_name');
            $table->timestamps();
        });

        $userTypes = [
            ['name' => 'Business'],
            ['name' => 'Customer']
        ];

        foreach ($userTypes as $userType) {
            $userTypeModel = new UserType();
            $userTypeModel->name = $userType['name'];
            $userTypeModel->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_type');
    }
}
