<?php

namespace App\Observers;

use App\Models\SuppliesAndMaterials;

class CriticalStockObserver
{
    /**
     * Handle the SuppliesAndMaterials "created" event.
     */
    public function created(SuppliesAndMaterials $suppliesAndMaterials): void
    {
        
    }

    /**
     * Handle the SuppliesAndMaterials "updated" event.
     */
    public function updated(SuppliesAndMaterials $suppliesAndMaterials): void
    {
        //
    }

    /**
     * Handle the SuppliesAndMaterials "deleted" event.
     */
    public function deleted(SuppliesAndMaterials $suppliesAndMaterials): void
    {
        //
    }

    /**
     * Handle the SuppliesAndMaterials "restored" event.
     */
    public function restored(SuppliesAndMaterials $suppliesAndMaterials): void
    {
        //
    }

    /**
     * Handle the SuppliesAndMaterials "force deleted" event.
     */
    public function forceDeleted(SuppliesAndMaterials $suppliesAndMaterials): void
    {
        //
    }
}
