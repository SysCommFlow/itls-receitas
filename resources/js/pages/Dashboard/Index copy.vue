// resources/js/Pages/Dashboard/Index.vue
<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Painel de controle
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" data-aos="fade-up">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Receitas</p>
                  <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_receitas }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Receitas Testadas</p>
                  <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.receitas_testadas }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Testes Pendentes</p>
                  <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.testes_pendentes }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Usuários</p>
                  <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_usuarios }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Receitas Recentes -->
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg" data-aos="fade-right">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Receitas Recentes</h3>
                <Link :href="route('receitas.index')" class="text-blue-600 hover:text-blue-900 text-sm">
                  Ver todas
                </Link>
              </div>
              <div class="space-y-3">
                <div v-for="receita in receitasRecentes" :key="receita.id"
                     class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                  <div class="flex-1">
                    <Link :href="route('receitas.show', receita.id)"
                          class="text-sm font-medium text-gray-900 dark:text-white hover:text-blue-600">
                      {{ receita.nome }}
                    </Link>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      {{ receita.categoria.nome }} • {{ receita.user.name }}
                    </p>
                  </div>
                  <div class="flex items-center space-x-2">
                    <span v-if="receita.testada"
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      Testada
                    </span>
                    <span v-if="receita.publicada"
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                      Publicada
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Testes Recentes -->
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg" data-aos="fade-left">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Testes Recentes</h3>
                <Link :href="route('testes.index')" class="text-blue-600 hover:text-blue-900 text-sm">
                  Ver todos
                </Link>
              </div>
              <div class="space-y-3">
                <div v-for="teste in testesRecentes" :key="teste.id"
                     class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                  <div class="flex-1">
                    <Link :href="route('testes.show', teste.id)"
                          class="text-sm font-medium text-gray-900 dark:text-white hover:text-blue-600">
                      {{ teste.receita.nome }}
                    </Link>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      Degustador: {{ teste.degustador.nome }}
                    </p>
                  </div>
                  <div>
                    <span :class="getStatusClass(teste.status)"
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                      {{ getStatusLabel(teste.status) }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8" data-aos="fade-up" data-aos-delay="200">
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ações Rápidas</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <Link :href="route('receitas.create')"
                  class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg text-center transition-colors transform hover:scale-105">
              <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Nova Receita
            </Link>

            <Link :href="route('testes.create')"
                  class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg text-center transition-colors transform hover:scale-105">
              <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
              </svg>
              Agendar Teste
            </Link>

            <Link :href="route('relatorios.index')"
                  class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-lg text-center transition-colors transform hover:scale-105">
              <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
              Relatórios
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/components/Layouts/AuthenticatedLayout.vue'
import type { Receita, Teste } from '@/types'

interface Props {
  stats: {
    total_receitas: number
    receitas_testadas: number
    testes_pendentes: number
    total_usuarios: number
    minhas_receitas?: number
    receitas_publicadas?: number
  }
  receitasRecentes: Receita[]
  testesRecentes: Teste[]
}

defineProps<Props>()

const getStatusClass = (status: string) => {
  const classes = {
    'agendado': 'bg-yellow-100 text-yellow-800',
    'em_andamento': 'bg-blue-100 text-blue-800',
    'concluido': 'bg-green-100 text-green-800',
    'cancelado': 'bg-red-100 text-red-800'
  }
  return classes[status as keyof typeof classes] || 'bg-gray-100 text-gray-800'
}

const getStatusLabel = (status: string) => {
  const labels = {
    'agendado': 'Agendado',
    'em_andamento': 'Em Andamento',
    'concluido': 'Concluído',
    'cancelado': 'Cancelado'
  }
  return labels[status as keyof typeof labels] || status
}
</script>
