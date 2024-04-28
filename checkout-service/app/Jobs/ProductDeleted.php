<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repository\ProductRepository;
use Illuminate\Support\Facades\Log;

class ProductDeleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $product;
    /**
     * Create a new job instance.
     */
    public function __construct($product)
    {
        $this->product =  $product;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $productRepo    = new ProductRepository;
            $product = $productRepo->delete($this->product['id']);
            
            if ($product) {
                Log::info('Product deleted successfully.');
            } else {
                Log::error('Failed to delete product.');
            }
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
        }
    }
}
