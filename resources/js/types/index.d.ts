import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export type BreadcrumbItemType = BreadcrumbItem;


// resources/js/types/index.d.ts
export interface User {
  id: number
  name: string
  email: string
  email_verified_at?: string
  tipo_usuario: 'cozinheiro' | 'degustador' | 'admin'
  telefone?: string
  data_nascimento?: string
  bio?: string
  foto_perfil?: string
  especializacoes?: string[]
  ativo: boolean
  ultimo_acesso?: string
  created_at: string
  updated_at: string
  // Computed attributes
  receitas_publicadas_count?: number
  receitas_testadas_count?: number
  nota_media_receitas?: number
}

export interface Categoria {
  id: number
  nome: string
  descricao?: string
  ativo: boolean
  created_at: string
  updated_at: string
  // Computed attributes
  receitas_count?: number
  receitas_publicadas_count?: number
}

export interface Ingrediente {
  id: number
  nome: string
  descricao?: string
  unidade_medida: string
  preco_medio?: number
  exotico: boolean
  created_at: string
  updated_at: string
  // Computed attributes
  receitas_count?: number
}

export interface ReceitaIngrediente {
  id: number
  receita_id: number
  ingrediente_id: number
  quantidade: number
  unidade: string
  observacoes?: string
  ingrediente: Ingrediente
}
/*
export interface Receita {
  id: number
  codigo_unico: string
  nome: string
  user_id: number
  categoria_id: number
  modo_preparacao: string
  tempo_cozimento: number
  numero_porcoes: number
  observacoes?: string
  imagens?: string[]
  publicada: boolean
  testada: boolean
  nota_media?: number
  created_at: string
  updated_at: string
  // Relations
  user: User
  categoria: Categoria
  ingredientes?: ReceitaIngrediente[]
  testes?: Teste[]
  avaliacoes?: Avaliacao[]
  // Computed attributes
  tempo_formatado?: string
  custo_estimado?: number
  classificacao?: string
  imagens_media?: MediaFile[]
}
*/

export interface Receita {
  id: number
  codigo_unico: string
  nome: string
  user_id: number
  categoria_id: number
  modo_preparacao: string
  tempo_cozimento: number
  numero_porcoes: number
  observacoes?: string
  imagens?: string[]
  publicada: boolean
  testada: boolean
  nota_media?: number
  created_at: string
  updated_at: string
  user: User
  categoria: Categoria
  ingredientes?: Ingrediente[]
  imagens_urls?: Array<{
    id: number
    url: string
    thumb: string
    preview: string
  }>
  imagens_media?: Array<{
    id: number
    url: string
    thumb: string
    preview: string
    name: string
    file_name: string
  }>
  imagens_existentes?: Array<{
    id: number
    url: string
    thumb: string
    preview: string
    name: string
    file_name: string
  }>
  classificacao?: string
  tempo_formatado?: string
  custo_estimado?: number
}

export interface Teste {
  id: number
  receita_id: number
  degustador_id: number
  data_teste: string
  observacoes?: string
  concluido: boolean
  created_at: string
  updated_at: string
  receita: Receita
  degustador: User
  avaliacao?: Avaliacao
}

export interface PageProps {
  auth: {
    user: {
      id: number
      name: string
      email: string
      tipo_usuario: string
    } | null
  }
  // ... outros campos
  [key: string]: unknown
}

export interface Degustador {
  id: number
  nome: string
  email: string
  telefone?: string
  especializacoes?: string
  experiencia_anos?: number
  nota_media_avaliacoes?: number
  ativo: boolean
  created_at: string
  updated_at: string
  // Computed attributes
  testes_concluidos_count?: number
  testes_pendentes_count?: number
}

export interface Teste {
  id: number
  receita_id: number
  degustador_id: number
  data_teste: string
  status: 'agendado' | 'em_andamento' | 'concluido' | 'cancelado'
  observacoes_pre_teste?: string
  observacoes_pos_teste?: string
  fotos_teste?: string[]
  created_at: string
  updated_at: string
  // Relations
  receita: Receita
  degustador: Degustador
  avaliacao?: Avaliacao
  // Computed attributes
  data_formatada?: string
  dias_da_data_teste?: number
  fotos_media?: MediaFile[]
}

export interface Avaliacao {
  id: number
  teste_id: number
  nota_sabor: number
  nota_apresentacao: number
  nota_aroma: number
  nota_textura: number
  nota_geral: number
  comentarios?: string
  recomenda: boolean
  sugestoes_melhoria?: string[]
  created_at: string
  updated_at: string
  // Relations
  teste: Teste
  // Computed attributes
  nota_geral_formatada?: string
  classificacao?: string
  cor_classificacao?: string
}

export interface Restaurante {
  id: number
  nome: string
  endereco: string
  telefone?: string
  email?: string
  tipo_cozinha: string
  pratos_confeccionados?: number[]
  nota_media?: number
  ativo: boolean
  created_at: string
  updated_at: string
  // Computed attributes
  pratos_count?: number
}

export interface Editor {
  id: number
  nome: string
  email: string
  telefone?: string
  editora: string
  especializacoes?: string
  ativo: boolean
  created_at: string
  updated_at: string
  // Computed attributes
  livros_publicados_count?: number
  livros_em_andamento_count?: number
}

export interface Livro {
  id: number
  titulo: string
  isbn: string
  editor_id: number
  receitas_incluidas?: number[]
  data_publicacao?: string
  descricao?: string
  capa?: string
  status: 'rascunho' | 'em_revisao' | 'publicado'
  created_at: string
  updated_at: string
  // Relations
  editor: Editor
  receitas?: Receita[]
  // Computed attributes
  numero_receitas?: number
}

