<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonHangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('don_hangs', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->double('tong_tien', 18, 0);
            $table->double('tien_giam_gia', 18, 0);
            $table->double('thuc_tra', 18, 0);
            $table->integer('status');
            $table->String('dia_chi');
            $table->String('nguoi_nhan');
            $table->String('sdt');
            $table->integer('loai_thanh_toan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.

     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('don_hangs');
    }
}
