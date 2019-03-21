<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 *
 * (c) SanKnight <cocacoffee@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sknight\LaravelInvite\Test;

use Illuminate\Database\Eloquent\Model;
use Sknight\LaravelInvite\Traits\CanInvite;
use Sknight\LaravelInvite\Traits\CanBeInvited;

class User extends Model
{
    use CanInvite, CanBeInvited;

    protected $table = 'users';

    protected $fillable = ['name'];
}
