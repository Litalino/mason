<?php

/*
 * This file is part of litalino/mason.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Litalino\Mason\Api\Serializers;

use Flarum\Api\Serializer\AbstractSerializer;
use Litalino\Mason\Answer;
use Tobscure\JsonApi\Relationship;

class AnswerSerializer extends AbstractSerializer
{
    protected $type = 'mason-answers';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param Answer|array $model
     *
     * @return array
     */
    protected function getDefaultAttributes($model)
    {
        return $model->toArray();
    }

    /**
     * @param $model
     *
     * @return Relationship
     */
    public function field($model)
    {
        return $this->hasOne(
            $model,
            FieldSerializer::class,
            'field'
        );
    }
}
