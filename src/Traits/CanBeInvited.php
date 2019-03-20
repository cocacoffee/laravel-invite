<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 * (c) SanKnight <cocacoffee@vip.qq.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SanKnight\LaravelInvite\Traits;

use App\Models\User;
use SanKnight\LaravelInvite\Invite;
use Illuminate\Support\Collection;
use SanKnight\LaravelInvite\Events\InvitationAccepted;
use SanKnight\LaravelInvite\Events\InvitationDeclined;

/**
 * Trait CanBeInvited.
 */
trait CanBeInvited
{
    
    /**
     * The variables for invite.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $applyingVariables;

    /**
     * Get the variable.
     *
     * @param string $name
     */
    public function getApplyingVariables($name)
    {
        if ($name !== null) {
            return $this->applyingVariables->get($name);
        }
        
        return $this->applyingVariables;
    }

    /**
     * Get the variables.
     *
     * @param array $data
     */
    public function setApplyingVariables(array $data)
    {
        $this->applyingVariables = collect($data);
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
        return Invite::isRelationExists($this, $user);
    }

    /**
     * 接受邀请
     */
    public function acceptInvitation(User $user)
    {
        if (false === \event(new InvitationAccepted($this, $this->getApplyingVariables('subject'), $user))) {
            return false;
        }
        
        return $this->inviters()->updateExistingPivot($user->id, [
            'status' => $this->getApplyingVariables('status')
        ]);
    }

    /**
     * 拒绝邀请
     */
    public function declineInvitation(User $user)
    {
        if (false === \event(new InvitationDeclined($this, $this->getApplyingVariables('subject'), $user))) {
            return false;
        }
        
        return $this->invitations()->updateExistingPivot($user->id, [
            'status' => $this->getApplyingVariables('status')
        ]);
    }

    /**
     * Return inviters.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function inviters()
    {
        return $this->morphToMany(config('invite.user_model'), config('invite.morph_prefix'), config('invite.inviteable_table'))->wherePivot('subject', '=', $this->getApplyingVariables('subject'))->withPivot('inviteable_type', 'subject', 'status', 'created_at');
    }
}
