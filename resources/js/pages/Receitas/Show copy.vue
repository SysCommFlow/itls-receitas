// resources/js/Pages/Receitas/Show.vue
<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <Link :href="route('receitas.index')"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
          </Link>
          <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ receita.nome }}
          </h2>
        </div>

        <div class="flex items-center space-x-2">
          <Link
            v-if="canEdit"
            :href="route('receitas.edit', receita.id)"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md transition-colors flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <span>Editar</span>
          </Link>

          <button
            v-if="canEdit"
            @click="togglePublicacao"
            :class="[
              'px-4 py-2 rounded-md transition-colors flex items-center space-x-2',
              receita.publicada
                ? 'bg-red-500 hover:bg-red-600 text-white'
                : 'bg-green-500 hover:bg-green-600 text-white'
            ]"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="receita.publicada" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            <span>{{ receita.publicada ? 'Despublicar' : 'Publicar' }}</span>
          </button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Imagens da Receita -->
        <div v-if="receita.imagens_media && receita.imagens_media.length > 0"
             class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden"
             data-aos="fade-up">
          <div class="swiper receita-images-swiper">
            <div class="swiper-wrapper">
              <div
                v-for="imagem in receita.imagens_media"
                :key="imagem.id"
                class="swiper-slide"
              >
                <img
                  :src="imagem.preview"
                  :alt="receita.nome"
                  class="w-full h-96 object-cover"
                />
              </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
          </div>
        </div>

        <!-- Grid Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Coluna Principal -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Informações Básicas -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6" data-aos="fade-up">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                  Informações da Receita
                </h3>
                <div class="flex space-x-2">
                  <span
                    v-if="receita.publicada"
                    class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300"
                  >
                    Publicada
                  </span>
                  <span
                    v-if="receita.testada"
                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"
                  >
                    Testada
                  </span>
                </div>
              </div>

              <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="text-center">
                  <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    {{ receita.tempo_cozimento }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">Minutos</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                    {{ receita.numero_porcoes }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">Porções</div>
                </div>
                <div v-if="typeof receita.nota_media === 'number'" class="text-center">
                  <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                    {{ receita.nota_media.toFixed(1) }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">Nota Média</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                    {{ estatisticas.total_testes }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">Testes</div>
                </div>
              </div>

              <div class="space-y-4">
                <div>
                  <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Categoria:</span>
                  <span class="ml-2 text-sm text-gray-900 dark:text-white">{{ receita.categoria.nome }}</span>
                </div>
                <div>
                  <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Criada por:</span>
                  <span class="ml-2 text-sm text-gray-900 dark:text-white">{{ receita.user.name }}</span>
                </div>
                <div>
                  <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Código:</span>
                  <span class="ml-2 text-sm text-gray-900 dark:text-white font-mono">{{ receita.codigo_unico }}</span>
                </div>
              </div>
            </div>

            <!-- Ingredientes -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6" data-aos="fade-up" data-aos-delay="100">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Ingredientes
              </h3>
              <div class="space-y-3">
                <div
                  v-for="ingrediente in receita.ingredientes"
                  :key="ingrediente.id"
                  class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
                >
                  <div class="flex-1">
                    <span class="font-medium text-gray-900 dark:text-white">
                      {{ ingrediente.nome }}
                    </span>
                    <p v-if="ingrediente.pivot?.observacoes" class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                      {{ ingrediente.pivot.observacoes }}
                    </p>
                  </div>
                  <div class="text-right">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                      {{ ingrediente.pivot?.quantidade }} {{ ingrediente.pivot?.unidade }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modo de Preparação -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6" data-aos="fade-up" data-aos-delay="200">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Modo de Preparação
              </h3>
              <div class="prose dark:prose-invert max-w-none">
                <div class="whitespace-pre-line text-gray-700 dark:text-gray-300">{{ receita.modo_preparacao }}</div>
              </div>
            </div>

            <!-- Observações -->
            <div v-if="receita.observacoes"
                 class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6"
                 data-aos="fade-up"
                 data-aos-delay="300">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Observações
              </h3>
              <div class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                {{ receita.observacoes }}
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="space-y-6">
            <!-- Estatísticas -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6" data-aos="fade-up" data-aos-delay="400">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Estatísticas
              </h3>
              <div class="space-y-4">
                <div class="flex justify-between">
                  <span class="text-sm text-gray-500 dark:text-gray-400">Total de Testes:</span>
                  <span class="text-sm font-medium text-gray-900 dark:text-white">{{ estatisticas.total_testes }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-500 dark:text-gray-400">Testes Concluídos:</span>
                  <span class="text-sm font-medium text-gray-900 dark:text-white">{{ estatisticas.testes_concluidos }}</span>
                </div>
                <div v-if="typeof receita.nota_media === 'number'" class="flex justify-between">
                  <span class="text-sm text-gray-500 dark:text-gray-400">Nota Média:</span>
                  <div class="flex items-center space-x-1">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ receita.nota_media.toFixed(1) }}</span>
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                  </div>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-500 dark:text-gray-400">Total de Avaliações:</span>
                  <span class="text-sm font-medium text-gray-900 dark:text-white">{{ estatisticas.total_avaliacoes }}</span>
                </div>
                <div v-if="estatisticas.percentual_recomendacao > 0" class="flex justify-between">
                  <span class="text-sm text-gray-500 dark:text-gray-400">% Recomendação:</span>
                  <span class="text-sm font-medium text-gray-900 dark:text-white">{{ estatisticas.percentual_recomendacao.toFixed(1) }}%</span>
                </div>
              </div>
            </div>

            <!-- Classificação -->
            <div v-if="typeof receita.nota_media === 'number'"
                 class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6"
                 data-aos="fade-up"
                 data-aos-delay="500">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Classificação
              </h3>
              <div class="text-center">
                <div class="text-3xl font-bold mb-2" :class="getClassificationColor(receita.classificacao)">
                  {{ receita.classificacao }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                  Baseado em {{ estatisticas.total_avaliacoes }} avaliação{{ estatisticas.total_avaliacoes !== 1 ? 'ões' : '' }}
                </div>
              </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6" data-aos="fade-up" data-aos-delay="600">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Ações
              </h3>
              <div class="space-y-3">
                <button
                  @click="imprimirReceita"
                  class="w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white px-4 py-2 rounded-md transition-colors flex items-center justify-center space-x-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                  </svg>
                  <span>Imprimir Receita</span>
                </button>

                <button
                  @click="compartilharReceita"
                  class="w-full bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-900 dark:text-blue-100 px-4 py-2 rounded-md transition-colors flex items-center justify-center space-x-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                  </svg>
                  <span>Compartilhar</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/components/Layouts/AuthenticatedLayout.vue'
import type { Receita as ReceitaBase, PageProps } from '@/types'

type IngredientePivot = {
  quantidade?: number | string
  unidade?: string
  observacoes?: string
}

type Ingrediente = {
  id: number
  nome: string
  pivot?: IngredientePivot
}

type Receita = ReceitaBase & {
  ingredientes: Ingrediente[]
}
import { Swiper } from 'swiper'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

interface Props {
  receita: Receita & {
    imagens_media?: Array<{
      id: number
      url: string
      thumb: string
      preview: string
      name: string
      file_name: string
    }>
    classificacao: string
  }
  estatisticas: {
    total_testes: number
    testes_concluidos: number
    nota_media: number | null
    total_avaliacoes: number
    percentual_recomendacao: number
  }
}

const props = defineProps<Props>()

const page = usePage<PageProps>()

const canEdit = computed(() => {
  const user = page.props.auth.user
  if (!user) return false
  return user.tipo_usuario === 'admin' || props.receita.user.id === user.id
})

const getClassificationColor = (classificacao: string) => {
  switch (classificacao) {
    case 'Excelente':
      return 'text-green-600 dark:text-green-400'
    case 'Muito Bom':
      return 'text-blue-600 dark:text-blue-400'
    case 'Bom':
      return 'text-yellow-600 dark:text-yellow-400'
    case 'Regular':
      return 'text-orange-600 dark:text-orange-400'
    case 'Ruim':
      return 'text-red-600 dark:text-red-400'
    default:
      return 'text-gray-600 dark:text-gray-400'
  }
}

const togglePublicacao = () => {
  router.post(route('receitas.toggle-publicacao', props.receita.id))
}

const imprimirReceita = () => {
  window.print()
}

const compartilharReceita = async () => {
  if (navigator.share) {
    try {
      await navigator.share({
        title: props.receita.nome,
        text: `Confira esta receita: ${props.receita.nome}`,
        url: window.location.href,
      })
    } catch (err) {
      console.log('Erro ao compartilhar:', err)
    }
  } else {
    // Fallback: copiar URL para clipboard
    try {
      await navigator.clipboard.writeText(window.location.href)
      alert('Link copiado para a área de transferência!')
    } catch (err) {
      console.log('Erro ao copiar:', err)
    }
  }
}

onMounted(() => {
  // Inicializar Swiper para as imagens
  if (props.receita.imagens_media && props.receita.imagens_media.length > 1) {
    new Swiper('.receita-images-swiper', {
      loop: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    })
  }
})
</script>

<style scoped>
.swiper {
  height: 400px;
}

.swiper-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

@media print {
  .swiper-button-next,
  .swiper-button-prev,
  .swiper-pagination {
    display: none !important;
  }
}</style>
