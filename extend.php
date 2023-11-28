<?php

/*
 * This file is part of litalino/mason.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Litalino\Mason;

use Flarum\Api\Controller\CreateDiscussionController;
use Flarum\Api\Controller\ListDiscussionsController;
use Flarum\Api\Controller\ShowDiscussionController;
use Flarum\Api\Controller\ShowForumController;
use Flarum\Api\Controller\UpdateDiscussionController;
use Flarum\Api\Serializer\DiscussionSerializer;
use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Discussion\Discussion;
use Flarum\Discussion\Event\Saving;
use Flarum\Extend;
use Litalino\Mason\Api\Serializers\AnswerSerializer;
use Litalino\Mason\Api\Serializers\ByTagSerializer;
use Litalino\Mason\Api\Serializers\FieldSerializer;
use Litalino\Mason\Listeners\DiscussionSaving;

return [
    (new Extend\Frontend('forum'))
        ->css(__DIR__.'/resources/less/forum.less')
        ->js(__DIR__.'/js/dist/forum.js'),

    (new Extend\Frontend('admin'))
        ->css(__DIR__.'/resources/less/admin.less')
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Extend\Routes('api'))
        // Fields
        ->post('/litalino/mason/fields/order', 'litalino-mason.api.fields.order', Api\Controllers\FieldOrderController::class)
        ->get('/litalino/mason/fields', 'litalino-mason.api.fields.index', Api\Controllers\FieldIndexController::class)
        ->post('/litalino/mason/fields', 'litalino-mason.api.fields.store', Api\Controllers\FieldStoreController::class)
        ->patch('/litalino/mason/fields/{id:[0-9]+}', 'litalino-mason.api.fields.update', Api\Controllers\FieldUpdateController::class)
        ->delete('/litalino/mason/fields/{id:[0-9]+}', 'litalino-mason.api.fields.delete', Api\Controllers\FieldDeleteController::class)

        // Answers
        ->post('/litalino/mason/fields/{id:[0-9]+}/answers/order', 'litalino-mason.api.answers.order', Api\Controllers\AnswerOrderController::class)
        ->post('/litalino/mason/fields/{id:[0-9]+}/answers', 'litalino-mason.api.answers.create', Api\Controllers\AnswerStoreController::class)
        ->patch('/litalino/mason/answers/{id:[0-9]+}', 'litalino-mason.api.answers.update', Api\Controllers\AnswerUpdateController::class)
        ->delete('/litalino/mason/answers/{id:[0-9]+}', 'litalino-mason.api.answers.delete', Api\Controllers\AnswerDeleteController::class)

        // ByTag // will have to update regex to match names for patch and delete
        ->post('/litalino/mason/bytag/order', 'litalino-mason.api.bytag.order', Api\Controllers\ByTagOrderController::class)
        ->get('/litalino/mason/bytag', 'litalino-mason.api.bytag.index', Api\Controllers\ByTagIndexController::class)
        ->post('/litalino/mason/bytag', 'litalino-mason.api.bytag.store', Api\Controllers\ByTagStoreController::class)
        ->patch('/litalino/mason/bytag/{id:[0-9]+}', 'litalino-mason.api.bytag.update', Api\Controllers\ByTagUpdateController::class)
        ->delete('/litalino/mason/bytag/{id:[0-9]+}', 'litalino-mason.api.bytag.delete', Api\Controllers\ByTagDeleteController::class),

    new Extend\Locales(__DIR__.'/resources/locale'),

    (new Extend\ApiController(ShowForumController::class))
        ->addInclude('masonFields.suggestedAnswers')
        ->addInclude('masonByTags')
        ->prepareDataForSerialization(LoadForumFieldsRelationship::class),

    (new Extend\ApiSerializer(ForumSerializer::class))
        ->hasMany('masonFields', FieldSerializer::class)
        ->attributes(ForumAttributes::class),

    (new Extend\ApiSerializer(ForumSerializer::class))
        ->hasMany('masonByTags', ByTagSerializer::class)
        ->attributes(ForumAttributes::class),

    (new Extend\ApiController(ListDiscussionsController::class))
        ->addInclude('masonAnswers.field'),

    (new Extend\ApiController(ShowDiscussionController::class))
        ->addInclude('masonAnswers.field'),

    (new Extend\ApiController(CreateDiscussionController::class))
        ->addInclude('masonAnswers.field'),

    (new Extend\ApiController(UpdateDiscussionController::class))
        ->addInclude('masonAnswers.field'),

    (new Extend\ApiSerializer(DiscussionSerializer::class))
        ->hasMany('masonAnswers', AnswerSerializer::class)
        ->attributes(function (DiscussionSerializer $serializer, Discussion $discussion): array {
            $canSee = $serializer->getActor()->can('seeMasonAnswers', $discussion);

            if (!$canSee) {
                // Will cause a skip of the relationship retrieval
                $discussion->setRelation('masonAnswers', null);
            }

            return [
                'canSeeMasonAnswers'    => $canSee,
                'canUpdateMasonAnswers' => $serializer->getActor()->can('updateMasonAnswers', $discussion),
            ];
        }),

    (new Extend\Model(Discussion::class))
        ->relationship('masonAnswers', function (Discussion $discussion) {
            return $discussion->belongsToMany(Answer::class, 'fof_mason_discussion_answer', 'discussion_id', 'answer_id')
                ->withTimestamps()
                ->whereHas('field', function ($query) {
                    // Only load answers to fields that have not been deleted
                    $query->whereNull('deleted_at');
                });
        }),

    (new Extend\Event())
        ->listen(Saving::class, DiscussionSaving::class),

    (new Extend\Policy())
        ->modelPolicy(Answer::class, Access\AnswerPolicy::class)
        ->modelPolicy(Discussion::class, Access\DiscussionPolicy::class)
        ->modelPolicy(Field::class, Access\FieldPolicy::class),
];
