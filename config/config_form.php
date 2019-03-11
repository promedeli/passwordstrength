<?php

$formFields[0]['form'] = [
    'legend' => [
        'title' => $this->l('Password strength meter settings'),
    ],
    'input' => [
        [
            'type' => 'switch',
            'label' => $this->l('Display text under password field'),
            'name' => 'display_text',
            'values' => [
                [
                    'id' => 'active_on',
                    'value' => 1,
                    'label' => $this->l('Enabled')
                ],
                [
                    'id' => 'active_off',
                    'value' => 0,
                    'label' => $this->l('Disabled')
                ]
            ],
        ],

        [
            'type' => 'text',
            'label' => $this->l('Text displayed under meter'),
            'desc' => $this->l('Enter %s as placeholder for password strength level text'),
            'name' => 'text',
            'size' => 20,
            'required' => true
        ],


        [
            'type' => 'text',
            'label' => $this->l('Level 0 word'),
            'name' => 's0',
            'size' => 20,
            'required' => true
        ],

        [
            'type' => 'text',
            'label' => $this->l('Level 1 word'),
            'name' => 's1',
            'size' => 20,
            'required' => true
        ],
        [
            'type' => 'text',
            'label' => $this->l('Level 2 word'),
            'name' => 's2',
            'size' => 20,
            'required' => true
        ],
        [
            'type' => 'text',
            'label' => $this->l('Level 3 word'),
            'name' => 's3',
            'size' => 20,
            'required' => true
        ],
        [
            'type' => 'text',
            'label' => $this->l('Level 4 word'),
            'name' => 's4',
            'size' => 20,
            'required' => true
        ],

        [
            'type' => 'color',
            'label' => $this->l('Color 1'),
            'name' => 'color1',
            'lang' => false,
            'data-hex' => true,
            'required' => true
        ],

        [
            'type' => 'color',
            'label' => $this->l('Color 2'),
            'name' => 'color2',
            'lang' => false,
            'data-hex' => true,
            'required' => true
        ],

        [
            'type' => 'color',
            'label' => $this->l('Color 3'),
            'name' => 'color3',
            'lang' => false,
            'data-hex' => true,
            'required' => true
        ],

        [
            'type' => 'color',
            'label' => $this->l('Color 4'),
            'name' => 'color4',
            'lang' => false,
            'data-hex' => true,
            'required' => true
        ],
    ],
    'submit' => [
        'title' => $this->l('Save'),
        'class' => 'btn btn-default pull-right'
    ]
];
