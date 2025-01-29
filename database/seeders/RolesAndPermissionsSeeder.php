<?php

namespace Database\Seeders;

use App\Http\Middleware\SetTimezone;
use App\Models\StaffUser;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //-----------------------  Make Permissions -----------------------
        $permissions = [
            "manage-users",
            "manage-staff-users",
            "manage-brands",
            "manage-categories",
            "manage-products",
            "manage-discounts",
        ];

        foreach ($permissions as $permission) {
            if(!Permission::where("name", $permission)->exists())
            {
                Permission::create(["name" => $permission, "guard_name" => "staff_users"]);
            }
        }

        //-----------------------  Make Roles -----------------------
        // - First Role (Super Admin) -----
        $role = Role::create([
            "guard_name" => "staff_users",
            "name" => "super-admin"
        ]);

        // assign permissions to roles
        $role->syncPermissions(Permission::all());

        //  ----------------------- Create a new instance of StaffUser -----------------------
        $staffUser = new StaffUser();
        $staffUser->name = 'Noah';
        $staffUser->email = 'n@n.com';
        $staffUser->password = Hash::make('123456789');
        $staffUser->status = 1;
        $staffUser->created_at = now();
        $staffUser->updated_at = now();
        $staffUser->save();

        $staffUser->assignRole($role);
        $staffUser->givePermissionTo(Permission::all());
        // - Rest of Roles -----


        // $rolePermissions = [

        // ];

        // // create roles
        // foreach ($rolePermissions as $role => $permission) {
        //     $role = Role::create([
        //         'name' => $role,
        //         'guard_name' => 'staff_users'
        //     ]);

        //     // Assign permissions to roles
        //     $role->givePermissionTo($permission);
        // }
    }
}
