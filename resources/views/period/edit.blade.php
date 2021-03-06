<x-app-layout>
    <x-slot name="styles">
         

        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    </x-slot>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Period</h1>
                    </div>
                    <div class="col-sm-6">
                        
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Edit Period</h3>
                            </div>
                            <form id="" method="POST" action="{{ route('period.update', ['period' => $period]) }}">
                                @csrf
                                @method('PATCH')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Academic Session">Academic Session</label>
                                        <input type="text" class="form-control"
                                            value="{{ $period->academicSession->name }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="Term">Term</label>
                                        <input type="text" class="form-control" value="{{ $period->term->name }}"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <div class="input-group date" id="startDate" data-target-input="nearest">
                                            <input type="text"
                                                class="form-control @error('start_date') is-invalid @enderror datetimepicker-input"
                                                data-target="#startDate"
                                                value="{{ old('start_date', $period->start_date) }}"
                                                name="start_date" />
                                            <div class="input-group-append" data-target="#startDate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @error('start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <div class="input-group date" id="endDate" data-target-input="nearest">
                                            <input type="text"
                                                class="form-control @error('end_date') is-invalid @enderror datetimepicker-input"
                                                data-target="#endDate"
                                                value="{{ old('end_date', $period->end_date) }}" name="end_date" />
                                            <div class="input-group-append" data-target="#endDate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @error('end_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="No of times school opened">No of times school opened</label>
                                        <input type="number" class="form-control"
                                            value="{{ old('no_times_school_opened', $period->no_times_school_opened) }}"
                                            name="no_times_school_opened">
                                        @error('no_times_school_opened')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
        </section>
        <!-- /.content -->
    </div>

    <x-slot name="scripts">
         

        <script src="{{ asset('TAssets/plugins/moment/moment.min.js') }}"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="{{ asset('TAssets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}">
        </script>

        <!-- AdminLTE App -->
        <script>
            //Date range picker
            $('#startDate').datetimepicker({
                format: 'YYYY-MM-DD'
            })

            $('#endDate').datetimepicker({
                format: 'YYYY-MM-DD'
            })

            $(function() {
                $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            });

        </script>
    </x-slot>
</x-app-layout>
