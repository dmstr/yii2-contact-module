## Configuring template

Navigate to `/contact/crud/contact-template` to create a new form template.

| property | required | description                                                                                                                                                                 |
| ---------|----------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Name | yes | a unique name, e.g. 'reservation'                                                                                                                                           |
| From Email | yes | valid email used as 'From:' header                                                                                                                                          |
| To Email | yes | one or more valid email addresses (comma separated) used as 'To:' header                                                                                                    |
| Reply To Email | no | optional, valid email. Use this if you want to set 'Reply-To:' header to a fixed address for ALL mails. If set 'Reply to Schema Property' will be irgnored!                 |
| Reply to Schema Property | no | can be used to define which property from schema should be used (if valid mail!) as 'Reply-To:' header. If 'Reply To Email' is set to a fixed address, this will be ignored |
| Return Path | no | if set and is a valid email, this will be used as 'Return-Path:' header where bounce Mails will be send to. Handle with care                                                |
| Captcha | no | this tells the contact module that a captcha will be used and that it has to validated against it (sets model scenario to captcha). Captcha widget is required in twig!     |
| Form Schema | yes | json-schema used to build a form with `dmstr/jsoneditor/JsonEditorWidget` (For more information about schema see examples on: https://github.com/json-editor/json-editor)   |

## Upgrade hints:

- If the form was build with version <= 1.0.0 there was the convention, that property reply_to in your schema was used as 'Reply-To:' header in message.
- this "magick" is removed! You must now set 'Reply to Schema Property' to 'reply_to' to get the same behavior!



## Twig templates (Views)

Each form needs 2 Twig templates. Navigate to `/prototype/twig/index` to create them:

* `contact:FORM_NAME`: template in which the form will be rendered
* `contact:FORM_NAME:send`: will be rendered as "thank you page" after message has been send

While `FORM_NAME` must be replaced with your template name

* The form can be seen at `/contact/default/?schema=FORM_NAME`
* The "thank you page" can be seen at `/contact/default/done?schema=FORM_NAME`

### Form Twig layout

```twig
{{ use('dmstr/jsoneditor/JsonEditorWidget') }}
{{ use('yii/widgets/ActiveForm') }}

<div class="container">
    <div class="row">
        <div class="col-md-12">
            {% set form = active_form_begin({
                'id': 'contact-form',
                'action' : '',
                'options': {
                }
            }) %}
    
            {{ form.errorSummary(model) | raw }}
    
            {{ json_editor_widget_widget({
                'model': model,
                'attribute': 'json',
                'options': {
                    'id': 'contact-json'
                },
                'clientOptions': {
                    'theme': 'bootstrap3',
                    'disable_collapse': true,
                    'disable_edit_json': true,
                    'disable_properties': true,
                    'no_additional_properties': true,
                    'show_errors': 'interaction'
                },
                'schema': schema,
            }) }}

            <button type="submit" class="btn btn-primary">{{ t('twig-widget', 'Send') }}</button>
            
            {{ active_form_end() }}
        </div>
    </div>
</div>
```

### Form Twig layout with captcha (requires `captcha` activate in the contact-template)

```twig
{{ use('dmstr/jsoneditor/JsonEditorWidget') }}
{{ use('yii/widgets/ActiveForm') }}
{{ use('yii/captcha/Captcha') }}

<div class="container">
    <div class="row">
        <div class="col-md-12">
            {% set form = active_form_begin({
                'id': 'contact-form',
                'action' : '',
                'options': {
                }
            }) %}
    
            {{ form.errorSummary(model) | raw }}
    
            {{ json_editor_widget_widget({
                'model': model,
                'attribute': 'json',
                'options': {
                    'id': 'contact-json'
                },
                'clientOptions': {
                    'theme': 'bootstrap3',
                    'disable_collapse': true,
                    'disable_edit_json': true,
                    'disable_properties': true,
                    'no_additional_properties': true,
                    'show_errors': 'interaction'
                },
                'schema': schema,
            }) }}

            {{ Captcha_widget({
                model: model,
                attribute: 'captcha',
                captchaAction: '/contact/default/captcha'
            }) }}

            <button type="submit" class="btn btn-primary">{{ t('twig-widget', 'Send') }}</button>
            
            {{ active_form_end() }}
        </div>
    </div>
</div>
```


* The background and foreground colors of the captcha can be defined in the settings module under the `captcha` section.
It uses a particular format for colors but the last 6 characters follow the css hex color code(eg 0xff0000 is red and 0x00ff00 is green)

### "Thank you page" Twig layout

```twig
<div class="alert alert-success">{{ t('twig-widget', 'Thank you for your message') }}</div>
```

### Contact form widgets twig example

```twig
{{ use('dmstr/modules/contact/widgets/ContactForm') }}

{{ contact_form_widget({schemaName: schemaName}) }}
```

### Settings schema

```json
{
  "title": " ",
  "type": "object",
  "format": "grid",
  "properties": {
    "Company": {
      "type": "string",
      "minLength": 3,
      "title": "Firma",
      "propertyOrder": 5,
      "options": {
        "grid_columns": 12
      }
    },
    "Title": {
      "type": "string",
      "title": "Anrede",
      "minLength": 1,
      "propertyOrder": 10,
      "enum": [
        "Herr",
        "Frau"
      ],
      "options": {
        "enum_titles": [
          "Herr",
          "Frau"
        ],
        "grid_columns": 2
      }
    },
    "LastName": {
      "type": "string",
      "minLength": 3,
      "title": "Name",
      "propertyOrder": 20,
      "options": {
        "grid_columns": 5
      }
    },
    "SurName": {
      "type": "string",
      "minLength": 3,
      "title": "Vorname",
      "propertyOrder": 30,
      "options": {
        "grid_columns": 5
      }
    },
    "Zip": {
      "type": "string",
      "minLength": 5,
      "title": "PLZ",
      "propertyOrder": 40,
      "options": {
        "grid_columns": 2
      }
    },
    "Location": {
      "type": "string",
      "minLength": 3,
      "title": "Ort",
      "propertyOrder": 50,
      "options": {
        "grid_columns": 5
      }
    },
    "Street": {
      "type": "string",
      "title": "Straße/Hausnr.",
      "minLength": 5,
      "propertyOrder": 60,
      "options": {
        "grid_columns": 5
      }
    },
    "Phone": {
      "type": "string",
      "title": "Telefon",
      "minLength": 6,
      "propertyOrder": 60,
      "options": {
        "grid_columns": 5
      }
    },
    "Fax": {
      "type": "string",
      "title": "Fax",
      "propertyOrder": 60,
      "options": {
        "grid_columns": 5
      }
    },
    "Email": {
      "type": "string",
      "format": "email",
      "minLength": 1,
      "title": "E-Mail Adresse",
      "propertyOrder": 70,
      "options": {
        "grid_columns": 6
      }
    },
    "Internet": {
      "type": "string",
      "title": "Internet",
      "propertyOrder": 70,
      "options": {
        "grid_columns": 6
      }
    },
    "Message": {
      "type": "string",
      "format": "textarea",
      "title": "Nachricht (stichwortartig)",
      "propertyOrder": 90,
      "options": {
        "grid_columns": 12
      }
    }
  }
}
```

## Export

To enable the export feature add *kartik\grid\Module* to your project modules

