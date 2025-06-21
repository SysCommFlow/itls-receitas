<template>
  <div class="min-h-screen bg-background">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 w-full border-b border-border/40 bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div class="container flex h-16 max-w-screen-2xl items-center justify-between px-4">
        <!-- Logo -->
        <div class="flex items-center space-x-4">
          <Link
            :href="route('dashboard')"
            class="flex items-center space-x-2 transition-opacity hover:opacity-80"
          >
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-primary-foreground">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
              </svg>
            </div>
            <span class="text-xl font-bold tracking-tight">ITLS Receitas</span>
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex md:items-center md:space-x-6">
          <NavigationMenu>
            <NavigationMenuList>
              <NavigationMenuItem>
                <NavigationMenuLink
                  :href="route('dashboard')"
                  :class="cn(
                    navigationMenuTriggerStyle(),
                    route().current('dashboard') && 'bg-accent text-accent-foreground'
                  )"
                >
                  Painel de controle
                </NavigationMenuLink>
              </NavigationMenuItem>

              <NavigationMenuItem>
                <NavigationMenuLink
                  :href="route('receitas.index')"
                  :class="cn(
                    navigationMenuTriggerStyle(),
                    route().current('receitas.*') && 'bg-accent text-accent-foreground'
                  )"
                >
                  Receitas
                </NavigationMenuLink>
              </NavigationMenuItem>

              <NavigationMenuItem>
                <NavigationMenuLink
                  :href="route('testes.index')"
                  :class="cn(
                    navigationMenuTriggerStyle(),
                    route().current('testes.*') && 'bg-accent text-accent-foreground'
                  )"
                >
                  Testes
                </NavigationMenuLink>
              </NavigationMenuItem>

              <NavigationMenuItem v-if="page.props.auth.user.tipo_usuario === 'admin'">
                <NavigationMenuLink
                  :href="route('ingredientes.index')"
                  :class="cn(
                    navigationMenuTriggerStyle(),
                    route().current('ingredientes.*') && 'bg-accent text-accent-foreground'
                  )"
                >
                  Ingredientes
                </NavigationMenuLink>
              </NavigationMenuItem>
            </NavigationMenuList>
          </NavigationMenu>
        </div>

        <!-- User Menu & Mobile Menu Button -->
        <div class="flex items-center space-x-4">
          <DropdownMenu>
            <DropdownMenuTrigger as-child>
              <Button variant="ghost" class="relative h-9 w-9 rounded-full">
                <Avatar class="h-9 w-9">
                  <AvatarImage :src="userAvatar" :alt="page.props.auth.user.name" />
                  <AvatarFallback>
                    {{ getInitials(page.props.auth.user.name) }}
                  </AvatarFallback>
                </Avatar>
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent class="w-56" align="end">
              <DropdownMenuLabel class="font-normal">
                <div class="flex flex-col space-y-1">
                  <p class="text-sm font-medium leading-none">{{ page.props.auth.user.name }}</p>
                  <p class="text-xs leading-none text-muted-foreground">
                    {{ page.props.auth.user.email }}
                  </p>
                </div>
              </DropdownMenuLabel>
              <DropdownMenuSeparator />
              <DropdownMenuGroup>
                <DropdownMenuItem @click="navigateTo('profile.edit')">
                  <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  <span>Perfil</span>
                </DropdownMenuItem>
                <DropdownMenuItem v-if="page.props.auth.user.tipo_usuario === 'admin'" @click="navigateTo('admin.settings')">
                  <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <span>Configurações</span>
                </DropdownMenuItem>
              </DropdownMenuGroup>
              <DropdownMenuSeparator />
              <DropdownMenuItem @click="logout" class="text-red-600 focus:text-red-600">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Sair</span>
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>

          <!-- Mobile Menu Button -->
          <Sheet v-model:open="mobileMenuOpen">
            <SheetTrigger as-child>
              <Button variant="ghost" size="icon" class="md:hidden">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <span class="sr-only">Abrir menu</span>
              </Button>
            </SheetTrigger>
            <SheetContent side="right" class="w-[300px] sm:w-[400px]">
              <SheetHeader>
                <SheetTitle>Menu de Navegação</SheetTitle>
              </SheetHeader>
              <div class="mt-6 flex flex-col space-y-3">
                <MobileNavLink
                  :href="route('dashboard')"
                  :active="route().current('dashboard')"
                  @click="mobileMenuOpen = false"
                >
                  Painel de controle
                </MobileNavLink>
                <MobileNavLink
                  :href="route('receitas.index')"
                  :active="route().current('receitas.*')"
                  @click="mobileMenuOpen = false"
                >
                  Receitas
                </MobileNavLink>
                <MobileNavLink
                  :href="route('testes.index')"
                  :active="route().current('testes.*')"
                  @click="mobileMenuOpen = false"
                >
                  Testes
                </MobileNavLink>
                <MobileNavLink
                  v-if="page.props.auth.user.tipo_usuario === 'admin'"
                  :href="route('ingredientes.index')"
                  :active="route().current('ingredientes.*')"
                  @click="mobileMenuOpen = false"
                >
                  Ingredientes
                </MobileNavLink>
              </div>
            </SheetContent>
          </Sheet>
        </div>
      </div>
    </nav>

    <!-- Page Header -->
    <header v-if="$slots.header" class="border-b border-border bg-muted/40">
      <div class="container mx-auto py-6 px-4">
        <slot name="header" />
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
      <div ref="contentRef" class="animate__animated">
        <slot />
      </div>
    </main>

    <!-- Sonner Toast Container -->
    <Toaster
      position="top-right"
      :theme="isDark ? 'dark' : 'light'"
      :rich-colors="true"
      :close-button="true"
      :expand="true"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { gsap } from 'gsap'
