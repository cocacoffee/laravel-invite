<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 *
 * (c) SanKnight <cocacoffee@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaravelInviteTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(config('invite.inviteable_table', 'inviteables'), function (Blueprint $table) {
            $userForeignKey = config('invite.users_table_foreign_key', 'user_id');
            $table->unsignedInteger($userForeignKey);
            $table->unsignedInteger('inviteable_id');
            $table->string('inviteable_type')->index();
            $table->string('subject')->comment('friend');
            $table->unsignedTinyInteger('status');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign($userForeignKey)
                ->references(config('invite.users_table_primary_key', 'id'))
                ->on(config('invite.users_table_name', 'users'))
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table(config('invite.inviteable_table', 'inviteables'), function ($table) {
            $table->dropForeign(config('invite.inviteable_table', 'inviteables').'_user_id_foreign');
        });

        Schema::drop(config('invite.inviteable_table', 'inviteables'));
    }
}
