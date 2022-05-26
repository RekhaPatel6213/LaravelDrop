<?php

namespace Database\Seeders;

use App\Mail\DispensaryWelcomeMail;
use Illuminate\Database\Seeder;
use Spatie\MailTemplates\Models\MailTemplate;

class MailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(int $dispensaryId)
    {
        $allTemplates = [
            [
                'dispensary_id' => $dispensaryId,
                'mailable' => DispensaryWelcomeMail::class,
                'subject' => 'Welcome, {{ firstName }}',
                'html_template' => '<html>
                                <head>
                                </head>
                                <body>
                                <h1>Hello, {{ firstName }} {{ lastName }}</h1>
                                <div style=\'border: 5px outset red;background-color: lightblue;text-align: center;\'>
                                  <h2>Welcome to, {{ dispensaryName }}</h2>
                                  <p>Now, you are able to access all our products.</p>
                                </div>
                                <p>Thank you</p>
                                <p><i>Team, {{ dispensaryName }}</i></p>
                                </body>
                                </html>',
                'text_template' => 'Hello, {{ firstName }}!, Welcome to, {{ dispensaryName }}, Thank you',
            ],

        ];

        foreach ($allTemplates as $template) {
            MailTemplate::create($template);
        }

    }
}
