<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Define all permissions
        $permissions = [
            // Dashboard & Access
            'view_dashboard',
            'access_platform',

            // Users Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'manage_user_roles',

            // Managers/Directors Management
            'view_managers',
            'create_managers',
            'edit_managers',
            'delete_managers',

            // Supervisors Management
            'view_supervisors',
            'create_supervisors',
            'edit_supervisors',
            'delete_supervisors',

            // Controllers Management
            'view_controllers',
            'create_controllers',
            'edit_controllers',
            'delete_controllers',

            // Agents Management
            'view_agents',
            'create_agents',
            'edit_agents',
            'delete_agents',
            'manage_agent_status',

            // Agencies Management
            'view_agencies',
            'create_agencies',
            'edit_agencies',
            'delete_agencies',

            // Clients Management
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',
            'manage_client_status',

            // Assignments/Missions Management
            'view_assignments',
            'create_assignments',
            'edit_assignments',
            'delete_assignments',
            'assign_agents',
            'validate_assignments',

            // Shifts Management
            'view_shifts',
            'create_shifts',
            'edit_shifts',
            'delete_shifts',

            // Documents Management
            'view_documents',
            'create_documents',
            'edit_documents',
            'delete_documents',
            'upload_documents',

            // Vehicles Management
            'view_vehicles',
            'create_vehicles',
            'edit_vehicles',
            'delete_vehicles',

            // Reports & Analytics
            'view_reports',
            'create_reports',
            'export_reports',
            'view_analytics',

            // Tenant Management (Super Admin Only)
            'view_tenants',
            'create_tenants',
            'edit_tenants',
            'delete_tenants',
            'manage_tenant_users',
            'manage_tenant_settings',

            // System Settings
            'view_settings',
            'edit_settings',
            'view_audit_logs',

            // Payments & Billing
            'view_payments',
            'create_invoices',
            'manage_billing',

            // Personal Dashboard
            'view_personal_dashboard',
            'update_personal_profile',
            'view_my_assignments',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // Define roles and their permissions
        $roles = [
            // System-wide role
            'super_admin' => [
                'view_dashboard',
                'access_platform',
                'view_users',
                'create_users',
                'edit_users',
                'delete_users',
                'manage_user_roles',
                'view_managers',
                'create_managers',
                'edit_managers',
                'delete_managers',
                'view_supervisors',
                'create_supervisors',
                'edit_supervisors',
                'delete_supervisors',
                'view_controllers',
                'create_controllers',
                'edit_controllers',
                'delete_controllers',
                'view_agents',
                'create_agents',
                'edit_agents',
                'delete_agents',
                'manage_agent_status',
                'view_agencies',
                'create_agencies',
                'edit_agencies',
                'delete_agencies',
                'view_clients',
                'create_clients',
                'edit_clients',
                'delete_clients',
                'manage_client_status',
                'view_assignments',
                'create_assignments',
                'edit_assignments',
                'delete_assignments',
                'assign_agents',
                'validate_assignments',
                'view_shifts',
                'create_shifts',
                'edit_shifts',
                'delete_shifts',
                'view_documents',
                'create_documents',
                'edit_documents',
                'delete_documents',
                'upload_documents',
                'view_vehicles',
                'create_vehicles',
                'edit_vehicles',
                'delete_vehicles',
                'view_reports',
                'create_reports',
                'export_reports',
                'view_analytics',
                'view_tenants',
                'create_tenants',
                'edit_tenants',
                'delete_tenants',
                'manage_tenant_users',
                'manage_tenant_settings',
                'view_settings',
                'edit_settings',
                'view_audit_logs',
                'view_payments',
                'create_invoices',
                'manage_billing',
            ],

            // Tenant-specific roles
            'general_director' => [
                'view_dashboard',
                'access_platform',
                'view_users',
                'create_users',
                'edit_users',
                'delete_users',
                'view_managers',
                'create_managers',
                'edit_managers',
                'delete_managers',
                'view_supervisors',
                'create_supervisors',
                'edit_supervisors',
                'delete_supervisors',
                'view_controllers',
                'create_controllers',
                'edit_controllers',
                'delete_controllers',
                'view_agents',
                'create_agents',
                'edit_agents',
                'delete_agents',
                'manage_agent_status',
                'view_agencies',
                'create_agencies',
                'edit_agencies',
                'delete_agencies',
                'view_clients',
                'create_clients',
                'edit_clients',
                'delete_clients',
                'manage_client_status',
                'view_assignments',
                'create_assignments',
                'edit_assignments',
                'delete_assignments',
                'assign_agents',
                'validate_assignments',
                'view_shifts',
                'create_shifts',
                'edit_shifts',
                'delete_shifts',
                'view_documents',
                'create_documents',
                'edit_documents',
                'delete_documents',
                'upload_documents',
                'view_vehicles',
                'create_vehicles',
                'edit_vehicles',
                'delete_vehicles',
                'view_reports',
                'create_reports',
                'export_reports',
                'view_analytics',
                'view_settings',
                'edit_settings',
                'view_audit_logs',
                'view_payments',
                'create_invoices',
                'manage_billing',
            ],

            'deputy_director' => [
                'view_dashboard',
                'access_platform',
                'view_users',
                'create_users',
                'edit_users',
                'view_managers',
                'view_supervisors',
                'create_supervisors',
                'edit_supervisors',
                'view_controllers',
                'create_controllers',
                'edit_controllers',
                'view_agents',
                'create_agents',
                'edit_agents',
                'manage_agent_status',
                'view_agencies',
                'view_clients',
                'create_clients',
                'edit_clients',
                'manage_client_status',
                'view_assignments',
                'create_assignments',
                'edit_assignments',
                'assign_agents',
                'validate_assignments',
                'view_shifts',
                'create_shifts',
                'edit_shifts',
                'view_documents',
                'create_documents',
                'edit_documents',
                'upload_documents',
                'view_vehicles',
                'view_reports',
                'create_reports',
                'export_reports',
                'view_analytics',
                'view_audit_logs',
            ],

            'operations_director' => [
                'view_dashboard',
                'access_platform',
                'view_agents',
                'create_agents',
                'edit_agents',
                'manage_agent_status',
                'view_agencies',
                'view_clients',
                'view_assignments',
                'create_assignments',
                'edit_assignments',
                'assign_agents',
                'validate_assignments',
                'view_shifts',
                'create_shifts',
                'edit_shifts',
                'view_supervisors',
                'view_controllers',
                'view_documents',
                'create_documents',
                'edit_documents',
                'upload_documents',
                'view_vehicles',
                'view_reports',
                'create_reports',
                'export_reports',
                'view_analytics',
            ],

            'supervisor' => [
                'view_dashboard',
                'access_platform',
                'view_agents',
                'view_assignments',
                'view_shifts',
                'view_documents',
                'upload_documents',
                'view_my_assignments',
                'view_analytics',
                'update_personal_profile',
            ],

            'controller' => [
                'view_dashboard',
                'access_platform',
                'view_agents',
                'view_assignments',
                'view_shifts',
                'validate_assignments',
                'view_documents',
                'upload_documents',
                'view_reports',
                'export_reports',
                'view_analytics',
                'update_personal_profile',
            ],

            'agent' => [
                'view_dashboard',
                'view_personal_dashboard',
                'access_platform',
                'view_my_assignments',
                'view_shifts',
                'upload_documents',
                'update_personal_profile',
            ],

            'client_individual' => [
                'access_platform',
                'view_dashboard',
                'view_assignments',
                'view_documents',
                'view_reports',
            ],

            'client_company' => [
                'access_platform',
                'view_dashboard',
                'view_assignments',
                'view_documents',
                'view_reports',
                'export_reports',
                'view_analytics',
                'view_payments',
            ],
        ];

        // Create roles
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web']
            );

            // Sync permissions to role
            $permissionIds = Permission::whereIn('name', $rolePermissions)
                ->where('guard_name', 'web')
                ->pluck('id')
                ->toArray();

            $role->syncPermissions($permissionIds);
        }
    }
}
