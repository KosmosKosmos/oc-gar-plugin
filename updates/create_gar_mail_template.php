<?php namespace Andosto\EventManager\Updates;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use October\Rain\Database\Updates\Seeder;
use Renatio\DynamicPDF\Models\Layout;
use Renatio\DynamicPDF\Models\Template;
use System\Models\MailLayout;
use System\Models\MailTemplate;


class CreateGarMailTemplate extends Seeder
{
   public function run() {
       $mailLayout = MailLayout::where('code', '=', 'default')->first();
       if ($mailLayout) {
           $template = MailTemplate::where('code', '=', 'kosmoskosmos.gar::mail.gar')->first();
           if (!$template) {
               $template = new MailTemplate();
               $template->code = 'kosmoskosmos.gar::mail.gar';
               $template->description = 'GAR confirmation E-Mail';
               $template->layout_id = $mailLayout->id;
               $template->subject = 'GAR Confirmation';
               $template->content_html = "{{mail_text | raw}}";
               $template->is_custom = true;
               $template->save();
           }
       }
   }
}
