<?php
namespace App\MailTemplates;

use App\Models\Admin\Dispensary\Dispensary;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Mail\Mailable;
use Spatie\MailTemplates\Interfaces\MailTemplateInterface;
use Spatie\MailTemplates\Models\MailTemplate;

class DispensaryMailTemplate extends MailTemplate implements MailTemplateInterface
{
    protected $table = 'mail_templates';

    public function dispensary(): BelongsTo
    {
        return $this->belongsTo(Dispensary::class);
    }

    public function scopeForMailable(Builder $query, Mailable $mailable): Builder
    {
        return $query
            ->where('mailable', get_class($mailable))
            ->where('dispensary_id', $mailable->getDispensaryId());
    }

    public function getHtmlLayout(): string
    {
        return '{{{ body }}}';
    }
}
