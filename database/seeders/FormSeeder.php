<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        $form = Form::firstOrCreate(
            ['slug' => 'contacto'],
            [
                'name'               => 'Formulario de Contacto',
                'description'        => 'Formulario general de contacto del sitio.',
                'active'             => true,
                'send_notification'  => true,
                'notification_email' => null,
            ]
        );

        $fields = [
            ['name' => 'nombre',   'label' => 'Nombre',   'type' => 'text',     'placeholder' => 'Tu nombre completo', 'required' => true,  'visible' => true, 'sort_order' => 1],
            ['name' => 'email',    'label' => 'Email',    'type' => 'email',    'placeholder' => 'tu@email.com',       'required' => true,  'visible' => true, 'sort_order' => 2],
            ['name' => 'telefono', 'label' => 'Teléfono', 'type' => 'tel',      'placeholder' => '(249) 444-0000',     'required' => false, 'visible' => true, 'sort_order' => 3],
            ['name' => 'mensaje',  'label' => 'Mensaje',  'type' => 'textarea', 'placeholder' => 'Escribí tu consulta...', 'required' => true, 'visible' => true, 'sort_order' => 4],
        ];

        foreach ($fields as $field) {
            FormField::firstOrCreate(
                ['form_id' => $form->id, 'name' => $field['name']],
                $field
            );
        }
    }
}
