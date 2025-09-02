import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: () => import('../views/Login/Login.vue'),
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('../views/Dashboard/Dashboard.vue'),
    },
    {
      meta: {
        title: 'Events',
      },
      path: '/events',
      name: 'events',
      component: () => import('../views/Event/EventList.vue'),
    },
    {
      meta: {
        title: 'Events Create',
      },
      path: '/events/create',
      name: 'eventsCreate',
      component: () => import('../views/Event/EventDetail.vue'),
    },
    {
      meta: {
        title: 'Event Edit',
      },
      path: '/events/:id/edit',
      name: 'eventsEdit',
      props: true,
      component: () => import('../views/Event/EventDetail.vue'),
    },
    {
      meta: {
        title: 'Users',
      },
      path: '/users',
      name: 'users',
      component: () => import('../views/User/UserList.vue'),
    },
    {
      meta: {
        title: 'Users Create',
      },
      path: '/users/create',
      name: 'usersCreate',
      component: () => import('../views/User/UserDetail.vue'),
    },
    {
      meta: {
        title: 'Users Edit',
      },
      path: '/users/:id/edit',
      name: 'usersEdit',
      props: true,
      component: () => import('../views/User/UserDetail.vue'),
    },
    {
      meta: {
        title: 'Bookings',
      },
      path: '/bookings',
      name: 'bookings',
      component: () => import('../views/Booking/BookingList.vue'),
    },
    {
      meta: {
        title: 'Booking Edit',
      },
      path: '/bookings/:id/edit',
      name: 'bookingsEdit',
      props: true,
      component: () => import('../views/Booking/BookingDetail.vue'),
    },
  ],
})

export default router
