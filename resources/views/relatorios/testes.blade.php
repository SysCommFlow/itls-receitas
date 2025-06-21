<?php
// resources/views/relatorios/testes.blade.php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Testes - Sistema ITLS</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #8b5cf6;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #5b21b6;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #6b7280;
        }
        .periodo-info {
            background-color: #f3f4f6;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            background-color: #faf5ff;
            padding: 10px;
            border-left: 4px solid #8b5cf6;
            margin-bottom: 15px;
            color: #1f2937;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-box {
            border: 1px solid #e5e7eb;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            background-color: #f9fafb;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #8b5cf6;
            display: block;
        }
        .stat-label {
            font-size: 10px;
            color: #6b7280;
            margin-top: 5px;
            text-transform: uppercase;
        }
        .highlight-box {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 8px 6px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-agendado { background-color: #fef3c7; color: #92400e; }
        .status-andamento { background-color: #dbeafe; color: #1e40af; }
        .status-concluido { background-color: #d1fae5; color: #065f46; }
        .status-cancelado { background-color: #fee2e2; color: #991b1b; }
        .nota-alta { color: #065f46; font-weight: bold; }
        .nota-media { color: #d97706; font-weight: bold; }
        .nota-baixa { color: #dc2626; font-weight: bold; }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .chart-area {
            height: 150px;
            background-color: #f9fafb;
            border: 2px dashed #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 15px 0;
            color: #6b7280;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Relatório de Testes de Receitas</div>
        <div class="subtitle">Análise completa dos testes realizados</div>
        <div class="subtitle">Gerado em: {{ $data_geracao }}</div>
    </div>

    <div class="periodo-info">
        <strong>Período do Relatório:</strong>
        {{ \Carbon\Carbon::parse($dados['periodo']['inicio'])->format('d/m/Y') }} até
        {{ \Carbon\Carbon::parse($dados['periodo']['fim'])->format('d/m/Y') }}
    </div>

    <div class="section">
        <div class="section-title">📊 Resumo dos Testes</div>
        <div class="stats-grid">
            <div class="stat-box">
                <span class="stat-number">{{ $dados['estatisticas']['total_testes'] }}</span>
                <div class="stat-label">Total de Testes</div>
            </div>
            <div class="stat-box">
                <span class="stat-number">{{ $dados['estatisticas']['testes_concluidos'] }}</span>
                <div class="stat-label">Concluídos</div>
            </div>
            <div class="stat-box">
                <span class="stat-number">{{ $dados['estatisticas']['testes_pendentes'] }}</span>
                <div class="stat-label">Pendentes</div>
            </div>
            <div class="stat-box">
                <span class="stat-number">{{ $dados['estatisticas']['testes_em_andamento'] }}</span>
                <div class="stat-label">Em Andamento</div>
            </div>
            <div class="stat-box">
                <span class="stat-number">{{ $dados['estatisticas']['testes_cancelados'] }}</span>
                <div class="stat-label">Cancelados</div>
            </div>
        </div>

        @if($dados['estatisticas']['nota_media_geral'])
        <div class="highlight-box">
            <strong>📈 Nota Média Geral dos Testes:</strong>
            <span style="font-size: 18px; color: #8b5cf6;">
                {{ number_format($dados['estatisticas']['nota_media_geral'], 1) }}/10
            </span>
        </div>
        @endif
    </div>

    @if(count($dados['estatisticas']['receitas_mais_testadas']) > 0)
    <div class="section">
        <div class="section-title">🏆 Receitas Mais Testadas</div>
        <table>
            <thead>
                <tr>
                    <th>Ranking</th>
                    <th>Receita</th>
                    <th>Total de Testes</th>
                    <th>Nota Média</th>
                    <th>Classificação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados['estatisticas']['receitas_mais_testadas'] as $index => $receita)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}º</td>
                    <td>{{ $receita['receita'] }}</td>
                    <td style="text-align: center;">{{ $receita['total_testes'] }}</td>
                    <td style="text-align: center;">
                        @if($receita['nota_media'])
                            <span class="{{ $receita['nota_media'] >= 8 ? 'nota-alta' : ($receita['nota_media'] >= 6 ? 'nota-media' : 'nota-baixa') }}">
                                {{ number_format($receita['nota_media'], 1) }}
                            </span>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($receita['nota_media'] >= 9)
                            Excelente
                        @elseif($receita['nota_media'] >= 8)
                            Muito Bom
                        @elseif($receita['nota_media'] >= 7)
                            Bom
                        @elseif($receita['nota_media'] >= 6)
                            Regular
                        @else
                            Precisa Melhorar
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="section">
        <div class="section-title">📋 Lista Detalhada dos Testes</div>

        @if(count($dados['data']) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Receita</th>
                        <th>Degustador</th>
                        <th>Status</th>
                        <th>Nota</th>
                        <th>Recomenda</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dados['data'] as $teste)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($teste['data_teste'])->format('d/m/Y') }}</td>
                        <td>{{ $teste['receita']['nome'] }}</td>
                        <td>{{ $teste['degustador']['nome'] }}</td>
                        <td>
                            <span class="status-badge status-{{ $teste['status'] }}">
                                {{ ucfirst(str_replace('_', ' ', $teste['status'])) }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            @if($teste['avaliacao'])
                                <span class="{{ $teste['avaliacao']['nota_geral'] >= 8 ? 'nota-alta' : ($teste['avaliacao']['nota_geral'] >= 6 ? 'nota-media' : 'nota-baixa') }}">
                                    {{ number_format($teste['avaliacao']['nota_geral'], 1) }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($teste['avaliacao'])
                                {{ $teste['avaliacao']['recomenda'] ? '✓ Sim' : '✗ Não' }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; color: #6b7280; padding: 30px;">
                Nenhum teste encontrado no período selecionado.
            </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">📈 Análise de Tendências</div>
        <div class="chart-area">
            Gráfico de distribuição de testes por status - {{ count($dados['data']) }} registros
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <strong>Taxa de Conclusão:</strong><br>
                {{ $dados['estatisticas']['total_testes'] > 0 ? number_format(($dados['estatisticas']['testes_concluidos'] / $dados['estatisticas']['total_testes']) * 100, 1) : 0 }}%
            </div>
            <div>
                <strong>Taxa de Cancelamento:</strong><br>
                {{ $dados['estatisticas']['total_testes'] > 0 ? number_format(($dados['estatisticas']['testes_cancelados'] / $dados['estatisticas']['total_testes']) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>

    <div class="footer">
        <div>Sistema ITLS - Relatório de Testes | Página 1</div>
        <div>Período: {{ \Carbon\Carbon::parse($dados['periodo']['inicio'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dados['periodo']['fim'])->format('d/m/Y') }}</div>
    </div>
</body>
</html>
