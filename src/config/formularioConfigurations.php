<?php
        
class formularioConfigurations extends \classes\Classes\Options{
                
    protected $files   = array(
        
        'formulario/editor' => array(
            'title'        => 'Opções do Plugin de Texto',
            'descricao'    => 'Configure todas as opções a serem exibidas no plugin de texto do site',
            'grupo'        => 'Formulário',
            'type'         => 'resource', //config, plugin, jsplugin, template, resource
            'referencia'   => 'formulario/editor',
            'visibilidade' => 'webmaster', //'usuario', 'admin', 'webmaster'
            'configs'      => array(
                'FORMULARIO_EDITOR_DEFAULT' => array(
                    'name'          => 'FORMULARIO_EDITOR_DEFAULT',
                    'label'         => 'Plugin de formulário padrão',
                    'type'          => 'enum',//varchar, text, enum
                    'options'       => "'redactor' => 'redactor', 'tinymce' => 'Tiny MCE', 'niceditor' => 'Nice Editor'",
                    'default'       => 'redactor',
                    'description'   => 'Escolha qual o plugin de texto será utilizado no site',
                    'value'         => 'true',
                    'value_default' => 'true'
                ),
            ),
        ),
    );
}

?>