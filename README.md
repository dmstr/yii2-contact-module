


### Examples

Twig layout

```
{{ use('beowulfenator/JsonEditor') }}

<div class="container">
    
    <h1>Contact</h1>
    
    {% if success %}
    
        <div class="alert alert-success">
            Your message has been sent. Thank you!
        </div>
    
    {% else %}
    
        {{ use('yii/widgets/ActiveForm') }}    
        {% set form = active_form_begin({
            'id': 'contact-form',
            'action' : '',
            'options': {
            }
        }) %}
    
        {{ this.registerJs('JSONEditor.plugins.selectize.enable = true;') }}
        {{ json_editor_widget_widget(
            {
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
            }
        ) }}   
    
        {{ html.submitButton('Send', {
            'class': 'btn btn-primary',
        }) | raw }}
               
        {{ form.errorSummary(model) | raw }}
        
    {{ active_form_end() }}
    
    {% endif %}

</div>
```

Settings schema

*TBD*


### Giiant CRUDs

    yii giiant-batch \
        --tables=core_dmstr_contact_log \
        --tablePrefix=core_dmstr \
        --modelNamespace=hrzg\\contact\\models \
        --modelQueryNamespace=hrzg\\contact\\models\\query \
        --crudViewPath=@hrzg/contact/views/crud \
        --crudControllerNamespace=hrzg\\contact\\controllers\\crud \
        --crudSearchModelNamespace=hrzg\\contact\\models\\search
        