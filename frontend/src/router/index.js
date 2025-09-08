import { createRouter, createWebHistory } from 'vue-router'
import { isAuthenticated, getStoredUser, hasRoleAccess } from '@/services/auth'

export const routes = [
  {
    path: '/',
    name: 'login',
    component: () => import('../views/Login/Login.vue'),
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('../views/Dashboard/Dashboard.vue'),
    meta: { requiresAuth: true },
  },
  {
    meta: {
      title: 'Events',
      requiresAuth: true,
      roles: ['user', 'organizer', 'admin'],
    },
    path: '/events',
    name: 'events',
    component: () => import('../views/Event/EventList.vue'),
  },
  {
    meta: {
      title: 'Events Create',
      requiresAuth: true,
      roles: ['organizer', 'admin'],
    },
    path: '/events/create',
    name: 'eventsCreate',
    component: () => import('../views/Event/EventDetail.vue'),
  },
  {
    meta: {
      title: 'Event Edit',
      requiresAuth: true,
      roles: ['organizer', 'admin'],
    },
    path: '/events/:id/edit',
    name: 'eventsEdit',
    props: true,
    component: () => import('../views/Event/EventDetail.vue'),
  },
  {
    meta: {
      title: 'Users',
      requiresAuth: true,
      roles: ['admin'],
    },
    path: '/users',
    name: 'users',
    component: () => import('../views/User/UserList.vue'),
  },
  {
    meta: {
      title: 'Users Create',
      requiresAuth: true,
      roles: ['admin'],
    },
    path: '/users/create',
    name: 'usersCreate',
    component: () => import('../views/User/UserDetail.vue'),
  },
  {
    meta: {
      title: 'Users Edit',
      requiresAuth: true,
      roles: ['admin'],
    },
    path: '/users/:id/edit',
    name: 'usersEdit',
    props: true,
    component: () => import('../views/User/UserDetail.vue'),
  },
  {
    meta: {
      title: 'Bookings',
      requiresAuth: true,
      roles: ['organizer', 'admin'],
    },
    path: '/bookings',
    name: 'bookings',
    component: () => import('../views/Booking/BookingList.vue'),
  },
  {
    meta: {
      title: 'Booking Edit',
      requiresAuth: true,
      roles: ['admin'],
    },
    path: '/bookings/:id/edit',
    name: 'bookingsEdit',
    props: true,
    component: () => import('../views/Booking/BookingDetail.vue'),
  },
  {
    meta: {
      title: 'My Bookings',
      requiresAuth: true,
      roles: ['user'],
    },
    path: '/my-bookings',
    name: 'myBookings',
    component: () => import('../views/Booking/MyBookingList.vue'),
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'catchAll',
    redirect: () => (localStorage.getItem('access_token') ? { name: 'dashboard' } : { name: 'login' }),
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { top: 0 }
  },
})

router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some((r) => r.meta?.requiresAuth || (r.meta?.roles && r.meta.roles.length))
  const requiredRoles = to.matched.flatMap((r) => r.meta?.roles || [])

  const authed = isAuthenticated()

  if (to.name === 'login' && authed) {
    return next({ name: 'dashboard' })
  }

  if (!requiresAuth) return next()

  if (!authed) {
    return next({ name: 'login', query: { redirect: to.fullPath } })
  }

  const user = getStoredUser()
  const userRoles = user?.roles || []
  if (!hasRoleAccess(userRoles, requiredRoles)) {
    return next({ name: 'dashboard' })
  }

  return next()
})

export default router
