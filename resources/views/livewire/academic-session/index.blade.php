<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    <x-slot name="styles">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <style>
            .content-wrapper {
                background: #f8f9fa;
            }
            .card {
                border: none;
                border-radius: 15px;
                box-shadow: 0 0 15px rgba(0,0,0,0.05);
            }
            .card-header {
                background: white;
                border-bottom: 1px solid #eee;
                border-radius: 15px 15px 0 0 !important;
            }
            .card-title {
                font-weight: 600;
                color: #2c3e50;
            }
            .form-control {
                border-radius: 8px;
                border: 1px solid #e0e0e0;
                padding: 10px 15px;
            }
            .form-control:focus {
                box-shadow: 0 0 0 0.2rem rgba(0,123,255,.15);
            }
            .btn {
                border-radius: 8px;
                padding: 8px 20px;
                font-weight: 500;
            }
            .btn-primary {
                background: #4361ee;
                border: none;
            }
            .btn-primary:hover {
                background: #3451d1;
            }
            .table {
                border-radius: 8px;
                overflow: hidden;
            }
            .table thead th {
                background: #f8f9fa;
                border-bottom: 2px solid #dee2e6;
                font-weight: 600;
            }
            .btn-group .btn {
                padding: 5px 10px;
            }
            .modal-content {
                border-radius: 15px;
            }
        </style>
    </x-slot>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h1 class="m-0 font-weight-bold text-dark">Academic Sessions</h1>
                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- New Academic Session Form -->
                    <div class="col-6">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h3 class="card-title">New Academic Session</h3>
                            </div>
                            <form id="addAcademicSession" method="POST" action="#" wire:submit="submit">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Academic Session" class="font-weight-bold mb-2">Academic Session</label>
                                        <input type="text" wire:model.live="name" class="form-control @error('name') is-invalid @enderror" id="academicSession" placeholder="Enter Academic Session">
                                        <small class="text-muted">e.g 2009-2010</small>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold mb-2">Start Date</label>
                                        <input type="text" wire:model.live="startDate" class="form-control @error('startDate') is-invalid @enderror" id="startDate" placeholder="">
                                        <small class="text-muted">format: YYYY-MM-DD</small>
                                        @error('startDate')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold mb-2">End Date</label>
                                        <input type="text" wire:model.live="endDate" class="form-control @error('endDate') is-invalid @enderror" id="endDate" placeholder="">
                                        <small class="text-muted">format: YYYY-MM-DD</small>
                                        @error('endDate')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer bg-white">
                                    <button type="submit" class="btn btn-primary px-4">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Academic Sessions Table -->
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header py-3">
                                <h3 class="card-title">Academic Sessions</h3>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($academicSessions as $academicSession)
                                            <tr>
                                                <td>{{ $academicSession->name }}</td>
                                                <td>{{ $academicSession->start_date }}</td>
                                                <td>{{ $academicSession->end_date }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('academic-session.edit', ['academicSession' => $academicSession]) }}">
                                                            <button type="button" class="btn btn-light" title="Edit">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                        </a>
                                                        <button type="button" class="btn btn-danger" title="Delete" onclick="deleteConfirmationModal('{{ $academicSession->name }}')">
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
        </section>
    </div>

    <div class="modal fade" id="deleteConfirmationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h4 class="modal-title font-weight-bold">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <span id="deleteItemName" class="font-weight-bold"></span>?
                </div>
                <div class="modal-footer border-0 justify-content-between">
                    <div>
                        <span data-delete-item='' id="deleteItem"></span>
                        <button type="button" class="btn btn-danger px-4" id="confirmDelete">
                            <span wire:loading.remove wire:target="delete">Yes</span>
                            <div class="spinner-border spinner-border text-light" wire:loading wire:target="delete"></div>
                        </button>
                    </div>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <!-- DataTables  & Plugins -->
        <script src="{{ asset('TAssets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/moment/moment.min.js') }}"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="{{ asset('TAssets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

        <!-- AdminLTE App -->
        <script>
            function deleteConfirmationModal(name) {
                $('#deleteItemName').html(name)
                $('#deleteConfirmationModal').modal('show')
                document.getElementById('deleteItem').dataset.deleteItem = name
            }

            $('#confirmDelete').click(() => {
                @this.set('deleteItem', document.getElementById('deleteItem').dataset.deleteItem)
                Livewire.dispatch('delete')
            })

            Livewire.on('success', _ => {
                $('#deleteConfirmationModal').modal('hide')
            })

            Livewire.on('error', _ => {
                $('#deleteConfirmationModal').modal('hide')
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
</div>
