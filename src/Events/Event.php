<?php

/*
  * This file is part of the cocacoffee/laravel-invite
 *
 * (c) SanKnight <cocacoffee@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SanKnight\LaravelInvite\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use SanKnight\LaravelInvite\Invite;

/**
 * Class Event.
 *
 * @author SanKnight <cocacoffee@vip.qq.com>
 */
class Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $causer;

    public $relation;

    public $targets;

    public $class;

    /**
     * Event constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model   $causer
     * @param \LaravelInvite\Events\string $relation
     * @param int|array                             $targets
     * @param \LaravelInvite\Events\string $class
     */
    public function __construct(Model $causer, string $relation, $targets, string $class)
    {
        $this->causer = $causer;
        $this->relation = $relation;
        $this->targets = $targets;
        $this->class = $class;
    }

    public function getRelationType()
    {
        return Invite::RELATION_TYPES[$this->relation];
    }

    public function getTargetsCollection()
    {
        return \forward_static_call([$this->targets->classname, 'find'], (array) $this->targets);
    }
}
