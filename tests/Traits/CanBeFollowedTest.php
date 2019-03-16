<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 *
 * (c) SanKnight <cocacoffee@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SanKnight\LaravelInvite\Test\Traits;

use SanKnight\LaravelInvite\Test\Other;
use SanKnight\LaravelInvite\Test\TestCase;
use SanKnight\LaravelInvite\Test\User;

class CanBeInvitedTest extends TestCase
{
    public function test_user_can_invite_by_id()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->invite($user2->id);

        $this->assertCount(1, $user2->inviteers);
    }

    public function test_user_can_invite_multiple_users()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);
        $user3 = User::find(3);

        $user1->invite([$user2->id, $user3->id]);

        $this->assertCount(1, $user2->inviteers);
        $this->assertCount(1, $user3->inviteers);
    }

    public function test_is_invited_by()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->invite($user2->id);

        $this->assertTrue($user2->isInvitedBy($user1->id));
    }

    public function test_user_can_invite_other_by_id()
    {
        $user = User::find(1);
        $other = Other::find(1);

        $user->invite($other);

        $this->assertCount(1, $other->inviteers);
    }

    public function test_is_invited_by_user()
    {
        $user = User::find(1);
        $other = Other::find(1);

        $user->invite($other);

        $this->assertTrue($other->isInvitedBy($user));
    }
}
