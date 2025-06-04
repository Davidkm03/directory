<template>
  <MainLayout>
    <div class="min-h-screen bg-gray-50">
      <!-- Header -->
      <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="py-6">
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="mt-1 text-gray-600">
              Welcome back, {{ user?.name || 'Administrator' }}! Here's what's happening on your platform.
            </p>
          </div>
        </div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Total Users -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ stats.totalUsers }}</dd>
                  </dl>
                </div>
              </div>
              <div class="mt-4">
                <div class="flex items-center text-sm">
                  <span class="text-green-600 font-medium">+12%</span>
                  <span class="text-gray-500 ml-2">from last month</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Dentists -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Dentists</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ stats.dentists }}</dd>
                  </dl>
                </div>
              </div>
              <div class="mt-4">
                <div class="flex items-center text-sm">
                  <span class="text-green-600 font-medium">+5</span>
                  <span class="text-gray-500 ml-2">new this week</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Patients -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Patients</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ stats.patients }}</dd>
                  </dl>
                </div>
              </div>
              <div class="mt-4">
                <div class="flex items-center text-sm">
                  <span class="text-green-600 font-medium">+8%</span>
                  <span class="text-gray-500 ml-2">from last month</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Pending Verifications -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Pending Verifications</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ stats.pendingVerifications }}</dd>
                  </dl>
                </div>
              </div>
              <div class="mt-4">
                <div class="flex items-center text-sm">
                  <span class="text-yellow-600 font-medium">{{ stats.pendingVerifications }}</span>
                  <span class="text-gray-500 ml-2">need review</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg mb-8">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              <!-- Manage Users -->
              <button
                @click="navigateTo('/admin/users')"
                class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 border border-gray-300 rounded-lg hover:border-gray-400 transition-colors"
              >
                <div>
                  <span class="rounded-lg inline-flex p-3 bg-blue-50 text-blue-700 ring-4 ring-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                  </span>
                </div>
                <div class="mt-8">
                  <h3 class="text-lg font-medium text-gray-900">Manage Users</h3>
                  <p class="mt-2 text-sm text-gray-500">
                    View, edit, and manage all user accounts on the platform.
                  </p>
                </div>
              </button>

              <!-- Verification Requests -->
              <button
                @click="navigateTo('/admin/verification')"
                class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-yellow-500 border border-gray-300 rounded-lg hover:border-gray-400 transition-colors"
              >
                <div>
                  <span class="rounded-lg inline-flex p-3 bg-yellow-50 text-yellow-700 ring-4 ring-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </span>
                </div>
                <div class="mt-8">
                  <h3 class="text-lg font-medium text-gray-900">Verification Requests</h3>
                  <p class="mt-2 text-sm text-gray-500">
                    Review and approve dentist verification requests.
                  </p>
                </div>
              </button>

              <!-- Content Management -->
              <button
                @click="navigateTo('/admin/content')"
                class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-green-500 border border-gray-300 rounded-lg hover:border-gray-400 transition-colors"
              >
                <div>
                  <span class="rounded-lg inline-flex p-3 bg-green-50 text-green-700 ring-4 ring-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </span>
                </div>
                <div class="mt-8">
                  <h3 class="text-lg font-medium text-gray-900">Content Management</h3>
                  <p class="mt-2 text-sm text-gray-500">
                    Manage articles, resources, and platform content.
                  </p>
                </div>
              </button>

              <!-- Platform Settings -->
              <button
                @click="navigateTo('/admin/settings')"
                class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-purple-500 border border-gray-300 rounded-lg hover:border-gray-400 transition-colors"
              >
                <div>
                  <span class="rounded-lg inline-flex p-3 bg-purple-50 text-purple-700 ring-4 ring-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </span>
                </div>
                <div class="mt-8">
                  <h3 class="text-lg font-medium text-gray-900">Platform Settings</h3>
                  <p class="mt-2 text-sm text-gray-500">
                    Configure platform settings and preferences.
                  </p>
                </div>
              </button>

              <!-- Reports & Analytics -->
              <button
                @click="navigateTo('/admin/reports')"
                class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-500 border border-gray-300 rounded-lg hover:border-gray-400 transition-colors"
              >
                <div>
                  <span class="rounded-lg inline-flex p-3 bg-red-50 text-red-700 ring-4 ring-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                  </span>
                </div>
                <div class="mt-8">
                  <h3 class="text-lg font-medium text-gray-900">Reports & Analytics</h3>
                  <p class="mt-2 text-sm text-gray-500">
                    View platform analytics and generate reports.
                  </p>
                </div>
              </button>

              <!-- Browse Directory -->
              <button
                @click="navigateTo('/directory')"
                class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500 border border-gray-300 rounded-lg hover:border-gray-400 transition-colors"
              >
                <div>
                  <span class="rounded-lg inline-flex p-3 bg-indigo-50 text-indigo-700 ring-4 ring-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                  </span>
                </div>
                <div class="mt-8">
                  <h3 class="text-lg font-medium text-gray-900">Browse Directory</h3>
                  <p class="mt-2 text-sm text-gray-500">
                    View the public dentist directory as users see it.
                  </p>
                </div>
              </button>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Activity</h3>
            
            <div class="flow-root">
              <ul class="-mb-8">
                <li v-for="(activity, index) in recentActivity" :key="activity.id">
                  <div class="relative pb-8" :class="{ 'pb-0': index === recentActivity.length - 1 }">
                    <span v-if="index !== recentActivity.length - 1" class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                    <div class="relative flex space-x-3">
                      <div>
                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white" :class="activity.iconBg">
                          <component :is="activity.icon" class="h-5 w-5 text-white" />
                        </span>
                      </div>
                      <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                        <div>
                          <p class="text-sm text-gray-500">{{ activity.content }}</p>
                        </div>
                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                          {{ activity.time }}
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
            
            <div v-if="!recentActivity.length" class="text-center py-6">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No recent activity</h3>
              <p class="mt-1 text-sm text-gray-500">Activity will appear here as users interact with the platform.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script lang="ts">
