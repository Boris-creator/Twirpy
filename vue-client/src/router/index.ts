import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import HomeView from '../views/HomeView.vue'

function prefix(prefixPath: string, routes: Array<RouteRecordRaw>) {
  return routes.map((route) => ({
    ...route,
    path: `${prefixPath}/${route.path}`
  }))
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    ...prefix('/books', [
      {
        path: '',
        name: 'books',
        component: () => import('@/components/Books/BookList.vue')
      },
      {
        path: ':id',
        name: 'book',
        component: () => import('@/components/Books/Page/BookPage.vue'),
        props: true
      },
      {
        path: 'add',
        name: 'addBook',
        component: () => import('@/components/Books/Form/BookForm.vue')
      }
    ])
  ]
})

export default router
