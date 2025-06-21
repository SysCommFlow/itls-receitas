<?php
// resources/views/relatorios/cozinheiros.blade.php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Cozinheiros - Sistema ITLS</title>
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
            border-bottom: 2px solid #10b981;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #065f46;
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
            background-color: #f0fdf4;
            padding: 10px;
            border-left: 4px solid #10b981;
            margin-bottom: 15px;
            color: #1f2937;
        }
        .stats-summary {
            background-color: #f0fdf4;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .stats-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .cozinheiro-card {
            border: 1px solid #d1fae5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #f9fafb;
            page-break-inside: avoid;
        }
        .cozinheiro-name {
            font-size: 16px;
            font-weight: bold;
            color: #065f46;
            margin-bottom: 10px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }
        .stat-item {
            text-align: center;
            padding: 8px;
            background-color: white;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #10b981;
        }
        .stat-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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
        .ranking {
            display: inline-block;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background-color: #10b981;
            color: white;
            text-align: center;
            line-height: 25px;
            font-weight: bold;
            margin-right: 10px;
        }
        .no-data {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Relatório de Performance dos Cozinheiros</div>
        <div class="subtitle">Análise detalhada da produtividade e qualidade</div>
        <div class="subtitle">Gerado em: {{ $data_geracao }}</div>
    </div>

    <div class="section">
        <div class="section-title">📊 Estatísticas Gerais</div>
        <div class="stats-summary">
            <div class="stats-row">
                <span><strong>Total de Cozinheiros:</strong></span>
                <span>{{ $dados['estatisticas']['total_cozinheiros'] }}</span>
            </div>
            <div class="stats-row">
                <span><strong>Total de Receitas:</strong></span>
                <span>{{ $dados['estatisticas']['total_receitas'] }}</span>
            </div>
            <div class="stats-row">
                <span><strong>Média de Receitas por Cozinheiro:</strong></span>
                <span>{{ number_format($dados['estatisticas']['media_receitas_por_cozinheiro'], 1) }}</span>
            </div>
            @if($dados['estatisticas']['cozinheiro_mais_produtivo'])
            <div class="stats-row">
                <span><strong>Cozinheiro Mais Produtivo:</strong></span>
                <span>{{ $dados['estatisticas']['cozinheiro_mais_produtivo']['name'] }}
                      ({{ $dados['estatisticas']['cozinheiro_mais_produtivo']['receitas_count'] }} receitas)</span>
            </div>
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">👨‍🍳 Ranking dos Cozinheiros</div>

        @if(count($dados['data']) > 0)
            @foreach($dados['data'] as $index => $cozinheiro)
            <div class="cozinheiro-card">
                <div class="cozinheiro-name">
                    <span class="ranking">{{ $index + 1 }}</span>
                    {{ $cozinheiro['name'] }}
                </div>

                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">{{ $cozinheiro['receitas_count'] }}</div>
                        <div class="stat-label">Total Receitas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $cozinheiro['receitas_publicadas_count'] }}</div>
                        <div class="stat-label">Publicadas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $cozinheiro['receitas_testadas_count'] }}</div>
                        <div class="stat-label">Testadas</div>
                    </div>
                </div>

                @if(count($cozinheiro['receitas']) > 0)
                <div style="margin-top: 15px;">
                    <strong>Receitas Recentes:</strong>
                    <table>
                        <thead>
                            <tr>
                                <th>Receita</th>
                                <th>Nota Média</th>
                                <th>Data Criação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cozinheiro['receitas'] as $receita)
                            <tr>
                                <td>{{ $receita['nome'] }}</td>
                                <td>{{ $receita['nota_media'] ? number_format($receita['nota_media'], 1) : 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($receita['created_at'])->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            @endforeach
        @else
            <div class="no-data">
                Nenhum cozinheiro encontrado no período selecionado.
            </div>
        @endif
    </div>

    <div class="footer">
        <div>Sistema ITLS - Relatório de Cozinheiros | Página 1</div>
        <div>Relatório gerado automaticamente em {{ $data_geracao }}</div>
    </div>
</body>
</html>
