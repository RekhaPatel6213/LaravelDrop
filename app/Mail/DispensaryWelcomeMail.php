<?php

namespace App\Mail;

use App\MailTemplates\DispensaryMailTemplate;
use App\Models\Admin\AdminUser;
use App\Models\Admin\Dispensary\Dispensary;
use Spatie\MailTemplates\TemplateMailable;

class DispensaryWelcomeMail extends TemplateMailable
{
    protected static $templateModelClass = DispensaryMailTemplate::class;

    /** @var string */
    public $firstName;
    public $lastName;
    public $email;
    public $dispensaryName;
    public $logoUrl;

    protected $dispensary;

    public function __construct(AdminUser $adminUser, Dispensary $dispensary)
    {
        $this->firstName = $adminUser->first_name;
        $this->lastName = $adminUser->last_name;
        $this->email = $adminUser->email;
        $this->dispensaryName = $dispensary->name;
        $this->logoUrl = $dispensary->getFirstMediaUrl();
        $this->dispensary = $dispensary;
    }

    public function getHtmlLayout(): string
    {
        return '{{{ body }}}';
    }

    public function getDispensaryId(): int
    {
        return $this->dispensary->id;
    }


    public function build()
    {
        $this->to($this->email);
    }
}
