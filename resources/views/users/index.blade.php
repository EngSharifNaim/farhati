@extends('layouts.app')
@section('content')
    <div class="main-container sidebar-closed sbar-open" id="container">

        <div class="overlay show"></div>
        <div class="cs-overlay"></div>
        <div class="search-overlay"></div>
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="page-header">
                    <div class="page-title">
                        <h3>قائمة الاشتراكات</h3>
                    </div>
                </div>
                <div class="row" id="cancel-row">
                    <div id="buttonsOutline" class="col-lg-12 layout-spacing">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                            </div>
                            <div class="widget-content widget-content-area text-center split-buttons">

                                <button class="btn btn-outline-primary mb-2" data-toggle="modal" data-target="#add_account">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                    اضافة اشتراك جديد</button>
                                <button class="btn btn-outline-success mb-2" data-toggle="modal" data-target="#import_modal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                                    استيراد من اكسل
                                </button>
                                <button class="btn btn-outline-warning mb-2">Warning</button>
                                <button class="btn btn-outline-danger mb-2">Danger</button>
                                <button class="btn btn-outline-secondary mb-2">Secondary</button>
                                <button class="btn btn-outline-dark mb-2">Dark</button>

                            </div>
                        </div>
                    </div>
                    <div class="modal fade login-modal" id="import_modal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" style="padding-right: 17px;" aria-modal="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">

                                <div class="modal-header" id="loginModalLabel">
                                    <h4 class="modal-title">استيراد ملف</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="mt-0" action="{{route('import_accounts')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="import_file" class="form-control" >


                                        <button type="submit" class="btn btn-success mt-2 mb-2 btn-block">تحميل</button>
                                    </form>


                                </div>
                                <div class="modal-footer justify-content-center">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade login-modal" id="add_account" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" style="padding-right: 17px;" aria-modal="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">

                                <div class="modal-header" id="loginModalLabel">
                                    <h4 class="modal-title">اضافة اشتراك جديد</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="mt-0" method="post" action="{{route('add_account')}}" >
                                        @csrf
                                        <div class="form-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            <input type="text" name="account_id" class="form-control mb-2" id="exampleInputEmail1" placeholder="رقم الاشتراك">
                                        </div>
                                        <div class="form-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            <input type="text" name="machine_id" class="form-control mb-2" id="exampleInputEmail1" placeholder="رقم العداد">
                                        </div>
                                        <div class="form-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            <input type="text" name="area" class="form-control mb-2" id="exampleInputEmail1" placeholder="الموقع">
                                        </div>
                                        <div class="form-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            <input type="text" name="customer_name" class="form-control mb-2" id="exampleInputEmail1" placeholder="اسم المشترك">
                                        </div>
                                        <div class="form-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            <input type="text" name="last_read" class="form-control mb-2" id="exampleInputEmail1" placeholder="قراءة سابقة">
                                        </div>
                                        <div class="form-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            <input type="text" name="amount" class="form-control mb-2" id="exampleInputEmail1" placeholder="الرصيد الحالة">
                                        </div>
                                        <div class="form-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            <select name="status" class="form-control">
                                                <option disabled="">حالة الاشتراك</option>
                                                <option value="ساريه">ساريه</option>
                                                <option value="موقفة">موقفة</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            <select name="type" class="form-control">
                                                <option disabled="">التعرفة</option>
                                                <option value="استهلاك منزلي">استهلاك منزلي</option>
                                                <option value="استهلاك زراعي">استهلاك زراعي</option>
                                                <option value="استهلاك تجاري">استهلاك تجاري</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-block">تخزين</button>
                                    </form>

                                </div>
                                <div class="modal-footer justify-content-center">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                        <div class="widget-content widget-content-area br-6">
                            <div class="table-responsive mb-4 mt-4">
                                <div id="html5-extension_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="html5-extension_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                                <div class="row"><div class="col-md-12">

                                                    </div>
                                                    <div class="col-md-12">
                                                        <div id="account_list" class="display">
                                                            <table id="accounts" class="table table-hover non-hover dataTable no-footer display" style="width:100%" role="grid" aria-describedby="html5-extension_info">
                                                            <thead>
                                                            <tr role="row">
                                                                <th class="sorting_asc" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 140.6px;">رقم الاشتراك</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 200.4px;">رقم العداد</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 101.4px;">اسم المشترك</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 49.4px;">المنطقة</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Start date: activate to sort column ascending" style="width: 123.8px;">الرصيد</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 120.8px;">القراءة الحالية</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Extn.: activate to sort column ascending" style="width: 64.6px;">الحالة</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Avatar: activate to sort column ascending" style="width: 80.6px;">النوع</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending" style="width: 108.6px;">اجراءات</th>
                                                            </tr>
                                                            </thead>
{{--                                                            <tbody>--}}
{{--                                                            @foreach($accounts as $account)--}}
{{--                                                                <tr role="row">--}}
{{--                                                                    <td class="sorting_1">{{$account->account_id}}</td>--}}
{{--                                                                    <td>{{$account->machine_id}}</td>--}}
{{--                                                                    <td>{{$account->customer_name}}</td>--}}
{{--                                                                    <td>{{$account->area}}</td>--}}
{{--                                                                    <td>{{$account->amount}}</td>--}}
{{--                                                                    <td>{{$account->last_read}}</td>--}}
{{--                                                                    <td>{{$account->status}}</td>--}}
{{--                                                                    <td>{{$account->type}}</td>--}}
{{--                                                                    <td>--}}
{{--                                                                        <div class="btn-group">--}}
{{--                                                                            <button type="button" class="btn btn-dark btn-sm">Open</button>--}}
{{--                                                                            <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">--}}
{{--                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>--}}
{{--                                                                            </button>--}}
{{--                                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuReference5">--}}
{{--                                                                                <a class="dropdown-item" href="#">Action</a>--}}
{{--                                                                                <a class="dropdown-item" href="#">Another action</a>--}}
{{--                                                                                <a class="dropdown-item" href="#">Something else here</a>--}}
{{--                                                                                <div class="dropdown-divider"></div>--}}
{{--                                                                                <a class="dropdown-item" href="#">Separated link</a>--}}
{{--                                                                            </div>--}}
{{--                                                                        </div>--}}
{{--                                                                    </td>--}}
{{--                                                                </tr>--}}
{{--                                                            @endforeach--}}
{{--                                                            </tbody>--}}
                                                        </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
        <!--  END CONTENT AREA  -->
    </div>
    <script
            src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
            crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#accounts').DataTable({
            'ajax' : '{{url('get_accounts')}}',
            'columns' : [
                {'data' : 'account_id'},
                {'data' : 'machine_id'},
                {'data' : 'customer_name'},
                {'data' : 'area'},
                {'data' : 'amount'},
                {'data' : 'last_read'},
                {'data' : 'status'},
                {'data' : 'type'},
                {'data' : 'updated_at'},
            ]
        });
    } );

    // $('.display').dataTable();
</script>
@endsection
