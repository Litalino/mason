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

use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Settings\SettingsRepositoryInterface;

class ForumAttributes
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function __invoke(ForumSerializer $serializer): array
    {
        $actor = $serializer->getActor();

        $canFill = $actor->can('litalino-mason.fill-fields');
        $canSeeSome = $actor->can('litalino-mason.see-other-fields') || $actor->can('litalino-mason.see-own-fields');

        $attributes = [
            'canFillMasonFields' => $canFill,
        ];

        if ($canFill || $canSeeSome) {
            $attributes['litalino-mason.fields-section-title'] = $this->settings->get('litalino-mason.fields-section-title', '');
            $attributes['litalino-mason.column-count'] = (int) $this->settings->get('litalino-mason.column-count', 1);
        }

        if ($canFill) {
            $attributes['litalino-mason.by-tag'] = (bool) $this->settings->get('litalino-mason.by-tag', false);
            $attributes['litalino-mason.labels-as-placeholders'] = (bool) $this->settings->get('litalino-mason.labels-as-placeholders', false);
            $attributes['litalino-mason.tags-as-fields'] = (bool) $this->settings->get('litalino-mason.tags-as-fields', false);
            $attributes['litalino-mason.tags-field-name'] = $this->settings->get('litalino-mason.tags-field-name', '');
        }

        if ($canSeeSome) {
            $attributes['litalino-mason.fields-in-hero'] = (bool) $this->settings->get('litalino-mason.fields-in-hero', false);
            $attributes['litalino-mason.hide-empty-fields-section'] = (bool) $this->settings->get('litalino-mason.hide-empty-fields-section', false);
        }

        return $attributes;
    }
}
