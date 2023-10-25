<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Noorfarooqy\SalaamEsb\Models\BankBranches;

class BranchSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_branches = [
            ['code' => '000', 'name' => 'Bank HQ'],
            ['code' => '001', 'name' => 'Park Plaza'],
            ['code' => '002', 'name' => 'Kimathi Street'],
            ['code' => '003', 'name' => 'Eastleigh'],
        ];

        foreach ($default_branches as $key => $branch) {
            $exists = BankBranches::where('branch_code', $branch['code'])->exists();
            if (!$exists) {
                try {
                    $branch = BankBranches::create([
                        'branch_code' => $branch['code'],
                        'branch_name' => $branch['name'],
                        'is_seeded' => true,
                    ]);
                    echo "[*] Completed seeding for branch " . $branch['name'] . PHP_EOL;
                } catch (\Throwable$th) {
                    echo "[-] Failed to seed the branches " . PHP_EOL;
                    echo "[-] " . $th->getMessage() . PHP_EOL;
                }
            }
        }
    }
}
