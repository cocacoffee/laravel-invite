<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 * (c) SanKnight <cocacoffee@vip.qq.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sknight\LaravelInvite\Traits;

use Sknight\LaravelInvite\Invite;

/**
 * Trait CanJoin.
 */
trait CanJoin
{
    /**
     * Check if user is joined given item.
     *
     * @param int|array|\Illuminate\Database\Eloquent\Model $target
     * @param string $class
     *
     * @return bool
     */
    public function hasJoined($target, $class = __CLASS__)
    {
        return Invite::isRelationExists($this, 'joins', $target, $class) !== false;
    }

    /**
     * Join an item or items.
     *
     * @param int|array|\Illuminate\Database\Eloquent\Model $targets
     * @param string $class
     *
     * @throws \Exception
     *
     * @return array
     */
    public function joining($targets, $class = __CLASS__)
    {
        return Invite::attachRelations($this, 'joins', $targets, $class);
    }

    /**
     * Unjoin an item or items.
     *
     * @param int|array|\Illuminate\Database\Eloquent\Model $targets
     * @param string $class
     *
     * @return array
     */
    public function unJoined($targets, $class = __CLASS__)
    {
        return Invite::detachRelations($this, 'joins', $targets, $class);
    }

    /**
     * Return item bookmarks.
     *
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function joins($class = __CLASS__)
    {
        return $this->morphedByMany($class, config('invite.morph_prefix'), config('invite.inviteable_table'))->wherePivot('subject', '=', $this->getSkVariables('subject'))->withPivot('subject', 'status', 'created_at');
    }
}
