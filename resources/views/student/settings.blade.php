<x-app-layout>
    <x-slot name="styles">

        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

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
                        <h1>Student Settings</h1>
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
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ $student->first_name . ' ' . $student->last_name }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-horizontal">
                                    <div class="form-group row">
                                        {{-- Status --}}
                                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                                        <div class="col-sm-10">
                                            @if ($student->hasGraduated())
                                                <div class='pt-2'>
                                                    Graduated
                                                </div>
                                            @else
                                                <div class="btn-group">
                                                    <form action="             @if ($student->isActive())
                                                        {{ route('student.deactivate', ['student' => $student]) }}
                                                    @else
                                                        {{ route('student.activate', ['student' => $student]) }}
                                                        @endif"
                                                        method="post">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="btn @if ($student->isActive()) btn-danger
                                                        @else btn-success @endif btn-flat"
                                                            @if ($student->hasGraduated())
                                                            disabled
                                            @endif>
                                            @if ($student->isActive())
                                                Deactivate
                                            @else
                                                Activate
                                            @endif
                                            </button>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                    {{-- /Status --}}
                                </div>

                                <div class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="classroom branch" class="col-sm-2 col-form-label">Classroom
                                            Branch</label>
                                        <div class="col-sm-10">
                                            <div class="btn-group">
                                                @foreach ($student->classroom->branches as $branch)
                                                    <form
                                                        action="{{ route('student.set.classroom.branch', ['student' => $student, 'branch' => $branch]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if (App\Models\BranchClassroom::where('classroom_id', $student->classroom->id)->where('branch_id', $branch->id)->first()->id == $student->branchClassroom?->id)
                                                            <button type="submit" class="btn btn-success btn-flat">
                                                                {{ $branch->name }}
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn btn-default btn-flat">
                                                                {{ $branch->name }}
                                                            </button>
                                                        @endif
                                                    </form>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if (!$student->hasGraduated())
                                    {{-- class --}}
                                    <div class="form-group row">
                                        <label for="class" class="col-sm-2 col-form-label">Class
                                            <a
                                                href="{{ route('classroom.show', ['classroom' => $student->classroom]) }}">({{ $student->classroom->name }})</a></label>
                                        <div class="col-sm-10">
                                            <div class="btn-group">
                                                <button type="button"
                                                    onclick="changeClassConfirmationModal('{{ route('student.promote', ['student' => $student]) }}', {{ $student }}, 'promote')"
                                                    class="btn btn-success btn-flat" @if ($student->canGraduate() || $student->hasGraduated()) disabled @endif>
                                                    Promote
                                                </button>

                                                <button type="button"
                                                    onclick="changeClassConfirmationModal('{{ route('student.demote', ['student' => $student]) }}',{{ $student }}, 'demote')"
                                                    class="btn btn-danger btn-flat">
                                                    Demote
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- /class --}}

                                    {{-- Set Graduation date --}}
                                    @if ($student->canGraduate())
                                        <form action="{{ route('student.graduate', ['student' => $student]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="form-group row">
                                                <label for="class" class="col-sm-2 col-form-label">Set
                                                    Graduation
                                                    Date
                                                    ({{ $currentAcademicSession->name }})</label>
                                                <div class="col-sm-2">
                                                    <div class="input-group date" id="graduationDate"
                                                        data-target-input="nearest">
                                                        <input type="text"
                                                            class="form-control @error('graduated_at') is-invalid @enderror datetimepicker-input"
                                                            data-target="#graduationDate"
                                                            value="{{ old('graduated_at') }}" name="graduated_at" />
                                                        <div class="input-group-append" data-target="#graduationDate"
                                                            data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('graduated_at')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                </div>
                                                <div class="col-sm-3">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif

                                    {{-- /Set graduation date --}}
                                @endif

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- /.content -->

    </div>

    {{-- change class confirmation modal --}}
    <div class="modal fade" id="changeClassConfirmationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to <span id="confirmationModalStudentName" class="font-bold"></span>?
                </div>
                <div class="modal-footer justify-content-between">
                    <form action="" id="changeClassForm" method="post">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">Yes</button>
                    </form>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <x-slot name="scripts">

        <!-- DataTables  & Plugins -->
        <script src="{{ asset('TAssets/plugins/datatables/jquery.dataTables.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.html5.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.print.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}">
        </script>
        <!-- AdminLTE App -->
        <script src="{{ asset('TAssets/plugins/moment/moment.min.js') }}"></script>

        <!-- Tempusdominus Bootstrap 4 -->
        <script src="{{ asset('TAssets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}">
        </script>
        <script>
            $('#graduationDate').datetimepicker({
                format: 'YYYY-MM-DD'
            })

            function changeClassConfirmationModal(url, data, action) {

                const studentName = data.first_name + ' ' + data.last_name

                $('#changeClassForm').attr("action", url)

                if (action == 'promote') {
                    $('#confirmationModalStudentName').html(action + ' ' + studentName)
                } else if (action == 'demote') {
                    $('#confirmationModalStudentName').html(action + ' ' + studentName)
                } else {
                    $('#confirmationModalStudentName').html(action + ' ' + studentName)
                }

                $('#changeClassConfirmationModal').modal('show')
            }
        </script>
    </x-slot>
</x-app-layout>
