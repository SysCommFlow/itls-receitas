/*import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}
*/

// resources/js/lib/utils.ts
import { type ClassValue, clsx } from 'clsx'
import { twMerge } from 'tailwind-merge'

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs))
}

export function formatCurrency(value: number): string {
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  }).format(value)
}

export function formatDate(date: string | Date): string {
  return new Intl.DateTimeFormat('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  }).format(new Date(date))
}

export function formatDateTime(date: string | Date): string {
  return new Intl.DateTimeFormat('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(new Date(date))
}

export function formatRelativeTime(date: string | Date): string {
  const rtf = new Intl.RelativeTimeFormat('pt-BR', { numeric: 'auto' })
  const now = new Date()
  const targetDate = new Date(date)
  const diffInSeconds = (targetDate.getTime() - now.getTime()) / 1000

  const intervals = [
    { unit: 'year', seconds: 31536000 },
    { unit: 'month', seconds: 2628000 },
    { unit: 'day', seconds: 86400 },
    { unit: 'hour', seconds: 3600 },
    { unit: 'minute', seconds: 60 }
  ]

  for (const interval of intervals) {
    const count = Math.floor(Math.abs(diffInSeconds) / interval.seconds)
    if (count >= 1) {
      return rtf.format(
        diffInSeconds < 0 ? -count : count,
        interval.unit as Intl.RelativeTimeFormatUnit
      )
    }
  }

  return 'agora'
}

export function truncateText(text: string, length: number): string {
  if (text.length <= length) return text
  return text.slice(0, length) + '...'
}

export function debounce<T extends (...args: any[]) => any>(
  func: T,
  wait: number
): (...args: Parameters<T>) => void {
  let timeout: NodeJS.Timeout
  return (...args: Parameters<T>) => {
    clearTimeout(timeout)
    timeout = setTimeout(() => func(...args), wait)
  }
}

export function getStatusColor(status: string): string {
  const colors = {
    agendado: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
    em_andamento: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
    concluido: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    cancelado: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    rascunho: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
    em_revisao: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
    publicado: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
  }
  return colors[status as keyof typeof colors] || 'bg-gray-100 text-gray-800'
}

export function getStatusLabel(status: string): string {
  const labels = {
    agendado: 'Agendado',
    em_andamento: 'Em Andamento',
    concluido: 'Concluído',
    cancelado: 'Cancelado',
    rascunho: 'Rascunho',
    em_revisao: 'Em Revisão',
    publicado: 'Publicado'
  }
  return labels[status as keyof typeof labels] || status
}

export function validateISBN(isbn: string): boolean {
  // Remove hífens e espaços
  const cleanISBN = isbn.replace(/[-\s]/g, '')

  // Verifica se tem 10 ou 13 dígitos
  if (!/^\d{10}(\d{3})?$/.test(cleanISBN)) {
    return false
  }

  if (cleanISBN.length === 10) {
    // Validação ISBN-10
    let sum = 0
    for (let i = 0; i < 9; i++) {
      sum += parseInt(cleanISBN[i]) * (10 - i)
    }
    const checkDigit = cleanISBN[9] === 'X' ? 10 : parseInt(cleanISBN[9])
    return (sum + checkDigit) % 11 === 0
  } else {
    // Validação ISBN-13
    let sum = 0
    for (let i = 0; i < 12; i++) {
      sum += parseInt(cleanISBN[i]) * (i % 2 === 0 ? 1 : 3)
    }
    const checkDigit = parseInt(cleanISBN[12])
    return (sum + checkDigit) % 10 === 0
  }
}