import { defineComponent, ref, computed, onMounted, h } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import MainLayout from '@/layouts/MainLayout.vue'

interface AdminStats {
  totalUsers: number
  dentists: number
  patients: number
  pendingVerifications: number
}

interface Activity {
  id: number
  content: string
  time: string
  icon: any
  iconBg: string
}

export default defineComponent({
  name: 'AdminPage',
  components: {
    MainLayout
  },
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    const loading = ref(false)
    const stats = ref<AdminStats>({
      totalUsers: 0,
      dentists: 0,
      patients: 0,
      pendingVerifications: 0
    })
    
    const recentActivity = ref<Activity[]>([])

    const user = computed(() => authStore.user)

    // Mock data for demonstration
    const loadStats = async () => {
      loading.value = true
      
      try {
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1000))
        
        stats.value = {
          totalUsers: 156,
          dentists: 43,
          patients: 112,
          pendingVerifications: 8
        }

        // Mock recent activity
        recentActivity.value = [
          {
            id: 1,
            content: 'Dr. Sarah Johnson submitted verification documents',
            time: '2 hours ago',
            icon: () => h('svg', { 
              fill: 'none', 
              stroke: 'currentColor', 
              viewBox: '0 0 24 24' 
            }, [
              h('path', {
                'stroke-linecap': 'round',
                'stroke-linejoin': 'round',
                'stroke-width': '2',
                d: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
              })
            ]),
            iconBg: 'bg-green-500'
          },
          {
            id: 2,
            content: 'New patient registration: Emily Rodriguez',
            time: '4 hours ago',
            icon: () => h('svg', { 
              fill: 'none', 
              stroke: 'currentColor', 
              viewBox: '0 0 24 24' 
            }, [
              h('path', {
                'stroke-linecap': 'round',
                'stroke-linejoin': 'round',
                'stroke-width': '2',
                d: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
              })
            ]),
            iconBg: 'bg-blue-500'
          },
          {
            id: 3,
            content: 'System maintenance completed successfully',
            time: '1 day ago',
            icon: () => h('svg', { 
              fill: 'none', 
              stroke: 'currentColor', 
              viewBox: '0 0 24 24' 
            }, [
              h('path', {
                'stroke-linecap': 'round',
                'stroke-linejoin': 'round',
                'stroke-width': '2',
                d: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'
              }),
              h('path', {
                'stroke-linecap': 'round',
                'stroke-linejoin': 'round',
                'stroke-width': '2',
                d: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z'
              })
            ]),
            iconBg: 'bg-purple-500'
          }
        ]
        
      } catch (error) {
        console.error('Error loading admin stats:', error)
      } finally {
        loading.value = false
      }
    }

    const navigateTo = (path: string) => {
      // For now, just log the navigation
      // In a real app, you'd have these routes configured
      console.log(`Navigating to: ${path}`)
      
      // Check if route exists before navigating
      if (path === '/admin/verification') {
        router.push({ name: 'admin-verification' })
      } else if (path === '/directory') {
        router.push({ name: 'directory' })
      } else {
        // For unimplemented routes, show a message
        alert(`Feature "${path}" is not yet implemented.`)
      }
    }

    onMounted(() => {
      loadStats()
    })

    return {
      user,
      loading,
      stats,
      recentActivity,
      navigateTo
    }
  }
})
</script>