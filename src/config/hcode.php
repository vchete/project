<?php
return [
    'crud' => [
        'view' => [
            'index'        => 'crud::index',
            'edit'         => 'crud::edit',
            'errorGeneric' => 'errors::generic',
            'backendIndex' => 'backend::index' //component vue
        ],
    ],
    'dataTable' => [
        'lengthMenu' => [10, 25, 50, 100, 200],
    ],
];