import { toast } from 'vue-sonner'

// Utility function for conditional class names
function cn(...classes: (string | boolean | undefined | null)[]) {
  return classes.filter(Boolean).join(' ')
}

// shadcn/ui Components
import {
  NavigationMenu,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuList,
  navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
  DropdownMenuGroup,
} from '@/components/ui/dropdown-menu'
import {
  Sheet,
  SheetContent,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from '@/components/ui/sheet'
import { Toaster } from '@/components/ui/sonner'
import { Button } from '@/components/ui/button'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'

// Types
interface User {
  id: number
  name: string
  email: string
  tipo_usuario: 'admin' | 'cozinheiro' | 'degustador'
}

interface PageProps {
  auth: {
    user: User
  }
  flash?: {
    success?: string
    error?: string
  }
  [key: string]: unknown
}

// Reactive state
const mobileMenuOpen = ref(false)
const contentRef = ref<HTMLElement>()
const page = usePage<PageProps>()

// Theme detection
const isDark = computed(() => {
  return document.documentElement.classList.contains('dark')
})

// Computed properties
const userAvatar = computed(() => {
  return `https://ui-avatars.com/api/?name=${encodeURIComponent(page.props.auth.user.name)}&background=0ea5e9&color=fff`
})

// Methods
const getInitials = (name: string): string => {
  return name
    .split(' ')
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
    .slice(0, 2)
}

const navigateTo = (routeName: string) => {
  router.visit(route(routeName))
}

const logout = () => {
  router.post(route('logout'))
}

// Watch for flash messages and show Sonner toasts
watch(
  () => page.props.flash,
  (newFlash) => {
    if (newFlash?.success) {
      toast.success('Sucesso', {
        description: newFlash.success,
        duration: 5000,
      })
    }
    if (newFlash?.error) {
      toast.error('Erro', {
        description: newFlash.error,
        duration: 5000,
      })
    }
  },
  { immediate: true, deep: true }
)

// GSAP Animations
onMounted(() => {
  if (contentRef.value) {
    gsap.fromTo(
      contentRef.value,
      { opacity: 0, y: 20 },
      { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out' }
    )
  }

  gsap.fromTo(
    'nav .container > *',
    { opacity: 0, y: -10 },
    { opacity: 1, y: 0, duration: 0.5, stagger: 0.1, ease: 'power2.out' }
  )
})

// Expose toast function for use in child components
defineExpose({
  showToast: toast
})
</script>

<style scoped>
/* Custom animations */
@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.slide-in {
  animation: slideIn 0.3s ease-out;
}


/* Mobile menu overlay */
.mobile-menu-overlay {
  backdrop-filter: blur(4px);
}
</style>
