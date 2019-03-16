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

class CanInviteTest extends TestCase
{
    public function test_user_can_invite_by_id()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->invite($user2->id);

        $this->assertCount(1, $user1->inviteings);
    }

    public function test_user_can_invite_multiple_users()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);
        $user3 = User::find(3);

        $user1->invite([$user2->id, $user3->id]);

        $this->assertCount(2, $user1->inviteings);
    }

    public function test_uninvite_user()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->invite($user2->id);
        $this->assertCount(1, $user2->inviteers);
        $user1->uninvite($user2->id);
        $this->assertCount(0, $user1->inviteings);
    }

    public function test_is_inviteing()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->invite($user2->id);

        $this->assertTrue($user1->isInviteing($user2->id));
    }

    public function test_user_can_invite_other_by_id()
    {
        $user = User::find(1);
        $other = Other::find(1);

        $user->invite($other);

        $this->assertCount(1, $user->inviteings(Other::class)->get());
    }

    public function test_uninvite_other()
    {
        $user = User::find(1);
        $other = Other::find(1);

        $user->invite($other);
        $user->uninvite($other);

        $this->assertCount(0, $user->inviteings);
    }

    public function test_is_inviteing_other()
    {
        $user = User::find(1);
        $other = Other::find(1);

        $user->invite($other);

        $this->assertTrue($user->isInviteing($other));
    }

    public function test_inviteing_each_other()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->invite($user2);

        $this->assertFalse($user1->areInviteingEachOther($user2));

        $user2->invite($user1);
        $this->assertTrue($user1->areInviteingEachOther($user2));
    }

    public function test_eager_loading()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->invite($user2);
        $user2->invite($user1);

        // eager loading
        $user2 = User::find(2)->load(['inviteings', 'inviteers']);
        $this->assertTrue($user2->isInvitedBy($user1));

        // without eager loading
        $this->assertTrue($user1->isInvitedBy($user2));
    }
}
