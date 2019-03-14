<?php namespace KosmosKosmos\GAR\Controllers;

use Backend\Facades\Backend;
use Backend\Facades\BackendAuth;
use Backend\Widgets\GARExport;
use BackendMenu;
use Backend\Classes\Controller;
use Carbon\Carbon;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use KosmosKosmos\GAR\Models\Confirm;
use KosmosKosmos\GAR\Models\GARSettings;
use KosmosKosmos\GAR\Models\RoleInfo;
use October\Rain\Support\Facades\Flash;
use Renatio\DynamicPDF\Classes\PDF;

/**
 * Confirms Back-end Controller
 */
class Confirms extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    protected $publicActions = ['index'];

    public function __construct()
    {
        parent::__construct();

        $this->layout = 'auth';
    }

    public function onConfirm() {
        $data = input();
        if (!array_key_exists('confirmed', $data) || in_array($data['confirmed'], [false, 'false', 0, '0'], true)) {
            Flash::error('Please confirm');
        } else if (!array_key_exists('firstname', $data) || $data['firstname'] == '') {
            Flash::error('First name is required');
        } else if (!array_key_exists('lastname', $data) || $data['lastname'] == '') {
            Flash::error('Last name is required');
        }
        if (in_array($data['confirmed'], [true, 'true', 1, '1'], true)) {
            $user = BackendAuth::getUser();
            $role = $user->role;
            $roleInfo = RoleInfo::where('role_id', '=', $role->id)->first();
            if ($roleInfo) {
                Confirm::create([
                    'confirmed' => true,
                    'confirmable_id' => $roleInfo->confirm_by_role ? $role->id : $user->id,
                    'confirmable_type' => $roleInfo->confirm_by_role ? get_class($role) : get_class($user),
                ]);

                if (!File::exists(storage_path('kosmoskosmos'))) {
                    File::makeDirectory(storage_path('kosmoskosmos'));
                }

                if (!File::exists(storage_path('kosmoskosmos/signed'))) {
                    File::makeDirectory(storage_path('kosmoskosmos/signed'));
                }

                $filename = 'signed_'.($roleInfo->confirm_by_role ? 'role_'.$role->id : 'user_'.$user->id).'.pdf';
                $gar = GARSettings::get('gar_text');

                PDF::loadTemplate('gar-confirm',
                        ['gar' => $gar,
                         'ip' => Request::ip(),
                         'date' => Carbon::now()->format('d.m.Y H:i:s'),
                         'roleInfo' => $roleInfo,
                         'url' => URL::to('/'),
                         'firstname' => $user->first_name,
                         'lastname' => $user->last_name,
                         'firstname_signed' => $data['firstname'],
                         'lastname_signed' => $data['lastname']
                        ]
                )->save(storage_path('kosmoskosmos/signed/'.$filename));

                return redirect(Backend::url('backend'));
            }
        }
    }

    public function export() {
        $files = glob(storage_path('kosmoskosmos/signed/*'));
        $filename = 'gar-'.Carbon::today()->format('Y-m-d').'.zip';
        Zipper::make(storage_path('kosmoskosmos/'.$filename))->add($files)->close();

        return response()->download(storage_path('kosmoskosmos/'.$filename))->deleteFileAfterSend(true);
    }

}
