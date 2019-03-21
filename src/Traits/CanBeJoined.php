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
use Illuminate\Support\Collection;
use Sknight\LaravelInvite\Events\InvitationAccepted;
use Sknight\LaravelInvite\Events\InvitationDeclined;

/**
 * Trait CanBeJoined.
 */
trait CanBeJoined
{
    
    /**
     * The variables for join.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $skVariables;

    /**
     * Get the variable.
     *
     * @param string $name
     */
    public function getSkVariables($name)
    {
        if ($name !== null) {
            return $this->skVariables->get($name);
        }
        
        return $this->skVariables;
    }

    /**
     * Get the variables.
     *
     * @param array $data
     */
    public function setSkVariables(array $data)
    {
        $this->skVariables = collect($data);
    }

    /**
     * Check if user is invited by given user.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function isInvitedBy(User $user)
    {
        return Invite::isRelationExists($this, 'joiners', $user);
    }

    /**
     * 接受邀请
     */
    public function acceptInvitation(User $user)
    {
        if (false === \event(new InvitationAccepted($this, $this->getSkVariables('subject'), $user))) {
            return false;
        }
        
        return $this->joiners()->updateExistingPivot($user->id, [
            'status' => $this->getSkVariables('status')
        ]);
    }

    /**
     * 拒绝邀请
     */
    public function declineInvitation(User $user)
    {
        if (false === \event(new InvitationDeclined($this, $this->getSkVariables('subject'), $user))) {
            return false;
        }
        
        return $this->inviters()->updateExistingPivot($user->id, [
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
