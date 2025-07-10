Formularios
===========

ADPerfilBundle define múltiples formularios que extienden `AbstractType` y encapsulan
la lógica de creación de entidades como `Permiso`, `Perfil`, `Menú`, `Reporte`, etc.

Ubicación: `Form/Type/`

Ejemplos de formularios incluidos
----------------------------------

- `PermisosPerfilFormType`
- `PerfilXPermisoType`
- `MenuFormType`
- `ReporteLoadEstaticoFormType`
- `CsvPermisosType`
- `ArchivoType`

Ejemplo de uso en un controlador
--------------------------------

.. code-block:: php

    $form = $this->createForm(PermisosPerfilFormType::class, $perfil);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($perfil);
        $em->flush();
    }

Validación de formularios
--------------------------

Cada tipo puede incorporar validaciones via:

- `@Assert` en la entidad
- `->add(...)` con `constraints` directamente en el formulario

.. code-block:: php

    ->add('nombre', TextType::class, [
        'constraints' => [
            new NotBlank(),
            new Length(['max' => 100]),
        ],
    ])

Formularios en tests funcionales
--------------------------------

Para testear formularios, se recomienda usar `WebTestCase` y `client->request()` con datos válidos:

.. code-block:: php

    $client = static::createClient();
    $crawler = $client->request('POST', '/formulario', [
        'form[nombre]' => 'Mi nombre',
    ]);

    $this->assertTrue($client->getResponse()->isSuccessful());

Formularios con archivos
------------------------

Para campos `FileType`, es necesario simular la subida usando `UploadedFile`:

.. code-block:: php

    $file = new UploadedFile('/path/to/fake.pdf', 'fake.pdf', 'application/pdf', null, true);
    $client->request('POST', '/subida', [], ['form' => ['file' => $file]]);
