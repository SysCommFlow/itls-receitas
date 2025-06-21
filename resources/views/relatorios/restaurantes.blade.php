<?php
// resources/views/relatorios/restaurantes.blade.php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Restaurantes - Sistema ITLS</title>
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
            border-bottom: 2px solid #f59e0b;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #92400e;
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
            background-color: #fffbeb;
            padding: 10px;
            border-left: 4px solid #f59e0b;
            margin-bottom: 15px;
            color: #1f2937;
        }
        .stats-summary {
            background-color: #fffbeb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fbbf24;
        }
        .stats-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .restaurante-card {
            border: 1px solid #fed7aa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #f9fafb;
            page-break-inside: avoid;
        }
        .restaurante-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .restaurante-name {
            font-size: 16px;
            font-weight: bold;
            color: #92400e;
        }
        .tipo-cozinha {
            background-color: #fbbf24;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .restaurante-info {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .info-item {
            margin-bottom: 5px;
        }
        .label {
            font-weight: bold;
            color: #374151;
        }
        .pratos-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .prato-item {
            background-color: white;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
            font-size: 11px;
        }
        .prato-nome {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 3px;
        }
        .prato-categoria {
            color: #6b7280;
            font-size: 10px;
        }
        .prato-nota {
            float: right;
            color: #f59e0b;
            font-weight: bold;
        }
        .nota-display {
            text-align: center;
            padding: 8px;
            background-color: white;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }
        .nota-numero {
            font-size: 18px;
            font-weight: bold;
            color: #f59e0b;
        }
        .nota-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
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
        .no-pratos {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            padding: 15px;
            background-color: #f3f4f6;
            border-radius: 4px;
        }
        .chart-placeholder {
            height: 120px;
            background-color: #f9fafb;
            border: 2px dashed #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 15px 0;
            color: #6b7280;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Relatório de Restaurantes e Pratos</div>
        <div class="subtitle">Análise detalhada dos restaurantes parceiros</div>
        <div class="subtitle">Gerado em: {{ $data_geracao }}</div>
    </div>

    <div class="section">
        <div class="section-title">📊 Estatísticas Gerais</div>
        <div class="stats-summary">
            <div class="stats-row">
                <span><strong>Total de Restaurantes:</strong></span>
                <span>{{ $dados['estatisticas']['total_restaurantes'] }}</span>
            </div>
            <div class="stats-row">
                <span><strong>Média de Pratos por Restaurante:</strong></span>
                <span>{{ number_format($dados['estatisticas']['media_pratos_por_restaurante'], 1) }}</span>
            </div>
            @if($dados['estatisticas']['restaurante_mais_pratos'])
            <div class="stats-row">
                <span><strong>Restaurante com Mais Pratos:</strong></span>
                <span>{{ $dados['estatisticas']['restaurante_mais_pratos']['nome'] }}
                      ({{ $dados['estatisticas']['restaurante_mais_pratos']['total_pratos'] }} pratos)</span>
            </div>
            @endif
        </div>

        <div class="chart-placeholder">
            Distribuição por Tipos de Cozinha - {{ count($dados['estatisticas']['tipos_cozinha']) }} categorias
        </div>

        <table>
            <thead>
                <tr>
                    <th>Tipo de Cozinha</th>
                    <th>Quantidade de Restaurantes</th>
                    <th>Percentual</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados['estatisticas']['tipos_cozinha'] as $tipo => $quantidade)
                <tr>
                    <td>{{ $tipo }}</td>
                    <td style="text-align: center;">{{ $quantidade }}</td>
                    <td style="text-align: center;">
                        {{ number_format(($quantidade / $dados['estatisticas']['total_restaurantes']) * 100, 1) }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">🏪 Detalhes dos Restaurantes</div>

        @if(count($dados['data']) > 0)
            @foreach($dados['data'] as $restaurante)
            <div class="restaurante-card">
                <div class="restaurante-header">
                    <div class="restaurante-name">{{ $restaurante['nome'] }}</div>
                    <div class="tipo-cozinha">{{ $restaurante['tipo_cozinha'] }}</div>
                </div>

                <div class="restaurante-info">
                    <div>
                        <div class="info-item">
                            <span class="label">Endereço:</span> {{ $restaurante['endereco'] }}
                        </div>
                        @if($restaurante['email'])
                        <div class="info-item">
                            <span class="label">Email:</span> {{ $restaurante['email'] }}
                        </div>
                        @endif
                        <div class="info-item">
                            <span class="label">Total de Pratos:</span> {{ $restaurante['total_pratos'] }}
                        </div>
                    </div>

                    <div class="nota-display">
                        <div class="nota-numero">
                            {{ $restaurante['nota_media'] ? number_format($restaurante['nota_media'], 1) : 'N/A' }}
                        </div>
                        <div class="nota-label">Nota Média</div>
                    </div>
                </div>

                @if(count($restaurante['pratos']) > 0)
                <div>
                    <strong style="margin-bottom: 10px; display: block;">Pratos Confeccionados:</strong>
                    <div class="pratos-grid">
                        @foreach($restaurante['pratos'] as $prato)
                        <div class="prato-item">
                            <div class="prato-nome">
                                {{ $prato['nome'] }}
                                @if($prato['nota_media'])
                                <span class="prato-nota">{{ number_format($prato['nota_media'], 1) }}</span>
                                @endif
                            </div>
                            <div class="prato-categoria">
                                {{ $prato['categoria'] }}
                                @if($prato['testada'])
                                    • <span style="color: #10b981;">✓ Testada</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="no-pratos">
                    Nenhum prato cadastrado para este restaurante.
                </div>
                @endif
            </div>
            @endforeach
        @else
            <div style="text-align: center; color: #6b7280; padding: 30px;">
                Nenhum restaurante encontrado.
            </div>
        @endif
    </div>

    <div class="footer">
        <div>Sistema ITLS - Relatório de Restaurantes | Página 1</div>
        <div>Total de {{ count($dados['data']) }} restaurantes analisados</div>
    </div>
</body>
</html>
