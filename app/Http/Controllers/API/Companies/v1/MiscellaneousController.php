<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ModelHelper;
use App\Models\Companies\v1\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class MiscellaneousController extends Controller
{
    public function migration(Request $request)
    {
        $res = [];

        if (config('services.is_onpremise')) {
            Artisan::call('migrate', ['--path' => 'database/migration_company', '--database' => 'pgsql_companies']);
            $res[] = Artisan::output();
            Artisan::call('migrate', ['--path' => 'database/migration_company_alter', '--database' => 'pgsql_companies']);
            $res[] = Artisan::output();

            DB::connection('pgsql_companies')->commit();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Migrate Success',
            'data' => $res
        ]);
    }

    public function userSetting(Request $request)
    {
        $login_user = config('user_companies');
        unset($login_user['details']);

        $user_permission = Users::where('id', $login_user)->with(['roles.permissions'])->first();

        $permissions = [];

        foreach ($user_permission['roles'] as $row) {
            foreach ($row['permissions'] as $permission){
                $permissions[] = $permission->name;
            }
        }
        sort($permissions);
        $login_user['permissions'] = $permissions;

        return response()->json($login_user);
    }

    public function errorCheck(Request $request)
    {
        return $params;
    }

    public function adjustSequence()
    {
        ModelHelper::adjustSequencePostgreSql();
    }
}