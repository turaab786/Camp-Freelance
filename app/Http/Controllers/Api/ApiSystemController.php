<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Model\User\UserAccount;
use App\Model\User\UserDetails;

use App\Model\App\AppSetting;
use App\Model\Shared\MembershipPlan;


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ApiSystemController extends Controller
{
    public function InitAppDefaultUsersSetup()
    {
        // Setup App Settings (auto review request/gigs etc)
        AppSetting::create([
            'auto_review_requests' => true,
            'auto_review_gigs' => true
        ]);

        // Add Basic Membership Plans
        // Basic Seller User Plan
        MembershipPlan::create([
            'title' => 'basic seller plan',
            'description' => 'basic free membership plan for seller users.',
            'price' => 0,
            'can_offer_requests' => true,
            'bids_allowed' => 10,
            'commission_per_order' => 20,
            'can_post_request' => false,
            'post_premium_requests' => false,
            'show_primium_request' => false,
            'can_add_gigs' => true,
            'plan_type' => 'seller'
        ]);
        // Basic Buyer User Plan
        MembershipPlan::create([
            'title' => 'basic buyer plan',
            'description' => 'basic free membership plan for buyer users.',
            'price' => 0,
            'can_offer_requests' => false,
            'bids_allowed' => 0,
            'commission_per_order' => 0,
            'can_post_request' => true,
            'post_premium_requests' => false,
            'show_primium_request' => false,
            'can_add_gigs' => false,
            'plan_type' => 'buyer'
        ]);

        // Creating Admin
        $admin = User::create([
            'name' => 'admin',
            'email' => 'a@p.c',
            'phone_number' => '03000000001',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'seller_plan_id' => 1,
            'buyer_plan_id' => 2
        ]);
        UserDetails::create([
            'user_id' => $admin->id
        ]);
        UserAccount::create([
            'user_id' => $admin->id
        ]);

        // Creating buyer
        $buyer = User::create([
            'name' => 'buyer',
            'email' => 'b@p.c',
            'phone_number' => '03000000002',
            'password' => Hash::make('123456'),
            'role' => 'buyer',
            'seller_plan_id' => 1,
            'buyer_plan_id' => 2
        ]);
        UserDetails::create([
            'user_id' => $buyer->id
        ]);
        UserAccount::create([
            'user_id' => $buyer->id
        ]);

        // Creating seller
        $seller = User::create([
            'name' => 'seller',
            'email' => 's@p.c',
            'phone_number' => '03000000003',
            'password' => Hash::make('123456'),
            'role' => 'seller',
            'seller_plan_id' => 1,
            'buyer_plan_id' => 2
        ]);
        UserDetails::create([
            'user_id' => $seller->id
        ]);
        UserAccount::create([
            'user_id' => $seller->id
        ]);

        $ahsan1 = User::create([
            'name' => 'ahsan1',
            'email' => 'student@pnyexam.com',
            'phone_number' => '03000000004',
            'password' => Hash::make('123456'),
            'role' => 'seller',
            'seller_plan_id' => 1,
            'buyer_plan_id' => 2
        ]);
        UserDetails::create([
            'user_id' => $ahsan1->id
        ]);
        UserAccount::create([
            'user_id' => $ahsan1->id
        ]);

        $ahsan2 = User::create([
            'name' => 'ahsan2',
            'email' => 'student@pnyexam.com1',
            'phone_number' => '03000000005',
            'password' => Hash::make('123456'),
            'role' => 'seller',
            'seller_plan_id' => 1,
            'buyer_plan_id' => 2
        ]);
        UserDetails::create([
            'user_id' => $ahsan2->id
        ]);
        UserAccount::create([
            'user_id' => $ahsan2->id
        ]);

        // Roles
        $admin_role = Role::create(['name' => 'admin']);
        $buyer_role = Role::create(['name' => 'buyer']);
        $seller_role = Role::create(['name' => 'seller']);

        // Permissions
        $app_user_p = Permission::create(['name' => 'app_user']);
        $view_dashboard_p = Permission::create(['name' => 'view_dashboard']);
        $add_new_user_p = Permission::create(['name' => 'add_new_user']);

        // Giving Permissions to Roles
        // Admin Role
        $admin_role->givePermissionTo($app_user_p);
        $admin_role->givePermissionTo($view_dashboard_p);
        $admin_role->givePermissionTo($add_new_user_p);

        // Buyer Role
        $buyer_role->givePermissionTo($app_user_p);
        $buyer_role->givePermissionTo($view_dashboard_p);
        
        // Seller Role
        $seller_role->givePermissionTo($app_user_p);
        $seller_role->givePermissionTo($view_dashboard_p);

        // Assigning Roles To Users
        $admin->assignRole($admin_role);
        $buyer->assignRole($buyer_role);
        $seller->assignRole($seller_role);
        $ahsan1->assignRole($seller_role);
        $ahsan2->assignRole($seller_role);


        return "All Done Users Created and Roles Assigned With respective Permissions";
    }
}
