<?php
return [
    'crud' => [
        'view' => [
            //Crud
            'index'                   => 'crud::index',
            'edit'                    => 'crud::edit',
            'errorGeneric'            => 'errors::generic',
            //To component vue
            'backendIndex'            => 'backend::index',
            //Layouts
            'layoutApp'               => 'layouts.app',
            'layoutSectionContent'    => 'content',
            'layoutSectionHead'       => 'head',
            'layoutSectionJs'         => 'js',
            'layoutSectionPageTitle'  => 'pageTitle',
            'layoutSectionBreadcrumb' => 'breadCrump'
        ],
    ],
    'dataTable' => [
        'lengthMenu' => [10, 25, 50, 100, 200],
    ],
];