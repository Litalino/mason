import app from 'flarum/admin/app';
import saveSettings from 'flarum/common/utils/saveSettings';
import Component from 'flarum/common/Component';
import Select from 'flarum/common/components/Select';
import Switch from 'flarum/common/components/Switch';

export default class MasonSettings extends Component {
    oninit(vnode) {
        super.oninit(vnode);

        this.fieldsSectionTitle = app.data.settings['litalino-mason.fields-section-title'] || '';
        this.columnCount = app.data.settings['litalino-mason.column-count'] || 1;
        this.labelsAsPlaceholders = app.data.settings['litalino-mason.labels-as-placeholders'] > 0;
        this.fieldsInHero = app.data.settings['litalino-mason.fields-in-hero'] > 0;
        this.hideEmptyFieldsSection = app.data.settings['litalino-mason.hide-empty-fields-section'] > 0;
        this.tagsAsFields = app.data.settings['litalino-mason.tags-as-fields'] > 0;
        this.tagsFieldName = app.data.settings['litalino-mason.tags-field-name'] || '';

        this.columnOptions = {};

        for (let i = 1; i <= 3; i++) {
            this.columnOptions[i] = app.translator.trans('litalino-mason.admin.settings.n-columns', { count: i });
        }
    }

    view() {
        return (
            <div className="Mason-Container">
                <div className="Form-group">
                    <label>
                        {app.translator.trans('litalino-mason.admin.settings.fields-section-title')}
                        <input
                            className="FormControl"
                            value={this.fieldsSectionTitle}
                            placeholder={app.translator.trans('litalino-mason.admin.settings.fields-section-title-placeholder')}
                            onchange={(event) => {
                                this.updateSetting('fieldsSectionTitle', 'litalino-mason.fields-section-title', event.target.value);
                            }}
                        />
                    </label>
                    <div className="helpText">{app.translator.trans('litalino-mason.admin.settings.fields-section-title-help')}</div>
                </div>
                <div className="Form-group">
                    <label>
                        {app.translator.trans('litalino-mason.admin.settings.column-count')},
                        <Select
                            options={this.columnOptions}
                            value={this.columnCount}
                            onchange={this.updateSetting.bind(this, 'columnCount', 'litalino-mason.column-count')}
                        />
                    </label>
                </div>
                <div className="Form-group">
                    <label>
                        <Switch
                            state={this.labelsAsPlaceholders}
                            onchange={this.updateSetting.bind(this, 'labelsAsPlaceholders', 'litalino-mason.labels-as-placeholders')}
                        >
                            {app.translator.trans('litalino-mason.admin.settings.labels-as-placeholders')}
                        </Switch>
                    </label>
                    <div className="helpText">{app.translator.trans('litalino-mason.admin.settings.labels-as-placeholders-help')}</div>
                </div>
                <div className="Form-group">
                    <label>
                        <Switch state={this.fieldsInHero} onchange={this.updateSetting.bind(this, 'fieldsInHero', 'litalino-mason.fields-in-hero')}>
                            {app.translator.trans('litalino-mason.admin.settings.fields-in-hero')}
                        </Switch>
                    </label>
                </div>
                <div className="Form-group">
                    <label>
                        <Switch
                            state={this.hideEmptyFieldsSection}
                            onchange={this.updateSetting.bind(this, 'hideEmptyFieldsSection', 'litalino-mason.hide-empty-fields-section')}
                        >
                            {app.translator.trans('litalino-mason.admin.settings.hide-empty-fields-section')}
                        </Switch>
                    </label>
                    <div className="helpText">{app.translator.trans('litalino-mason.admin.settings.hide-empty-fields-section-help')}</div>
                </div>
                <div className="Form-group">
                    <label>
                        <Switch state={this.tagsAsFields} onchange={this.updateSetting.bind(this, 'tagsAsFields', 'litalino-mason.tags-as-fields')}>
                            {app.translator.trans('litalino-mason.admin.settings.tags-as-field')}
                        </Switch>
                    </label>
                    <div className="helpText">{app.translator.trans('litalino-mason.admin.settings.tags-as-field-help')}</div>
                </div>
                {this.tagsAsFields && (
                    <div className="Form-group">
                        <label>{app.translator.trans('litalino-mason.admin.settings.tags-field-name')}</label>
                        <input
                            className="FormControl"
                            value={this.tagsFieldName}
                            placeholder={app.translator.trans('litalino-mason.admin.settings.tags-field-name-placeholder')}
                            onchange={(event) => {
                                this.updateSetting('tagsFieldName', 'litalino-mason.tags-field-name', event.target.value);
                            }}
                        />
                    </div>
                )}
            </div>
        );
    }

    /**
     * Updates setting in database.
     * @param attribute
     * @param setting
     * @param value
     */
    updateSetting(attribute, setting, value) {
        saveSettings({
            [setting]: value,
        });

        this[attribute] = value;
    }
}
