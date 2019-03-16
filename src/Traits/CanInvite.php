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
 * Trait CanBookmark.
 */
trait CanInvite
{
    /**
     * Invite an item or items.
     *
     * @param int|array|\Illuminate\Database\Eloquent\Model $targets
     * @param string                                        $class
     *
     * @throws \Exception
     *
     * @return array
     */
    public function bookmark($targets, $class = __CLASS__)
    {
        return Invite::attachRelations($this, 'bookmarks', $targets, $class);
    }

    /**
     * Unbookmark an item or items.
     *
     * @param int|array|\Illuminate\Database\Eloquent\Model $targets
     * @param string                                        $class
     *
     * @return array
     */
    public function unbookmark($targets, $class = __CLASS__)
    {
        return Invite::detachRelations($this, 'bookmarks', $targets, $class);
    }

    /**
     * Toggle bookmark an item or items.
     *
     * @param int|array|\Illuminate\Database\Eloquent\Model $targets
     * @param string                                        $class
     *
     * @throws \Exception
     *
     * @return array
     */
    public function toggleBookmark($targets, $class = __CLASS__)
    {
        return Invite::toggleRelations($this, 'bookmarks', $targets, $class);
    }

    /**
     * Check if user is bookmarked given item.
     *
     * @param int|array|\Illuminate\Database\Eloquent\Model $target
     * @param string                                        $class
     *
     * @return bool
     */
    public function hasBookmarked($target, $class = __CLASS__)
    {
        return Invite::isRelationExists($this, 'bookmarks', $target, $class);
    }

    /**
     * Return item bookmarks.
     *
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bookmarks($class = __CLASS__)
    {
        return $this->morphedByMany($class, config('invite.morph_prefix'), config('invite.inviteable_table'))
                    ->wherePivot('relation', '=', Invite::RELATION_BOOKMARK)
                    ->withPivot('inviteable_type', 'relation', 'created_at');
    }
}
