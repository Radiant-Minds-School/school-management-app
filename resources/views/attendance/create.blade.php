<x-app-layout>
    <x-slot name="styles">
         
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    </x-slot>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Attendance</h1>
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
                    <div class="col-lg-6">
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Attendance</h3>
                            </div>
                            <form method="POST"
                                action="{{ route('attendance.store', ['student' => $student, 'periodSlug' => $period->slug]) }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">Number of Times <span
                                                class="text-lg">{{ $student->first_name }}
                                                {{ $student->last_name }}</span> was present in <span
                                                class="text-lg">{{ $period->term->name }}
                                                {{ $period->academicSession->name }}
                                            </span>
                                        </label>
                                        <input type="number" name="value"
                                            class="form-control @error('value') is-invalid @enderror"
                                            value="@isset($attendance){{ old('value', $attendance->value) }}@else{{ old('value') }}@endisset"
                                                required>
                                            @error('value')
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
            <!-- Toastr -->
            <script src="{{ asset('TAssets/plugins/toastr/toastr.min.js') }}"></script>
            <!-- Select2 -->
            <script src="{{ asset('TAssets/plugins/select2/js/select2.full.min.js') }}"></script>
            <!-- AdminLTE App -->
            <script>
                $(function() {

                    //Initialize Select2 Elements
                    $('.select2').select2()
                })

            </script>
        </x-slot>
    </x-app-layout>
