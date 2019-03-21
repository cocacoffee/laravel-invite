<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 * (c) SanKnight <cocacoffee@vip.qq.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sknight\LaravelInvite\Traits;

use Sknight\LaravelInvite\Invite;
use Illuminate\Support\Collection;

/**
 * Trait CanInvite.
 */
trait CanInvite
{
    /**
     * The variables for invite.
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
     * Check if user is bookmarked given item.
     *
     * @param int|array|\Illuminate\Database\Eloquent\Model $target
     * @param string $class
     *
     * @return bool
     */
    public function hasInvited($target, $class = __CLASS__)
    {
        return Invite::isRelationExists($this, 'invitations', $target, $class) !== false;
    }

    /**
     * Invite an item or items.
     *
     * @param int|array|\Illuminate\Database\Eloquent\Model $targets
     * @param string $class
     *
     * @throws \Exception
     *
     * @return array
     */
    public function invite($targets, $class = __CLASS__)
    {
        return Invite::attachRelations($this, 'invitations', $targets, $class);
    }

    /**
     * UnInvite an item or items.
     *
     * @param int|array|\Illuminate\Database\Eloquent\Model $targets
     * @param string $class
     *
     * @return array
     */
    public function uninvite($targets, $class = __CLASS__)
    {
        return Invite::detachRelations($this, 'invitations', $targets, $class);
    }

    /**
     * Return item bookmarks.
     *
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function invitations($class = __CLASS__)
    {
        return $this->morphedByMany($class, config('invite.morph_prefix'), config('invite.inviteable_table'))->wherePivot('subject', '=', $this->getSkVariables('subject'))->withPivot('subject', 'status', 'created_at');
    }
}
