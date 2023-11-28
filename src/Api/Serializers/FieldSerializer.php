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
use Litalino\Mason\Field;
use Litalino\Mason\Repositories\AnswerRepository;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Relationship;

class FieldSerializer extends AbstractSerializer
{
    protected $type = 'mason-fields';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param Field|array $model
     *
     * @return array
     */
    protected function getDefaultAttributes($model)
    {
        return $model->toArray();
    }

    /**
     * @param Field $model
     *
     * @return Relationship
     */
    public function suggestedAnswers($model)
    {
        /**
         * @var AnswerRepository $answers
         */
        $answers = resolve(AnswerRepository::class);

        return new Relationship(new Collection($answers->suggested($model), resolve(AnswerSerializer::class)));
    }

    /**
     * @param Field $model
     *
     * @return Relationship|null
     */
    public function allAnswers($model)
    {
        $actor = $this->getActor();

        if (!$actor || !$actor->isAdmin()) {
            return null;
        }

        /**
         * @var AnswerRepository $answers
         */
        $answers = resolve(AnswerRepository::class);

        return new Relationship(new Collection($answers->all($model), resolve(AnswerSerializer::class)));
    }
}
