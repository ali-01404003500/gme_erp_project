<?php
namespace Database\Seeders;

use App\Models\Customer\Settings\PercentageType;
use Modules\Inventory\Models\Settings\Tag;
use Exception;
use Illuminate\Database\Seeder;

class ProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $codes = [
            "PDTG-001",
            "PDTG-002",
            "PDTG-003",
            "PDTG-004",
            "PDTG-005",
            "PDTG-006",
            "PDTG-007",
            "PDTG-008",
            "PDTG-009",
            "PDTG-010",
            "PDTG-020",
            "PDTG-021",
            "PDTG-022",
            "PDTG-023",
            "PDTG-024",
            "PDTG-025",
        ];
        $names = [
            "IC",
            "BC",
            "CC",
            "EL",
            "Others",
            "DIFF LYSE",
            "LH LYSE",
            "DILUENT",
            "AIKANG/ Easy Life Plus",
            "CC - 5 Part",
            "Accessories",
            "Machine",
            "Device/Strip",
            "Probe",
            "I-Chroma Machine",
            "Service"
        ];
    
        // Make sure both arrays have the same length
        if (count($codes) !== count($names)) {
            throw new Exception("Number of codes and names doesn't match");
        }
    
        foreach ($names as $key => $name) {
            $code = $codes[$key];
            $tag = Tag::withTrashed()->where('code', $code)->first();
        
            if ($tag) {
                // Check if the tag is soft deleted
                if ($tag->deleted_at) {
                    // Recreate the tag
                    $tag->restore(); // Restore the soft deleted tag
                    $tag->name = $name;
                    $tag->save();
                } else {
                    $tag->name = $name;
                    $tag->save();
                }
            } else {
                Tag::updateOrCreate([
                    'name' => $name,
                    'code' => $code,
                ]);
            }
        }
    }
    
}
