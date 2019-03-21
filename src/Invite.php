<?php

/*
 * This file is part of the cocacoffee/laravel-invite
 * (c) SanKnight <cocacoffee@vip.qq.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sknight\LaravelInvite;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sknight\LaravelInvite\Events\InvitationAttached;
use Sknight\LaravelInvite\Events\InvitationCancelled;
use stdClass;

/**
 * Class Invite.
 */
class Invite
{
    use SoftDeletes;

    /**
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $relation
     * @param array|string|\Illuminate\Database\Eloquent\Model $target
     * @param string $class
     *
     * @return bool
     */
    public static function isRelationExists(Model $model, string $relation, $target, $class = null)
    {
        $target = self::formatTargets($target, $class ?  : config('invite.user_model'));
        
        if ($model->relationLoaded($relation)) {
            return $model->{$relation}->where('id', head($target->ids))->isNotEmpty();
        }
        
        return $model->{$relation}($target->classname)->where('id', head($target->ids))->exists();
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $relation
     * @param array|string|\Illuminate\Database\Eloquent\Model $targets
     * @param string $class
     *
     * @throws \Exception
     *
     * @return array
     */
    public static function attachRelations(Model $model, string $relation, $targets, $class)
    {
        $targets = self::attachPivotsFromRelation($model, $targets, $class);
        
        if (false === \event(new InvitationAttached($model, $model->getSkVariables('subject'), $targets))) {
            return false;
        }
        
        return $model->{$relation}($targets->classname)->sync($targets->targets, false);
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $relation
     * @param array|string|\Illuminate\Database\Eloquent\Model $targets
     * @param string $class
     *
     * @return array
     */
    public static function detachRelations(Model $model, string $relation, $targets, $class)
    {
        $targets = self::formatTargets($targets, $class);
        
        if (false === \event(new InvitationCancelled($model, $model->getSkVariables('subject'), $targets))) {
            return false;
        }
        
        return $model->{$relation}($targets->classname)->detach($targets->ids);
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Relations\MorphToMany $morph
     * @param array|string|\Illuminate\Database\Eloquent\Model $targets
     * @param string $class
     *
     * @throws \Exception
     *
     * @return \stdClass
     */
    public static function attachPivotsFromRelation(Model $model, $targets, $class)
    {
        return self::formatTargets($targets, $class, [
            'subject' => $model->getSkVariables('subject'),
            'status' => $model->getSkVariables('status'),
            'created_at' => Carbon::now()->format(config('invite.date_format', 'Y-m-d H:i:s'))
        ]);
    }

    /**
     *
     * @param array|string|\Illuminate\Database\Eloquent\Model $targets
     * @param string $classname
     * @param array $update
     *
     * @return \stdClass
     */
    public static function formatTargets($targets, $classname, array $update = [])
    {
        $result = new stdClass();
        $result->classname = $classname;
        
        if (!is_array($targets)) {
            $targets = [
                $targets
            ];
        }
        
        $result->ids = array_map(function ($target) use($result) {
            if ($target instanceof Model) {
                $result->classname = get_class($target);
                
                return $target->getKey();
            }
            
            return intval($target);
        }, $targets);
        
        $result->targets = empty($update) ? $result->ids : array_combine($result->ids, array_pad([], count($result->ids), $update));
        
        return $result;
    }

    /**
     *
     * @param string $field
     *
     * @return string
     */
    protected static function tablePrefixedField($field)
    {
        return \sprintf('%s.%s', config('invite.inviteable_table'), $field);
    }
}
