<?php namespace Andosto\EventManager\Updates;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use October\Rain\Database\Updates\Seeder;


class CreateGarFile extends Seeder
{
   public function run() {
       if (!File::exists(storage_path('kosmoskosmos'))) {
           File::makeDirectory(storage_path('kosmoskosmos'));
       }

       if (!File::exists(storage_path('kosmoskosmos/gar.html'))) {
           File::put(storage_path('kosmoskosmos/gar.html'),
                   '<h1>GAR ist gar nicht gar....</h1>'.PHP_EOL.
'<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>'.PHP_EOL.
'<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi.</p>'.PHP_EOL.
'<p>Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar,</p>'.PHP_EOL.
'<a href="http://google.com">Visit Google!</a>'
           );
       }
   }
}
