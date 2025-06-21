// resources/js/Pages/Receitas/Create.vue - CORRIGIDO
<template>
  <AppLayout :breadcrumbs="breadcambs">
    <Head title="Nova Receita" />
    <template #header>
      <div class="flex items-center space-x-4">
        <Link :href="route('receitas.index')"
              class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
        </Link>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          Nova Receita
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
          <form @submit.prevent="submit" class="p-6 space-y-6" enctype="multipart/form-data">
            <!-- Informações Básicas -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-6" data-aos="fade-up">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Informações Básicas
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nome da Receita *
                  </label>
                  <input
                    v-model="form.nome"
                    type="text"
                    required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.nome }"
                  />
                  <p v-if="errors.nome" class="mt-1 text-sm text-red-600">{{ errors.nome }}</p>
                </div>

                <!-- Categoria -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Categoria *
                  </label>
                  <select
                    v-model="form.categoria_id"
                    required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.categoria_id }"
                  >
                    <option value="">Selecione uma categoria</option>
                    <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">
                      {{ categoria.nome }}
                    </option>
                  </select>
                  <p v-if="errors.categoria_id" class="mt-1 text-sm text-red-600">{{ errors.categoria_id }}</p>
                </div>

                <!-- Tempo de Cozimento -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tempo de Cozimento (minutos) *
                  </label>
                  <input
                    v-model="form.tempo_cozimento"
                    type="number"
                    min="1"
                    required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.tempo_cozimento }"
                  />
                  <p v-if="errors.tempo_cozimento" class="mt-1 text-sm text-red-600">{{ errors.tempo_cozimento }}</p>
                </div>

                <!-- Número de Porções -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Número de Porções *
                  </label>
                  <input
                    v-model="form.numero_porcoes"
                    type="number"
                    min="1"
                    required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                    :class="{ 'border-red-500': errors.numero_porcoes }"
                  />
                  <p v-if="errors.numero_porcoes" class="mt-1 text-sm text-red-600">{{ errors.numero_porcoes }}</p>
                </div>
              </div>
            </div>

            <!-- Ingredientes -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-6" data-aos="fade-up" data-aos-delay="100">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                  Ingredientes
                </h3>
                <button
                  type="button"
                  @click="addIngrediente"
                  class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm transition-colors"
                >
                  Adicionar Ingrediente
                </button>
              </div>

              <div class="space-y-4">
                <div
                  v-for="(ingrediente, index) in form.ingredientes"
                  :key="index"
                  class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end p-4 bg-gray-50 dark:bg-gray-700 rounded-lg animate__animated animate__fadeIn"
                >
                  <!-- Ingrediente -->
                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      Ingrediente
                    </label>
                    <select
                      v-model="ingrediente.id"
                      required
                      class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                    >
                      <option value="">Selecione um ingrediente</option>
                      <option v-for="ing in ingredientes" :key="ing.id" :value="ing.id">
                        {{ ing.nome }}
                      </option>
                    </select>
                  </div>

                  <!-- Quantidade -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      Quantidade
                    </label>
                    <input
                      v-model="ingrediente.quantidade"
                      type="number"
                      step="0.01"
                      min="0"
                      required
                      class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                    />
                  </div>

                  <!-- Unidade -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      Unidade
                    </label>
                    <input
                      v-model="ingrediente.unidade"
                      type="text"
                      placeholder="ex: xícaras, gramas..."
                      required
                      class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                    />
                  </div>

                  <!-- Remover -->
                  <div>
                    <button
                      type="button"
                      @click="removeIngrediente(index)"
                      class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md transition-colors"
                    >
                      Remover
                    </button>
                  </div>
                </div>
              </div>

              <p v-if="errors.ingredientes" class="mt-2 text-sm text-red-600">{{ errors.ingredientes }}</p>
            </div>

            <!-- Modo de Preparação -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-6" data-aos="fade-up" data-aos-delay="200">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Modo de Preparação
              </h3>

              <textarea
                v-model="form.modo_preparacao"
                rows="8"
                required
                placeholder="Descreva detalhadamente o modo de preparação da receita..."
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.modo_preparacao }"
              ></textarea>
              <p v-if="errors.modo_preparacao" class="mt-1 text-sm text-red-600">{{ errors.modo_preparacao }}</p>
            </div>

            <!-- Observações e Imagens -->
            <div data-aos="fade-up" data-aos-delay="300">
              <!-- Observações -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Observações (opcional)
                </label>
                <textarea
                  v-model="form.observacoes"
                  rows="3"
                  placeholder="Dicas especiais, variações, etc..."
                  class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                ></textarea>
              </div>

              <!-- Upload de Imagens -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Imagens da Receita (opcional)
                </label>
                <input
                  ref="fileInput"
                  type="file"
                  multiple
                  accept="image/*"
                  @change="handleImageUpload"
                  class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300"
                />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                  PNG, JPG, JPEG até 2MB cada. Máximo 5 imagens.
                </p>

                <!-- Preview das imagens selecionadas -->
                <div v-if="selectedImages.length > 0" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                  <div
                    v-for="(image, index) in selectedImages"
                    :key="index"
                    class="relative group"
                  >
                    <img
                      :src="image.preview"
                      :alt="`Preview ${index + 1}`"
                      class="w-full h-32 object-cover rounded-lg"
                    />
                    <button
                      type="button"
                      @click="removeImage(index)"
                      class="absolute top-2 right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center text-xs transition-colors"
                    >
                      ×
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-end space-x-4 pt-6" data-aos="fade-up" data-aos-delay="400">
              <Link
                :href="route('receitas.index')"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition-colors"
              >
                Cancelar
              </Link>
              <button
                type="submit"
                :disabled="processing"
                class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-6 py-2 rounded-md transition-colors flex items-center space-x-2"
              >
                <span v-if="processing">
                  <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </span>
                <span>{{ processing ? 'Salvando...' : 'Salvar Receita' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import type { Categoria, Ingrediente, BreadcrumbItem } from '@/types'


const breadcambs: BreadcrumbItem[] = [
    {
        title: 'Criar nova receita',
        href: '/receitas/create',
    }
];

interface Props {
  categorias: Categoria[]
  ingredientes: Ingrediente[]
  errors: Record<string, string>
}

defineProps<Props>()

const form = useForm({
  nome: '',
  categoria_id: '',
  modo_preparacao: '',
  tempo_cozimento: '',
  numero_porcoes: '',
  observacoes: '',
  ingredientes: [{ id: '', quantidade: '', unidade: '', observacoes: '' }],
  imagens: [] as File[]
})

const processing = ref(false)
const fileInput = ref<HTMLInputElement>()
const selectedImages = ref<Array<{ file: File; preview: string }>>([])

const addIngrediente = () => {
  form.ingredientes.push({ id: '', quantidade: '', unidade: '', observacoes: '' })
}

const removeIngrediente = (index: number) => {
  if (form.ingredientes.length > 1) {
    form.ingredientes.splice(index, 1)
  }
}

const handleImageUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files) {
    const files = Array.from(target.files).slice(0, 5) // Máximo 5 imagens

    // Limpar previews anteriores
    selectedImages.value.forEach(img => URL.revokeObjectURL(img.preview))
    selectedImages.value = []

    // Criar previews das novas imagens
    files.forEach(file => {
      if (file.type.startsWith('image/')) {
        const preview = URL.createObjectURL(file)
        selectedImages.value.push({ file, preview })
      }
    })

    // Atualizar form com os arquivos
    form.imagens = files
  }
}

const removeImage = (index: number) => {
  // Remover preview da memória
  URL.revokeObjectURL(selectedImages.value[index].preview)

  // Remover da lista de previews
  selectedImages.value.splice(index, 1)

  // Atualizar form
  form.imagens = selectedImages.value.map(img => img.file)

  // Atualizar input file
  if (fileInput.value) {
    const dataTransfer = new DataTransfer()
    form.imagens.forEach(file => dataTransfer.items.add(file))
    fileInput.value.files = dataTransfer.files
  }
}

const submit = () => {
  processing.value = true

  // Usar post com forceFormData para garantir que os arquivos sejam enviados corretamente
  form.post(route('receitas.store'), {
    forceFormData: true,
    onFinish: () => {
      processing.value = false
    },
    onSuccess: () => {
      // Limpar previews da memória ao sucesso
      selectedImages.value.forEach(img => URL.revokeObjectURL(img.preview))
    },
    onError: () => {
      // Manter previews em caso de erro para o usuário não perder o trabalho
    }
  })
}
</script>
