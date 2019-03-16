<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 *
 * (c) SanKnight <cocacoffee@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SanKnight\LaravelInvite\Test;

use Illuminate\Database\Eloquent\Model;
use SanKnight\LaravelInvite\Traits\CanBeInvited;

class Other extends Model
{
    use CanBeInvited;

    protected $invite = User::class;

    protected $table = 'others';

    protected $fillable = ['name'];
}
