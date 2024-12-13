<x-app-layout>
    <x-slot name="styles">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        <style>
            .content-wrapper {
                background-color: #f4f6f9;
            }
            .card {
                box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
                margin-bottom: 1.5rem;
            }
            .card-header {
                background-color: #fff;
                border-bottom: 1px solid rgba(0,0,0,.125);
            }
            .table-bordered {
                border: 1px solid #dee2e6;
            }
            .btn-group .btn {
                margin: 0 2px;
            }
            .form-control:focus {
                border-color: #80bdff;
                box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
            }
            .dataTables_filter {
                width: 100%;
                float: none !important;
            }
            .dataTables_filter input {
                max-width: 100%;
            }
        </style>
    </x-slot>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Classrooms Management</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header bg-primary">
                                <h3 class="card-title text-white">New Classroom</h3>
                            </div>
                            <form id="addClassroom" method="POST" action="{{ route('classroom.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Classroom" class="font-weight-bold">Name</label>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror" id="classroom"
                                            placeholder="Enter Classroom">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="Type" class="font-weight-bold">Type</label>
                                        <select class="form-control" name="type">
                                            <option value="primary">Primary</option>
                                            <option value="secondary">Secondary</option>
                                        </select>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer bg-white">
                                    <button type="submit" class="btn btn-primary btn-block">Create Classroom</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-success">
                                        <h3 class="card-title text-white">Primary Classrooms</h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="primaryTable" class="table table-bordered table-striped">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Rank</th>
                                                    <th>Name</th>
                                                    <th>Population</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($classrooms->where('type', 'primary') as $classroom)
                                                    <tr>
                                                        <td>{{ $classroom->rank }}</td>
                                                        <td>{{ $classroom->name }}</td>
                                                        <td>{{ $classroom->countActiveStudents() }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="{{ route('classroom.show', ['classroom' => $classroom]) }}" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('classroom.edit', ['classroom' => $classroom]) }}" class="btn btn-warning btn-sm">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <button type="button" class="btn btn-danger btn-sm"
                                                                    onclick="deleteConfirmationModal('{{ route('classroom.destroy', ['classroom' => $classroom]) }}', '{{ $classroom->name }}')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-info">
                                        <h3 class="card-title text-white">Secondary Classrooms</h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="secondaryTable" class="table table-bordered table-striped">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Rank</th>
                                                    <th>Name</th>
                                                    <th>Population</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($classrooms->where('type', 'secondary') as $classroom)
                                                    <tr>
                                                        <td>{{ $classroom->rank }}</td>
                                                        <td>{{ $classroom->name }}</td>
                                                        <td>{{ $classroom->countActiveStudents() }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="{{ route('classroom.show', ['classroom' => $classroom]) }}" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('classroom.edit', ['classroom' => $classroom]) }}" class="btn btn-warning btn-sm">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <button type="button" class="btn btn-danger btn-sm"
                                                                    onclick="deleteConfirmationModal('{{ route('classroom.destroy', ['classroom' => $classroom]) }}', '{{ $classroom->name }}')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Delete confirmation modal --}}
    <div class="modal fade" id="deleteConfirmationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <span id="deleteItemName" class="font-bold"></span>?
                </div>
                <div class="modal-footer justify-content-between">
                    <form action="" method="POST" id="yesDeleteConfirmation">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">Yes</button>
                    </form>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
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
        <script>
            function deleteConfirmationModal(url, name) {
                $('#yesDeleteConfirmation').attr("action", url)
                $('#deleteItemName').html(name)
                $('#deleteConfirmationModal').modal('show')
            }

            $(function() {
                $("#primaryTable, #secondaryTable").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            });
        </script>
    </x-slot>
</x-app-layout>
