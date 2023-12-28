<x-app-layout>
    <div class="row">
        <div class="col-12">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-6 col-md-4 col-lg-2 mb-4">
                    <div class="card h-100" style="cursor: pointer">
                        <div class="card-body text-center"
                            onclick="window.location.href = '{{ route('alunos.index') }}'">
                            <div class=" mx-auto mb-2">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <span class="d-block text-nowrap">Alunos</span>
                            <h2 class="mb-0">{{ $alunos->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2 mb-4">
                    <div class="card h-100" style="cursor: pointer">
                        <div class="card-body text-center"
                            onclick="window.location.href = '{{ route('exercicios.index') }}'">
                            <div class="mx-auto mb-2">
                                <i class="fa fa-user fa-2x"></i>
                            </div>
                            <span class="d-block text-nowrap">Usuários</span>
                            <h2 class="mb-0">{{ $usuarios->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2 mb-4">
                    <div class="card h-100" style="cursor: pointer">
                        <div class="card-body text-center"
                            onclick="window.location.href = '{{ route('treinos.index') }}'">
                            <div class="mx-auto mb-2">
                                <i class="fa fa-users fa-2x"></i>
                            </div>
                            <span class="d-block text-nowrap">Treinos</span>
                            <h2 class="mb-0">{{ $treinos->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2 mb-4">
                    <!-- alterar filter_dias_vencimento -->
                    <div class="card h-100" style="cursor: pointer"
                        onclick="$('#filter_dias_vencimento').val(7); atualizarTela();">
                        <div class="card-body text-center">
                            <div class=" mx-auto mb-2">
                                <i class="far fa-calendar-alt fa-2x"></i>
                            </div>
                            <span class="d-block text-nowrap">7 dias<br>Vencimento</span>
                            <h2 class="mb-0">{{ $alunos7->count() }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2 mb-4">
                    <div class="card h-100" style="cursor: pointer"
                        onclick="$('#filter_dias_vencimento').val(15); atualizarTela();">
                        <div class="card-body text-center">
                            <div class=" mx-auto mb-2">
                                <i class="far fa-calendar-alt fa-2x"></i>
                            </div>
                            <span class="d-block text-nowrap">15 dias<br>Vencimento</span>
                            <h2 class="mb-0">{{ $alunos15->count() }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2 mb-4">
                    <div class="card h-100" style="cursor: pointer"
                        onclick="$('#filter_dias_vencimento').val(30); atualizarTela();">
                        <div class="card-body text-center">
                            <div class=" mx-auto mb-2">
                                <i class="far fa-calendar-alt fa-2x"></i>
                            </div>
                            <span class="d-block text-nowrap">30 dias<br>Vencimento</span>
                            <h2 class="mb-0">{{ $alunos30->count() }}</h2>
                        </div>
                    </div>
                </div>

            </div>
            <!--/ Statistics Cards -->
            <!-- Revenue Growth Chart -->
            <div class="row">
                <div class="col-12 col-md-6 col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center pb-3">
                            <h5 class="card-title mb-0">Planos a vencer
                            </h5>
                        </div>
                        <form name="formSearch" id="formSearch" method="post" action="{{ route('dashboard.search') }}">
                            @csrf
                            {!! Form::hidden('page', 0, ['id' => 'page']) !!}
                            {!! Form::hidden('filter_sort', 'id') !!}
                            {!! Form::hidden('filter_order', 'desc') !!}
                            {!! Form::hidden('filter_take', '10', ['id' => 'filter_take']) !!}
                            {!! Form::hidden('filter_dias_vencimento', '90', ['id' => 'filter_dias_vencimento']) !!}
                        </form>

                        <div id="divList">
                        </div>

                    </div>
                </div>
                @push('page_scripts')
                    <script>
                        $(function() {
                            Filtro.inicializaFormBusca("#formSearch", "#divList", true);
                        });

                        function atualizarTela() {
                            $("#formSearch").submit();
                        }

                        function executarLista(titulo) {
                            var formData = $("#fmCertificados").serialize();
                            var url = "{{ route('alunos.compartilharEmLoteForm') }}";
                            Tela.abrirJanelaPost(url, titulo, formData, 'G');
                        }
                    </script>
                @endpush
                <!--/ Revenue Growth Chart -->
            </div>
        </div>
    </div>
</x-app-layout>
