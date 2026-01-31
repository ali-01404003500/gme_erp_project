<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\CRM\Models\Customer\Customer;

class CreateCustomerUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:users';

    /** 
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user accounts for all customers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to create user accounts for customers...');
        
        // Get all customers
        $customers = Customer::actived()->get();
        
        if ($customers->isEmpty()) {
            $this->info('No customers found.');
            return 0;
        }
        
        $this->info("Found {$customers->count()} customers.");
        
        $createdCount = 0;
        $failedCount = 0;
        
        foreach ($customers as $customer) {
            try {
       
                // Use the existing createUser method from the Customer model
                $user = $customer->createUser();
                
                if ($user) {
                    $this->info("Created user for customer: {$customer->company_name} username is {$user->name}");
                $roles = $user->roles()->pluck('name')->toArray();
                $this->info("User has roles: " . implode(', ', $roles));
                    $createdCount++;
                } else {
                    $this->error("Failed to create user for customer: {$customer->company_name}");
                    $failedCount++;
                }
            } catch (\Exception $e) {
                $this->error("Error creating user for customer {$customer->company_name}: " . $e->getMessage());
                $failedCount++;
            }
        }
        
        $this->info("Completed! Created {$createdCount} users, {$failedCount} failed.");
        
        return 0;
    }
}