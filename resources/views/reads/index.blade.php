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
                        <h3>قائمة الموظفين</h3>
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
                                    اضافة دورة جديدة</button>
                                <button class="btn btn-outline-success mb-2" data-toggle="modal" data-target="#import_modal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download-cloud"><polyline points="8 17 12 21 16 17"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"></path></svg>                                    تصدير الى اكسل
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
                                    <form class="mt-0" action="{{route('import_employees')}}" method="post" enctype="multipart/form-data">
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
                                    <h4 class="modal-title">انشاء دورة جديدة</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="mt-0" method="post" action="" id="create_reads">
                                        @csrf
                                        <div class="form-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                            <input type="text" id="month" name="month" class="form-control mb-2" id="exampleInputEmail1" placeholder="الشهر">
                                        </div>
                                        <div class="form-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                            <input type="text" id="year" name="year" class="form-control mb-2" id="exampleInputEmail1" placeholder="السنة">
                                        </div>
                                        <button type="submit" id="reads_button" class="btn btn-primary mt-2 mb-2 btn-block">تخزين</button>
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
                                                                <th class="sorting_asc" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 140.6px;">اسم المكلف</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 200.4px;">المنطقة</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 200.4px;">رقم الاشتراك</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 101.4px;">رقم العداد</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 49.4px;">القراءة السابقة</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 49.4px;">القراءة الحالية</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 49.4px;">الحالة</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 49.4px;">الموظف</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 49.4px;">الشهر</th>
                                                                <th class="sorting" tabindex="0" aria-controls="html5-extension" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 49.4px;">السنة</th>
                                                            </tr>
                                                            </thead>
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
        $('#create_reads').on('submit',function (e) {
            e.preventDefault();
            $('#reads_button').html('<span class="spinner-grow text-white mr-2 align-self-center loader-sm"></span>')
            $.ajax({
                url: "{{url('add_reads/')}}" + "/" + $('#month').val() + '/' + $('#year').val(),
                method:"get",
                processData: false,
                contentType: false,
                context: document.body,
                success: function(data){
                    const toast = swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        padding: '2em'
                    });

                    toast({
                        type: 'success',
                        title: 'Signed in successfully',
                        padding: '2em',
                    })

                    $('#add_account').modal('hide');


                }
            });
        })
        $('#accounts').DataTable({
            'ajax' : '{{url('get_reads')}}',
            'columns' : [
                {'data' : 'customer_name'},
                {'data' : 'area'},
                {'data' : 'account_id'},
                {'data' : 'machine_id'},
                {'data' : 'last_read'},
                {'data' : 'current_read'},
                {'data' : 'status'},
                {'data' : 'user_id'},
                {'data' : 'month'},
                {'data' : 'year'},

            ]
        });
    } );

    // $('.display').dataTable();
</script>
@endsection
