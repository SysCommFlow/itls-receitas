// resources/js/Pages/Testes/Index.vue
<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          Testes de Receitas
        </h2>
        <Link :href="route('testes.create')"
              class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          <span>Agendar Teste</span>
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filtros -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6" data-aos="fade-down">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Status
              </label>
              <select
                v-model="form.status"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-green-500 focus:ring-green-500"
                @change="search"
              >
                <option value="">Todos os status</option>
                <option value="agendado">Agendado</option>
                <option value="em_andamento">Em Andamento</option>
                <option value="concluido">Concluído</option>
                <option value="cancelado">Cancelado</option>
              </select>
            </div>

            <!-- Degustador -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Degustador
              </label>
              <select
                v-model="form.degustador"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-green-500 focus:ring-green-500"
                @change="search"
              >
                <option value="">Todos os degustadores</option>
                <option v-for="degustador in degustadores" :key="degustador.id" :value="degustador.id">
                  {{ degustador.nome }}
                </option>
              </select>
            </div>

            <!-- Data Início -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Data Início
              </label>
              <input
                v-model="form.data_inicio"
                type="date"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-green-500 focus:ring-green-500"
                @change="search"
              />
            </div>

            <!-- Data Fim -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Data Fim
              </label>
              <input
                v-model="form.data_fim"
                type="date"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-green-500 focus:ring-green-500"
                @change="search"
              />
            </div>
          </div>

          <div class="flex justify-end mt-4 space-x-2">
            <button
              @click="clearFilters"
              class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors"
            >
              Limpar Filtros
            </button>
          </div>
        </div>

        <!-- Lista de Testes -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Receita
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Degustador
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Data do Teste
                  </th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Status
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Avaliação
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Ações
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr
                  v-for="(teste, index) in testes.data"
                  :key="teste.id"
                  :data-aos="'fade-up'"
                  :data-aos-delay="index * 50"
                  class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                >
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <img
                          v-if="teste.receita.imagens && teste.receita.imagens.length > 0"
                          :src="`/storage/${teste.receita.imagens[0]}`"
                          :alt="teste.receita.nome"
                          class="h-10 w-10 rounded-full object-cover"
                        />
                        <div v-else class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                          <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                          </svg>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                          {{ teste.receita.nome }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                          {{ teste.receita.codigo_unico }}
                        </div>
                      </div>
                    </div>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 dark:text-white">{{ teste.degustador.name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ teste.degustador.email }}</div>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    {{ formatDate(teste.data_teste) }}
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getStatusClass(teste.status)"
                          class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                      {{ getStatusLabel(teste.status) }}
                    </span>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <div v-if="teste.avaliacao" class="flex items-center">
                      <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                      </svg>
                      <span class="text-sm text-gray-900 dark:text-white">
                        {{ teste.avaliacao.nota_geral.toFixed(1) }}
                      </span>
                    </div>
                    <span v-else class="text-sm text-gray-500 dark:text-gray-400">
                      Pendente
                    </span>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex space-x-2">
                      <Link
                        :href="route('testes.show', teste.id)"
                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                      >
                        Ver
                      </Link>

                      <button
                        v-if="teste.status === 'agendado'"
                        @click="updateStatus(teste.id, 'em_andamento')"
                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                      >
                        Iniciar
                      </button>

                      <Link
                        v-if="teste.status === 'em_andamento' || teste.status === 'concluido'"
                        :href="route('testes.avaliar', teste.id)"
                        class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300"
                      >
                        {{ teste.avaliacao ? 'Editar' : 'Avaliar' }}
                      </Link>

                      <button
                        v-if="teste.status === 'agendado'"
                        @click="updateStatus(teste.id, 'cancelado')"
                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                      >
                        Cancelar
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="testes.links.length > 3" class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
            <div class="flex items-center justify-between">
              <div class="flex-1 flex justify-between sm:hidden">
                <Link
                  v-if="testes.links[0].url"
                  :href="testes.links[0].url"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Anterior
                </Link>
                <Link
                  v-if="testes.links[testes.links.length - 1].url"
                  :href="testes.links[testes.links.length - 1].url"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Próximo
                </Link>
              </div>
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <Link
                    v-for="link in testes.links"
                    :key="link.label"
                    :href="link.url"
                    :class="[
                      'relative inline-flex items-center px-2 py-2 border border-gray-300 text-sm font-medium',
                      link.active
                        ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                        : 'bg-white text-gray-500 hover:bg-gray-50',
                      !link.url ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'
                    ]"
                  >
                    {{ link.label }}
                  </Link>
                </nav>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div
          v-if="testes.data.length === 0"
          class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow-sm"
          data-aos="fade-up"
        >
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum teste encontrado</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Comece agendando um novo teste de receita.
          </p>
          <div class="mt-6">
            <Link
              :href="route('testes.create')"
              class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Agendar Teste
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/components/Layouts/AuthenticatedLayout.vue'
import type { Teste, Degustador } from '@/types'

interface Props {
  testes: {
    data: Teste[]
    links: Array<{
      url: string | null
      label: string
      active: boolean
    }>
  }
  degustadores: Degustador[]
  filters: {
    status?: string
    degustador?: string
    data_inicio?: string
    data_fim?: string
  }
}

const props = defineProps<Props>()

const form = reactive({
  status: props.filters.status || '',
  degustador: props.filters.degustador || '',
  data_inicio: props.filters.data_inicio || '',
  data_fim: props.filters.data_fim || ''
})

const search = () => {
  router.get(route('testes.index'), form, {
    preserveState: true,
    replace: true
  })
}

const clearFilters = () => {
  form.status = ''
  form.degustador = ''
  form.data_inicio = ''
  form.data_fim = ''
  search()
}

const getStatusClass = (status: string) => {
  const classes = {
    'agendado': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
    'em_andamento': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
    'concluido': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    'cancelado': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
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

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const updateStatus = (testeId: number, newStatus: string) => {
  router.patch(route('testes.updateStatus', testeId), { status: newStatus }, {
    preserveScroll: true
  })
}
</script>

