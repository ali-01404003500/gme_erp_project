<?php

namespace App\Services\AccessControl;

use App\Models\AccessControl\CommercialInfo;
use App\Models\AccessControl\CompanyInfo;
use App\Models\AccessControl\GlobalSetting;
use App\Traits\S3FileHandler;

class GlobalSettingService
{
    use S3FileHandler;

    public function getAll(int $limit = 20) {
        return CompanyInfo::query()->paginate($limit);
    }
    
  

    public function update($id, $request)
    {
        $globalSetting = CompanyInfo::findOrFail($id);

        // if(isset($request->company_logo))
        // $request->company_logo = $this->uploadFile($request->company_logo, 'global_settings/company_logo');
        // if(isset($request->report_logo))
        // $request->report_logo = $this->uploadFile($request->report_logo, 'global_settings/report_logo');
        // if(isset($request->company_fav))
        // $request->company_fav = $this->uploadFile($request->company_fav, 'global_settings/company_fav');

        $companyInfo = $globalSetting->update([
            'company_name'      => $request->company_name, 
            'software_title'     => $request->software_title,
            'company_email'     => $request->company_email,
            'company_phone'     => $request->company_phone,
            'company_address'   => $request->company_address,
            'website'           => $request->website,
            'company_bio'       => $request->company_bio,
            'company_logo'      => $request->company_logo ?? null,
            'company_fav'       => $request->company_fav ?? null,
            'report_logo'       => $request->report_logo ?? null,
        ]);

        $commercialInfo = CommercialInfo::where('company_id', $id)->first();

        if($commercialInfo){
            $commercialInfo->update([
                'vat_percentage' => $request->vat_percentage,
                'ait_percentage' => $request->ait_percentage,
                'remarks' => $request->remarks,
            ]);
        }else{
            CommercialInfo::create([
                'company_id' => $id,
                'vat_percentage' => $request->vat_percentage,
                'ait_percentage' => $request->ait_percentage,
                'remarks' => $request->remarks,
            ]);
        }


        return $companyInfo;
    }

 
}

