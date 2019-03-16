<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 *
 * (c) SanKnight <cocacoffee@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SanKnight\LaravelInvite\Traits;

use SanKnight\LaravelInvite\Invite;

/**
 * Trait CanBeBookmarked.
 */
trait CanBeInvited
{
    /**
     * Check if user is bookmarked by given user.
     *
     * @param int $user
     *
     * @return bool
     */
    public function isBookmarkedBy($user)
    {
        return Invite::isRelationExists($this, 'bookmarkers', $user);
    }

    /**
     * Return bookmarkers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bookmarkers()
    {
        return $this->morphToMany(config('invite.user_model'), config('invite.morph_prefix'), config('invite.inviteable_table'))
                    ->wherePivot('relation', '=', Invite::RELATION_BOOKMARK)
                    ->withPivot('inviteable_type', 'relation', 'created_at');
    }
}
