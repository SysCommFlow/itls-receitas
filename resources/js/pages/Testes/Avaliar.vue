<!-- resources/js/Pages/Testes/Avaliar.vue -->
<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center space-x-4">
        <Link :href="route('testes.index')"
              class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
        </Link>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          Avaliar Teste - {{ teste.receita.nome }}
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Informações do Teste -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6" data-aos="fade-down">
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informações do Teste</h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Receita</p>
              <p class="text-gray-900 dark:text-white">{{ teste.receita.nome }}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Degustador</p>
              <p class="text-gray-900 dark:text-white">{{ teste.degustador.name }}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Data do Teste</p>
              <p class="text-gray-900 dark:text-white">{{ formatDate(teste.data_teste) }}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
              <span :class="getStatusClass(teste.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                {{ getStatusLabel(teste.status) }}
              </span>
            </div>
          </div>
        </div>

        <!-- Formulário de Avaliação -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
          <form @submit.prevent="submit" class="p-6 space-y-6">
            <!-- Critérios de Avaliação -->
            <div data-aos="fade-up">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Critérios de Avaliação</h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nota Sabor -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Sabor (0-10) *
                  </label>
                  <div class="flex items-center space-x-4">
                    <input
                      v-model="form.nota_sabor"
                      type="number"
                      step="0.1"
                      min="0"
                      max="10"
                      required
                      class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-purple-500 focus:ring-purple-500"
                      :class="{ 'border-red-500': errors.nota_sabor }"
                    />
                    <StarRating v-model="form.nota_sabor" />
                  </div>
                  <p v-if="errors.nota_sabor" class="mt-1 text-sm text-red-600">{{ errors.nota_sabor }}</p>
                </div>

                <!-- Nota Apresentação -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Apresentação (0-10) *
                  </label>
                  <div class="flex items-center space-x-4">
                    <input
                      v-model="form.nota_apresentacao"
                      type="number"
                      step="0.1"
                      min="0"
                      max="10"
                      required
                      class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-purple-500 focus:ring-purple-500"
                      :class="{ 'border-red-500': errors.nota_apresentacao }"
                    />
                    <StarRating v-model="form.nota_apresentacao" />
                  </div>
                  <p v-if="errors.nota_apresentacao" class="mt-1 text-sm text-red-600">{{ errors.nota_apresentacao }}</p>
                </div>

                <!-- Nota Aroma -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Aroma (0-10) *
                  </label>
                  <div class="flex items-center space-x-4">
                    <input
                      v-model="form.nota_aroma"
                      type="number"
                      step="0.1"
                      min="0"
                      max="10"
                      required
                      class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-purple-500 focus:ring-purple-500"
                      :class="{ 'border-red-500': errors.nota_aroma }"
                    />
                    <StarRating v-model="form.nota_aroma" />
                  </div>
                  <p v-if="errors.nota_aroma" class="mt-1 text-sm text-red-600">{{ errors.nota_aroma }}</p>
                </div>

                <!-- Nota Textura -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Textura (0-10) *
                  </label>
                  <div class="flex items-center space-x-4">
                    <input
                      v-model="form.nota_textura"
                      type="number"
                      step="0.1"
                      min="0"
                      max="10"
                      required
                      class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-purple-500 focus:ring-purple-500"
                      :class="{ 'border-red-500': errors.nota_textura }"
                    />
                    <StarRating v-model="form.nota_textura" />
                  </div>
                  <p v-if="errors.nota_textura" class="mt-1 text-sm text-red-600">{{ errors.nota_textura }}</p>
                </div>
              </div>

              <!-- Nota Geral (calculada automaticamente) -->
              <div class="mt-6 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                <div class="flex items-center justify-between">
                  <span class="text-lg font-medium text-gray-900 dark:text-white">
                    Nota Geral:
                  </span>
                  <div class="flex items-center space-x-2">
                    <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                      {{ notaGeral.toFixed(1) }}
                    </span>
                    <div class="flex">
                      <svg
                        v-for="i in 5"
                        :key="i"
                        :class="[
                          'w-5 h-5',
                          i <= Math.ceil(notaGeral / 2) ? 'text-yellow-400' : 'text-gray-300'
                        ]"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Comentários e Recomendação -->
            <div data-aos="fade-up" data-aos-delay="100">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Comentários e Recomendação</h3>

              <div class="space-y-4">
                <!-- Comentários -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Comentários sobre a receita
                  </label>
                  <textarea
                    v-model="form.comentarios"
                    rows="4"
                    placeholder="Descreva sua experiência com a receita, pontos positivos e negativos..."
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-purple-500 focus:ring-purple-500"
                  ></textarea>
                </div>

                <!-- Recomendação -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Recomenda esta receita? *
                  </label>
                  <div class="flex space-x-4">
                    <label class="flex items-center">
                      <input
                        v-model="form.recomenda"
                        type="radio"
                        :value="true"
                        class="text-green-600 focus:ring-green-500"
                      />
                      <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Sim, recomendo</span>
                    </label>
                    <label class="flex items-center">
                      <input
                        v-model="form.recomenda"
                        type="radio"
                        :value="false"
                        class="text-red-600 focus:ring-red-500"
                      />
                      <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Não recomendo</span>
                    </label>
                  </div>
                </div>

                <!-- Sugestões de Melhoria -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Sugestões de Melhoria
                  </label>
                  <div class="space-y-2">
                    <div v-for="(sugestao, index) in form.sugestoes_melhoria" :key="index" class="flex items-center space-x-2">
                      <input
                        v-model="form.sugestoes_melhoria[index]"
                        type="text"
                        placeholder="Digite uma sugestão..."
                        class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-purple-500 focus:ring-purple-500"
                      />
                      <button
                        type="button"
                        @click="removeSugestao(index)"
                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md transition-colors"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                      </button>
                    </div>
                    <button
                      type="button"
                      @click="addSugestao"
                      class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm transition-colors"
                    >
                      Adicionar Sugestão
                    </button>
                  </div>
                </div>

                <!-- Observações Pós-Teste -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Observações Pós-Teste
                  </label>
                  <textarea
                    v-model="form.observacoes_pos_teste"
                    rows="3"
                    placeholder="Observações adicionais sobre o teste realizado..."
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-purple-500 focus:ring-purple-500"
                  ></textarea>
                </div>
              </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700" data-aos="fade-up" data-aos-delay="200">
              <Link
                :href="route('testes.show', teste.id)"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition-colors"
              >
                Cancelar
              </Link>
              <button
                type="submit"
                :disabled="processing"
                class="bg-purple-600 hover:bg-purple-700 disabled:bg-purple-400 text-white px-6 py-2 rounded-md transition-colors flex items-center space-x-2"
              >
                <span v-if="processing">
                  <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </span>
                <span>{{ processing ? 'Salvando...' : 'Salvar Avaliação' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/components/Layouts/AuthenticatedLayout.vue';
import StarRating from '@/components/Common/StarRating.vue';
import type { Teste } from '@/types'

interface Props {
  teste: Teste
  errors: Record<string, string>
}

const props = defineProps<Props>()
const processing = ref(false)

const form = useForm({
  nota_sabor: props.teste.avaliacao?.nota_sabor || 0,
  nota_apresentacao: props.teste.avaliacao?.nota_apresentacao || 0,
  nota_aroma: props.teste.avaliacao?.nota_aroma || 0,
  nota_textura: props.teste.avaliacao?.nota_textura || 0,
  comentarios: props.teste.avaliacao?.comentarios || '',
  recomenda: props.teste.avaliacao?.recomenda ?? true,
  sugestoes_melhoria: props.teste.avaliacao?.sugestoes_melhoria || [''],
  observacoes_pos_teste: props.teste.observacoes_pos_teste || ''
})

const notaGeral = computed(() => {
  const soma = Number(form.nota_sabor) + Number(form.nota_apresentacao) +
               Number(form.nota_aroma) + Number(form.nota_textura)
  return soma / 4
})

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

const addSugestao = () => {
  form.sugestoes_melhoria.push('')
}

const removeSugestao = (index: number) => {
  if (form.sugestoes_melhoria.length > 1) {
    form.sugestoes_melhoria.splice(index, 1)
  }
}

const submit = () => {
  processing.value = true

  // Filtrar sugestões vazias
  form.sugestoes_melhoria = form.sugestoes_melhoria.filter(s => s.trim() !== '')

  form.post(route('testes.salvarAvaliacao', props.teste.id), {
    onFinish: () => {
      processing.value = false
    }
  })
}
</script>
