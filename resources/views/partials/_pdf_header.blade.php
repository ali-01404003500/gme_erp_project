<div class="header" style="width: 100%; margin-bottom: 10px; position: relative; overflow: hidden;">
    <div style="display: flex; width:100%; transform:skewX(35deg); position: absolute;">
        <div
            style="height: 100px; width: 14%; border-top:4px solid rgb(0, 0, 179); border-right:4px solid rgb(0, 0, 179); ">
        </div>
        <div style="height: 100px; width: 86%; border-bottom:4px solid rgb(0, 0, 179); ">
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 com-logo">
            <img src="{{ $company_info->company_logo }}"
                alt="{{ $company_info->company_logo }}" style="max-width: 100px; margin-left: 50px;">
        </div>
        <div class="com-info col-md-9" style="display: flex; align-items: left; justify-content: left; text-align: left;">
            <div class="com" style="display: flex; margin-left: -100px; flex-direction: column;">
                <h1 width="200px">{{ $company_info->company_name }}</h1>
                <p width="50px" style="color:rgb(226, 35, 35); margin-left:5px;">
                    {{ $company_info->company_bio }}</p>
            </div>
        </div>
    </div>
</div>