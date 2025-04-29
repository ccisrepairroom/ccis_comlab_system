<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Cookie;
use App\Models\Equipment;
use App\Models\Category;
use App\Models\Facility;




class RequestManagement {

    //add equipment to requestlist
    public static function addEquipmentToRequestList($equipment_id)
    {
        $requestlist_equipment = self::getRequestListEquipmentFromCookie();
    
        $existing_requestlist_equipment = null;
    
        foreach ($requestlist_equipment as $key => $equipment) {
            if ($equipment['equipment_id'] == $equipment_id) {
                $existing_requestlist_equipment = $key;
                break;
            }
        }
    
        if ($existing_requestlist_equipment !== null) {
            // Do nothing if the equipment is already in the request list
        } else {
            // Fetch equipment details from the database
            $equip = Equipment::with(['category', 'facility'])
                ->where('id', $equipment_id)
                ->first(['id', 'brand_name', 'serial_no', 'property_no', 'main_image', 'category_id', 'facility_id']);
            
            if ($equip) {
                $requestlist_equipment[] = [
                    'equipment_id' => $equipment_id,
                    'brand_name' => $equip->brand_name,
                    'serial_no' => $equip->serial_no,
                    'property_no' => $equip->property_no,
                    'main_image' => $equip->main_image ?? null,
                    'quantity' => 1, // Stays as 1, does not increase
                    'category_description' => $equip->category->description ?? 'N/A', 
                    'facility_name' => $equip->facility->name ?? 'N/A', 
                ];
            }
        }
    
    
        // Update the cookie with the modified request list
        self::addRequestListEquipmentToCookie($requestlist_equipment);

        return count($requestlist_equipment);
    }

    //remove equipment from requestlist
    static public function removeRequestListEquipment($equipment_id)
    {
        $requestlist_equipment = self::getRequestListEquipmentFromCookie();

        foreach ($requestlist_equipment as $key => $equipment) {
            if ($equipment['equipment_id'] == $equipment_id) {
                unset($requestlist_equipment[$key]);
            }
        }
        self::addRequestListEquipmentToCookie($requestlist_equipment);
        return $requestlist_equipment;
        
    }

    //add requestlist equipment to cookie
    public static function addRequestListEquipmentToCookie($requestlist_equipment)
    {
        Cookie::queue('requestlist_equipment', json_encode($requestlist_equipment), 60 * 24 * 30);
    }

    //clear requestlist equipment from cookie
    public static function clearRequestListEquipment()
    {
        Cookie::queue(Cookie::forget('requestlist_equipment'));
    }

    //get all requestlist equipment  from cookie
    public static function getRequestListEquipmentFromCookie()
    {
        $requestlist_equipment = json_decode(Cookie::get('requestlist_equipment'), true);
        if (!$requestlist_equipment) {
            $requestlist_equipment = [];
        }
        return $requestlist_equipment;
    }

    //increment equipment quantity
    public static function incrementQuantityToRequestList($equipment_id)
    {
    $requestlist_equipment = self::getRequestListEquipmentFromCookie();

    foreach ($requestlist_equipment as $key => $equipment) {
        if ($equipment['equipment_id'] == $equipment_id) {
            $requestlist_equipment[$key]['quantity']++;
            break; // Stop looping once the item is found
        }
    }

    self::addRequestListEquipmentToCookie($requestlist_equipment);
    return $requestlist_equipment;
    }

    //decrement equipment quantity
    public static function decrementQuantityToRequestList($equipment_id)
    {
    $requestlist_equipment = self::getRequestListEquipmentFromCookie();

    foreach ($requestlist_equipment as $key => $equipment) {
        if ($equipment['equipment_id'] == $equipment_id) {
            if ($requestlist_equipment[$key]['quantity'] > 1) {
                $requestlist_equipment[$key]['quantity']--;
            } else {
                // Remove item if quantity is 1 and user decrements
                unset($requestlist_equipment[$key]);
            }
            break; // Stop looping once the item is found
        }
    }

    // Save updated request list to cookie
    self::addRequestListEquipmentToCookie($requestlist_equipment);
    return $requestlist_equipment;
    }

   // Group equipment by category and get totals
    public static function calculateTotalByCategory(array $requestlist_equipment = null)
    {
        $requestlist_equipment = $requestlist_equipment ?? self::getRequestListEquipmentFromCookie();
        $category_totals = [];

        foreach ($requestlist_equipment as $equipment) {
            $category = $equipment['category_description'] ?? 'Uncategorized';
            if (!isset($category_totals[$category])) {
                $category_totals[$category] = 0;
            }
            $category_totals[$category] += $equipment['quantity'] ?? 1;
        }

        return $category_totals;
    }

    // Calculate total equipment
    public static function calculateTotalRequestedEquipment(array $requestlist_equipment = null)
    {
        $requestlist_equipment = $requestlist_equipment ?? self::getRequestListEquipmentFromCookie();

        return array_sum(array_column($requestlist_equipment, 'quantity'));
    }

    // Just in case you want to make this flexible too:
    public static function getRequestedEquipmentIds(array $requestlist_equipment = null)
    {
        $requestlist_equipment = $requestlist_equipment ?? self::getRequestListEquipmentFromCookie();

        return array_column($requestlist_equipment, 'equipment_id');
    }


}