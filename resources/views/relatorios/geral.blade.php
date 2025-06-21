<?php
// resources/views/relatorios/geral.blade.php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório Geral - Sistema ITLS</title>
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
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #6b7280;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            background-color: #f3f4f6;
            padding: 10px;
            border-left: 4px solid #3b82f6;
            margin-bottom: 15px;
            color: #1f2937;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
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
            font-size: 28px;
            font-weight: bold;
            color: #3b82f6;
            display: block;
        }
        .stat-label {
            font-size: 11px;
            color: #6b7280;
            margin-top: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 12px 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #374151;
            font-size: 11px;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
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
        .chart-placeholder {
            height: 200px;
            background-color: #f3f4f6;
            border: 2px dashed #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 15px 0;
            color: #6b7280;
        }
        .highlight {
            background-color: #fef3c7;
            padding: 2px 4px;
            border-radius: 3px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 12px;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Sistema ITLS - Relatório Geral</div>
        <div class="subtitle">Relatório completo de atividades e estatísticas</div>
        <div class="subtitle">Gerado em: {{ $data_geracao }}</div>
    </div>

    <div class="section">
        <div class="section-title">📊 Resumo Executivo</div>
        <div class="stats-grid">
            <div class="stat-box">
                <span class="stat-number">{{ $dados['resumo']['total_receitas'] }}</span>
                <div class="stat-label">Total de Receitas</div>
            </div>
            <div class="stat-box">
                <span class="stat-number">{{ $dados['resumo']['receitas_testadas'] }}</span>
                <div class="stat-label">Receitas Testadas</div>
            </div>
            <div class="stat-box">
                <span class="stat-number">{{ $dados['resumo']['total_testes'] }}</span>
                <div class="stat-label">Total de Testes</div>
            </div>
            <div class="stat-box">
                <span class="stat-number">{{ $dados['resumo']['total_cozinheiros'] }}</span>
                <div class="stat-label">Cozinheiros Ativos</div>
            </div>
        </div>

        <p><strong>Taxa de Aprovação:</strong>
            <span class="highlight">{{ number_format($dados['resumo']['taxa_aprovacao'], 1) }}%</span>
        </p>
    </div>

    <div class="section">
        <div class="section-title">🏷️ Receitas por Categoria</div>
        <table>
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Número de Receitas</th>
                    <th>Percentual do Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados['receitas_por_categoria'] as $categoria)
                <tr>
                    <td>{{ $categoria['nome'] }}</td>
                    <td>{{ $categoria['receitas_count'] }}</td>
                    <td>{{ number_format(($categoria['receitas_count'] / $dados['resumo']['total_receitas']) * 100, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">🏆 Top 10 Receitas Mais Bem Avaliadas</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Receita</th>
                    <th>Cozinheiro</th>
                    <th>Categoria</th>
                    <th>Nota Média</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados['top_receitas'] as $index => $receita)
                <tr>
                    <td>{{ $index + 1 }}º</td>
                    <td>{{ $receita['nome'] }}</td>
                    <td>{{ $receita['user']['name'] }}</td>
                    <td>{{ $receita['categoria']['nome'] }}</td>
                    <td>
                        <strong>{{ number_format($receita['nota_media'], 1) }}</strong>
                        @if($receita['nota_media'] >= 9)
                            <span class="badge badge-success">Excelente</span>
                        @elseif($receita['nota_media'] >= 8)
                            <span class="badge badge-info">Muito Bom</span>
                        @else
                            <span class="badge badge-warning">Bom</span>
                        @endif
                    </td>
                    <td>
                        @if($receita['publicada'])
                            <span class="badge badge-success">Publicada</span>
                        @endif
                        @if($receita['testada'])
                            <span class="badge badge-info">Testada</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">📈 Evolução de Receitas (Últimos 12 Meses)</div>
        <div class="chart-placeholder">
            Gráfico de evolução mensal - {{ count($dados['evolucao_receitas']) }} pontos de dados
        </div>
        <table>
            <thead>
                <tr>
                    <th>Período</th>
                    <th>Receitas Criadas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados['evolucao_receitas'] as $periodo)
                <tr>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $periodo['periodo'])->format('M/Y') }}</td>
                    <td>{{ $periodo['total'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div>Sistema ITLS - Receitas Culinárias | Página 1 de 1</div>
        <div>Relatório gerado automaticamente em {{ $data_geracao }}</div>
    </div>
</body>
</html>