export interface MediaFile {
  id: number
  name: string
  file_name: string
  mime_type: string
  size: number
  url: string
  preview_url?: string
}

export interface DashboardStats {
  total_receitas: number
  receitas_publicadas: number
  receitas_testadas: number
  testes_pendentes: number
  testes_concluidos: number
  total_usuarios: number
  total_degustadores: number
  total_restaurantes: number
  minhas_receitas?: number
  receitas_publicadas_minhas?: number
  nota_media_minhas?: number
}

export interface PaginatedData<T> {
  data: T[]
  links: PaginationLink[]
  meta: {
    current_page: number
    from: number
    last_page: number
    per_page: number
    to: number
    total: number
  }
}

export interface PaginationLink {
  url: string | null
  label: string
  active: boolean
}

export interface PageProps {
  auth: {
    user: User | null
  }
  ziggy: {
    location: string
    query?: Record<string, string>
  }
  flash: {
    success?: string
    error?: string
    warning?: string
    info?: string
  }
}

export interface FilterOptions {
  categoria?: string
  search?: string
  status?: string
  order_by?: string
  order_direction?: 'asc' | 'desc'
  data_inicio?: string
  data_fim?: string
  degustador?: string
  receita?: string
  exotico?: boolean
  unidade?: string
  ativo?: boolean
}

/* Tipos para formulários
export interface ReceitaFormData {
  nome: string
  categoria_id: string
  modo_preparacao: string
  tempo_cozimento: number
  numero_porcoes: number
  observacoes?: string
  publicada: boolean
  ingredientes: {
    id: string
    quantidade: number
    unidade: string
    observacoes?: string
  }[]
  imagens?: File[]
  novas_imagens?: File[]
  imagens_remover?: number[]
}
*/
// Tipos para formulários
export interface ReceitaFormData {
  nome: string
  categoria_id: string | number
  modo_preparacao: string
  tempo_cozimento: string | number
  numero_porcoes: string | number
  observacoes?: string
  ingredientes: Array<{
    id: string | number
    quantidade: string | number
    unidade: string
    observacoes?: string
  }>
  imagens?: File[]
  novas_imagens?: File[]
  imagens_remover?: number[]
}

// Tipos para filtros
export interface ReceitaFilters {
  categoria?: string
  search?: string
  status?: string
  order_by?: string
  order_direction?: 'asc' | 'desc'
}
// Tipos para estatísticas
export interface ReceitaEstatisticas {
  total_testes: number
  testes_concluidos: number
  nota_media: number | null
  total_avaliacoes: number
  percentual_recomendacao: number
}


export interface TesteFormData {
  receita_id: string
  degustador_id: string
  data_teste: string
  observacoes_pre_teste?: string
}

export interface AvaliacaoFormData {
  nota_sabor: number
  nota_apresentacao: number
  nota_aroma: number
  nota_textura: number
  comentarios?: string
  recomenda: boolean
  sugestoes_melhoria: string[]
  observacoes_pos_teste?: string
  fotos_teste?: File[]
}

export interface IngredienteFormData {
  nome: string
  descricao?: string
  unidade_medida: string
  preco_medio?: number
  exotico: boolean
}

export interface CategoriaFormData {
  nome: string
  descricao?: string
  ativo: boolean
}

export interface DegustadorFormData {
  nome: string
  email: string
  telefone?: string
  especializacoes?: string
  experiencia_anos?: number
  ativo: boolean
}

export interface RestauranteFormData {
  nome: string
  endereco: string
  telefone?: string
  email?: string
  tipo_cozinha: string
  ativo: boolean
}

export interface EditorFormData {
  nome: string
  email: string
  telefone?: string
  editora: string
  especializacoes?: string
  ativo: boolean
}

export interface LivroFormData {
  titulo: string
  isbn: string
  editor_id: string
  receitas_incluidas?: number[]
  data_publicacao?: string
  descricao?: string
  status: 'rascunho' | 'em_revisao' | 'publicado'
  capa?: File
}

export interface UserProfileFormData {
  name: string
  email: string
  telefone?: string
  data_nascimento?: string
  bio?: string
  especializacoes?: string[]
  foto_perfil?: File
}

// Tipos para relatórios
export interface RelatorioData {
  data: any[]
  estatisticas: Record<string, any>
  periodo?: {
    inicio: string
    fim: string
  }
}

export interface ChartData {
  labels: string[]
  datasets: {
    label: string
    data: number[]
    backgroundColor?: string[]
    borderColor?: string[]
  }[]
}

// Tipos para componentes específicos
export interface StarRatingProps {
  modelValue: number
  readonly?: boolean
  size?: 'sm' | 'md' | 'lg'
}

export interface TableColumn {
  key: string
  label: string
  sortable?: boolean
  width?: string
  align?: 'left' | 'center' | 'right'
}

export interface ActionButton {
  label: string
  action: () => void
  variant?: 'primary' | 'secondary' | 'danger' | 'success'
  icon?: string
  loading?: boolean
  disabled?: boolean
}

export interface NotificationData {
  id: string
  type: 'success' | 'error' | 'warning' | 'info'
  title: string
  message?: string
  duration?: number
  actions?: ActionButton[]
}

// Global declarations
declare global {
  interface Window {
    Laravel: {
      csrfToken: string
    }
  }
}

export {}
