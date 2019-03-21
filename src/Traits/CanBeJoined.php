<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 * (c) SanKnight <cocacoffee@vip.qq.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sknight\LaravelInvite\Traits;

use App\Models\User;
use Sknight\LaravelInvite\Invite;
use Sknight\LaravelInvite\Events\InvitationAccepted;
use Sknight\LaravelInvite\Events\InvitationDeclined;

/**
 * Trait CanBeJoined.
 */
trait CanBeJoined
{
    /**
     * Check if user is invited by given user.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function isJoinedBy(User $user)
    {
        return Invite::isRelationExists($this, 'joiners', $user);
    }

    /**
     * 接受/同意
     */
    public function accept(User $user)
    {
        if (false === \event(new InvitationAccepted($this, $this->getSkVariables('subject'), $user))) {
            return false;
        }
        
        return $this->joiners()->updateExistingPivot($user->id, [
            'status' => $this->getSkVariables('status')
        ]);
    }

    /**
     * 拒绝/不通过
     */
    public function decline(User $user)
    {
        if (false === \event(new InvitationDeclined($this, $this->getSkVariables('subject'), $user))) {
            return false;
        }
        
        return $this->joiners()->updateExistingPivot($user->id, [
            'status' => $this->getSkVariables('status')
        ]);
    }

    /**
     * Return joiners.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function joiners()
    {
        return $this->morphToMany(config('invite.user_model'), config('invite.morph_prefix'), config('invite.inviteable_table'))->wherePivot('subject', '=', $this->getSkVariables('subject'))->withPivot('inviteable_type', 'subject', 'status', 'created_at');
    }
}
