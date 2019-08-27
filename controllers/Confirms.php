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
use Illuminate\Support\Facades\Mail;
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

    protected $publicActions = ['confirm'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('KosmosKosmos.GAR', 'gar', 'confirms');
    }

    public function confirm () {
        $this->layout = 'auth';
    }
    public function onConfirm() {
        $data = input();
        if (!array_key_exists('confirmed', $data) || in_array($data['confirmed'], [false, 'false', 0, '0'], true)) {
            Flash::error(trans('kosmoskosmos.gar::lang.form.please_confirm'));
        } else if (!array_key_exists('firstname', $data) || $data['firstname'] == '') {
            Flash::error(trans('kosmoskosmos.gar::lang.form.firstname_required'));
        } else if (!array_key_exists('lastname', $data) || $data['lastname'] == '') {
            Flash::error(trans('kosmoskosmos.gar::lang.form.lastname_required'));
        } else if (in_array($data['confirmed'], [true, 'true', 1, '1'], true)) {
            $user = BackendAuth::getUser();
            $role = $user->role;
            $roleInfo = RoleInfo::where('role_id', '=', $role->id)->first();
            if ($roleInfo) {
                if (!File::exists(storage_path('kosmoskosmos'))) {
                    File::makeDirectory(storage_path('kosmoskosmos'));
                }

                if (!File::exists(storage_path('kosmoskosmos/signed'))) {
                    File::makeDirectory(storage_path('kosmoskosmos/signed'));
                }

                $filename = 'signed_'.($roleInfo->is_confirmed_by_role ? 'role_'.$role->id : 'user_'.$user->id).'.pdf';
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

                Confirm::create([
                        'is_confirmed' => true,
                        'confirmable_id' => $roleInfo->is_confirmed_by_role ? $role->id : $user->id,
                        'confirmable_type' => $roleInfo->is_confirmed_by_role ? get_class($role) : get_class($user),
                        'file' => $filename
                ]);

                if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    Mail::queue('kosmoskosmos.gar::mail.gar', [
                            'mail_text' => GARSettings::get('gar_mail_text'),
                            'gar_subject' => GARSettings::get('gar_mail_subject', 'GAR Confirmation')
                    ], function ($message) use ($user, $filename) {
                        $message->to($user->email);

                        if (GARSettings::get('gar_cc_mail') && filter_var(GARSettings::get('gar_cc_mail'), FILTER_VALIDATE_EMAIL)) {
                            $message->cc(GARSettings::get('gar_cc_mail'));
                        }
                        $message->attach(storage_path('kosmoskosmos/signed/'.$filename), ['as' => 'confirmation.pdf', 'mime' => 'application/pdf']);
                    });
                }
                return redirect(Backend::url('backend'));
            }
        }
    }

    public function onExport() {
        $data = input();

        $filename = 'signed-'.Carbon::today()->format('Y-m-d').'.zip';
        $password = str_random(6);

        if (array_key_exists('checked', $data)) {
            $files = Confirm::whereIn('id', $data['checked'])->get()->lists('file');
            $files = array_map(function ($a) {
                return storage_path('kosmoskosmos/signed/'.$a);
                },
            $files);
            $files = implode(' ', $files);
        } else {
            $files = storage_path('kosmoskosmos/signed/*');
        }

        exec('zip -P '.$password.' -j '.storage_path('kosmoskosmos/'.$filename).' '.$files);
        if (!File::exists(storage_path('kosmoskosmos/'.$filename))) {
            throw new \Exception('Cannot create zip file');
        }

        return ['file' => $filename, 'password' => $password];
    }

    public function download($file) {
        if (File::exists(storage_path('kosmoskosmos/'.$file))) {
            return response()->download(storage_path('kosmoskosmos/'.$file))->deleteFileAfterSend(true);
        }
    }

}
