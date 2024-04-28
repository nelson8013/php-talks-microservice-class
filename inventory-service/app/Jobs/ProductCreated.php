<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repository\ProductRepository;
use Illuminate\Support\Facades\Log;

class ProductCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $product;
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
            $productRepo = new ProductRepository;
            $productRepo->save([
                'id'          => $this->product['id'],
                'name'        => $this->product['name'],
                'description' => $this->product['description'],
                'price'       => $this->product['price'],
                'created_at'  => $this->product['created_at'],
                'updated_at'  => $this->product['updated_at']
                
            ]);

            Log::info("Product Created");
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
        }
    }
}
