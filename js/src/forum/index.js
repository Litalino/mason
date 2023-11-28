import app from 'flarum/forum/app';
import Model from 'flarum/common/Model';
import Discussion from 'flarum/common/models/Discussion';
import Forum from 'flarum/common/models/Forum';
import Answer from '@common/models/Answer';
import Field from '@common/models/Field';
import ByTag from '@common/models/ByTag';
import addComposerFields from './addComposerFields';
import addFieldUpdateControl from './addFieldUpdateControl';
import addFieldsOnDiscussionHero from './addFieldsOnDiscussionHero';
import addFieldsOnDiscussionPost from './addFieldsOnDiscussionPost';
import patchModelIdentifier from './patchModelIdentifier';

app.initializers.add('litalino-mason', (app) => {
    app.store.models['mason-fields'] = Field;
    app.store.models['mason-answers'] = Answer;
    app.store.models['mason-bytags'] = ByTag;

    Discussion.prototype.masonAnswers = Model.hasMany('masonAnswers');
    Discussion.prototype.canSeeMasonAnswers = Model.attribute('canSeeMasonAnswers');
    Discussion.prototype.canUpdateMasonAnswers = Model.attribute('canUpdateMasonAnswers');
    Forum.prototype.canFillMasonFields = Model.attribute('canFillMasonFields');

    addComposerFields();
    addFieldsOnDiscussionHero();
    addFieldsOnDiscussionPost();
    addFieldUpdateControl();
    patchModelIdentifier();
});
