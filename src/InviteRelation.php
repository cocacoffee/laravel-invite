<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 *
 * (c) SanKnight <cocacoffee@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sknight\LaravelInvite;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sknight\InvalidArgumentException;

/**
 * Class InviteRelation.
 */
class InviteRelation extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $with = ['inviteable'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function inviteable()
    {
        return $this->morphTo(config('invite.morph_prefix', 'inviteable'));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null                           $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopular($query, $type = null)
    {
        $query->select('inviteable_id', 'inviteable_type', \DB::raw('COUNT(*) AS count'))
                        ->groupBy('inviteable_id', 'inviteable_type')
                        ->orderByDesc('count');

        if ($type) {
            $query->where('inviteable_type', $this->normalizeInviteableType($type));
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function getTable()
    {
        if (!$this->table) {
            $this->table = config('invite.inviteable_table', 'inviteables');
        }

        return parent::getTable();
    }

    /**
     * {@inheritdoc}
     */
    public function getDates()
    {
        return [parent::CREATED_AT];
    }

    /**
     * @param string $type
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function normalizeInviteableType($type)
    {
        $morphMap = Relation::morphMap();

        if (!empty($morphMap) && in_array($type, $morphMap, true)) {
            $type = array_search($type, $morphMap, true);
        }

        if (class_exists($type)) {
            return $type;
        }

        $namespace = config('invite.model_namespace', 'App');

        $modelName = $namespace.'\\'.studly_case($type);

        if (!class_exists($modelName)) {
            throw new InvalidArgumentException("Model {$modelName} not exists. Please check your config 'invite.model_namespace'.");
        }

        return $modelName;
    }
}
