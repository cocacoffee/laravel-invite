<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 * (c) SanKnight <cocacoffee@vip.qq.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sknight\LaravelInvite\Traits;

use Illuminate\Support\Collection;

/**
 * Trait HasVariables.
 */
trait HasVariables
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
}