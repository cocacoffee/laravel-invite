<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 * (c) SanKnight <cocacoffee@vip.qq.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SanKnight\LaravelInvite\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class Event.
 *
 * @author SanKnight <cocacoffee@vip.qq.com>
 */
class Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $causer;
    
    public $subject;
    
    public $targets;

    /**
     * Event constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $causer
     * @param string $relation
     * @param int|array $targets
     */
    public function __construct(Model $causer, string $subject, $targets)
    {
        $this->causer = $causer;
        $this->subject = $subject;
        $this->targets = $targets;
    }

    public function getTargetsCollection()
    {
        return \forward_static_call([
            $this->targets->classname,
            'find'
        ], (array)$this->targets);
    }
}
