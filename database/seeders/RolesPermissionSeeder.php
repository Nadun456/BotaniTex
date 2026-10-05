<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdmin = User::where('email', 'admin@admin.com')->first();

        $roles = [
            'Super Admin',
            'Property Group Admin',
            'Property Admin',
            'System Admin',
            'Admin',
            'Staff',
        ];

        foreach ($roles as $role) {
            $has_roles = Role::where(['name'=>$role])->first();
            if(!isset($has_roles)) {
                Role::create(['name' => $role]);
            }
        }

        $superAdmin->syncRoles('Super Admin');

        $permission_array = [
            ['section_name' => 'profile', 'name' => 'profile.updatePhoto'],
            ['section_name' => 'profile', 'name' => 'profile.updateInfo'],
            ['section_name' => 'profile', 'name' => 'profile.updatePassword'],
            ['section_name' => 'profile', 'name' => 'profile.2faAuth'],
            ['section_name' => 'profile', 'name' => 'profile.deactivate'],
            ['section_name' => 'profile', 'name' => 'profile.delete'],

            ['section_name' => 'dashboard', 'name' => 'property.view'],

            ['section_name' => 'admin-dashboard', 'name' => 'admin-dashboard.view'],
            ['section_name' => 'property-dashboard', 'name' => 'property-dashboard.view'],

            ['section_name' => 'media', 'name' => 'media.view'],
            ['section_name' => 'media', 'name' => 'media.create'],
            ['section_name' => 'media', 'name' => 'media.edit'],
            ['section_name' => 'media', 'name' => 'media.delete'],

            ['section_name' => 'backend-user', 'name' => 'backend-user.view'],
            ['section_name' => 'backend-user', 'name' => 'backend-user.create'],
            ['section_name' => 'backend-user', 'name' => 'backend-user.edit'],
            ['section_name' => 'backend-user', 'name' => 'backend-user.delete'],

            ['section_name' => 'roles-permissions', 'name' => 'roles-permissions.view'],
            ['section_name' => 'roles-permissions', 'name' => 'roles-permissions.create'],
            ['section_name' => 'roles-permissions', 'name' => 'roles-permissions.edit'],
            ['section_name' => 'roles-permissions', 'name' => 'roles-permissions.delete'],

            ['section_name' => 'system-setting', 'name' => 'system-setting.view'],
            ['section_name' => 'system-setting', 'name' => 'system-setting.create'],
            ['section_name' => 'system-setting', 'name' => 'system-setting.edit'],
            ['section_name' => 'system-setting', 'name' => 'system-setting.delete'],
            
            //vehicle cms
            // ['section_name' => 'vehicle_cms', 'name' => 'vehicle_cms.view'],
            // ['section_name' => 'vehicle_cms', 'name' => 'vehicle_cms.create'],
            // ['section_name' => 'vehicle_cms', 'name' => 'vehicle_cms.edit'],
            // ['section_name' => 'vehicle_cms', 'name' => 'vehicle_cms.delete'],

            //Vehicle type
            ['section_name' => 'vehicle_type', 'name' => 'vehicle_type.view'],
            ['section_name' => 'vehicle_type', 'name' => 'vehicle_type.create'],
            ['section_name' => 'vehicle_type', 'name' => 'vehicle_type.edit'],
            ['section_name' => 'vehicle_type', 'name' => 'vehicle_type.delete'],

            //vehicle_manufacture
            ['section_name' => 'vehicle_manufacture', 'name' => 'vehicle_manufacture.view'],
            ['section_name' => 'vehicle_manufacture', 'name' => 'vehicle_manufacture.create'],
            ['section_name' => 'vehicle_manufacture', 'name' => 'vehicle_manufacture.edit'],
            ['section_name' => 'vehicle_manufacture', 'name' => 'vehicle_manufacture.delete'],

            //vehicle_model
            ['section_name' => 'vehicle_model', 'name' => 'vehicle_model.view'],
            ['section_name' => 'vehicle_model', 'name' => 'vehicle_model.create'],
            ['section_name' => 'vehicle_model', 'name' => 'vehicle_model.edit'],
            ['section_name' => 'vehicle_model', 'name' => 'vehicle_model.delete'],

            //vehicle_color
            ['section_name' => 'vehicle_color', 'name' => 'vehicle_color.view'],
            ['section_name' => 'vehicle_color', 'name' => 'vehicle_color.create'],
            ['section_name' => 'vehicle_color', 'name' => 'vehicle_color.edit'],
            ['section_name' => 'vehicle_color', 'name' => 'vehicle_color.delete'],

            //vehicle_feature
            ['section_name' => 'vehicle_feature', 'name' => 'vehicle_feature.view'],
            ['section_name' => 'vehicle_feature', 'name' => 'vehicle_feature.create'],
            ['section_name' => 'vehicle_feature', 'name' => 'vehicle_feature.edit'],
            ['section_name' => 'vehicle_feature', 'name' => 'vehicle_feature.delete'],
            
            //vehicle
            ['section_name' => 'vehicle', 'name' => 'vehicle.view'],
            ['section_name' => 'vehicle', 'name' => 'vehicle.create'],
            ['section_name' => 'vehicle', 'name' => 'vehicle.edit'],
            ['section_name' => 'vehicle', 'name' => 'vehicle.delete'],

            //Vehicle testimonials
            ['section_name' => 'testimonials', 'name' => 'testimonials.view'],
            ['section_name' => 'testimonials', 'name' => 'testimonials.create'],
            ['section_name' => 'testimonials', 'name' => 'testimonials.edit'],
            ['section_name' => 'testimonials', 'name' => 'testimonials.delete'],

            //Newsletter
            ['section_name' => 'newsletter', 'name' => 'newsletter.view'],
            ['section_name' => 'newsletter', 'name' => 'newsletter.create'],
            ['section_name' => 'newsletter', 'name' => 'newsletter.edit'],
            ['section_name' => 'newsletter', 'name' => 'newsletter.delete'],

            //Homebanner
            ['section_name' => 'homebanner', 'name' => 'homebanner.view'],
            ['section_name' => 'homebanner', 'name' => 'homebanner.create'],
            ['section_name' => 'homebanner', 'name' => 'homebanner.edit'],
            ['section_name' => 'homebanner', 'name' => 'homebanner.delete'],

            //Advertisements
            ['section_name' => 'advertisements', 'name' => 'advertisements.view'],
            ['section_name' => 'advertisements', 'name' => 'advertisements.create'],
            ['section_name' => 'advertisements', 'name' => 'advertisements.edit'],
            ['section_name' => 'advertisements', 'name' => 'advertisements.delete'],

            //News And Events
            ['section_name' => 'events', 'name' => 'events.view'],
            ['section_name' => 'events', 'name' => 'events.create'],
            ['section_name' => 'events', 'name' => 'events.edit'],
            ['section_name' => 'events', 'name' => 'events.delete'],

            //Customer
            ['section_name' => 'customer', 'name' => 'customer.view'],
            ['section_name' => 'customer', 'name' => 'customer.create'],
            ['section_name' => 'customer', 'name' => 'customer.edit'],
            ['section_name' => 'customer', 'name' => 'customer.delete'],

            //Manufactures
            ['section_name' => 'manufactures', 'name' => 'manufactures.view'],
            ['section_name' => 'manufactures', 'name' => 'manufactures.create'],
            ['section_name' => 'manufactures', 'name' => 'manufactures.edit'],
            ['section_name' => 'manufactures', 'name' => 'manufactures.delete'],

            //Model
            ['section_name' => 'model', 'name' => 'model.view'],
            ['section_name' => 'model', 'name' => 'model.create'],
            ['section_name' => 'model', 'name' => 'model.edit'],
            ['section_name' => 'model', 'name' => 'model.delete'],

            //Colors
            ['section_name' => 'color', 'name' => 'color.view'],
            ['section_name' => 'color', 'name' => 'color.create'],
            ['section_name' => 'color', 'name' => 'color.edit'],
            ['section_name' => 'color', 'name' => 'color.delete'],

            //category
            ['section_name' => 'category', 'name' => 'category.view'],
            ['section_name' => 'category', 'name' => 'category.create'],
            ['section_name' => 'category', 'name' => 'category.edit'],
            ['section_name' => 'category', 'name' => 'category.delete'],

            //product
            ['section_name' => 'product', 'name' => 'product.view'],
            ['section_name' => 'product', 'name' => 'product.create'],
            ['section_name' => 'product', 'name' => 'product.edit'],
            ['section_name' => 'product', 'name' => 'product.delete'],
            
            //contact
            ['section_name' => 'contact', 'name' => 'contact.view'],
            ['section_name' => 'contact', 'name' => 'contact.create'],
            ['section_name' => 'contact', 'name' => 'contact.edit'],
            ['section_name' => 'contact', 'name' => 'contact.delete'],

        ];

        foreach ($permission_array as $permission) {

            $check_has_permission = Permission::where('name', $permission['name'])->first();
            if (!isset($check_has_permission)) {
                Permission::create($permission);
            }
        }
    }
}