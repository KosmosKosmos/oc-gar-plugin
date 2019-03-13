<?php namespace KosmosKosmos\GAR\Controllers;

use Backend\Facades\Backend;
use Backend\Facades\BackendAuth;
use BackendMenu;
use Backend\Classes\Controller;
use Illuminate\Support\Facades\Log;
use KosmosKosmos\GAR\Models\Confirm;
use KosmosKosmos\GAR\Models\RoleInfo;

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
        if (array_key_exists('confirmed', $data) && in_array($data['confirmed'], [true, 'true', 1, '1'], true)) {
            $user = BackendAuth::getUser();
            $role = $user->role;
            $roleInfo = RoleInfo::where('role_id', '=', $role->id)->first();
            if ($roleInfo) {
                Confirm::create([
                    'confirmed' => true,
                    'confirmable_id' => $roleInfo->confirm_by_role ? $role->id : $user->id,
                    'confirmable_type' => $roleInfo->confirm_by_role ? get_class($role) : get_class($user),
                ]);

                return redirect(Backend::url('backend'));
            }
        }
    }

}
