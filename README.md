
## Configuration

Each form has a unique name, e.g. 'reservation'.

Each Form has to be defined by a couple of settings in the section 'contact'

settings:

|Name                |Type   |Required   |Default        |
|--------------------|-------|-----------|---------------|
|formname.schema     |json   |yes        |-              |
|formname.toEmail    |string |yes        |-              |
|formname.fromEmail  |string |yes        |-              |
|formname.subject    |string |no         |Contact Form   |
|formname.confirmMail|string |no         |-              |
|formname.returnPath |string |no         |-              |

conventions:
* if you have a property reply_to in your schema and send valid email address as value, this will be used as Reply-To: header in message
* if you have a property reply_to AND formname.confirmMail setting is set, this will be used to send additional confirmation mail to the user (be careful not to generate spam!)
* you can define a hidden field for reply_to in your schema which get the value from another field (e.g. email). see example below
* if formname.returnPath setting is set, this will be used as ReturnPath: and Envelope From Header for the Mail. The address has to be a valid mail address where e.g. bounce Mail will be send to.

Each form needs 2 Twig templates:
* `contact:formname` (template in which the form will be rendered)
* `contact:formname:send` (will be rendered as "thank you page" after message has been send)

For more information about schema see examples on: https://github.com/jdorn/json-editor 

## Examples

### Twig layout (

Form:
```twig
{{ use('dmstr/jsoneditor/JsonEditorWidget') }}
{{ use ('hrzg/widget/widgets') }}
{{ use('yii/widgets/ActiveForm') }}

{{ cell_widget({id: 'top'}) }}

<div class="row">
    <div class="col-md-12">
        {% set form = active_form_begin({
            'id': 'contact-form',
            'action' : '',
            'options': {
            }
        }) %}

        {% set script %}
            JSONEditor.defaults.language = "de";
            JSONEditor.defaults.languages.de = {
            error_minLength: "Muss mindestens \{\{\{0\}\} Zeichen enthalten.",
            error_notset: "Muss gesetzt sein",
            error_notempty: "Pflichtfeld"
            };
        {% endset %}
        {{ this.registerJs(script) }}
        {{ form.errorSummary(model) | raw }}
        {{ this.registerJs('JSONEditor.plugins.selectize.enable = true;') }}


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
                'show_errors': 'always'
            },
            'schema': schema,
        }) }}

        <button type="submit" class="btn btn-primary">{{ t('twig-widget', 'Send') }}</button>

        {{ active_form_end() }}
    </div>
</div>

{{ cell_widget({id: 'bottom'}) }}
```

Done:
```twig
{{ use ('hrzg/widget/widgets') }}
{{ cell_widget({id: 'top'}) }}

<div class="alert alert-success">{{ t('twig-widget', 'Thank you for your message') }}</div>

{{ cell_widget({id: 'bottom'}) }}
```

### Settings schema

```json
{
  "title": " ",
  "type": "object",
  "format": "grid",
  "properties": {
    "reply_to": {
      "type": "string",
      "template": "{{email}}",
      "options": {
        "hidden": true
      },
      "watch": {
        "email": "Email"
      }
    },
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


## Giiant CRUDs

```bash
yii giiant-batch \
        --tables=app_dmstr_contact_template,app_dmstr_contact_log \
        --tablePrefix=app_dmstr_ \
        --modelNamespace=dmstr\\modules\\contact\\models \
        --modelQueryNamespace=dmstr\\modules\\contact\\models\\query \
        --crudViewPath=@dmstr/modules/contact/views/crud \
        --crudControllerNamespace=dmstr\\modules\\contact\\controllers\\crud \
        --crudSearchModelNamespace=dmstr\\modules\\contact\\models\\search \
        --useTimestampBehavior=0 \
        --interactive=0
        
```
