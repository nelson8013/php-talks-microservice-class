<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repository\InventoryRepository;
use Illuminate\Support\Facades\Log;

class InventoryCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $inventory){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            Log::info("Attempting to create the inventory");
            Log::info("INVENTORY ARRAY",$this->inventory);
            $inventoryRepo = new InventoryRepository;
            $inventoryRepo->save([
                'product_id'  => $this->inventory['product_id'],
                'quantity'    => $this->inventory['quantity'],
                'is_available'=> $this->inventory['is_available'],
                'created_at'  => $this->inventory['created_at'],
                'updated_at'  => $this->inventory['updated_at'],
                'id'          => $this->inventory['id'],
                
            ]);

            Log::info("Incentory Created");
        } catch (\Exception $e) {
            Log::error('Error creating inventory: ' . $e->getMessage());
        }
    }
}
