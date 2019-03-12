<?php namespace Andosto\EventManager\Updates;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use October\Rain\Database\Updates\Seeder;
use Renatio\DynamicPDF\Models\Layout;
use Renatio\DynamicPDF\Models\Template;


class CreateGarPdf extends Seeder
{
   protected $layoutHtml = '<html>
    <head>
        <style type="text/css" media="screen">
            {{ css|raw }}
        </style>
    </head>
    <body style="background: url({{ background_img }}) top left no-repeat;">
        {{ content_html|raw }}
    </body>
</html>';

   protected $layoutCss = '@font-face {
    font-family: \'Open Sans\';
    src: url(\'plugins/renatio/dynamicpdf/assets/fonts/OpenSans-Regular.ttf\');
}

@font-face {
    font-family: \'Open Sans\';
    font-weight: bold;
    src: url(\'plugins/renatio/dynamicpdf/assets/fonts/OpenSans-Bold.ttf\');
}

@font-face {
    font-family: \'Open Sans\';
    font-style: italic;
    src: url(\'plugins/renatio/dynamicpdf/assets/fonts/OpenSans-Italic.ttf\');
}

@font-face {
    font-family: \'Open Sans\';
    font-style: italic;
    font-weight: bold;
    src: url(\'plugins/renatio/dynamicpdf/assets/fonts/OpenSans-BoldItalic.ttf\');
}

body {
    font-family: \'Open Sans\', sans-serif;
    font-size: 14px;
}
.page-break {
    page-break-after: always;
}';

   protected $templateHtml = '{{gar| raw}}';

   public function run() {
       $layout = Layout::where('code', '=', 'clear')->first();
       if (!$layout) {
           $layout = Layout::create([
               'code' => 'clear',
               'name' => 'Clear',
               'content_html' => $this->layoutHtml,
               'content_css' => $this->layoutCss
           ]);
       }
       $template = Template::where('code', '=', 'gar-confirm')->first();
       if (!$template) {
           $template = Template::create([
               'layout_id' => $layout->id,
               'code' => 'gar-confirm',
               'title' => 'GAR Confirmation',
               'content_html' => $this->templateHtml
           ]);
       }
   }
}
