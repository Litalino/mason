<?php

/*
 * This file is part of litalino/mason.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Litalino\Mason\Api\Controllers;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Http\RequestUtil;
use Litalino\Mason\Api\Serializers\FieldSerializer;
use Litalino\Mason\Repositories\AnswerRepository;
use Litalino\Mason\Repositories\FieldRepository;
use Litalino\Mason\Validators\OrderValidator;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class AnswerOrderController extends AbstractShowController
{
    public $serializer = FieldSerializer::class;

    public $include = [
        'allAnswers',
    ];

    protected $validator;
    protected $answers;
    protected $fields;

    public function __construct(OrderValidator $validator, AnswerRepository $answers, FieldRepository $fields)
    {
        $this->validator = $validator;
        $this->answers = $answers;
        $this->fields = $fields;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        RequestUtil::getActor($request)->assertAdmin();

        $attributes = $request->getParsedBody();

        $this->validator->assertValid($attributes);

        $order = Arr::get($attributes, 'sort');

        $this->answers->sorting($order);

        $fieldId = Arr::get($request->getQueryParams(), 'id');

        // Return updated sorting values
        // Return the field instead of individual answers as it's easier to update the store client-side
        return $this->fields->findOrFail($fieldId);
    }
}
