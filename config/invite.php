<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 *
 * (c) SanKnight <cocacoffee@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    /*
     * Model class name of users.
     */
    'user_model' => config('auth.providers.users.model', App\Models\User::class),

    /*
     * Table name of users table.
     */
    'users_table_name' => 'users',

    /*
     * Primary key of users table.
     */
    'users_table_primary_key' => 'id',

    /*
     * Foreign key of users table.
     */
    'users_table_foreign_key' => 'user_id',

    /*
     * Table name of inviteable relations.
     */
    'followable_table' => 'inviteables',

    /*
     * Prefix of many-to-many relation fields.
     */
    'morph_prefix' => 'inviteable',

    /*
     * Date format for created_at.
     */
    'date_format' => 'Y-m-d H:i:s',

    /*
     * Namespace of models.
     */
    'model_namespace' => 'App',
];
