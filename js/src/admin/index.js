import app from 'flarum/admin/app';

import Answer from '@common/models/Answer';
import Field from '@common/models/Field';
import ByTag from '@common/models/ByTag';
import MasonFieldsPage from './pages/MasonFieldsPage';

app.initializers.add('litalino-mason', () => {
    app.store.models['mason-fields'] = Field;
    app.store.models['mason-answers'] = Answer;
    app.store.models['mason-bytags'] = ByTag;

    app.extensionData
        .for('litalino-mason')
        .registerPage(MasonFieldsPage)
        .registerPermission(
            {
                icon: 'fas fa-eye',
                label: app.translator.trans('litalino-mason.admin.permissions.see-own-fields'),
                permission: 'litalino-mason.see-own-fields',
            },
            'view'
        )
        .registerPermission(
            {
                icon: 'fas fa-eye',
                label: app.translator.trans('litalino-mason.admin.permissions.see-other-fields'),
                permission: 'litalino-mason.see-other-fields',
                allowGuest: true,
            },
            'view'
        )
        .registerPermission(
            {
                icon: 'fas fa-tasks',
                label: app.translator.trans('litalino-mason.admin.permissions.fill-fields'),
                permission: 'litalino-mason.fill-fields',
            },
            'reply'
        )
        .registerPermission(
            {
                icon: 'fas fa-edit',
                label: app.translator.trans('litalino-mason.admin.permissions.update-own-fields'),
                permission: 'litalino-mason.update-own-fields',
            },
            'reply'
        )
        .registerPermission(
            {
                icon: 'fas fa-edit',
                label: app.translator.trans('litalino-mason.admin.permissions.update-other-fields'),
                permission: 'litalino-mason.update-other-fields',
                allowGuest: true,
            },
            'moderate'
        )
        .registerPermission(
            {
                icon: 'fas fa-forward',
                label: app.translator.trans('litalino-mason.admin.permissions.skip-required-fields'),
                permission: 'litalino-mason.skip-required-fields',
            },
            'moderate'
        );
});
