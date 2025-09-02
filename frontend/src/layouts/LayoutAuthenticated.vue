<script setup>
  import router from '@/router/index'
  import { asideMenuList } from '@/utils/menu'

  const logout = () => {
    localStorage.removeItem('access_token')
    router.push('/')
  }
</script>

<template>
  <div class="flex min-h-full h-[calc(100vh_-_5rem)]">
    <aside class="ml-3 w-56 bg-gray-100 p-4 border-r border-gray-200 mt-3 rounded-xl h-[calc(100vh_-_5rem)] flex flex-col">
      <nav>
        <ul class="list-none p-0">
          <li v-for="menuItem in asideMenuList" :key="menuItem.name">
            <a
              @click="router.push(menuItem.route)"
              class="text-gray-700 font-medium hover:text-blue-600 transition block text-left cursor-pointer"
              >{{ menuItem.name }}</a
            >
          </li>
        </ul>
      </nav>

      <div class="mt-auto pt-4 border-t border-gray-200">
        <button @click="logout" class="w-full rounded-lg px-3 py-2 text-left bg-red-200 hover:bg-red-300 text-red-700 hover:cursor-pointer">
          Logout
        </button>
      </div>
    </aside>
    <main class="flex-1 p-4 overflow-y-auto">
      <slot />
    </main>
  </div>
</template>
