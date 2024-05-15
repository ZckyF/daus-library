<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make super admin
        $super_admin = User::factory()->create([
            'username' => 'admin_hebat',
            'password' => 'password123',
            'is_actived' => 1, 
            'avatar_name' => 'default.jpg',
            'employee_id' => 1,
        ]);
        // Make admin
        $admin = User::factory()->create([
            'username' => 'admin_hitam',
            'password' => 'password123', 
            'is_actived' => 1, 
            'avatar_name' => 'default.jpg',
            'employee_id' => 2,
        ]);
        // Make staff
        $staff = User::factory()->create([
            'username' => 'staff_hitam',
            'password' => 'password123', 
            'is_actived' => 1, 
            'avatar_name' => 'default.jpg',
            'employee_id' => 3,
        ]);
        // Make staff
        $librarian = User::factory()->create([
            'username' => 'librarian123',
            'password' => 'password123', 
            'is_actived' => 1, 
            'avatar_name' => 'default.jpg',
            'employee_id' => 4,
        ]);

        // Make 50 users
        // $dataUsers = User::factory(10)->create();

        // Role
        $adminRole = Role::create(['name' => 'admin']); 
        $superAdminRole = Role::create(['name' => 'super_admin']); 
        $staffServiceRole = Role::create(['name' => 'staff_service']); 
        $librarianRole = Role::create(['name' => 'librarian']); 

        // Assign Permissions to Roles
        $basePermissions= [
            'book.view_any',
            'book.view',

            'book_category.view_any',
            'book_category.view',

            'bookshelf.view_any',
            'bookshelf.view',

            'user.view',
            'user.update',

            'member.view_any',
            'member.view',
        ];
        
        $staffServicePermissions = [
            'borrowing_book.view_any',
            'borrowing_book.create',
            'borrowing_book.view',
            'borrowing_book.update',
            'borrowing_book.delete',

            'fine.view_any',
            'fine.create',
            'fine.view',
            'fine.update',
            'fine.delete',
        ];

        $librarianPermissions = [
            'book.create',
            'book.update',
            'book.delete',

            'book_category.create',
            'book_category.update',
            'book_category.delete',

            'bookshelf.create',
            'bookshelf.update',
            'bookshelf.delete',
        ];

        $adminPermissions= [
            'employee.view_any',
            'employee.create',
            'employee.view',
            'employee.update',
            'employee.delete',
          
            'member.create',
            'member.update',
            'member.delete',

            'user.view_any',
            'user.create',
            'user.delete',
        ];

        // Permession combined
        $permissionsArrays = [
            $basePermissions,
            $staffServicePermissions,
            $librarianPermissions,
            $adminPermissions,
        ];

        // looping all permissions to create
        foreach ($permissionsArrays as $permissions) {
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission]);
            }
        }
        // Assign Permissions to Staff Service Role
        $staffServiceRole->syncPermissions(array_merge($staffServicePermissions, $basePermissions));

        // Assign Permissions to Librarian Role
        $librarianRole->syncPermissions(array_merge($librarianPermissions, $basePermissions));

        // Assign Permissions to Admin Role
        $adminRole->syncPermissions(array_merge($adminPermissions, $staffServicePermissions, $librarianPermissions, $basePermissions));




        // Assign Role to user
        $admin->assignRole('admin');
        $staff->assignRole('staff_service');
        $super_admin->assignRole('super_admin');
        $librarian->assignRole('librarian');

        // foreach ($dataUsers as $user) {
        //     // $roles = [$adminRole,$staffServiceRole,$librarianRole];
        //     $randomRole = array_rand([$adminRole,$staffServiceRole,$librarianRole]);
        //     $user->assignRole($randomRole);
        // }
    }   
}
