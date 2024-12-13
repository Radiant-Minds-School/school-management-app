<x-app-layout>
    <x-slot name="styles">
        <style>
            .small-box {
                border-radius: 15px;
                transition: transform 0.3s;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            .small-box:hover {
                transform: translateY(-5px);
            }
            .small-box.bg-info { background: linear-gradient(45deg, #00c0ef, #0097bc) !important; }
            .small-box.bg-success { background: linear-gradient(45deg, #00a65a, #008d4c) !important; }
            .small-box.bg-warning { background: linear-gradient(45deg, #f39c12, #db8b0b) !important; }
            .small-box.bg-danger { background: linear-gradient(45deg, #dd4b39, #d73925) !important; }
            .small-box.bg-purple { background: linear-gradient(45deg, #605ca8, #4b478b) !important; }
            .small-box.bg-teal { background: linear-gradient(45deg, #39cccc, #2eafaf) !important; }
            .small-box.bg-olive { background: linear-gradient(45deg, #3d9970, #2d7253) !important; }
            .small-box.bg-maroon { background: linear-gradient(45deg, #d81b60, #b71752) !important; }
            .content-wrapper { background: #f4f6f9; }
            .card {
                border-radius: 15px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            .card-header {
                border-radius: 15px 15px 0 0;
                background: linear-gradient(45deg, #343a40, #23272b);
                color: white;
            }
        </style>
    </x-slot>
    <div class="content-wrapper" style="min-height: 286px;">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $dashboardData['students'] }}</h3>
                                <p class="font-semibold">Students</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <a href="{{ route('student.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $dashboardData['teachers'] }}</h3>
                                <p class="font-semibold">Teachers</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <a href="{{ route('teacher.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $dashboardData['users'] }}</h3>
                                <p class="font-semibold">Users</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="{{ route('user.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $dashboardData['classrooms'] }}</h3>
                                <p class="font-semibold">Classrooms</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chalkboard"></i>
                            </div>
                            <a href="{{ route('classroom.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-purple">
                            <div class="inner">
                                <h3>
                                    @if ($dashboardData['period'] !== null)
                                        {{ $dashboardData['period']->academicSession->name }}
                                    @else
                                        Not Set
                                    @endif
                                </h3>
                                <p class="font-semibold">Academic Session</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <a href="{{ route('academic-session.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-teal">
                            <div class="inner">
                                <h3>
                                    @if ($dashboardData['period'] !== null)
                                        {{ $dashboardData['period']->term->name }}
                                    @else
                                        Not Set
                                    @endif
                                </h3>
                                <p class="font-semibold">Term</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <a href="{{ route('term.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-olive">
                            <div class="inner">
                                <h3>{{ $dashboardData['subjects'] }}</h3>
                                <p class="font-semibold">Subjects</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <a href="{{ route('subject.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-maroon">
                            <div class="inner">
                                <h3>{{ $dashboardData['alumni'] }}</h3>
                                <p class="font-semibold">Alumni</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <a href="{{ route('student.get.alumni') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <section class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Classrooms Population
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="chart tab-pane active" id="class-population-chart" style="position: relative; height: 400px;">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="class-population" height="300" style="height: 300px; display: block; width: 578px;" width="578" class="chartjs-render-monitor"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Gender Distribution
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="chart tab-pane active" id="gender-distribution-chart" style="position: relative; height: 400px;">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="gender-distribution" height="300" style="height: 300px; display: block; width: 578px;" width="578" class="chartjs-render-monitor"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Education Level Distribution
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="chart tab-pane active" id="education-level-chart" style="position: relative; height: 300px;">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="education-level-distribution" height="300" style="height: 300px; display: block; width: 578px;" width="578" class="chartjs-render-monitor"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>
    <x-slot name="scripts">
        <script src="{{ asset('TAssets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
        <script>
            $(function() {
                generateClassroomPopulationChart()
                generateGenderDistributionChart()
                generateEducationLevelDistributionChart();
            });

            function generateClassroomPopulationChart() {
                const ctx = document.getElementById('class-population').getContext('2d');
                const data = {
                    labels: @json($dashboardData['classroomPopulationChartData']['classroomNames']),
                    datasets: [{
                        label: 'Classroom Population',
                        data: @json($dashboardData['classroomPopulationChartData']['populations']),
                        backgroundColor: @json($dashboardData['classroomPopulationChartData']['colors']),
                        hoverOffset: 4
                    }]
                };
                const classPopulation = new Chart(ctx, {
                    type: 'doughnut',
                    data: data,
                });
            }

            function generateGenderDistributionChart() {
                const ctx = document.getElementById('gender-distribution').getContext('2d');
                const data = {
                    labels: ['Male', 'Female'],
                    datasets: [{
                        label: 'Gender Distribution',
                        data: [@json($dashboardData['genderDistributionChartData']['male']), @json($dashboardData['genderDistributionChartData']['female'])],
                        backgroundColor: ['Blue', 'Pink'],
                        hoverOffset: 4
                    }]
                };

                const genderDistribution = new Chart(ctx, {
                    type: 'pie',
                    data: data,
                });
            }


            function generateEducationLevelDistributionChart() {
                const ctx = document.getElementById('education-level-distribution').getContext('2d');
                const data = {
                    labels: ['Primary', 'Secondary'],
                    datasets: [{
                        label: 'Education Level Distribution',
                        data: [
                            @json($dashboardData['educationLevelDistributionChartData']['primary']),
                            @json($dashboardData['educationLevelDistributionChartData']['secondary'])
                        ],
                        backgroundColor: ['#4e73df', '#1cc88a'],
                        hoverOffset: 4
                    }]
                };

                const educationLevelDistribution = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Number of Students by Education Level'
                            }
                        }
                    }
                });
            }
        </script>
    </x-slot>
</x-app-layout>
