 <form action="{{ route('crm.brokers.update', $broker->id) }}" method="POST" enctype="multipart/form-data">
     @csrf
     @method('PUT')
     <div class="row">
         <div class="form-group">
             <div class="row">
                 <div class="col-sm-5" style="text-align: right">
                     <label class="col-sm-12 control-label"> Commission Type </label>
                 </div>
                 <div class="col-sm-7">

                     <div class="col-sm-8 col-sm-8 @error('commission_type') has-error @enderror">
                         <label>
                             <input name="commission_type" type="radio" value="0" class="ace"
                                 {{ old('commission_type', $broker->commission_type) == '0' ? 'checked' : '' }}>
                             <span class="lbl"> N/A</span>
                         </label>
                         <label>
                             <input name="commission_type" type="radio" value="1" class="ace"
                                 {{ old('commission_type', $broker->commission_type) == '1' ? 'checked' : '' }}>
                             <span class="lbl"> Percentage</span>
                         </label>
                         <label>
                             <input name="commission_type" type="radio" value="2" class="ace"
                                 {{ old('commission_type', $broker->commission_type) == '2' ? 'checked' : '' }}>
                             <span class="lbl"> Fixed</span>
                         </label>

                         @error('commission_type')
                             <span class="text-danger">
                                 {{ $message }}
                             </span>
                         @enderror
                     </div>
                 </div>
             </div>
         </div>
         <div class="form-group" id="percentage" style="display: none;">
             <table class="table table-bordered percentage-table">
                 <thead>
                     <th>Percentage Type</th>
                     <th>Percentage %</th>
                     <th>
                         <div class="btn-group btn-corner">
                             <button class="btn btn-success btn-xs add-row" onclick="addPercentageRow()" type="button">
                                 <i class="fa fa-plus"></i>
                             </button>
                         </div>
                     </th>
                 </thead>
                 <tbody>
                     @if ($broker->brokerCommission->count() > 0)
                         @foreach ($broker->brokerCommission as $key => $item)
                             <tr>
                                 <td>
                                     <select name="percentage_type[]" class="form-control"
                                         onchange="getPercentage(this)">
                                         <option value="">Select Type</option>
                                         @foreach ($percentageTypes as $percentageType)
                                             <option value="{{ $percentageType->id }}"
                                                 {{ $item->percentage_type == $percentageType->id ? 'selected' : '' }}>
                                                 {{ $percentageType->name }}</option>
                                         @endforeach
                                     </select>
                                 </td>
                                 <td>
                                     <input type="text" class="form-control input-sm" name="percentage[]"
                                         value="{{ $item->percentage }}" placeholder="percentage">
                                 </td>

                                 <td>
                                     <div class="btn-group btn-corner">
                                         <button class="btn btn-danger btn-xs" onclick="deletePercentageRow(this)"
                                             type="button">
                                             <i class="fa fa-trash"></i>
                                         </button>
                                     </div>
                                 </td>
                             </tr>
                         @endforeach
                     @else
                         <tr>
                             <td>
                                 <select name="percentage_type[]" class="form-control" onchange="getPercentage(this)">
                                     <option value="">Select Type</option>
                                     @foreach ($percentageTypes as $percentageType)
                                         <option value="{{ $percentageType->id }}">
                                             {{ $percentageType->name }}</option>
                                     @endforeach
                                 </select>
                             </td>
                             <td>
                                 <input type="text" class="form-control input-sm" name="percentage[]" value=""
                                     placeholder="percentage">
                             </td>

                             <td>
                                 <div class="btn-group btn-corner">
                                     <button class="btn btn-danger btn-xs" onclick="deletePercentageRow(this)"
                                         type="button">
                                     </button>
                                 </div>
                             </td>
                         </tr>
                     @endif
                 </tbody>
             </table>
         </div>
         <div class="form-group" id="fixed" style="display: none;">
             <div class="row">
                 @if ($broker->brokerCommission->count() > 0)
                     <div class="col-md-6">
                         <div class="form-group mb-25">

                             <label for="Fixed" class="color-dark fs-14 fw-500 align-center">Fixed
                                 Type</label>
                             <select class="form-control ih-medium ip-gray radius-xs b-light px-15" name="fixed_type"
                                 id="type">
                                 <option value="">Choose Type</option>
                                 <option @if ($broker->brokerCommission->first()->fixed_type == 1) selected @endif value="1">Invoice Wise
                                 </option>
                                 <option @if ($broker->brokerCommission->first()->fixed_type == 2) selected @endif value="2">Monthly
                                 </option>
                                 <option @if ($broker->brokerCommission->first()->fixed_type == 3) selected @endif value="3">Yearly
                                 </option>
                                 <option @if ($broker->brokerCommission->first()->fixed_type == 4) selected @endif value="4">Daily</option>
                                 <option @if ($broker->brokerCommission->first()->fixed_type == 5) selected @endif value="5">Festival
                                 </option>
                             </select>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="form-group mb-25">

                             <label for="Fixed" class="color-dark fs-14 fw-500 align-center">Amount</label>
                             <input type="number" class="form-control" name="fixed"
                                 value="{{ $broker->brokerCommission->first()->fixed }}" placeholder="Fixed">
                         </div>
                     </div>
                 @else
                     <div class="col-md-6">
                         <div class="form-group mb-25">

                             <label for="Fixed" class="color-dark fs-14 fw-500 align-center">Fixed
                                 Type</label>
                             <select class="form-control ih-medium ip-gray radius-xs b-light px-15" name="fixed_type"
                                 id="type">
                                 <option value="">Choose Type</option>
                                 <option value="1">Invoice Wise</option>
                                 <option value="2">Monthly</option>
                                 <option value="3">Yearly</option>
                                 <option value="4">Daily</option>
                                 <option value="5">Festival</option>
                             </select>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="form-group mb-25">

                             <label for="Fixed" class="color-dark fs-14 fw-500 align-center">Amount</label>
                             <input type="number" class="form-control" name="fixed" value=""
                                 placeholder="Fixed">
                         </div>
                     </div>
                 @endif
             </div>
         </div>
     </div>
     <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
         <button type="submit"
             class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Update</button>
     </div>
 </form>
 <script>
     $(document).ready(function() {

         changeCommissionType($('input[name="commission_type"]:checked'));

         $('input[name="commission_type"]').change(function() {
             console.log(this.value);
             if ($(this).val() == '1') {
                 $('#percentage').show(); // Show the percentage options if "Percentage" is selected
                 $('#fixed').hide(); // Hide the fixed options if "Percentage" is selected
             } else if ($(this).val() == '2') {
                 $('#percentage').hide(); // Hide the percentage options if "Fixed" is selected
                 $('#fixed').show(); // Show the fixed options if "Fixed" is selected
             } else {
                 $('#percentage')
                     .hide(); // Hide the options if neither "Percentage" nor "Fixed" is selected
                 $('#fixed').hide(); // Hide the options if neither "Percentage" nor "Fixed" is selected
             }
         });
     });

     function changeCommissionType(elem) {
         console.log("Load data", elem.val());
         if ($(elem).val() == '1') {
             $('#percentage').show(); // Show the percentage options if "Percentage" is selected
             $('#fixed').hide(); // Hide the fixed options if "Percentage" is selected
         } else if ($(elem).val() == '2') {
             $('#percentage').hide(); // Hide the percentage options if "Fixed" is selected
             $('#fixed').show(); // Show the fixed options if "Fixed" is selected
         } else {
             $('#percentage').hide(); // Hide the options if neither "Percentage" nor "Fixed" is selected
             $('#fixed').hide(); // Hide the options if neither "Percentage" nor "Fixed" is selected
         }
     }
 </script>


 </script>
 <script>
     $('.datePicker').datepicker({
         format: 'yyyy-mm-dd',
         autoclose: true
     });
 </script>

 <script>
     function addPercentageRow() {
         var table = $(".percentage-table tbody tr:last");
         var table2 = table.clone().find('select option:selected').removeAttr('selected').end();
         table2.find('input').val('').end().insertAfter(table);
     }

     function deletePercentageRow(object) {

         var table = $(".percentage-table tbody tr")

         if (table.length > 1) {
             $(object).closest('tr').remove()
         }
     }
 </script>


 <script>
     var selectedPercentageIds = []; // Array to store selected percentage type IDs

     function getPercentage(selectElement) {
         var percentageId = selectElement.value;
         if (percentageId === "") {
             // If no option is selected, do nothing
             return;
         }
         if (selectedPercentageIds.includes(percentageId)) {
             showToast('warning', 'You have already selected this Percentage Type.');
             // Reset the select element to default value
             selectElement.value = "";
             return;
         }
         // Add the selected percentage type ID to the array
         selectedPercentageIds.push(percentageId);
     }

     function showToast(type, message) {
         // Display toast message
         if (type === 'warning') {
             toastr.warning(message);
         } else if (type === 'error') {
             toastr.error(message);
         }
     }
 </script>
