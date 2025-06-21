// resources/js/Pages/Receitas/Index.vue - CORRIGIDO
<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          Receitas
        </h2>
        <Link :href="route('receitas.create')"
              class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          <span>Nova Receita</span>
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filtros -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6" data-aos="fade-down">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Busca -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Buscar
              </label>
              <input
                v-model="form.search"
                type="text"
                placeholder="Nome ou código da receita..."
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                @keyup.enter="search"
              />
            </div>

            <!-- Categoria -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Categoria
              </label>
              <select
                v-model="form.categoria"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                @change="search"
              >
                <option value="">Todas as categorias</option>
                <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">
                  {{ categoria.nome }}
                </option>
              </select>
            </div>

            <!-- Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Status
              </label>
              <select
                v-model="form.status"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                @change="search"
              >
                <option value="">Todos os status</option>
                <option value="publicadas">Publicadas</option>
                <option value="testadas">Testadas</option>
                <option value="pendentes">Pendentes</option>
              </select>
            </div>

            <!-- Botões de ação -->
            <div class="flex items-end space-x-2">
              <button
                @click="search"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors"
              >
                Filtrar
              </button>
              <button
                @click="clearFilters"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors"
              >
                Limpar
              </button>
            </div>
          </div>
        </div>

        <!-- Grid de Receitas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
          <div
            v-for="(receita, index) in receitas.data"
            :key="receita.id"
            :data-aos="'fade-up'"
            :data-aos-delay="index * 50"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
          >
            <!-- Imagem da receita -->
            <div class="relative h-48 bg-gray-200 dark:bg-gray-700">
              <img
                v-if="receita.imagens_urls && receita.imagens_urls.length > 0"
                :src="receita.imagens_urls[0].thumb"
                :alt="receita.nome"
                class="w-full h-full object-cover"
              />
              <div v-else class="flex items-center justify-center h-full">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>

              <!-- Status badges -->
              <div class="absolute top-2 right-2 flex flex-col space-y-1">
                <span
                  v-if="receita.publicada"
                  class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-medium"
                >
                  Publicada
                </span>
                <span
                  v-if="receita.testada"
                  class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium"
                >
                  Testada
                </span>
              </div>

              <!-- Nota média -->
              <div
                v-if="typeof receita.nota_media === 'number' && !isNaN(receita.nota_media)"
                class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded-full text-xs flex items-center"
              >
                <svg class="w-3 h-3 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
                {{ receita.nota_media.toFixed(1) }}
              </div>
            </div>

            <!-- Conteúdo do card -->
            <div class="p-4">
              <div class="flex items-start justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">
                  {{ receita.nome }}
                </h3>
              </div>

              <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                {{ receita.categoria.nome }}
              </p>

              <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">
                Por: {{ receita.user.name }}
              </p>

              <!-- Informações da receita -->
              <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4 space-x-4">
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  {{ receita.tempo_cozimento }}min
                </div>
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                  </svg>
                  {{ receita.numero_porcoes }}
                </div>
              </div>

              <!-- Ações -->
              <div class="flex space-x-2">
                <Link
                  :href="route('receitas.show', receita.id)"
                  class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded-md text-sm transition-colors"
                >
                  Ver Detalhes
                </Link>

                <div v-if="canEdit(receita)" class="flex space-x-1">
                  <Link
                    :href="route('receitas.edit', receita.id)"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md transition-colors"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </Link>

                  <button
                    @click="confirmDelete(receita)"
                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md transition-colors"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="receitas.links.length > 3" class="flex justify-center" data-aos="fade-up">
          <nav class="flex items-center space-x-1">
            <Link
              v-for="link in receitas.links"
              :key="link.label"
              :href="link.url ?? ''"
              :class="[
                'px-3 py-2 text-sm font-medium rounded-md transition-colors',
                link.active
                  ? 'bg-blue-600 text-white'
                  : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700',
                !link.url ? 'opacity-50 cursor-not-allowed' : ''
              ]"
            >
              {{ link.label }}
            </Link>
          </nav>
        </div>

        <!-- Empty State -->
        <div
          v-if="receitas.data.length === 0"
          class="text-center py-12"
          data-aos="fade-up"
        >
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhuma receita encontrada</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Comece criando uma nova receita.
          </p>
          <div class="mt-6">
            <Link
              :href="route('receitas.create')"
              class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Nova Receita
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmação de exclusão -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
      @click="showDeleteModal = false"
    >
      <div
        class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800"
        @click.stop
      >
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-2">
            Confirmar Exclusão
          </h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-500 dark:text-gray-400">
              Tem certeza que deseja excluir a receita "{{ receitaToDelete?.nome }}"?
              Esta ação não pode ser desfeita.
            </p>
          </div>
          <div class="items-center px-4 py-3">
            <div class="flex space-x-3">
              <button
                @click="showDeleteModal = false"
                class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300"
              >
                Cancelar
              </button>
              <button
                @click="deleteReceita"
                class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
              >
                Excluir
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/components/Layouts/AuthenticatedLayout.vue'
import type { Receita, Categoria, PageProps } from '@/types'

interface Props {
  receitas: {
    data: Array<Receita & {
      imagens_urls?: Array<{
        id: number
        url: string
        thumb: string
        preview: string
      }>
    }>
    links: Array<{
      url: string | null
      label: string
      active: boolean
    }>
  }
  categorias: Categoria[]
  filters: {
    categoria?: string
    search?: string
    status?: string
  }
}

const props = defineProps<Props>()

const form = reactive({
  search: props.filters.search || '',
  categoria: props.filters.categoria || '',
  status: props.filters.status || ''
})

const showDeleteModal = ref(false)
const receitaToDelete = ref<Receita | null>(null)

const search = () => {
  router.get(route('receitas.index'), form, {
    preserveState: true,
    replace: true
  })
}

const clearFilters = () => {
  form.search = ''
  form.categoria = ''
  form.status = ''
  search()
}

const canEdit = (receita: Receita) => {
  const page = usePage<PageProps>()
  const user = page.props.auth.user
  if (!user) return false
  return user.tipo_usuario === 'admin' || receita.user.id === user.id
}

const confirmDelete = (receita: Receita) => {
  receitaToDelete.value = receita
  showDeleteModal.value = true
}

const deleteReceita = () => {
  if (receitaToDelete.value) {
    router.delete(route('receitas.destroy', receitaToDelete.value.id), {
      onSuccess: () => {
        showDeleteModal.value = false
        receitaToDelete.value = null
      }
    })
  }
}
</script>
